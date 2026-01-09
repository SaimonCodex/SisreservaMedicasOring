<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiquidacionDetallesTableSeeder extends Seeder
{
    public function run(): void
    {
        $detalles = [
            [
                'liquidacion_id' => 1,
                'factura_total_id' => 2, // Total del médico
            ],
            [
                'liquidacion_id' => 2,
                'factura_total_id' => 3, // Total del consultorio
            ],
        ];

        foreach ($detalles as $detalle) {
            DB::table('liquidacion_detalles')->insert(array_merge($detalle, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
