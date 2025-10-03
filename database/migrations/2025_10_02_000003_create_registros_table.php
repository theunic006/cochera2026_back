<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_ingreso')->nullable();
            $table->time('hora_ingreso')->nullable();
            $table->unsignedBigInteger('id_vehiculo')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->index('id_vehiculo');
            $table->index('id_user');
            $table->index('id_empresa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
