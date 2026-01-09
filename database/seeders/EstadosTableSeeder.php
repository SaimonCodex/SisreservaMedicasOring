<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosTableSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['estado' => 'Distrito Capital', 'iso_3166_2' => 'CCS'],
            ['estado' => 'Miranda', 'iso_3166_2' => 'MIR'],
            ['estado' => 'Zulia', 'iso_3166_2' => 'ZUL'],
            ['estado' => 'Carabobo', 'iso_3166_2' => 'CAR'],
            ['estado' => 'Lara', 'iso_3166_2' => 'LAR'],
        ];

        foreach ($estados as $estado) {
            DB::table('estados')->insert(array_merge($estado, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
