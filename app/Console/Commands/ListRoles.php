<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class ListRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:list
                            {--page=1 : Página a mostrar}
                            {--limit=10 : Número de roles por página}
                            {--search= : Buscar roles por descripción}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar roles con paginación y búsqueda';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = (int) $this->option('page');
        $limit = (int) $this->option('limit');
        $search = $this->option('search');

        $this->info('👥 Listando roles de la base de datos');
        $this->newLine();

        // Construir la consulta
        $query = Role::orderBy('id', 'desc');

        if ($search) {
            $query->where('descripcion', 'LIKE', "%{$search}%");
        }

        // Obtener total y calcular paginación
        $total = $query->count();
        $totalPages = ceil($total / $limit);

        if ($total === 0) {
            $this->warn('❌ No se encontraron roles');
            if ($search) {
                $this->comment("🔍 Término buscado: '{$search}'");
            }
            return;
        }

        // Obtener los roles paginados
        $offset = ($page - 1) * $limit;
        $roles = $query->skip($offset)->take($limit)->get();

        // Mostrar información de paginación
        if ($search) {
            $this->info("📊 Mostrando {$roles->count()} de {$total} roles para: '{$search}' (Página {$page} de {$totalPages})");
        } else {
            $this->info("📊 Mostrando {$roles->count()} de {$total} roles (Página {$page} de {$totalPages})");
        }
        $this->newLine();

        // Crear tabla
        $headers = ['ID', 'Descripción', 'Creado'];
        $rows = [];

        foreach ($roles as $role) {
            $rows[] = [
                $role->id,
                $role->descripcion,
                $role->created_at ? $role->created_at->format('Y-m-d H:i:s') : 'N/A'
            ];
        }

        $this->table($headers, $rows);

        // Mostrar comandos útiles
        $this->newLine();
        if ($page < $totalPages) {
            $nextPage = $page + 1;
            $this->comment("⏭️  Para ver más roles: php artisan roles:list --page={$nextPage}");
        }
        $this->comment('🔍 Buscar: php artisan roles:list --search="descripcion"');
        $this->comment('📄 Cambiar límite: php artisan roles:list --limit=20');
    }
}
