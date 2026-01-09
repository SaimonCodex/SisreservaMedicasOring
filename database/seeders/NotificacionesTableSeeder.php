<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificacionesTableSeeder extends Seeder
{
    public function run(): void
    {
        $notificaciones = [
            [
                'receptor_id' => 2,
                'receptor_rol' => 'Medico',
                'tipo' => 'Recordatorio_Cita',
                'titulo' => 'Recordatorio de Cita Programada',
                'mensaje' => 'Tiene una cita programada para mañana a las 15:00 con el paciente Juan Martínez',
                'via' => 'Sistema',
                'estado_envio' => 'Enviado',
            ],
            [
                'receptor_id' => 4,
                'receptor_rol' => 'Paciente',
                'tipo' => 'Recordatorio_Cita',
                'titulo' => 'Recordatorio de Su Cita',
                'mensaje' => 'Su cita con el Dr. Pérez está programada para el '.now()->addDays(5)->format('d/m/Y').' a las 09:00',
                'via' => 'Sistema',
                'estado_envio' => 'Pendiente',
            ],
            [
                'receptor_id' => 5,
                'receptor_rol' => 'Paciente',
                'tipo' => 'Pago_Aprobado',
                'titulo' => 'Pago Confirmado',
                'mensaje' => 'Su pago por la consulta ha sido confirmado. Gracias por su preferencia.',
                'via' => 'Correo',
                'estado_envio' => 'Enviado',
            ],
            [
                'receptor_id' => 1,
                'receptor_rol' => 'Admin',
                'tipo' => 'Alerta_Adm',
                'titulo' => 'Nueva Factura Emitida',
                'mensaje' => 'Se ha emitido una nueva factura para la paciente Laura Hernández',
                'via' => 'Sistema',
                'estado_envio' => 'Leido',
            ],
        ];

        foreach ($notificaciones as $notificacion) {
            DB::table('notificaciones')->insert(array_merge($notificacion, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
