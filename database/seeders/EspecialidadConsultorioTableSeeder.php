<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadConsultorioTableSeeder extends Seeder
{
    public function run(): void
    {
        $relaciones = [
            ['especialidad_id' => 1, 'consultorio_id' => 1], // Cardiología en Consultorio Central
            ['especialidad_id' => 2, 'consultorio_id' => 1], // Pediatría en Consultorio Central
            ['especialidad_id' => 2, 'consultorio_id' => 2], // Pediatría en Clínica Los Teques
            ['especialidad_id' => 7, 'consultorio_id' => 1], // Medicina General en Consultorio Central
            ['especialidad_id' => 7, 'consultorio_id' => 2], // Medicina General en Clínica Los Teques
        ];

        foreach ($relaciones as $relacion) {
            DB::table('especialidad_consultorio')->insert(array_merge($relacion, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
