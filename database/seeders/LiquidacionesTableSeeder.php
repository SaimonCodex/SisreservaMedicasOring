<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiquidacionesTableSeeder extends Seeder
{
    public function run(): void
    {
        $liquidaciones = [
            [
                'entidad_tipo' => 'Medico',
                'entidad_id' => 2,
                'monto_total_usd' => 28.00,
                'monto_total_bs' => 994.00,
                'metodo_pago' => 'Transferencia',
                'referencia' => 'TXN-'.now()->format('Ymd').'-001',
                'fecha_pago' => now()->addDays(30)->format('Y-m-d'),
                'observaciones' => 'Liquidación mensual de honorarios - Dra. González',
            ],
            [
                'entidad_tipo' => 'Consultorio',
                'entidad_id' => 1,
                'monto_total_usd' => 8.00,
                'monto_total_bs' => 284.00,
                'metodo_pago' => 'Transferencia',
                'referencia' => 'TXN-'.now()->format('Ymd').'-002',
                'fecha_pago' => now()->addDays(30)->format('Y-m-d'),
                'observaciones' => 'Pago por alquiler de consultorio',
            ],
        ];

        foreach ($liquidaciones as $liquidacion) {
            DB::table('liquidaciones')->insert(array_merge($liquidacion, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
