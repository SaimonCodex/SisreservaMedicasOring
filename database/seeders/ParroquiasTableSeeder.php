<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParroquiasTableSeeder extends Seeder
{
    public function run(): void
    {
        $parroquias = [
            ['id_municipio' => 1, 'parroquia' => 'Catedral'],
            ['id_municipio' => 1, 'parroquia' => 'Altagracia'],
            ['id_municipio' => 2, 'parroquia' => 'Los Teques'],
            ['id_municipio' => 3, 'parroquia' => 'Guarenas'],
            ['id_municipio' => 4, 'parroquia' => 'Centro'],
            ['id_municipio' => 5, 'parroquia' => 'Valencia'],
            ['id_municipio' => 6, 'parroquia' => 'Barquisimeto'],
        ];

        foreach ($parroquias as $parroquia) {
            DB::table('parroquias')->insert(array_merge($parroquia, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
