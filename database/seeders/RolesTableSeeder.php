<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso completo al sistema'],
            ['nombre' => 'Médico', 'descripcion' => 'Acceso médico para citas y pacientes'],
            ['nombre' => 'Paciente', 'descripcion' => 'Acceso paciente para solicitar citas'],
        ];

        foreach ($roles as $rol) {
            // Usar updateOrCreate para evitar duplicados
            DB::table('roles')->updateOrInsert(
                ['nombre' => $rol['nombre']], // Condición para buscar
                [
                    'descripcion' => $rol['descripcion'],
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}