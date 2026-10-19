<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partida;
use App\Services\BuscaminasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartidaController extends Controller
{
    public function __construct(
        private readonly BuscaminasService $service
    ) {}

    /**
     * POST /api/partidas
     * Crea la partida, genera el tablero completo y lo envía al cliente.
     * A partir de aquí, todo el juego es 100% cliente (sin más consultas a BD).
     */
    public function iniciar(Request $request): JsonResponse
    {
        $user = $request->user();

        // Crear la partida en BD (única consulta de inicio)
        $partida = $this->service->crearPartida($user);

        // Generar tablero completo en el servidor (sin consultas adicionales)
        $tablero = $this->service->generarTablero();

        return response()->json([
            'partida_id'       => $partida->id,
            'filas'            => BuscaminasService::FILAS,
            'columnas'         => BuscaminasService::COLUMNAS,
            'minas'            => BuscaminasService::MINAS,
            'casillas_seguras' => BuscaminasService::CASILLAS_SEGURAS,
            'tablero'          => $tablero,           // minas y números (todo el estado)
            'estado'           => $partida->estado,
            'iniciada_en'      => $partida->iniciada_en->toIso8601String(),
        ], 201);
    }

    /**
     * POST /api/partidas/{partida}/finalizar
     * Única consulta al final del juego (victoria/derrota/abandono).
     * Recibe el estado final calculado por el cliente.
     */
    public function finalizar(Request $request, Partida $partida): JsonResponse
    {
        $validated = $request->validate([
            'estado'              => 'required|in:victoria,derrota,abandonada',
            'tiempo_segundos'     => 'required|integer|min:0|max:9999',
            'casillas_reveladas'  => 'required|integer|min:0|max:330',
        ]);

        $user = $request->user();

        if ($partida->user_id !== $user->id) {
            return response()->json(['message' => 'Partida inexistente o no pertenece al usuario.'], 403);
        }

        if ($partida->estado !== Partida::ESTADO_EN_CURSO) {
            return response()->json(['message' => 'La partida ya fue finalizada.'], 409);
        }

        try {
            $partida = $this->service->finalizarPartida(
                $partida,
                $user,
                $validated['estado'],
                (int) $validated['tiempo_segundos'],
                (int) $validated['casillas_reveladas']
            );

            // Si fue victoria, devolver el récord actualizado del usuario
            $record = null;
            if ($validated['estado'] === Partida::ESTADO_VICTORIA) {
                $record = $this->service->recordUsuario($user);
            }

            return response()->json([
                'estado'         => $partida->estado,
                'tiempo_segundos'=> $partida->tiempo_segundos,
                'record'         => $record ? [
                    'mejor_tiempo_segundos' => $record->mejor_tiempo_segundos,
                    'total_victorias'       => $record->total_victorias,
                ] : null,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}