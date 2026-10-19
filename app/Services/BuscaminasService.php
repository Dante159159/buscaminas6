<?php

namespace App\Services;

use App\Models\Partida;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class BuscaminasService
{
    public const FILAS    = 20;
    public const COLUMNAS = 20;
    public const MINAS    = 70;
    public const CASILLAS_SEGURAS = self::FILAS * self::COLUMNAS - self::MINAS; // 330

    public const CELDA_MINA = -1;

    /**
     * Crea una nueva partida en BD.
     */
    public function crearPartida(User $user): Partida
    {
        if (! $user->exists) {
            throw new RuntimeException('El usuario no existe.');
        }

        return DB::transaction(function () use ($user) {
            return Partida::create([
                'user_id'        => $user->id,
                'estado'         => Partida::ESTADO_EN_CURSO,
                'filas'          => self::FILAS,
                'columnas'       => self::COLUMNAS,
                'cantidad_minas' => self::MINAS,
                'iniciada_en'    => now(),
            ]);
        });
    }

    /**
     * Genera el tablero completo con minas distribuidas y números calculados.
     * Garantiza que el primer clic (casilla inicial) sea seguro.
     *
     * @return array{minas: array, numeros: array}
     */
    public function generarTablero(int $filaInicial = null, int $columnaInicial = null): array
    {
        $minas   = array_fill(0, self::FILAS, array_fill(0, self::COLUMNAS, 0));
        $numeros = array_fill(0, self::FILAS, array_fill(0, self::COLUMNAS, 0));

        // Si se indicó casilla inicial, excluirla junto a sus vecinas
        $excluir = [];
        if ($filaInicial !== null && $columnaInicial !== null) {
            for ($df = -1; $df <= 1; $df++) {
                for ($dc = -1; $dc <= 1; $dc++) {
                    $nf = $filaInicial + $df;
                    $nc = $columnaInicial + $dc;
                    if ($nf >= 0 && $nf < self::FILAS && $nc >= 0 && $nc < self::COLUMNAS) {
                        $excluir[$nf . ',' . $nc] = true;
                    }
                }
            }
        }

        // Colocar 70 minas en posiciones aleatorias únicas (excluyendo la casilla inicial)
        $posiciones = [];
        $intentos = 0;
        while (count($posiciones) < self::MINAS && $intentos < 10000) {
            $f = random_int(0, self::FILAS - 1);
            $c = random_int(0, self::COLUMNAS - 1);
            $key = $f . ',' . $c;
            if (! isset($excluir[$key]) && ! isset($posiciones[$key])) {
                $posiciones[$key] = [$f, $c];
            }
            $intentos++;
        }

        foreach ($posiciones as [$f, $c]) {
            $minas[$f][$c] = self::CELDA_MINA;
        }

        // Calcular números
        for ($f = 0; $f < self::FILAS; $f++) {
            for ($c = 0; $c < self::COLUMNAS; $c++) {
                if ($minas[$f][$c] === self::CELDA_MINA) {
                    $numeros[$f][$c] = self::CELDA_MINA;
                    continue;
                }
                $numeros[$f][$c] = $this->contarMinasVecinas($minas, $f, $c);
            }
        }

        return ['minas' => $minas, 'numeros' => $numeros];
    }

    private function contarMinasVecinas(array $minas, int $f, int $c): int
    {
        $contador = 0;
        for ($df = -1; $df <= 1; $df++) {
            for ($dc = -1; $dc <= 1; $dc++) {
                if ($df === 0 && $dc === 0) continue;
                $nf = $f + $df;
                $nc = $c + $dc;
                if ($nf < 0 || $nf >= self::FILAS || $nc < 0 || $nc >= self::COLUMNAS) continue;
                if ($minas[$nf][$nc] === self::CELDA_MINA) $contador++;
            }
        }
        return $contador;
    }

    /**
     * Finaliza una partida: victoria, derrota o abandonada.
     */
    public function finalizarPartida(
        Partida $partida,
        User $user,
        string $estado,
        int $tiempoSegundos,
        int $casillasReveladas
    ): Partida {
        if (! in_array($estado, [
            Partida::ESTADO_VICTORIA,
            Partida::ESTADO_DERROTA,
            Partida::ESTADO_ABANDONADA,
        ])) {
            throw new RuntimeException('Estado final no válido.');
        }

        if ($tiempoSegundos < 0) {
            throw new RuntimeException('El tiempo no puede ser negativo.');
        }

        if ($casillasReveladas < 0 || $casillasReveladas > self::CASILLAS_SEGURAS) {
            throw new RuntimeException('Cantidad de casillas reveladas inválida.');
        }

        return DB::transaction(function () use ($partida, $user, $estado, $tiempoSegundos, $casillasReveladas) {
            $p = Partida::where('id', $partida->id)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (! $p) {
                throw new RuntimeException('Partida inexistente o no pertenece al usuario.');
            }

            if ($p->estado !== Partida::ESTADO_EN_CURSO) {
                throw new RuntimeException('La partida ya fue finalizada.');
            }

            if ($estado === Partida::ESTADO_VICTORIA
                && $casillasReveladas !== self::CASILLAS_SEGURAS) {
                throw new RuntimeException(
                    'No se puede registrar victoria sin revelar todas las casillas seguras.'
                );
            }

            $p->update([
                'estado'                     => $estado,
                'tiempo_segundos'            => $tiempoSegundos,
                'casillas_seguras_reveladas' => $casillasReveladas,
                'terminada_en'               => now(),
            ]);

            return $p->fresh();
        });
    }

    public function historialUsuario(User $user, int $limite = 50)
    {
        if ($limite < 1 || $limite > 100) $limite = 50;

        return Partida::where('user_id', $user->id)
            ->orderByDesc('iniciada_en')
            ->orderByDesc('id')
            ->limit($limite)
            ->get();
    }

    public function rankingRecords(int $limite = 50)
    {
        if ($limite < 1 || $limite > 100) $limite = 50;

        return User::query()
            ->join('partidas', 'partidas.user_id', '=', 'users.id')
            ->where('partidas.estado', Partida::ESTADO_VICTORIA)
            ->whereNotNull('partidas.tiempo_segundos')
            ->select(
                'users.id as user_id',
                'users.name as jugador',
                DB::raw('MIN(partidas.tiempo_segundos) as mejor_tiempo_segundos'),
                DB::raw('COUNT(*) as total_victorias')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('mejor_tiempo_segundos', 'asc')
            ->orderBy('users.id', 'asc')
            ->limit($limite)
            ->get();
    }

    public function recordUsuario(User $user): ?object
    {
        return User::query()
            ->join('partidas', 'partidas.user_id', '=', 'users.id')
            ->where('users.id', $user->id)
            ->where('partidas.estado', Partida::ESTADO_VICTORIA)
            ->whereNotNull('partidas.tiempo_segundos')
            ->select(
                'users.id as user_id',
                'users.name as jugador',
                DB::raw('MIN(partidas.tiempo_segundos) as mejor_tiempo_segundos'),
                DB::raw('COUNT(*) as total_victorias')
            )
            ->groupBy('users.id', 'users.name')
            ->first();
    }
}