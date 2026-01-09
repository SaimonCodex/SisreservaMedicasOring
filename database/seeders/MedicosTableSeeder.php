<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MedicosTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');

        $medicos = [];
        $now = now();

        // 1. Médicos Fijos (IDs 2 y 3)
        $medicos[] = [
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
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $medicos[] = [
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
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // 2. Médicos Generados (IDs 10-27) - 18 médicos
        for ($i = 0; $i < 18; $i++) {
            $medicos[] = [
                'user_id' => 10 + $i,
                'primer_nombre' => $faker->firstName,
                'primer_apellido' => $faker->lastName,
                'tipo_documento' => $faker->randomElement(['V', 'E']),
                'numero_documento' => $faker->unique()->numberBetween(10000000, 30000000),
                'fecha_nac' => $faker->dateTimeBetween('-60 years', '-28 years')->format('Y-m-d'),
                'estado_id' => 1, // Simplificamos usando IDs existentes o aleatorios si hay más
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => $faker->address,
                'prefijo_tlf' => '+58',
                'numero_tlf' => '4' . $faker->randomElement(['12', '14', '16', '24', '26']) . $faker->numberBetween(1000000, 9999999),
                'genero' => $faker->randomElement(['Masculino', 'Femenino']),
                'nro_colegiatura' => 'MP-' . $faker->unique()->numberBetween(10000, 99999),
                'formacion_academica' => 'Médico Cirujano - ' . $faker->randomElement(['UCV', 'ULA', 'LUZ', 'UC']) . '\nEspecialidad en ' . $faker->word,
                'experiencia_profesional' => $faker->numberBetween(3, 30) . ' años de experiencia.',
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('medicos')->insert($medicos);
    }
}
