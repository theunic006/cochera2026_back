<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('ubicacion', 255)->nullable();
            $table->text('logo')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['activo', 'suspendido', 'inactivo', 'pendiente'])->default('activo')->comment('Estado de la company: activo, suspendido, inactivo, pendiente');
            $table->integer('capacidad')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
