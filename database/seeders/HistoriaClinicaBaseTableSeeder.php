<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class HistoriaClinicaBaseTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $historias = [];

        // Generar historia para cada paciente (1 al 30)
        for ($pacienteId = 1; $pacienteId <= 30; $pacienteId++) {
            
            $tieneAlergia = $faker->boolean(30);
            $tieneEnfermedad = $faker->boolean(20);
            
            $historias[] = [
                'paciente_id' => $pacienteId,
                'tipo_sangre' => $faker->randomElement(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']),
                'alergias' => $tieneAlergia ? $faker->randomElement(['Polvo', 'Polen', 'Ácaros', 'Mariscos', 'Nueces', 'Gatos']) : 'Ninguna conocida',
                'alergias_medicamentos' => $faker->boolean(15) ? $faker->randomElement(['Penicilina', 'Sulfa', 'Aspirina']) : 'Ninguna conocida',
                'antecedentes_familiares' => $faker->boolean(40) ? $faker->randomElement(['Diabetes', 'Hipertensión', 'Cáncer', 'Enfermedades cardíacas']) : 'Ninguno relevante',
                'antecedentes_personales' => $faker->sentence,
                'enfermedades_cronicas' => $tieneEnfermedad ? $faker->randomElement(['Diabetes Tipo 2', 'Hipertensión Arterial', 'Asma']) : 'Ninguna',
                'medicamentos_actuales' => $tieneEnfermedad ? $faker->randomElement(['Losartán', 'Metformina', 'Inhalador']) : 'Ninguno',
                'cirugias_previas' => $faker->boolean(25) ? $faker->randomElement(['Apendicectomía', 'Cesárea', 'Amigdalectomía']) . ' (' . $faker->year . ')' : 'Ninguna',
                'habitos' => $faker->randomElement(['No fuma, ejercicio regular', 'Fumador ocasional', 'Sedentario', 'Bebedor social']),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($historias, 50) as $chunk) {
            DB::table('historia_clinica_base')->insert($chunk); // O updateOrInsert si fuera necesario, pero migrate:fresh limpia todo
        }
    }
}
