<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Models\Pago;
use App\Models\Administrador;
use Illuminate\Console\Command;
use Carbon\Carbon;

class EnviarResumenDiario extends Command
{
    protected $signature = 'admin:resumen-diario';
    protected $description = 'Envía el resumen diario de actividades a todos los administradores';

    public function handle()
    {
        $this->info('Generando resúmenes diarios...');

        $hoy = Carbon::today();
        $admins = Administrador::where('status', true)->get();

        foreach ($admins as $admin) {
            $data = [];

            if ($admin->tipo_admin === 'Root') {
                // Root: estadísticas globales
                $data['citas_hoy'] = Cita::whereDate('fecha_cita', $hoy)
                    ->where('status', true)
                    ->count();

                $data['pacientes_nuevos'] = \App\Models\Paciente::whereDate('created_at', $hoy)
                    ->where('status', true)
                    ->count();

                $data['pagos_pendientes'] = Pago::where('estado', 'Pendiente')
                    ->where('status', true)
                    ->count();

                $data['citas_especiales'] = Cita::whereDate('fecha_cita', $hoy)
                    ->whereNotNull('paciente_especial_id')
                    ->where('status', true)
                    ->count();
            } else {
                // Local: solo sus consultorios
                $consultorioIds = $admin->consultorios->pluck('id')->toArray();

                $data['citas_hoy'] = Cita::whereDate('fecha_cita', $hoy)
                    ->whereIn('consultorio_id', $consultorioIds)
                    ->where('status', true)
                    ->count();

                // Pacientes nuevos con citas en estos consultorios hoy
                $data['pacientes_nuevos'] = \App\Models\Paciente::whereDate('pacientes.created_at', $hoy)
                    ->where('pacientes.status', true)
                    ->whereHas('citas', function($q) use ($hoy, $consultorioIds) {
                        $q->whereDate('fecha_cita', $hoy)
                          ->whereIn('consultorio_id', $consultorioIds);
                    })
                    ->count();

                $data['pagos_pendientes'] = Pago::where('estado', 'Pendiente')
                    ->where('status', true)
                    ->whereHas('facturaPaciente.cita', function($q) use ($consultorioIds) {
                        $q->whereIn('consultorio_id', $consultorioIds);
                    })
                    ->count();

                $data['citas_especiales'] = Cita::whereDate('fecha_cita', $hoy)
                    ->whereIn('consultorio_id', $consultorioIds)
                    ->whereNotNull('paciente_especial_id')
                    ->where('status', true)
                    ->count();
            }

            // Enviar notificación solo si hay actividad
            if ($data['citas_hoy'] > 0 || $data['pagos_pendientes'] > 0) {
                $admin->notify(new \App\Notifications\Admin\ResumenDiario($data));
                $this->info("Resumen enviado a: {$admin->primer_nombre} {$admin->primer_apellido}");
            } else {
                $this->info("Sin actividad para: {$admin->primer_nombre} {$admin->primer_apellido} (resumen omitido)");
            }
        }

        $this->info('✅ Resúmenes diarios enviados.');
        return 0;
    }
}
