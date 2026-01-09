<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosTableSeeder extends Seeder
{
    public function run(): void
    {
        // Función para aplicar MD2 dos veces
        $doubleMd5 = function($password) {
            return md5(md5($password));
        };

        $usuarios = [
            [
                'rol_id' => 1, // Administrador
                'correo' => 'admin@clinica.com',
                'password' => $doubleMd5('admin123'),
            ],
            [
                'rol_id' => 2, // Médico
                'correo' => 'dr.perez@clinica.com',
                'password' => $doubleMd5('medico123'),
            ],
            [
                'rol_id' => 2, // Médico
                'correo' => 'dra.gonzalez@clinica.com',
                'password' => $doubleMd5('medico123'),
            ],
            [
                'rol_id' => 3, // Paciente
                'correo' => 'paciente1@email.com',
                'password' => $doubleMd5('paciente123'),
            ],
            [
                'rol_id' => 3, // Paciente
                'correo' => 'paciente2@email.com',
                'password' => $doubleMd5('paciente123'),
            ],
        ];

        foreach ($usuarios as $usuario) {
            DB::table('usuarios')->insert(array_merge($usuario, [
                'status' => true,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
