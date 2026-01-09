<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EspecialidadConsultorioTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $relaciones = [];

        // Consultorios 1-8
        for ($consultorioId = 1; $consultorioId <= 8; $consultorioId++) {
            // Asignar entre 3 y 8 especialidades por consultorio
            $numEspecialidades = $faker->numberBetween(3, 8);
            $especialidadesIds = $faker->randomElements(range(1, 20), $numEspecialidades);

            foreach ($especialidadesIds as $espId) {
                $relaciones[] = [
                    'especialidad_id' => $espId,
                    'consultorio_id' => $consultorioId
                ];
            }
        }
        
        // Asegurar algunas asignaciones básicas si el random no las cubrió
        $relaciones[] = ['especialidad_id' => 1, 'consultorio_id' => 1];
        $relaciones[] = ['especialidad_id' => 2, 'consultorio_id' => 1];
        $relaciones[] = ['especialidad_id' => 7, 'consultorio_id' => 1];

        foreach ($relaciones as $relacion) {
            DB::table('especialidad_consultorio')->updateOrInsert(
                ['especialidad_id' => $relacion['especialidad_id'], 'consultorio_id' => $relacion['consultorio_id']],
                array_merge($relacion, [
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
