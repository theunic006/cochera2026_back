<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tolerancia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('minutos')->nullable();
            $table->string('descripcion', 100);
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();
            $table->unique(['descripcion', 'id_empresa']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tolerancia');
    }
};
