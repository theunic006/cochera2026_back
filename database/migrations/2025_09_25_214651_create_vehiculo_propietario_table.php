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
        Schema::create('vehiculo_propietario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            $table->foreignId('propietario_id')->constrained('propietarios')->onDelete('cascade');
            $table->date('fecha_inicio')->comment('Fecha de inicio de la relación vehículo-propietario');
            $table->date('fecha_fin')->nullable()->comment('Fecha de fin de la relación (null = vigente)');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['vehiculo_id', 'propietario_id']);
            $table->index(['fecha_inicio', 'fecha_fin']);

            // Evitar registros duplicados vigentes para el mismo vehículo
            $table->unique(['vehiculo_id', 'propietario_id', 'fecha_inicio'], 'unique_vehiculo_propietario_fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculo_propietario');
    }
};
