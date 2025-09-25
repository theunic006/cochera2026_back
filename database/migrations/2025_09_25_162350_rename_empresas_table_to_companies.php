<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renombrar la tabla empresas a companies
        Schema::rename('empresas', 'companies');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: renombrar companies de vuelta a empresas
        Schema::rename('companies', 'empresas');
    }
};
