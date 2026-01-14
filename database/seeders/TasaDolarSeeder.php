<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasaDolarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tasa actual del BCV (ejemplo - ajustar según tasa real)
        $tasas = [
            [
                'fuente' => 'BCV',
                'valor' => 45.50,
                'fecha_tasa' => now()->subDays(2),
                'status' => false,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2)
            ],
            [
                'fuente' => 'BCV',
                'valor' => 46.20,
                'fecha_tasa' => now()->subDay(),
                'status' => false,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay()
            ],
            [
                'fuente' => 'BCV',
                'valor' => 46.85,
                'fecha_tasa' => now(),
                'status' => true, // Esta es la tasa activa
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('tasas_dolar')->insert($tasas);
    }
}
