<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tolerancia', function (Blueprint $table) {
            $table->id();
            $table->integer('minutos')->comment('Minutos de tolerancia');
            $table->foreignId('tipo_vehiculo_id')->nullable()->constrained('tipo_vehiculos')->onDelete('set null');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('tipo_vehiculo_id');
            $table->index('minutos');

            // Evitar duplicados por tipo de vehículo
            $table->unique('tipo_vehiculo_id', 'unique_tolerancia_tipo_vehiculo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tolerancia');
    }
};
