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
            $table->string('placa', 15)->unique();
            $table->string('modelo', 50)->nullable();
            $table->string('marca', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->year('anio')->nullable();
            $table->integer('frecuencia')->nullable();
            $table->unsignedBigInteger('tipo_vehiculo_id')->nullable();
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();

            $table->foreign('tipo_vehiculo_id')->references('id')->on('tipo_vehiculos')->onDelete('restrict');
            $table->foreign('id_empresa')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
