<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BuscaminasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RecordController extends Controller
{
    public function __construct(
        private readonly BuscaminasService $service
    ) {}

    /**
     * GET /api/records
     * Ranking global cacheado por 10 minutos.
     */
    public function ranking(Request $request): JsonResponse
    {
        $limite = (int) $request->query('limite', 50);
        if ($limite < 1 || $limite > 100) $limite = 50;

        $cacheKey = 'ranking_global_' . $limite;
        $ranking = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($limite) {
            // ✅ Forzar a array plano para que JSON lo devuelva como []
            return $this->service->rankingRecords($limite)->values()->toArray();
        });

        // ✅ Doble seguridad: asegurar que sea array
        if (! is_array($ranking)) {
            $ranking = [];
        }

        return response()->json(['ranking' => $ranking]);
    }

    /**
     * GET /api/records/personal
     * Récord personal cacheado por 5 minutos.
     */
    public function personal(Request $request): JsonResponse
    {
        $cacheKey = 'record_user_' . $request->user()->id;
        $record = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request) {
            return $this->service->recordUsuario($request->user());
        });

        if (! $record) {
            return response()->json([
                'message' => 'Aún no tienes victorias registradas.',
                'record'  => null,
            ]);
        }

        return response()->json(['record' => $record]);
    }

    /**
     * GET /api/historial
     */
    public function historial(Request $request): JsonResponse
    {
        $limite = (int) $request->query('limite', 50);
        $partidas = $this->service->historialUsuario($request->user(), $limite);

        return response()->json(['partidas' => $partidas]);
    }
}