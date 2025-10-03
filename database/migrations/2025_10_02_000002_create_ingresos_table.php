<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_ingreso');
            $table->time('hora_ingreso');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->unsignedBigInteger('id_vehiculo')->nullable();
            $table->timestamps();

            $table->index('id_user');
            $table->index('id_empresa');
            $table->index('id_vehiculo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
