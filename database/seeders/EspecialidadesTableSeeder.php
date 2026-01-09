<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadesTableSeeder extends Seeder
{
    public function run(): void
    {
        $especialidades = [
            ['nombre' => 'Cardiología', 'descripcion' => 'Especialidad en enfermedades del corazón'],
            ['nombre' => 'Pediatría', 'descripcion' => 'Medicina para niños y adolescentes'],
            ['nombre' => 'Dermatología', 'descripcion' => 'Especialidad en enfermedades de la piel'],
            ['nombre' => 'Ginecología', 'descripcion' => 'Salud femenina y sistema reproductivo'],
            ['nombre' => 'Traumatología', 'descripcion' => 'Especialidad en huesos y músculos'],
            ['nombre' => 'Oftalmología', 'descripcion' => 'Especialidad en ojos y visión'],
            ['nombre' => 'Medicina General', 'descripcion' => 'Atención primaria y general'],
        ];

        foreach ($especialidades as $especialidad) {
            DB::table('especialidades')->insert(array_merge($especialidad, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
