<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TasasDolarTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now = now();
        $tasas = [];
        $valorActual = 35.50; // Valor base

        // Generar tasas para los últimos 60 días
        for ($i = 60; $i >= 0; $i--) {
            // Fluctación aleatoria pequeña
            $fluctuacion = $faker->randomFloat(2, -0.5, 0.8);
            $valorActual += $fluctuacion;
            
            // Asegurar que no sea negativo ni demasiado bajo
            if ($valorActual < 20) $valorActual = 20;

            $tasas[] = [
                'fuente' => 'BCV',
                'valor' => number_format($valorActual, 2, '.', ''),
                'fecha_tasa' => now()->subDays($i)->format('Y-m-d'),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($tasas, 50) as $chunk) {
            DB::table('tasas_dolar')->insert($chunk);
        }
    }
}
