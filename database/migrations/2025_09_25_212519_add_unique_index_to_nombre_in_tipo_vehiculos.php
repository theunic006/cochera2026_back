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
        Schema::table('tipo_vehiculos', function (Blueprint $table) {
            $table->unique('nombre', 'tipo_vehiculos_nombre_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_vehiculos', function (Blueprint $table) {
            $table->dropUnique('tipo_vehiculos_nombre_unique');
        });
    }
};
