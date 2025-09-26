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
        Schema::table('propietarios', function (Blueprint $table) {
            $table->string('nombres', 100)->after('id')->comment('Nombres del propietario');
            $table->string('apellidos', 100)->after('nombres')->comment('Apellidos del propietario');
            $table->string('documento', 20)->unique()->after('apellidos')->comment('Documento de identidad único');
            $table->string('telefono', 15)->nullable()->after('documento')->comment('Número de teléfono');
            $table->string('email', 100)->unique()->after('telefono')->comment('Correo electrónico único');
            $table->text('direccion')->nullable()->after('email')->comment('Dirección completa');

            // Índices para optimizar búsquedas
            $table->index('documento');
            $table->index('email');
            $table->index(['apellidos', 'nombres']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propietarios', function (Blueprint $table) {
            $table->dropIndex(['propietarios_documento_index']);
            $table->dropIndex(['propietarios_email_index']);
            $table->dropIndex(['propietarios_apellidos_nombres_index']);

            $table->dropColumn([
                'nombres',
                'apellidos',
                'documento',
                'telefono',
                'email',
                'direccion'
            ]);
        });
    }
};
