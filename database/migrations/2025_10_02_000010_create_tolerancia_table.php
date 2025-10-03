<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tolerancia', function (Blueprint $table) {
            $table->id();
            $table->integer('minutos')->nullable()->comment('Minutos de tolerancia');
            $table->string('descripcion', 100)->nullable()->comment('Descripción de la tolerancia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tolerancia');
    }
};
