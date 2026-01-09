<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionRepartoTableSeeder extends Seeder
{
    public function run(): void
    {
        $configuraciones = [
            [
                'medico_id' => 1,
                'consultorio_id' => 1,
                'porcentaje_medico' => 70.00,
                'porcentaje_consultorio' => 20.00,
                'porcentaje_sistema' => 10.00,
                'observaciones' => 'Configuración estándar para cardiología',
            ],
            [
                'medico_id' => 2,
                'consultorio_id' => 1,
                'porcentaje_medico' => 70.00,
                'porcentaje_consultorio' => 20.00,
                'porcentaje_sistema' => 10.00,
                'observaciones' => 'Configuración estándar para pediatría',
            ],
            [
                'medico_id' => 2,
                'consultorio_id' => 2,
                'porcentaje_medico' => 65.00,
                'porcentaje_consultorio' => 25.00,
                'porcentaje_sistema' => 10.00,
                'observaciones' => 'Configuración para consultorio externo',
            ],
        ];

        foreach ($configuraciones as $config) {
            DB::table('configuracion_reparto')->insert(array_merge($config, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
