<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EnviarRecordatoriosCitas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citas:recordatorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia recordatorios a los pacientes con citas programadas para el día siguiente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $manana = Carbon::tomorrow()->format('Y-m-d');
        
        $this->info("Buscando citas para mañana: $manana");
        Log::info("Iniciando comando de recordatorios para: $manana");

        $citas = Cita::with(['paciente.usuario', 'medico', 'consultorio'])
                     ->whereDate('fecha_cita', $manana)
                     ->whereIn('estado_cita', ['Programada', 'Confirmada'])
                     ->where('status', true)
                     ->get();

        $count = 0;
        
        foreach ($citas as $cita) {
            try {
                if ($cita->paciente && $cita->paciente->usuario) {
                    $cita->paciente->usuario->notify(new \App\Notifications\RecordatorioCita($cita));
                    $this->info("Recordatorio enviado a Cita #{$cita->id}");
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error("Error enviando recordatorio cita #{$cita->id}: " . $e->getMessage());
                $this->error("Error en cita #{$cita->id}");
            }
        }

        $this->info("Proceso finalizado. $count recordatorios enviados.");
        Log::info("Comando recordatorios finalizado. $count enviados.");
    }
}