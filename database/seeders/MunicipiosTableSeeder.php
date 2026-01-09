<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipiosTableSeeder extends Seeder
{
    public function run(): void
    {
        $municipios = [
            ['id_estado' => 1, 'municipio' => 'Libertador'],
            ['id_estado' => 2, 'municipio' => 'Guacaipuro'],
            ['id_estado' => 2, 'municipio' => 'Plaza'],
            ['id_estado' => 3, 'municipio' => 'Maracaibo'],
            ['id_estado' => 4, 'municipio' => 'Valencia'],
            ['id_estado' => 5, 'municipio' => 'Iribarren'],
        ];

        foreach ($municipios as $municipio) {
            DB::table('municipios')->insert(array_merge($municipio, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
