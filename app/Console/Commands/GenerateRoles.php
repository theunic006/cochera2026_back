<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class GenerateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:generate {count=10 : Número de roles a generar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar roles de prueba con datos realistas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');

        $this->info("👥 Generando {$count} roles de prueba...");

        // Roles típicos en sistemas de cochera/parking
        $rolesComunes = [
            'Administrador General',
            'Gerente de Operaciones',
            'Supervisor',
            'Cajero',
            'Operador de Ingreso',
            'Operador de Salida',
            'Vigilante de Seguridad',
            'Mantenimiento',
            'Recepcionista',
            'Auditor',
            'Jefe de Turno',
            'Asistente Administrativo',
            'Contador',
            'Recursos Humanos',
            'Soporte Técnico',
            'Coordinador de Flota',
            'Inspector de Calidad',
            'Operador Nocturno',
            'Supervisor de Limpieza',
            'Encargado de Facturación'
        ];

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            // Si tenemos roles predefinidos y no hemos llegado al límite, usar uno
            if ($i < count($rolesComunes)) {
                $descripcion = $rolesComunes[$i];
            } else {
                // Generar roles adicionales con numeración
                $baseRoles = ['Operador', 'Asistente', 'Coordinador', 'Especialista'];
                $areas = ['Área A', 'Área B', 'Turno Mañana', 'Turno Tarde', 'Turno Noche'];

                $base = $baseRoles[array_rand($baseRoles)];
                $area = $areas[array_rand($areas)];
                $descripcion = "{$base} {$area}";
            }

            // Verificar que no exista ya
            if (!Role::where('descripcion', $descripcion)->exists()) {
                Role::create([
                    'descripcion' => $descripcion
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $total = Role::count();
        $this->info("✅ {$count} roles generados exitosamente!");
        $this->info("📊 Total de roles en la base de datos: {$total}");
        $this->newLine();
        $this->comment('💡 Comandos útiles:');
        $this->comment('   php artisan roles:list - Ver roles');
        $this->comment('   php artisan roles:list --search="Admin" - Buscar roles');
    }
}
