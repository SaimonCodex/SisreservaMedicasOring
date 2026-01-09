<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitasTableSeeder extends Seeder
{
    public function run(): void
    {
        $citas = [
            [
                'paciente_id' => 1,
                'medico_id' => 1,
                'especialidad_id' => 1, // Cardiología
                'consultorio_id' => 1,
                'fecha_cita' => now()->addDays(5)->format('Y-m-d'),
                'hora_inicio' => '09:00:00',
                'hora_fin' => '09:30:00',
                'duracion_minutos' => 30,
                'tarifa' => 50.00,
                'tipo_consulta' => 'Presencial',
                'estado_cita' => 'Programada',
                'observaciones' => 'Control cardíaco rutinario',
            ],
            [
                'paciente_id' => 2,
                'medico_id' => 2,
                'especialidad_id' => 2, // Pediatría
                'consultorio_id' => 1,
                'fecha_cita' => now()->addDays(3)->format('Y-m-d'),
                'hora_inicio' => '15:00:00',
                'hora_fin' => '15:45:00',
                'duracion_minutos' => 45,
                'tarifa' => 40.00,
                'tipo_consulta' => 'Presencial',
                'estado_cita' => 'Confirmada',
                'observaciones' => 'Consulta pediátrica de control',
            ],
        ];

        foreach ($citas as $cita) {
            DB::table('citas')->insert(array_merge($cita, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
