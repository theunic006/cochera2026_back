<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propietarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100)->comment('Nombres del propietario');
            $table->string('apellidos', 100)->nullable()->comment('Apellidos del propietario');
            $table->string('documento', 20)->nullable()->comment('Documento de identidad único');
            $table->string('telefono', 15)->nullable()->comment('Número de teléfono');
            $table->string('email', 100)->nullable()->comment('Correo electrónico único');
            $table->text('direccion')->nullable()->comment('Dirección completa');
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
