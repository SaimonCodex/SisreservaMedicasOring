<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicosTableSeeder extends Seeder
{
    public function run(): void
    {
        $medicos = [
            [
                'user_id' => 2,
                'primer_nombre' => 'Carlos',
                'primer_apellido' => 'Pérez',
                'tipo_documento' => 'V',
                'numero_documento' => '87654321',
                'fecha_nac' => '1975-08-20',
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => 'Urbanización Los Caobos, Calle 2',
                'prefijo_tlf' => '+58',
                'numero_tlf' => '4145558877',
                'genero' => 'Masculino',
                'nro_colegiatura' => 'MP-12345',
                'formacion_academica' => 'Médico Cirujano - Universidad Central de Venezuela\nEspecialista en Cardiología',
                'experiencia_profesional' => '15 años de experiencia en cardiología intervencionista',
            ],
            [
                'user_id' => 3,
                'primer_nombre' => 'Ana',
                'primer_apellido' => 'González',
                'tipo_documento' => 'V',
                'numero_documento' => '11222333',
                'fecha_nac' => '1982-03-10',
                'estado_id' => 2,
                'ciudad_id' => 2,
                'municipio_id' => 2,
                'parroquia_id' => 3,
                'direccion_detallada' => 'Residencias El Paraíso, Torre B',
                'prefijo_tlf' => '+58',
                'numero_tlf' => '4165559988',
                'genero' => 'Femenino',
                'nro_colegiatura' => 'MP-67890',
                'formacion_academica' => 'Médico Pediatra - Universidad de Carabobo',
                'experiencia_profesional' => '10 años en atención pediátrica',
            ],
        ];

        foreach ($medicos as $medico) {
            DB::table('medicos')->insert(array_merge($medico, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
