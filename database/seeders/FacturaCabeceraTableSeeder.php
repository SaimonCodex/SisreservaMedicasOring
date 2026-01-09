<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturaCabeceraTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('factura_cabecera')->insert([
            'cita_id' => 2,
            'nro_control' => 'CTL-'.now()->format('Ymd').'-001',
            'paciente_id' => 2,
            'medico_id' => 2,
            'tasa_id' => 1,
            'fecha_emision' => now(),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
