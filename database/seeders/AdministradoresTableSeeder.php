<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministradoresTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('administradores')->insert([
            'user_id' => 1,
            'primer_nombre' => 'María',
            'primer_apellido' => 'Rodríguez',
            'tipo_documento' => 'V',
            'numero_documento' => '12345678',
            'fecha_nac' => '1980-05-15',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Av. Principal, Edificio Centro, Piso 5',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4125556677',
            'genero' => 'Femenino',
            'tipo_admin' => 'Root',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
