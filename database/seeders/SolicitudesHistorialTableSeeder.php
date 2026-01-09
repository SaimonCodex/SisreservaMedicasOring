<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudesHistorialTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('solicitudes_historial')->insert([
            'cita_id' => 2,
            'paciente_id' => 2,
            'medico_solicitante_id' => 1, // Dr. Pérez solicita
            'medico_propietario_id' => 2, // Dra. González es propietaria
            'token_validacion' => 'A1B2C3',
            'token_expira_at' => now()->addHours(24),
            'intentos_fallidos' => 0,
            'motivo_solicitud' => 'Interconsulta',
            'estado_permiso' => 'Pendiente',
            'acceso_valido_hasta' => null,
            'observaciones' => 'Solicitud de historial para valoración cardiológica complementaria',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
