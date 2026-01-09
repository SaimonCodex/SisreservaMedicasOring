<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RespuestasSeguridadTableSeeder extends Seeder
{
    public function run(): void
    {
        // Función para aplicar MD5 dos veces (igual que en el modelo)
        $doubleMd5 = function($respuesta) {
            return md5(md5($respuesta));
        };

        $respuestas = [
            [
                'user_id' => 1,
                'pregunta_id' => 1,
                'respuesta_hash' => $doubleMd5('Firulais'),
            ],
            [
                'user_id' => 2,
                'pregunta_id' => 2,
                'respuesta_hash' => $doubleMd5('Caracas'),
            ],
            [
                'user_id' => 3,
                'pregunta_id' => 3,
                'respuesta_hash' => $doubleMd5('Azul'),
            ],
            [
                'user_id' => 4,
                'pregunta_id' => 4,
                'respuesta_hash' => $doubleMd5('Escuela Bolivariana'),
            ],
            [
                'user_id' => 5,
                'pregunta_id' => 5,
                'respuesta_hash' => $doubleMd5('Pizza'),
            ],
        ];

        foreach ($respuestas as $respuesta) {
            DB::table('respuestas_seguridad')->insert(array_merge($respuesta, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
