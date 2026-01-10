<?php

namespace App\Console\Commands;

use App\Models\TasaDolar;
use App\Services\DollarExchangeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ActualizarTasasDolar extends Command
{
    protected $signature = 'tasas:actualizar';
    protected $description = 'Actualizar tasas de dólar desde fuentes externas';

    public function handle(DollarExchangeService $service)
    {
        $this->info("Iniciando actualización de tasas...");

        if ($service->syncRate()) {
            $this->info("Tasa BCV sincronizada exitosamente.");
        } else {
            $this->error("No se pudo sincronizar la tasa BCV.");
        }

        $this->info("Proceso completado.");
    }
}