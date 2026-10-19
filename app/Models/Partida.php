<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partida extends Model
{
    use HasFactory;

    protected $table = 'partidas';

    protected $fillable = [
        'user_id',
        'estado',
        'filas',
        'columnas',
        'cantidad_minas',
        'tiempo_segundos',
        'casillas_seguras_reveladas',
        'iniciada_en',
        'terminada_en',
    ];

    protected $casts = [
        'iniciada_en'   => 'datetime',
        'terminada_en'  => 'datetime',
        'tiempo_segundos'        => 'integer',
        'casillas_seguras_reveladas' => 'integer',
        'filas'         => 'integer',
        'columnas'      => 'integer',
        'cantidad_minas'=> 'integer',
    ];

    // ---------- Relaciones ----------
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ---------- Constantes de estado ----------
    public const ESTADO_EN_CURSO    = 'en_curso';
    public const ESTADO_VICTORIA    = 'victoria';
    public const ESTADO_DERROTA     = 'derrota';
    public const ESTADO_ABANDONADA  = 'abandonada';

    // ---------- Configuración fija del nivel ----------
    public const FILAS_DEFAULT      = 20;
    public const COLUMNAS_DEFAULT   = 20;
    public const MINAS_DEFAULT      = 70;

    /**
     * Casillas seguras totales = filas * columnas - minas
     */
    public static function casillasSegurasTotales(): int
    {
        return self::FILAS_DEFAULT * self::COLUMNAS_DEFAULT - self::MINAS_DEFAULT;
    }
}