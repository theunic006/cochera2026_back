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
            $table->unique(['descripcion', 'id_empresa'], 'tolerancia_descripcion_id_empresa_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tolerancia', function (Blueprint $table) {
            $table->dropUnique('tolerancia_descripcion_id_empresa_unique');
        });
    }
};
