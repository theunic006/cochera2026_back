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
            $table->string('nombre', 50)->after('id')->comment('Nombre del tipo de vehículo');
            $table->float('valor')->nullable()->after('nombre')->comment('Valor asociado al tipo de vehículo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_vehiculos', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'valor']);
        });
    }
};
