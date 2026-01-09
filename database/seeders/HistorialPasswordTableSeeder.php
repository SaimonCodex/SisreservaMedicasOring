<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistorialPasswordTableSeeder extends Seeder
{
    public function run(): void
    {
        // Función para aplicar MD5 dos veces (igual que en el modelo)
        $doubleMd5 = function($password) {
            return md5(md5($password));
        };

        $historial = [
            [
                'user_id' => 1,
                'password_hash' => $doubleMd5('admin123'),
            ],
            [
                'user_id' => 2,
                'password_hash' => $doubleMd5('medico123'),
            ],
            [
                'user_id' => 3,
                'password_hash' => $doubleMd5('medico123'),
            ],
            [
                'user_id' => 4,
                'password_hash' => $doubleMd5('paciente123'),
            ],
            [
                'user_id' => 5,
                'password_hash' => $doubleMd5('paciente123'),
            ],
        ];

        foreach ($historial as $registro) {
            DB::table('historial_password')->insert(array_merge($registro, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
