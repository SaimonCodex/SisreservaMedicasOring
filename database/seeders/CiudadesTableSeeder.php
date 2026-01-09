<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CiudadesTableSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            ['id_estado' => 1, 'ciudad' => 'Caracas', 'capital' => true],
            ['id_estado' => 2, 'ciudad' => 'Los Teques', 'capital' => true],
            ['id_estado' => 2, 'ciudad' => 'Guarenas', 'capital' => false],
            ['id_estado' => 3, 'ciudad' => 'Maracaibo', 'capital' => true],
            ['id_estado' => 4, 'ciudad' => 'Valencia', 'capital' => true],
            ['id_estado' => 5, 'ciudad' => 'Barquisimeto', 'capital' => true],
        ];

        foreach ($ciudades as $ciudad) {
            DB::table('ciudades')->insert(array_merge($ciudad, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
