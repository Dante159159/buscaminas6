<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relación con users
            $table->unsignedBigInteger('user_id');

            // Estado de la partida
            $table->enum('estado', ['en_curso', 'victoria', 'derrota', 'abandonada'])
                  ->default('en_curso');

            // Configuración del nivel medio-experto
            $table->unsignedSmallInteger('filas')->default(20);
            $table->unsignedSmallInteger('columnas')->default(20);
            $table->unsignedSmallInteger('cantidad_minas')->default(70);

            // Duración total en segundos
            $table->unsignedInteger('tiempo_segundos')->nullable();

            // Casillas seguras reveladas
            $table->unsignedSmallInteger('casillas_seguras_reveladas')->default(0);

            // Fechas de la partida
            $table->dateTime('iniciada_en')->useCurrent();
            $table->dateTime('terminada_en')->nullable();

            // Timestamps de Laravel
            $table->timestamps();

            // Llave foránea
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Índices
            $table->index(['user_id', 'created_at'], 'idx_partidas_usuario_fecha');
            $table->index(['estado', 'tiempo_segundos'], 'idx_partidas_estado_tiempo');
        });

        // Restricción CHECK: que la cantidad de minas no ocupe todo el tablero
        DB::statement("
            ALTER TABLE partidas
            ADD CONSTRAINT chk_configuracion_tablero
            CHECK (
                filas > 0
                AND columnas > 0
                AND cantidad_minas > 0
                AND cantidad_minas < filas * columnas
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};