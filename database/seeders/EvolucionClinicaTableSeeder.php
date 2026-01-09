<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvolucionClinicaTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('evolucion_clinica')->insert([
            'cita_id' => 2,
            'paciente_id' => 2,
            'medico_id' => 2,
            'peso_kg' => 65.5,
            'talla_cm' => 165.0,
            'imc' => 24.1,
            'tension_sistolica' => 120,
            'tension_diastolica' => 80,
            'frecuencia_cardiaca' => 72,
            'temperatura_c' => 36.5,
            'frecuencia_respiratoria' => 16,
            'saturacion_oxigeno' => 98.0,
            'motivo_consulta' => 'Control pediátrico anual',
            'enfermedad_actual' => 'Paciente asintomática, realiza control de rutina',
            'examen_fisico' => 'Paciente en buen estado general, mucosas húmedas, buena hidratación',
            'diagnostico' => 'Control de niño sano',
            'tratamiento' => 'Continuar con hábitos saludables, control en 6 meses',
            'recomendaciones' => 'Mantener dieta balanceada y ejercicio regular',
            'notas_adicionales' => 'Paciente muy colaboradora',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
