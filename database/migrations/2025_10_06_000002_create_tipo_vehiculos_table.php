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
            $table->string('nombre', 50);
            $table->double('valor')->nullable();
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();

            $table->foreign('id_empresa')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_vehiculos');
    }
};
