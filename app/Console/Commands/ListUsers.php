<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{

    protected $signature = 'users:list {--limit=10} {--page=1} {--search=}';
    protected $description = 'Listar usuarios de la base de datos';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        $page = (int) $this->option('page');
        $search = $this->option('search');

        $this->info("👥 Listando usuarios de la base de datos");
        $this->newLine();

        // Construir query
        $query = User::query();

        // Filtro de búsqueda
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
            $this->info("🔍 Búsqueda: '{$search}'");
        }

        // Total de usuarios
        $total = $query->count();

        // Paginación
        $offset = ($page - 1) * $limit;
        $users = $query->skip($offset)->take($limit)->get(['id', 'name', 'email', 'created_at']);

        if ($users->isEmpty()) {
            $this->warn('No se encontraron usuarios.');
            return 0;
        }

        // Mostrar estadísticas
        $totalPages = ceil($total / $limit);
        $this->info("📊 Mostrando {$users->count()} de {$total} usuarios (Página {$page} de {$totalPages})");
        $this->newLine();

        // Tabla de usuarios
        $this->table(
            ['ID', 'Nombre', 'Email', 'Creado'],
            $users->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray()
        );

        // Información adicional
        $this->newLine();
        if ($page < $totalPages) {
            $this->info("⏭️  Para ver más usuarios: php artisan users:list --page=" . ($page + 1));
        }

        if ($search) {
            $this->info("🔄 Para ver todos: php artisan users:list");
        }

        $this->info("🔍 Buscar: php artisan users:list --search='nombre'");
        $this->info("📄 Cambiar límite: php artisan users:list --limit=20");

        return 0;
    }
}
