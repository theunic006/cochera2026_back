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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 15)->unique()->comment('Placa del vehículo (única)');
            $table->string('modelo', 50)->nullable()->comment('Modelo del vehículo');
            $table->string('marca', 50)->nullable()->comment('Marca del vehículo');
            $table->string('color', 30)->nullable()->comment('Color del vehículo');
            $table->unsignedBigInteger('id_tipo_vehiculo')->nullable()->comment('Relación con tipo de vehículo');
            $table->timestamps();

            // Índices y relaciones
            $table->index('id_tipo_vehiculo');
            $table->foreign('id_tipo_vehiculo')->references('id')->on('tipo_vehiculos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
