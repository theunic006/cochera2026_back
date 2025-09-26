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
        Schema::table('companies', function (Blueprint $table) {
            $table->enum('estado', ['activo', 'suspendido', 'inactivo', 'pendiente'])
                  ->default('activo')
                  ->after('descripcion')
                  ->comment('Estado de la company: activo, suspendido, inactivo, pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
