<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
  protected function schedule(Schedule $schedule)
{
    // Enviar recordatorios diarios a las 8:00 AM
    $schedule->command('citas:enviar-recordatorios')->dailyAt('08:00');
    
    // Actualizar tasas de dólar cada hora
    $schedule->command('tasas:actualizar')->hourly();
    
    // Limpiar notificaciones antiguas semanalmente
    $schedule->command('notificaciones:limpiar')->weekly();
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
