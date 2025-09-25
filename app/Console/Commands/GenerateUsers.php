<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class GenerateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate {count=100} {--password=password123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar usuarios ficticios para la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $defaultPassword = $this->option('password');

        $this->info("🚀 Generando {$count} usuarios...");

        // Crear instancia de Faker en español
        $faker = Faker::create('es_ES');

        // Barra de progreso
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        for ($i = 1; $i <= $count; $i++) {
            try {
                // Generar datos realistas
                $name = $faker->firstName . ' ' . $faker->lastName;
                $email = $faker->unique()->safeEmail;

                // Crear el usuario
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($defaultPassword),
                ]);

                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Usuario {$i}: " . $e->getMessage();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Mostrar resultados
        if ($successCount > 0) {
            $this->info("✅ {$successCount} usuarios creados exitosamente!");
        }

        if ($errorCount > 0) {
            $this->error("❌ {$errorCount} usuarios fallaron al crearse.");
            if ($this->confirm('¿Quieres ver los errores?')) {
                foreach ($errors as $error) {
                    $this->line($error);
                }
            }
        }

        // Mostrar estadísticas
        $totalUsers = User::count();
        $this->newLine();
        $this->info("📊 Estadísticas:");
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Usuarios creados en esta ejecución', $successCount],
                ['Errores en esta ejecución', $errorCount],
                ['Total de usuarios en la base de datos', $totalUsers],
                ['Contraseña por defecto', $defaultPassword],
            ]
        );

        // Mostrar algunos usuarios de ejemplo
        $this->newLine();
        $this->info("👥 Últimos usuarios creados:");
        $latestUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);

        $this->table(
            ['ID', 'Nombre', 'Email', 'Creado'],
            $latestUsers->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray()
        );

        $this->newLine();
        $this->info("🔑 Todos los usuarios tienen la contraseña: {$defaultPassword}");
        $this->info("📋 Puedes hacer login con cualquiera de estos emails.");

        return $successCount > 0 ? 0 : 1;
    }
}
