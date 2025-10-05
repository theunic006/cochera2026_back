<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tolerancia', function (Blueprint $table) {
            // Eliminar el índice único por nombre (si existe)
            $table->dropUnique('tolerancia_descripcion_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tolerancia', function (Blueprint $table) {
            $table->unique('descripcion', 'tolerancia_descripcion_unique');
        });
    }
};
