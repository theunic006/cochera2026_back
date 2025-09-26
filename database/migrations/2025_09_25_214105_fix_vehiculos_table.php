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
        Schema::table('vehiculos', function (Blueprint $table) {
            // 1. Primero eliminamos la clave foránea y el índice existente
            $table->dropForeign(['id_tipo_vehiculo']);
            $table->dropIndex(['id_tipo_vehiculo']);

            // 2. Agregamos la columna año
            $table->year('anio')->after('color')->comment('Año del vehículo');

            // 3. Renombramos la columna id_tipo_vehiculo a tipo_vehiculo_id
            $table->renameColumn('id_tipo_vehiculo', 'tipo_vehiculo_id');
        });

        // 4. En una segunda operación, agregamos la nueva clave foránea
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->index('tipo_vehiculo_id');
            $table->foreign('tipo_vehiculo_id')->references('id')->on('tipo_vehiculos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            // 1. Eliminamos la nueva clave foránea
            $table->dropForeign(['tipo_vehiculo_id']);
            $table->dropIndex(['tipo_vehiculo_id']);

            // 2. Renombramos de vuelta
            $table->renameColumn('tipo_vehiculo_id', 'id_tipo_vehiculo');

            // 3. Eliminamos la columna año
            $table->dropColumn('anio');
        });

        // 4. Restauramos la clave foránea original
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->index('id_tipo_vehiculo');
            $table->foreign('id_tipo_vehiculo')->references('id')->on('tipo_vehiculos')->onDelete('set null');
        });
    }
};
