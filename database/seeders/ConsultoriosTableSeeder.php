<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultoriosTableSeeder extends Seeder
{
    public function run(): void
    {
        $consultorios = [
            [
                'nombre' => 'Consultorio Central Caracas',
                'descripcion' => 'Consultorio principal en zona céntrica',
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => 'Av. Principal de El Rosal, Edificio Médico, Piso 3',
                'telefono' => '(0212) 555-1234',
                'email' => 'info@consultoriocentral.com',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '18:00:00',
            ],
            [
                'nombre' => 'Clínica Los Teques',
                'descripcion' => 'Atención especializada en Miranda',
                'estado_id' => 2,
                'ciudad_id' => 2,
                'municipio_id' => 2,
                'parroquia_id' => 3,
                'direccion_detallada' => 'Centro Comercial Los Altos, Local 15',
                'telefono' => '(0212) 555-5678',
                'email' => 'clinicateques@email.com',
                'horario_inicio' => '07:30:00',
                'horario_fin' => '17:30:00',
            ],
        ];

        foreach ($consultorios as $consultorio) {
            DB::table('consultorios')->insert(array_merge($consultorio, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
