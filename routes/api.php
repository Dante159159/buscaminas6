<?php

use App\Http\Controllers\Api\PartidaController;
use App\Http\Controllers\Api\RecordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {

    // Partidas - solo 2 endpoints: iniciar y finalizar
    Route::post('/partidas',                       [PartidaController::class, 'iniciar'])->name('api.partidas.iniciar');
    Route::post('/partidas/{partida}/finalizar',   [PartidaController::class, 'finalizar'])->name('api.partidas.finalizar');

    // Récords e historial
    Route::get('/records',          [RecordController::class, 'ranking'])->name('api.records.ranking');
    Route::get('/records/personal', [RecordController::class, 'personal'])->name('api.records.personal');
    Route::get('/historial',        [RecordController::class, 'historial'])->name('api.historial');
});