<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vehiculo_propietario', function (Blueprint $table) {
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
            $table->foreign('propietario_id')->references('id')->on('propietarios')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::table('vehiculo_propietario', function (Blueprint $table) {
            $table->dropForeign(['vehiculo_id']);
            $table->dropForeign(['propietario_id']);
        });
    }
};
