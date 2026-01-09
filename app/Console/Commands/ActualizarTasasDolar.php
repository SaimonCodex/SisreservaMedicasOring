<?php

namespace App\Console\Commands;

use App\Models\TasaDolar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ActualizarTasasDolar extends Command
{
    protected $signature = 'tasas:actualizar';
    protected $description = 'Actualizar tasas de dólar desde fuentes externas';

    public function handle()
    {
        // Fuente BCV (ejemplo)
        try {
            $response = Http::get('https://api.bcv.org.ve/api/v1/tasas');
            if ($response->successful()) {
                $data = $response->json();
                
                TasaDolar::create([
                    'fuente' => 'BCV',
                    'valor' => $data['tasa_usd'] ?? 0,
                    'status' => true
                ]);
                
                $this->info("Tasa BCV actualizada: " . ($data['tasa_usd'] ?? 0));
            }
        } catch (\Exception $e) {
            $this->error("Error obteniendo tasa BCV: " . $e->getMessage());
        }

        // Otras fuentes pueden agregarse aquí

        $this->info("Proceso de actualización de tasas completado");
    }
}