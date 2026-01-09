<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdenesMedicasTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ordenes_medicas')->insert([
            'cita_id' => 2,
            'paciente_id' => 2,
            'medico_id' => 2,
            'tipo_orden' => 'Laboratorio',
            'descripcion_detallada' => 'Hemograma completo, perfil lipídico, glicemia',
            'indicaciones' => 'Ayuno de 8 horas previas',
            'resultados' => null,
            'fecha_emision' => now()->format('Y-m-d'),
            'fecha_vigencia' => now()->addDays(30)->format('Y-m-d'),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
