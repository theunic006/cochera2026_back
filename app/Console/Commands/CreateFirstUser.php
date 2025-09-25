<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateFirstUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-first {--email=} {--password=} {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear el primer usuario administrador del sistema cuando no existen usuarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar si ya existen usuarios
        if (User::count() > 0) {
            $this->error('❌ Ya existen usuarios en el sistema.');
            $this->info('💡 Para crear nuevos usuarios, usa el endpoint /api/auth/register con un token válido.');
            return 1;
        }

        $this->info('🚀 Creando el primer usuario administrador...');

        // Obtener datos del usuario
        $email = $this->option('email') ?: $this->ask('📧 Email del administrador');
        $name = $this->option('name') ?: $this->ask('👤 Nombre del administrador');
        $password = $this->option('password') ?: $this->secret('🔒 Contraseña del administrador');

        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ El email no tiene un formato válido.');
            return 1;
        }

        // Validar contraseña
        if (strlen($password) < 8) {
            $this->error('❌ La contraseña debe tener al menos 8 caracteres.');
            return 1;
        }

        try {
            // Crear el usuario
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $this->newLine();
            $this->info('✅ ¡Primer usuario creado exitosamente!');
            $this->newLine();

            // Mostrar información del usuario
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['ID', $user->id],
                    ['Nombre', $user->name],
                    ['Email', $user->email],
                    ['Creado', $user->created_at->format('Y-m-d H:i:s')],
                ]
            );

            $this->newLine();
            $this->info('🔑 Ahora puedes hacer login con estas credenciales:');
            $this->info("📧 Email: {$email}");
            $this->info('🔒 Contraseña: [la que ingresaste]');
            $this->newLine();
            $this->info('📋 Endpoint de login:');
            $this->info('POST /api/auth/login');
            $this->newLine();

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error al crear el usuario: ' . $e->getMessage());
            return 1;
        }
    }
}
