<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicoConsultorioTableSeeder extends Seeder
{
    public function run(): void
    {
        $horarios = [
            // Dr. Pérez en Consultorio Central - Lunes y Miércoles
            ['medico_id' => 1, 'consultorio_id' => 1, 'dia_semana' => 'Lunes', 'turno' => 'mañana', 'horario_inicio' => '08:00:00', 'horario_fin' => '12:00:00'],
            ['medico_id' => 1, 'consultorio_id' => 1, 'dia_semana' => 'Miércoles', 'turno' => 'mañana', 'horario_inicio' => '08:00:00', 'horario_fin' => '12:00:00'],
            
            // Dra. González en Consultorio Central - Martes y Jueves
            ['medico_id' => 2, 'consultorio_id' => 1, 'dia_semana' => 'Martes', 'turno' => 'tarde', 'horario_inicio' => '14:00:00', 'horario_fin' => '18:00:00'],
            ['medico_id' => 2, 'consultorio_id' => 1, 'dia_semana' => 'Jueves', 'turno' => 'tarde', 'horario_inicio' => '14:00:00', 'horario_fin' => '18:00:00'],
            
            // Dra. González en Clínica Los Teques - Viernes
            ['medico_id' => 2, 'consultorio_id' => 2, 'dia_semana' => 'Viernes', 'turno' => 'completo', 'horario_inicio' => '08:00:00', 'horario_fin' => '16:00:00'],
        ];

        foreach ($horarios as $horario) {
            DB::table('medico_consultorio')->insert(array_merge($horario, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
