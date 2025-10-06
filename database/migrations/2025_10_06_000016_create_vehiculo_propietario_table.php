<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehiculo_propietario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('propietario_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
            $table->unique(['vehiculo_id', 'propietario_id', 'fecha_inicio'], 'unique_vehiculo_propietario_fecha');
            $table->index(['vehiculo_id', 'propietario_id']);
            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('vehiculo_propietario');
    }
};
