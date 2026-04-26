<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateTransactionTables extends Command
{
    protected $signature = 'db:truncate
        {--force : Ejecutar sin confirmación}
        {--seed : Ejecutar seeders después de truncar}
        {--only-transactions : Solo truncar tablas transaccionales (no maestras)}';

    protected $description = 'Trunca tablas de datos y opcionalmente re-seedea. Por defecto trunca todo (maestras + transaccionales).';

    /**
     * Tablas maestras (se llenan por web/seeders)
     * Orden: hijas primero, padres después (por FK)
     */
    private array $masterTables = [
        // Hijas de relación
        'product_enterprises',
        'enterprise_rels_enterprises',
        'targeted_rels_inspections',
        'targeted_rels_load_types',
        // Evidencias y categorías
        'evidences',
        'categories',
        // Empleados
        'employees',
        // Productos
        'products',
        // Empresas
        'enterprises',
        // Dirigidos
        'targeteds',
        // Catálogos base
        'checkpoints',
        'product_types',
        'unit_types',
        'load_types',
        'inspection_types',
        'enterprise_types',
        // units y companies NO se truncan (tienen seeder con datos base)
    ];

    /**
     * Tablas transaccionales (se llenan por API móvil / web)
     * Orden: hijas primero, padres después (por FK)
     */
    private array $transactionTables = [
        'evidence_rels_inspections',
        'inspection_convoys',
        'inspections',
        'daily_dialogs',
        'active_pauses',
        'alcohol_test_details',
        'alcohol_tests',
        'gps_controls',
        'unit_movement_details',
        'unit_movements',
        'error_logs',
    ];

    public function handle(): int
    {
        $onlyTransactions = $this->option('only-transactions');

        $tables = $onlyTransactions
            ? $this->transactionTables
            : array_merge($this->transactionTables, $this->masterTables);

        $label = $onlyTransactions ? 'transaccionales' : 'transaccionales + maestras';
        $this->warn("Se truncarán las tablas {$label}:");
        $this->newLine();

        $totalRecords = 0;
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $totalRecords += $count;
                $this->line("  - {$table} ({$count} registros)");
            }
        }

        $this->newLine();
        $this->warn("Total: " . count($tables) . " tablas, {$totalRecords} registros");

        if (!$onlyTransactions) {
            $this->newLine();
            $this->warn('⚠ Todas las tablas maestras tienen CRUD web. También se pueden re-seedear con: php artisan db:seed');
        }

        if (!$this->option('force') && !$this->confirm('¿Estás seguro de que deseas truncar estas tablas?')) {
            $this->info('Operación cancelada.');
            return 0;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->info("  ✓ {$table}");
            } else {
                $this->error("  ✗ {$table} no existe");
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->newLine();
        $this->info("Tablas {$label} truncadas correctamente.");

        if ($this->option('seed') || (!$this->option('force') && !$onlyTransactions && $this->confirm('¿Deseas ejecutar los seeders ahora?'))) {
            $this->newLine();
            $this->info('Ejecutando seeders...');
            $this->call('db:seed', ['--force' => true]);
            $this->info('Seeders ejecutados correctamente.');
        }

        return 0;
    }
}
