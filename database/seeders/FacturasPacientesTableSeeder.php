<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturasPacientesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('facturas_pacientes')->insert([
            'cita_id' => 2,
            'paciente_id' => 2,
            'medico_id' => 2,
            'monto_usd' => 40.00,
            'tasa_id' => 1, // Última tasa disponible
            'monto_bs' => 1420.00, // 40 * 35.50
            'fecha_emision' => now()->format('Y-m-d'),
            'fecha_vencimiento' => now()->addDays(15)->format('Y-m-d'),
            'numero_factura' => 'FAC-'.now()->format('Ymd').'-001',
            'status_factura' => 'Emitida',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
