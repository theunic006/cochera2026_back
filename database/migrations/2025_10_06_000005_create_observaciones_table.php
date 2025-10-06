<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('observaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_vehiculo')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->timestamps();
            $table->index('id_vehiculo');
            $table->index('id_empresa');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('observaciones');
    }
};
