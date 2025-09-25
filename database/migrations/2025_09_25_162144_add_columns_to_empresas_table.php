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
        Schema::table('empresas', function (Blueprint $table) {
            // Agregar las columnas que necesitamos
            $table->string('nombre', 100)->after('id');
            $table->string('ubicacion', 255)->nullable()->after('nombre');
            $table->text('logo')->nullable()->after('ubicacion');
            $table->text('descripcion')->nullable()->after('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Revertir agregando las columnas en orden inverso
            $table->dropColumn(['descripcion', 'logo', 'ubicacion', 'nombre']);
        });
    }
};
