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
        Schema::table('tolerancia', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea
            $table->dropForeign(['tipo_vehiculo_id']);

            // Eliminar los índices
            $table->dropIndex(['tipo_vehiculo_id']);
            $table->dropUnique('unique_tolerancia_tipo_vehiculo');

            // Eliminar la columna tipo_vehiculo_id
            $table->dropColumn('tipo_vehiculo_id');

            // Agregar una columna descripción para identificar la tolerancia
            $table->string('descripcion', 100)->after('minutos')->comment('Descripción de la tolerancia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tolerancia', function (Blueprint $table) {
            // Restaurar la columna tipo_vehiculo_id
            $table->foreignId('tipo_vehiculo_id')->nullable()->after('minutos')->constrained('tipo_vehiculos')->onDelete('set null');

            // Restaurar índices
            $table->index('tipo_vehiculo_id');
            $table->unique('tipo_vehiculo_id', 'unique_tolerancia_tipo_vehiculo');

            // Eliminar la columna descripción agregada
            $table->dropColumn('descripcion');
        });
    }
};
