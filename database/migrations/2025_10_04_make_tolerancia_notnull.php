<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tolerancia', function (Blueprint $table) {
            $table->string('descripcion', 100)->nullable(false)->change();
            $table->unsignedBigInteger('id_empresa')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tolerancia', function (Blueprint $table) {
            $table->string('descripcion', 100)->nullable()->change();
            $table->unsignedBigInteger('id_empresa')->nullable()->change();
        });
    }
};
