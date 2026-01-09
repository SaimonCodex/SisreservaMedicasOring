<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AdministradoresTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $admins = [];

        // 1. Administrador Principal (Fijo) - User ID 1
        $admins[] = [
            'user_id' => 1,
            'primer_nombre' => 'María',
            'primer_apellido' => 'Rodríguez',
            'tipo_documento' => 'V',
            'numero_documento' => '12345678',
            'fecha_nac' => '1980-05-15',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Av. Principal, Edificio Centro, Piso 5',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4125556677',
            'genero' => 'Femenino',
            'tipo_admin' => 'Root',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // 2. Administradores Adicionales (Generados) - User IDs 6-9
        for ($i = 0; $i < 4; $i++) {
            $admins[] = [
                'user_id' => 6 + $i,
                'primer_nombre' => $faker->firstName,
                'primer_apellido' => $faker->lastName,
                'tipo_documento' => $faker->randomElement(['V', 'E']),
                'numero_documento' => $faker->unique()->numberBetween(10000000, 30000000),
                'fecha_nac' => $faker->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d'),
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => $faker->address,
                'prefijo_tlf' => '+58',
                'numero_tlf' => '4' . $faker->randomElement(['12', '14', '16']) . $faker->numberBetween(1000000, 9999999),
                'genero' => $faker->randomElement(['Masculino', 'Femenino']),
                'tipo_admin' => $faker->randomElement(['Supervisor', 'Recepcionista', 'Root']),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('administradores')->insert($admins);
    }
}
