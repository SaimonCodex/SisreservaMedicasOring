<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsuariosTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');

        // Función para aplicar MD5 dos veces
        $doubleMd5 = function($password) {
            return md5(md5($password));
        };

        $passwordComun = $doubleMd5('123456');
        $now = now();

        $usuarios = [];

        // 1. Usuarios Fijos (5 usuarios)
        // ID 1: Admin Principal
        $usuarios[] = [
            'id' => 1,
            'rol_id' => 1, // Administrador
            'correo' => 'admin@clinica.com',
            'password' => $doubleMd5('admin123'),
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // ID 2: Médico 1
        $usuarios[] = [
            'id' => 2,
            'rol_id' => 2, // Médico
            'correo' => 'dr.perez@clinica.com',
            'password' => $doubleMd5('medico123'),
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // ID 3: Médico 2
        $usuarios[] = [
            'id' => 3,
            'rol_id' => 2, // Médico
            'correo' => 'dra.gonzalez@clinica.com',
            'password' => $doubleMd5('medico123'),
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // ID 4: Paciente 1
        $usuarios[] = [
            'id' => 4,
            'rol_id' => 3, // Paciente
            'correo' => 'paciente1@email.com',
            'password' => $doubleMd5('paciente123'),
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // ID 5: Paciente 2
        $usuarios[] = [
            'id' => 5,
            'rol_id' => 3, // Paciente
            'correo' => 'paciente2@email.com',
            'password' => $doubleMd5('paciente123'),
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // 2. Administradores Adicionales (4 usuarios) -> IDs 6-9
        for ($i = 0; $i < 4; $i++) {
            $usuarios[] = [
                'id' => 6 + $i,
                'rol_id' => 1,
                'correo' => $faker->unique()->userName . '@admin.com',
                'password' => $passwordComun,
                'status' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 3. Médicos Adicionales (18 médicos) -> IDs 10-27
        for ($i = 0; $i < 18; $i++) {
            $usuarios[] = [
                'id' => 10 + $i,
                'rol_id' => 2,
                'correo' => $faker->unique()->userName . '@medico.com',
                'password' => $passwordComun,
                'status' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 4. Pacientes Adicionales (28 pacientes) -> IDs 28-55
        for ($i = 0; $i < 28; $i++) {
            $usuarios[] = [
                'id' => 28 + $i,
                'rol_id' => 3,
                'correo' => $faker->unique()->email,
                'password' => $passwordComun,
                'status' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insertar en lotes
        foreach (array_chunk($usuarios, 50) as $chunk) {
            DB::table('usuarios')->insert($chunk);
        }
    }
}
