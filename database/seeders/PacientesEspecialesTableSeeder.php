<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacientesEspecialesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pacientes_especiales')->insert([
            'paciente_id' => 1,
            'tipo' => 'Menor de Edad',
            'observaciones' => 'Paciente menor de edad, requiere representante legal',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
