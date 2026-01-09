<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadesTableSeeder extends Seeder
{
    public function run(): void
    {
        $especialidades = [
            // IDs 1-7 (Originales)
            ['nombre' => 'Cardiología', 'descripcion' => 'Especialidad en enfermedades del corazón'],
            ['nombre' => 'Pediatría', 'descripcion' => 'Medicina para niños y adolescentes'],
            ['nombre' => 'Dermatología', 'descripcion' => 'Especialidad en enfermedades de la piel'],
            ['nombre' => 'Ginecología', 'descripcion' => 'Salud femenina y sistema reproductivo'],
            ['nombre' => 'Traumatología', 'descripcion' => 'Especialidad en huesos y músculos'],
            ['nombre' => 'Oftalmología', 'descripcion' => 'Especialidad en ojos y visión'],
            ['nombre' => 'Medicina General', 'descripcion' => 'Atención primaria y general'],
            
            // IDs 8-20 (Nuevas)
            ['nombre' => 'Neurología', 'descripcion' => 'Especialidad en el sistema nervioso'],
            ['nombre' => 'Psiquiatría', 'descripcion' => 'Salud mental y trastornos'],
            ['nombre' => 'Gastroenterología', 'descripcion' => 'Sistema digestivo'],
            ['nombre' => 'Urología', 'descripcion' => 'Sistema urinario y aparato reproductor masculino'],
            ['nombre' => 'Otorrinolaringología', 'descripcion' => 'Oído, nariz y garganta'],
            ['nombre' => 'Neumología', 'descripcion' => 'Enfermedades respiratorias y pulmones'],
            ['nombre' => 'Endocrinología', 'descripcion' => 'Sistema endocrino y hormonas'],
            ['nombre' => 'Reumatología', 'descripcion' => 'Enfermedades musculoesqueléticas y autoinmunes'],
            ['nombre' => 'Nefrología', 'descripcion' => 'Enfermedades de los riñones'],
            ['nombre' => 'Oncología', 'descripcion' => 'Diagnóstico y tratamiento del cáncer'],
            ['nombre' => 'Hematología', 'descripcion' => 'Enfermedades de la sangre'],
            ['nombre' => 'Medicina Interna', 'descripcion' => 'Atención integral del adulto'],
            ['nombre' => 'Anestesiología', 'descripcion' => 'Cuidado perioperatorio y manejo del dolor'],
        ];

        foreach ($especialidades as $especialidad) {
            DB::table('especialidades')->updateOrInsert(
                ['nombre' => $especialidad['nombre']],
                array_merge($especialidad, [
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
