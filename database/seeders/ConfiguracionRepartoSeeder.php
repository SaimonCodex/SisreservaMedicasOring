<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Medico;
use App\Models\Consultorio;

class ConfiguracionRepartoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos médicos y consultorios para configuración de ejemplo
        $medicos = Medico::take(3)->get();
        $consultorios = Consultorio::take(2)->get();

        $configuraciones = [];

        // Configuración específica para algunos médicos con consultorio
        if ($medicos->count() > 0 && $consultorios->count() > 0) {
            // Médico 1 en Consultorio 1 - Reparto 70/20/10
            $configuraciones[] = [
                'medico_id' => $medicos[0]->id,
                'consultorio_id' => $consultorios[0]->id ?? null,
                'porcentaje_medico' => 70.00,
                'porcentaje_consultorio' => 20.00,
                'porcentaje_sistema' => 10.00,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Médico 2 en Consultorio 2 - Reparto 75/15/10 (médico con más experiencia)
            if ($medicos->count() > 1 && $consultorios->count() > 1) {
                $configuraciones[] = [
                    'medico_id' => $medicos[1]->id,
                    'consultorio_id' => $consultorios[1]->id,
                    'porcentaje_medico' => 75.00,
                    'porcentaje_consultorio' => 15.00,
                    'porcentaje_sistema' => 10.00,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Médico 3 sin consultorio específico - Reparto 80/0/20 (consultas a domicilio)
            if ($medicos->count() > 2) {
                $configuraciones[] = [
                    'medico_id' => $medicos[2]->id,
                    'consultorio_id' => null,
                    'porcentaje_medico' => 80.00,
                    'porcentaje_consultorio' => 0.00,
                    'porcentaje_sistema' => 20.00,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('configuracion_reparto')->insert($configuraciones);
        }
    }
}
