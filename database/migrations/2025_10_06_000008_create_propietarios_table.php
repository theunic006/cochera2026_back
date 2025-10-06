<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('propietarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombres', 100);
            $table->string('apellidos', 100)->nullable();
            $table->string('documento', 20)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();
            $table->index('documento');
            $table->index('email');
            $table->index(['apellidos', 'nombres']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('propietarios');
    }
};
