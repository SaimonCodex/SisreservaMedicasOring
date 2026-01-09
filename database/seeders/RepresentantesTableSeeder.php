<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepresentantesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('representantes')->insert([
            'primer_nombre' => 'Roberto',
            'primer_apellido' => 'Martínez',
            'tipo_documento' => 'V',
            'numero_documento' => '44555666',
            'fecha_nac' => '1965-03-15',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Mismo domicilio del paciente',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4125551122',
            'genero' => 'Masculino',
            'parentesco' => 'Padre',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
