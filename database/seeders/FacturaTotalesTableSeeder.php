<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturaTotalesTableSeeder extends Seeder
{
    public function run(): void
    {
        $totales = [
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Paciente',
                'entidad_id' => 2,
                'base_imponible_usd' => 40.00,
                'impuestos_usd' => 0.00,
                'total_final_usd' => 40.00,
                'total_final_bs' => 1420.00,
                'estado_liquidacion' => 'Liquidado',
            ],
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Medico',
                'entidad_id' => 2,
                'base_imponible_usd' => 28.00,
                'impuestos_usd' => 0.00,
                'total_final_usd' => 28.00,
                'total_final_bs' => 994.00,
                'estado_liquidacion' => 'Pendiente',
            ],
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Consultorio',
                'entidad_id' => 1,
                'base_imponible_usd' => 8.00,
                'impuestos_usd' => 0.00,
                'total_final_usd' => 8.00,
                'total_final_bs' => 284.00,
                'estado_liquidacion' => 'Pendiente',
            ],
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Sistema',
                'entidad_id' => null,
                'base_imponible_usd' => 4.00,
                'impuestos_usd' => 0.00,
                'total_final_usd' => 4.00,
                'total_final_bs' => 142.00,
                'estado_liquidacion' => 'No Aplica',
            ],
        ];

        foreach ($totales as $total) {
            DB::table('factura_totales')->insert(array_merge($total, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
