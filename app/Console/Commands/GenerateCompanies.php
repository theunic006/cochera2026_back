<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Faker\Factory as Faker;
use Carbon\Carbon;

class GenerateCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:generate {count=20 : Número de companies a generar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar companies de prueba con datos realistas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');

        $this->info("🏢 Generando {$count} companies de prueba...");

        // Configurar Faker en español
        $faker = Faker::create('es_ES');

        // Tipos de empresas para más realismo
        $tiposEmpresa = [
            'S.L.', 'S.A.', 'S.L.U.', 'Sociedad Cooperativa', 'Asociaciones',
            'Fundaciones', 'Cooperativas', 'Autónomos'
        ];

        $sectores = [
            'Tecnología', 'Consultoria', 'Marketing', 'Construcción', 'Alimentación',
            'Textil', 'Transporte', 'Educación', 'Salud', 'Turismo', 'Energía',
            'Finanzas', 'Retail', 'Manufactura', 'Servicios'
        ];

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $nombreBase = $faker->company;
            $tipo = $faker->randomElement($tiposEmpresa);
            $sector = $faker->randomElement($sectores);

            Company::create([
                'nombre' => $nombreBase . ' ' . $tipo,
                'ubicacion' => $faker->address,
                'logo' => $faker->imageUrl(200, 200, 'business', true, $nombreBase),
                'descripcion' => "Empresa del sector {$sector}. " . $faker->realText(200),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $total = Company::count();
        $this->info("✅ {$count} companies generadas exitosamente!");
        $this->info("📊 Total de companies en la base de datos: {$total}");
        $this->newLine();
        $this->comment('💡 Comandos útiles:');
        $this->comment('   php artisan companies:list - Ver companies');
        $this->comment('   php artisan companies:list --search="Tecnología" - Buscar companies');
    }
}
