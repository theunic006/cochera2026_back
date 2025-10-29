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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // Nombre legible del permiso
            $table->string('slug', 100)->unique(); // Identificador único del permiso
            $table->string('description')->nullable(); // Descripción del permiso
            $table->string('module', 50)->nullable(); // Módulo al que pertenece (Dashboard, Usuarios, etc.)
            $table->boolean('is_active')->default(true); // Si el permiso está activo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
