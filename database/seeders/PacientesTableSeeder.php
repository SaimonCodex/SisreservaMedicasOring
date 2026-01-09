<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacientesTableSeeder extends Seeder
{
    public function run(): void
    {
        $pacientes = [
            [
                'user_id' => 4,
                'primer_nombre' => 'Juan',
                'primer_apellido' => 'Martínez',
                'tipo_documento' => 'V',
                'numero_documento' => '22333444',
                'fecha_nac' => '1990-12-05',
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => 'Sector La Trinidad, Calle Principal',
                'prefijo_tlf' => '+58',
                'numero_tlf' => '4245557766',
                'genero' => 'Masculino',
                'ocupacion' => 'Ingeniero',
                'estado_civil' => 'Soltero',
            ],
            [
                'user_id' => 5,
                'primer_nombre' => 'Laura',
                'primer_apellido' => 'Hernández',
                'tipo_documento' => 'V',
                'numero_documento' => '33444555',
                'fecha_nac' => '1985-07-22',
                'estado_id' => 2,
                'ciudad_id' => 3,
                'municipio_id' => 3,
                'parroquia_id' => 4,
                'direccion_detallada' => 'Urbanización Mirador, Casa 25',
                'prefijo_tlf' => '+58',
                'numero_tlf' => '4265554433',
                'genero' => 'Femenino',
                'ocupacion' => 'Docente',
                'estado_civil' => 'Casada',
            ],
        ];

        foreach ($pacientes as $paciente) {
            DB::table('pacientes')->insert(array_merge($paciente, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
