<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 15)->unique()->comment('Placa del vehículo (única)');
            $table->string('modelo', 50)->nullable()->comment('Modelo del vehículo');
            $table->string('marca', 50)->nullable()->comment('Marca del vehículo');
            $table->string('color', 30)->nullable()->comment('Color del vehículo');
            $table->year('anio')->nullable()->comment('Año del vehículo');
            $table->integer('frecuencia')->nullable();
            $table->unsignedBigInteger('tipo_vehiculo_id')->nullable()->comment('Relación con tipo de vehículo');
            $table->timestamps();

            $table->index('tipo_vehiculo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
