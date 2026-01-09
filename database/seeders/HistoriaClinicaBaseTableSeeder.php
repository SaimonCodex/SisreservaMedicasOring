<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoriaClinicaBaseTableSeeder extends Seeder
{
    public function run(): void
    {
        $historias = [
            [
                'paciente_id' => 1,
                'tipo_sangre' => 'O+',
                'alergias' => 'Polvo, ácaros',
                'alergias_medicamentos' => 'Penicilina',
                'antecedentes_familiares' => 'Diabetes, Hipertensión',
                'antecedentes_personales' => 'Asma infantil',
                'enfermedades_cronicas' => 'Ninguna',
                'medicamentos_actuales' => 'Ninguno',
                'cirugias_previas' => 'Apendicectomía (2010)',
                'habitos' => 'No fuma, ejercicio regular 3 veces por semana',
            ],
            [
                'paciente_id' => 2,
                'tipo_sangre' => 'A+',
                'alergias' => 'Ninguna conocida',
                'alergias_medicamentos' => 'Ninguna conocida',
                'antecedentes_familiares' => 'Cáncer de mama (abuela)',
                'antecedentes_personales' => 'Varicela, Paperas',
                'enfermedades_cronicas' => 'Migraña',
                'medicamentos_actuales' => 'Ibuprofeno ocasional',
                'cirugias_previas' => 'Cesárea (2015)',
                'habitos' => 'No fuma, dieta balanceada',
            ],
        ];

        foreach ($historias as $historia) {
            DB::table('historia_clinica_base')->insert(array_merge($historia, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
