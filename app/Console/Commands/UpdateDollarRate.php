<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DollarExchangeService;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Log;

class UpdateDollarRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dollar:update {--force : Forzar actualización ignorando configuración}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar la tasa del dólar BCV desde API externa';

    /**
     * Execute the console command.
     */
    public function handle(DollarExchangeService $service)
    {
        $force = $this->option('force');
        
        // Verificar configuración
        $autoUpdate = Configuracion::where('key', 'auto_update_tasa')->value('value');
        
        if ($autoUpdate !== '1' && !$force) {
            $this->info('La actualización automática está desactivada.');
            return;
        }

        $this->info('Obteniendo tasa del dólar...');
        
        if ($service->syncRate()) {
            $this->info('Tasa actualizada correctamente.');
        } else {
            $this->error('Error al actualizar la tasa.');
        }
    }
}
