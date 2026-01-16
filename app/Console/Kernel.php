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
    
    // Actualizar tasas de dólar (Mañana)
    $schedule->command('tasas:actualizar')->dailyAt('09:00');
    
    // Actualizar tasas de dólar (Tarde)
    $schedule->command('tasas:actualizar')->dailyAt('17:00');
    
    // Limpiar notificaciones antiguas semanalmente
    $schedule->command('notificaciones:limpiar')->weekly();
    
    // Resumen diario para administradores a las 7:00 AM
    $schedule->command('admin:resumen-diario')->dailyAt('07:00');
    
    // Verificar pagos pendientes cada 6 horas
    $schedule->command('pagos:verificar-pendientes')->everySixHours();
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
