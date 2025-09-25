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
        Schema::table('users', function (Blueprint $table) {
            // Agregar columna categoria
            $table->string('categoria', 50)->nullable()->after('password');

            // Agregar columna idrol con foreign key
            $table->unsignedBigInteger('idrol')->nullable()->after('categoria');
            $table->foreign('idrol')->references('id')->on('roles')->onDelete('set null');

            // Agregar columna id_company con foreign key
            $table->unsignedBigInteger('id_company')->nullable()->after('idrol');
            $table->foreign('id_company')->references('id')->on('companies')->onDelete('set null');

            // Agregar índices para mejor rendimiento
            $table->index('idrol');
            $table->index('id_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar foreign keys primero
            $table->dropForeign(['idrol']);
            $table->dropForeign(['id_company']);

            // Eliminar índices
            $table->dropIndex(['idrol']);
            $table->dropIndex(['id_company']);

            // Eliminar columnas
            $table->dropColumn(['categoria', 'idrol', 'id_company']);
        });
    }
};
