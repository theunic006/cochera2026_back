<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_salida');
            $table->time('hora_salida');
            $table->time('tiempo')->nullable();
            $table->double('precio')->nullable();
            $table->string('tipo_pago', 20)->nullable();
            $table->unsignedBigInteger('id_registro')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->timestamps();
            $table->index('id_registro');
            $table->index('id_user');
            $table->index('id_empresa');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('salidas');
    }
};
