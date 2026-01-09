<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturaDetallesTableSeeder extends Seeder
{
    public function run(): void
    {
        $detalles = [
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Paciente',
                'entidad_id' => 2,
                'descripcion' => 'Consulta de Pediatría - Dra. González',
                'cantidad' => 1,
                'precio_unitario_usd' => 40.00,
                'subtotal_usd' => 40.00,
            ],
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Medico',
                'entidad_id' => 2,
                'descripcion' => 'Honorarios Médicos (70%)',
                'cantidad' => 1,
                'precio_unitario_usd' => 28.00,
                'subtotal_usd' => 28.00,
            ],
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Consultorio',
                'entidad_id' => 1,
                'descripcion' => 'Alquiler Consultorio (20%)',
                'cantidad' => 1,
                'precio_unitario_usd' => 8.00,
                'subtotal_usd' => 8.00,
            ],
            [
                'cabecera_id' => 1,
                'entidad_tipo' => 'Sistema',
                'entidad_id' => null,
                'descripcion' => 'Comisión Sistema (10%)',
                'cantidad' => 1,
                'precio_unitario_usd' => 4.00,
                'subtotal_usd' => 4.00,
            ],
        ];

        foreach ($detalles as $detalle) {
            DB::table('factura_detalles')->insert(array_merge($detalle, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
