<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagoTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pago')->insert([
            'id_factura_paciente' => 1,
            'id_metodo' => 4, // Pago Móvil
            'fecha_pago' => now()->format('Y-m-d'),
            'monto_pagado_bs' => 1420.00,
            'monto_equivalente_usd' => 40.00,
            'tasa_aplicada_id' => 1,
            'referencia' => 'PM123456789',
            'comentarios' => 'Pago realizado mediante Pago Móvil',
            'estado' => 'Confirmado',
            'confirmado_por' => 1, // Administrador
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
