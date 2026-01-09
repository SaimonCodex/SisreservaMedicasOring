<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Http\Controllers\NotificacionController;
use Illuminate\Console\Command;
use Carbon\Carbon;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:enviar-recordatorios';
    protected $description = 'Enviar recordatorios de citas 24 horas antes';

    public function handle()
    {
        $fechaManana = Carbon::tomorrow()->toDateString();
        
        $citas = Cita::where('fecha_cita', $fechaManana)
            ->whereIn('estado_cita', ['Programada', 'Confirmada'])
            ->where('status', true)
            ->with(['paciente.usuario'])
            ->get();

        $controller = new NotificacionController();
        
        foreach ($citas as $cita) {
            if ($cita->paciente && $cita->paciente->usuario) {
                try {
                    $controller->enviarRecordatorioCita($cita->id);
                    $this->info("Recordatorio enviado para cita ID: {$cita->id}");
                } catch (\Exception $e) {
                    $this->error("Error enviando recordatorio para cita ID: {$cita->id} - " . $e->getMessage());
                }
            }
        }

        $this->info("Total recordatorios enviados: " . $citas->count());
    }
}