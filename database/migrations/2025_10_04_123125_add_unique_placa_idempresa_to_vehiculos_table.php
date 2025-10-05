<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('vehiculos', function (Blueprint $table) {
        $table->unique(['placa', 'id_empresa'], 'vehiculos_placa_id_empresa_unique');
    });
}

public function down()
{
    Schema::table('vehiculos', function (Blueprint $table) {
        $table->dropUnique('vehiculos_placa_id_empresa_unique');
    });
}
};
