<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FechaIndisponibleTableSeeder extends Seeder
{
    public function run(): void
    {
        $fechas = [
            [
                'medico_id' => 1,
                'consultorio_id' => 1,
                'fecha' => now()->addDays(10)->format('Y-m-d'),
                'motivo' => 'Congreso Nacional de Cardiología',
                'todo_el_dia' => true,
                'hora_inicio' => null,
                'hora_fin' => null,
            ],
            [
                'medico_id' => 2,
                'consultorio_id' => 2,
                'fecha' => now()->addDays(15)->format('Y-m-d'),
                'motivo' => 'Vacaciones',
                'todo_el_dia' => true,
                'hora_inicio' => null,
                'hora_fin' => null,
            ],
            [
                'medico_id' => 1,
                'consultorio_id' => null, // Indisponibilidad general
                'fecha' => now()->addDays(20)->format('Y-m-d'),
                'motivo' => 'Capacitación interna',
                'todo_el_dia' => false,
                'hora_inicio' => '14:00:00',
                'hora_fin' => '16:00:00',
            ],
        ];

        foreach ($fechas as $fecha) {
            DB::table('fecha_indisponible')->insert(array_merge($fecha, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
