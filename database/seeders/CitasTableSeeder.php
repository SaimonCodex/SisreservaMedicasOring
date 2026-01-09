<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CitasTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $citas = [];

        // Generar 150 citas
        for ($i = 0; $i < 150; $i++) {
            // Fecha aleatoria: últimos 2 meses a próximos 2 meses
            $fecha = $faker->dateTimeBetween('-2 months', '+2 months');
            $horaInicio = $faker->randomElement(['08:00:00', '09:00:00', '10:00:00', '11:00:00', '14:00:00', '15:00:00', '16:00:00']);
            
            // Calcular hora fin (30 min después)
            $horaFin = date('H:i:s', strtotime($horaInicio) + 1800);
            
            // Estado basado en fecha
            if ($fecha < $now) {
                $estado = $faker->randomElement(['Completada', 'Cancelada', 'No Asistió']);
            } else {
                $estado = $faker->randomElement(['Programada', 'Confirmada']);
            }

            $citas[] = [
                'paciente_id' => $faker->numberBetween(1, 30),
                'medico_id' => $faker->numberBetween(1, 20),
                'especialidad_id' => $faker->numberBetween(1, 20),
                'consultorio_id' => $faker->numberBetween(1, 8),
                'fecha_cita' => $fecha->format('Y-m-d'),
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
                'duracion_minutos' => 30,
                'tarifa' => $faker->randomFloat(2, 20, 100),
                'tipo_consulta' => $faker->randomElement(['Presencial', 'Online']),
                'estado_cita' => $estado,
                'observaciones' => $faker->sentence,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($citas, 50) as $chunk) {
            DB::table('citas')->insert($chunk);
        }
    }
}
