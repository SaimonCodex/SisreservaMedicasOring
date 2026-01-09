<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicoEspecialidadTableSeeder extends Seeder
{
    public function run(): void
    {
        $relaciones = [
            ['medico_id' => 1, 'especialidad_id' => 1, 'tarifa' => 50.00, 'anos_experiencia' => 15], // Cardiología
            ['medico_id' => 2, 'especialidad_id' => 2, 'tarifa' => 40.00, 'anos_experiencia' => 10], // Pediatría
            ['medico_id' => 1, 'especialidad_id' => 7, 'tarifa' => 30.00, 'anos_experiencia' => 15], // Medicina General
        ];

        foreach ($relaciones as $relacion) {
            DB::table('medico_especialidad')->insert(array_merge($relacion, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
