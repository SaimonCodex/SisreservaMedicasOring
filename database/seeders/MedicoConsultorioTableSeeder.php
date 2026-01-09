<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MedicoConsultorioTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $horarios = [];
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        // Médicos 1-20
        for ($medicoId = 1; $medicoId <= 20; $medicoId++) {
            // Asignar a 1-3 consultorios
            $numConsultorios = $faker->numberBetween(1, 3);
            $consultoriosIds = $faker->randomElements(range(1, 8), $numConsultorios);

            foreach ($consultoriosIds as $consultorioId) {
                // Asignar días de trabajo en este consultorio (1-3 días)
                $diasTrabajo = $faker->randomElements($dias, $faker->numberBetween(1, 3));
                
                foreach ($diasTrabajo as $dia) {
                    $turno = $faker->randomElement(['mañana', 'tarde', 'completo']);
                    
                    if ($turno === 'mañana') {
                        $inicio = '08:00:00';
                        $fin = '12:00:00';
                    } elseif ($turno === 'tarde') {
                        $inicio = '13:00:00';
                        $fin = '17:00:00';
                    } else {
                        $inicio = '08:00:00';
                        $fin = '16:00:00';
                    }

                    $horarios[] = [
                        'medico_id' => $medicoId,
                        'consultorio_id' => $consultorioId,
                        'dia_semana' => $dia,
                        'turno' => $turno,
                        'horario_inicio' => $inicio,
                        'horario_fin' => $fin,
                        'status' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }
        
        // Insertar en chunks para evitar problemas de memoria
        foreach (array_chunk($horarios, 100) as $chunk) {
            DB::table('medico_consultorio')->insert($chunk);
        }
    }
}
