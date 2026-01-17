<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Models\Administrador;
use Illuminate\Console\Command;
use Carbon\Carbon;

class VerificarPagosPendientes extends Command
{
    protected $signature = 'pagos:verificar-pendientes';
    protected $description = 'Verifica pagos pendientes por más de 24 horas y notifica a los administradores';

    public function handle()
    {
        $this->info('Verificando pagos pendientes...');

        // Buscar pagos en estado "Pendiente" por más de 24 horas
        $cutoffTime = Carbon::now()->subHours(24);
        
        $pagosPendientes = Pago::where('estado', 'Pendiente')
            ->where('status', true)
            ->where('created_at', '<=', $cutoffTime)
            ->with(['facturaPaciente.cita.consultorio'])
            ->get();

        if ($pagosPendientes->isEmpty()) {
            $this->info('No hay pagos pendientes de revisión.');
            return 0;
        }

        $this->info("Encontrados {$pagosPendientes->count()} pagos pendientes.");

        // Agrupar pagos por conductorio para notificar a los admins correspondientes
        $pagosPorConsultorio = $pagosPendientes->groupBy(function($pago) {
            return $pago->facturaPaciente->cita->consultorio_id ?? 0;
        });

        $admins = Administrador::where('status', true)->get();

        foreach ($admins as $admin) {
            $pagosParaAdmin = collect();

            if ($admin->tipo_admin === 'Root') {
                // Root ve todos los pagos
                $pagosParaAdmin = $pagosPendientes;
            } else {
                // Local solo ve los de sus consultorios
                $consultoriosLocal = $admin->consultorios->pluck('id')->toArray();
                
                foreach ($consultoriosLocal as $consultorioId) {
                    if ($pagosPorConsultorio->has($consultorioId)) {
                        $pagosParaAdmin = $pagosParaAdmin->merge($pagosPorConsultorio->get($consultorioId));
                    }
                }
            }

            if ($pagosParaAdmin->isNotEmpty()) {
                $admin->notify(new \App\Notifications\Admin\AlertaPagoPendiente($pagosParaAdmin));
                $this->info("Notificación enviada a: {$admin->primer_nombre} {$admin->primer_apellido} ({$pagosParaAdmin->count()} pagos)");
            }
        }

        $this->info('✅ Verificación completada.');
        return 0;
    }
}
