<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->comment('Nombre del tipo de vehículo');
            $table->double('valor')->nullable()->comment('Valor asociado al tipo de vehículo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_vehiculos');
    }
};
