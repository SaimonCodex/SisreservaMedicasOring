<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasasDolarTableSeeder extends Seeder
{
    public function run(): void
    {
        $tasas = [
            ['fuente' => 'BCV', 'valor' => 35.50, 'fecha_tasa' => now()->format('Y-m-d')],
            ['fuente' => 'BCV', 'valor' => 35.20, 'fecha_tasa' => now()->subDays(1)->format('Y-m-d')],
            ['fuente' => 'BCV', 'valor' => 34.80, 'fecha_tasa' => now()->subDays(2)->format('Y-m-d')],
        ];

        foreach ($tasas as $tasa) {
            DB::table('tasas_dolar')->insert(array_merge($tasa, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
