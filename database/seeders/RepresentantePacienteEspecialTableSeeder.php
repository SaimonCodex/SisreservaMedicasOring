<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepresentantePacienteEspecialTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('representante_paciente_especial')->insert([
            'representante_id' => 1,
            'paciente_especial_id' => 1,
            'tipo_responsabilidad' => 'Principal',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
