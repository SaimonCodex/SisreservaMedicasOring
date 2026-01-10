<?php

namespace App\Services;

use App\Models\TasaDolar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DollarExchangeService
{
    /**
     * Obtener la tasa del BCV desde una API externa.
     * 
     * @return float|null
     */
    public function fetchRate($source = 'BCV')
    {
        try {
            // URL de API: ve.dolarapi.com
            // Esta API devuelve el promedio, compra, venta, fecha, etc.
            $response = Http::timeout(10)->withoutVerifying()->get('https://ve.dolarapi.com/v1/dolares/oficial');
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Estructura: { "promedio": 36.5, "fechaActualizacion": "..." }
                $price = $data['promedio'] ?? null;
                
                return $price;
            }
            
            Log::error("Error fetching dollar rate: API responded with status " . $response->status());
            return null;

        } catch (\Exception $e) {
            Log::error("Error fetching dollar rate: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Sincronizar la tasa y guardarla en la base de datos.
     */
    public function syncRate()
    {
        $rate = $this->fetchRate();

        if ($rate) {
            // Verificar si ya existe una tasa para hoy
            $exists = TasaDolar::where('fecha_tasa', today())
                               ->where('fuente', 'BCV')
                               ->exists();

            if (!$exists) {
                TasaDolar::create([
                    'fuente' => 'BCV',
                    'valor' => $rate,
                    'fecha_tasa' => today(),
                    'status' => true
                ]);
                Log::info("Dollar rate updated successfully: $rate");
                return true;
            } else {
                // Verificar si el valor cambió para actualizarlo
                $existingRate = TasaDolar::where('fecha_tasa', today())
                                       ->where('fuente', 'BCV')
                                       ->first();
                
                if ($existingRate && $existingRate->valor != $rate) {
                    $existingRate->update([
                        'valor' => $rate
                    ]);
                    Log::info("Dollar rate updated due to change: $rate");
                    return true;
                }

                Log::info("Dollar rate already exists for today and has not changed.");
                return true;
            }
        }
        
        return false;
    }
}
