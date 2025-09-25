<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class ListCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:list
                            {--page=1 : Página a mostrar}
                            {--limit=10 : Número de companies por página}
                            {--search= : Buscar companies por nombre, ubicación o descripción}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar companies con paginación y búsqueda';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = (int) $this->option('page');
        $limit = (int) $this->option('limit');
        $search = $this->option('search');

        $this->info('🏢 Listando companies de la base de datos');
        $this->newLine();

        // Construir la consulta
        $query = Company::orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('ubicacion', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        // Obtener total y calcular paginación
        $total = $query->count();
        $totalPages = (int) ceil($total / $limit);
        $offset = ($page - 1) * $limit;

        if ($total === 0) {
            if ($search) {
                $this->warn("❌ No se encontraron companies que coincidan con: '{$search}'");
            } else {
                $this->warn('❌ No hay companies en la base de datos');
                $this->comment('💡 Genera companies con: php artisan companies:generate 20');
            }
            return;
        }

        // Obtener companies de la página actual
        $companies = $query->skip($offset)->take($limit)->get();

        if ($search) {
            $this->info("🔍 Mostrando {$companies->count()} de {$total} companies que coinciden con: '{$search}' (Página {$page} de {$totalPages})");
        } else {
            $this->info("📊 Mostrando {$companies->count()} de {$total} companies (Página {$page} de {$totalPages})");
        }

        $this->newLine();

        // Crear tabla
        $headers = ['ID', 'Nombre', 'Ubicación', 'Creado'];
        $rows = [];

        foreach ($companies as $company) {
            $rows[] = [
                $company->id,
                $this->truncateString($company->nombre, 30),
                $this->truncateString($company->ubicacion ?? 'N/A', 35),
                $company->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();

        // Mostrar navegación si hay más páginas
        if ($totalPages > 1) {
            if ($page < $totalPages) {
                $nextPage = $page + 1;
                $this->comment("⏭️  Para ver más companies: php artisan companies:list --page={$nextPage}");
            }
            if ($page > 1) {
                $prevPage = $page - 1;
                $this->comment("⏮️  Página anterior: php artisan companies:list --page={$prevPage}");
            }
        }

        $this->comment('🔍 Buscar: php artisan companies:list --search="nombre"');
        $this->comment('📄 Cambiar límite: php artisan companies:list --limit=20');
    }

    /**
     * Truncar string para mejor visualización en tabla
     */
    private function truncateString($string, $length = 30): string
    {
        return strlen($string) > $length ? substr($string, 0, $length) . '...' : $string;
    }
}
