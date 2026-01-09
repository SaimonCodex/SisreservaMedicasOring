<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PacientesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $pacientes = [];

        // 1. Pacientes Fijos (IDs 4 y 5)
        $pacientes[] = [
            'user_id' => 4,
            'primer_nombre' => 'Juan',
            'primer_apellido' => 'Martínez',
            'tipo_documento' => 'V',
            'numero_documento' => '22333444',
            'fecha_nac' => '1990-12-05',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Sector La Trinidad, Calle Principal',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4245557766',
            'genero' => 'Masculino',
            'ocupacion' => 'Ingeniero',
            'estado_civil' => 'Soltero',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $pacientes[] = [
            'user_id' => 5,
            'primer_nombre' => 'Laura',
            'primer_apellido' => 'Hernández',
            'tipo_documento' => 'V',
            'numero_documento' => '33444555',
            'fecha_nac' => '1985-07-22',
            'estado_id' => 2,
            'ciudad_id' => 3,
            'municipio_id' => 3,
            'parroquia_id' => 4,
            'direccion_detallada' => 'Urbanización Mirador, Casa 25',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4265554433',
            'genero' => 'Femenino',
            'ocupacion' => 'Docente',
            'estado_civil' => 'Casada',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // 2. Pacientes Generados (IDs 28-55) - 28 pacientes
        for ($i = 0; $i < 28; $i++) {
            $pacientes[] = [
                'user_id' => 28 + $i,
                'primer_nombre' => $faker->firstName,
                'primer_apellido' => $faker->lastName,
                'tipo_documento' => $faker->randomElement(['V', 'E']),
                'numero_documento' => $faker->unique()->numberBetween(10000000, 30000000),
                'fecha_nac' => $faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
                'estado_id' => 1, // Usar 1 por simplicidad
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => $faker->address,
                'prefijo_tlf' => '+58',
                'numero_tlf' => '4' . $faker->randomElement(['12', '14', '16', '24', '26']) . $faker->numberBetween(1000000, 9999999),
                'genero' => $faker->randomElement(['Masculino', 'Femenino']),
                'ocupacion' => $faker->jobTitle,
                'estado_civil' => $faker->randomElement(['Soltero', 'Casado', 'Divorciado', 'Viudo']),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($pacientes, 50) as $chunk) {
            DB::table('pacientes')->insert($chunk);
        }
    }
}
