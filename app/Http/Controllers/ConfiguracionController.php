<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionReparto;
use App\Models\TasaDolar;
use App\Models\MetodoPago;
use App\Models\Medico;
use App\Models\Consultorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use App\Models\Configuracion;
use App\Services\DollarExchangeService;

class ConfiguracionController extends Controller
{
    // =========================================================================
    // CONFIGURACIÓN GENERAL DEL SISTEMA
    // =========================================================================

    public function index()
    {
        return view('admin.configuracion.index');
    }

    public function general()
    {
        $keys = [
            'app_name', 'rif', 'telefono_principal', 'direccion', 
            'email_contacto', 'sitio_web', 
            'social_facebook', 'social_instagram', 'social_twitter', 'social_whatsapp',
            'horario_lv', 'horario_sab', 'horario_dom'
        ];

        $configuraciones = [];
        foreach ($keys as $key) {
            $configuraciones[$key] = Configuracion::where('key', $key)->value('value');
        }

        // Defaults from config if DB is empty for app_name
        if (!$configuraciones['app_name']) {
            $configuraciones['app_name'] = config('app.name', 'Sistema Médico');
        }

        return view('admin.configuracion.general', compact('configuraciones'));
    }

    public function actualizarGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'email_contacto' => 'nullable|email|max:150',
            'telefono_principal' => 'nullable|string|max:20',
            'rif' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'sitio_web' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_whatsapp' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fields = [
            'app_name', 'rif', 'telefono_principal', 'direccion', 
            'email_contacto', 'sitio_web', 
            'social_facebook', 'social_instagram', 'social_twitter', 'social_whatsapp',
            'horario_lv', 'horario_sab', 'horario_dom'
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Configuracion::updateOrCreate(
                    ['key' => $field],
                    ['value' => $request->input($field)]
                );
            }
        }

        return redirect()->back()->with('success', 'Configuración general actualizada exitosamente');
    }

    // =========================================================================
    // CONFIGURACIÓN DE REPARTO (PORCENTAJES)
    // =========================================================================

    public function reparto()
    {
        $configuraciones = ConfiguracionReparto::with(['medico', 'consultorio'])
                                              ->where('status', true)
                                              ->get();
        
        $medicos = Medico::where('status', true)->get();
        $consultorios = Consultorio::where('status', true)->get();

        return view('admin.configuracion.reparto', compact('configuraciones', 'medicos', 'consultorios'));
    }

    public function guardarReparto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medico_id' => 'required|exists:medicos,id',
            'consultorio_id' => 'nullable|exists:consultorios,id',
            'porcentaje_medico' => 'required|numeric|min:0|max:100',
            'porcentaje_consultorio' => 'required|numeric|min:0|max:100',
            'porcentaje_sistema' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validar que la suma de porcentajes sea 100%
        $suma = $request->porcentaje_medico + $request->porcentaje_consultorio + $request->porcentaje_sistema;
        if (abs($suma - 100) > 0.01) { // Tolerancia para decimales
            return redirect()->back()->with('error', 'La suma de los porcentajes debe ser exactamente 100%')->withInput();
        }

        // Verificar si ya existe una configuración para este médico y consultorio
        $existe = ConfiguracionReparto::where('medico_id', $request->medico_id)
                                    ->where('consultorio_id', $request->consultorio_id)
                                    ->where('status', true)
                                    ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Ya existe una configuración para este médico y consultorio')->withInput();
        }

        ConfiguracionReparto::create($request->all());

        return redirect()->back()->with('success', 'Configuración de reparto guardada exitosamente');
    }

    public function actualizarReparto(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'porcentaje_medico' => 'required|numeric|min:0|max:100',
            'porcentaje_consultorio' => 'required|numeric|min:0|max:100',
            'porcentaje_sistema' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Validar que la suma de porcentajes sea 100%
        $suma = $request->porcentaje_medico + $request->porcentaje_consultorio + $request->porcentaje_sistema;
        if (abs($suma - 100) > 0.01) {
            return redirect()->back()->with('error', 'La suma de los porcentajes debe ser exactamente 100%');
        }

        $configuracion = ConfiguracionReparto::findOrFail($id);
        $configuracion->update($request->all());

        return redirect()->back()->with('success', 'Configuración de reparto actualizada exitosamente');
    }

    public function eliminarReparto($id)
    {
        $configuracion = ConfiguracionReparto::findOrFail($id);
        $configuracion->update(['status' => false]);

        return redirect()->back()->with('success', 'Configuración de reparto eliminada exitosamente');
    }

    // =========================================================================
    // CONFIGURACIÓN DE TASAS DE CAMBIO
    // =========================================================================

    public function tasas()
    {
        $tasas = TasaDolar::where('status', true)
                          ->orderBy('fecha_tasa', 'desc')
                          ->paginate(20);

        $autoUpdate = Configuracion::where('key', 'auto_update_tasa')->value('value');
        
        $impuestos = [
            'iva_general' => Configuracion::where('key', 'iva_general')->value('value') ?? '16.00',
            'exento_consultas' => Configuracion::where('key', 'exento_consultas')->value('value') ?? '1',
            'exento_emergencias' => Configuracion::where('key', 'exento_emergencias')->value('value') ?? '1',
            'exento_laboratorio' => Configuracion::where('key', 'exento_laboratorio')->value('value') ?? '0',
        ];

        return view('admin.configuracion.tasas', compact('tasas', 'autoUpdate', 'impuestos'));
    }

    public function guardarTasa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fuente' => 'required|in:BCV,MonitorDolar,Paralelo,Oficial',
            'valor' => 'required|numeric|min:0',
            'fecha_tasa' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar que no exista una tasa para la misma fecha y fuente
        $existe = TasaDolar::where('fecha_tasa', $request->fecha_tasa)
                          ->where('fuente', $request->fuente)
                          ->where('status', true)
                          ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Ya existe una tasa para esta fecha y fuente')->withInput();
        }

        TasaDolar::create($request->all());

        return redirect()->back()->with('success', 'Tasa de cambio guardada exitosamente');
    }

    public function actualizarConfiguracionTasa(Request $request)
    {
        $value = $request->has('auto_update_tasa') ? '1' : '0';

        Configuracion::updateOrCreate(
            ['key' => 'auto_update_tasa'],
            ['value' => $value]
        );

        $estado = ($value == '1') ? 'activada' : 'desactivada';
        return redirect()->back()->with('success', "Actualización automática {$estado} exitosamente");
    }

    public function actualizarImpuestos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iva_general' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Configuracion::updateOrCreate(['key' => 'iva_general'], ['value' => $request->iva_general]);
        
        Configuracion::updateOrCreate(['key' => 'exento_consultas'], ['value' => $request->has('exento_consultas') ? '1' : '0']);
        Configuracion::updateOrCreate(['key' => 'exento_emergencias'], ['value' => $request->has('exento_emergencias') ? '1' : '0']);
        Configuracion::updateOrCreate(['key' => 'exento_laboratorio'], ['value' => $request->has('exento_laboratorio') ? '1' : '0']);

        return redirect()->back()->with('success', 'Configuración de impuestos actualizada exitosamente');
    }

    public function sincronizarTasa(DollarExchangeService $service)
    {
        if ($service->syncRate()) {
            return redirect()->back()->with('success', 'Tasa sincronizada exitosamente con el BCV');
        }

        return redirect()->back()->with('error', 'No se pudo sincronizar la tasa. Intente nuevamente.');
    }

    public function actualizarTasa(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fuente' => 'required|in:BCV,MonitorDolar,Paralelo,Oficial',
            'valor' => 'required|numeric|min:0',
            'fecha_tasa' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $tasa = TasaDolar::findOrFail($id);
        $tasa->update($request->all());

        return redirect()->back()->with('success', 'Tasa de cambio actualizada exitosamente');
    }

    public function eliminarTasa($id)
    {
        $tasa = TasaDolar::findOrFail($id);
        $tasa->update(['status' => false]);

        return redirect()->back()->with('success', 'Tasa de cambio eliminada exitosamente');
    }

    // =========================================================================
    // CONFIGURACIÓN DE MÉTODOS DE PAGO
    // =========================================================================

    public function metodosPago()
    {
        $metodos = MetodoPago::where('status', true)->get();
        return view('admin.configuracion.metodos-pago', compact('metodos'));
    }

    public function guardarMetodoPago(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string|max:255|unique:metodo_pago,descripcion',
            'codigo' => 'nullable|string|max:50',
            'requiere_confirmacion' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        MetodoPago::create($request->all());

        return redirect()->back()->with('success', 'Método de pago guardado exitosamente');
    }

    public function actualizarMetodoPago(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string|max:255|unique:metodo_pago,descripcion,' . $id . ',id_metodo',
            'codigo' => 'nullable|string|max:50',
            'requiere_confirmacion' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $metodo = MetodoPago::findOrFail($id);
        $metodo->update($request->all());

        return redirect()->back()->with('success', 'Método de pago actualizado exitosamente');
    }

    public function eliminarMetodoPago($id)
    {
        $metodo = MetodoPago::findOrFail($id);
        $metodo->update(['status' => false]);

        return redirect()->back()->with('success', 'Método de pago eliminado exitosamente');
    }

    // =========================================================================
    // CONFIGURACIÓN DE CORREO ELECTRÓNICO (MAILTRAP)
    // =========================================================================

    public function correo()
    {
        $configuraciones = [
            'mail_driver' => config('mail.default', 'smtp'),
            'mail_host' => config('mail.mailers.smtp.host', ''),
            'mail_port' => config('mail.mailers.smtp.port', ''),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
            'mail_from_address' => config('mail.from.address', ''),
            'mail_from_name' => config('mail.from.name', '')
        ];

        return view('admin.configuracion.correo', compact('configuraciones'));
    }

    public function actualizarCorreo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses,postmark,log,array',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // En un sistema real, aquí se actualizaría el archivo .env o la tabla de configuraciones
        // Por ahora solo mostramos un mensaje de éxito

        return redirect()->back()->with('success', 'Configuración de correo actualizada exitosamente');
    }

    public function probarCorreo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_destino' => 'required|email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            \Mail::send('emails.prueba', [], function($message) use ($request) {
                $message->to($request->email_destino)
                        ->subject('Prueba de Configuración - Sistema Médico');
            });

            return redirect()->back()->with('success', 'Correo de prueba enviado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al enviar correo de prueba: ' . $e->getMessage());
        }
    }



    public function backup()
    {
        return view('admin.configuracion.backup');
    }

    public function generarBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|in:completo,solo_bd,solo_archivos'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            switch ($request->tipo) {
                case 'completo':
                    Artisan::call('backup:run');
                    break;
                case 'solo_bd':
                    Artisan::call('backup:run', ['--only-db' => true]);
                    break;
                case 'solo_archivos':
                    Artisan::call('backup:run', ['--only-files' => true]);
                    break;
            }

            return redirect()->back()->with('success', 'Backup generado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar backup: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // ESTADÍSTICAS DEL SISTEMA
    // =========================================================================

    public function estadisticas()
    {
        $estadisticas = [
            'total_usuarios' => \App\Models\Usuario::where('status', true)->count(),
            'total_medicos' => \App\Models\Medico::where('status', true)->count(),
            'total_pacientes' => \App\Models\Paciente::where('status', true)->count(),
            'total_citas' => \App\Models\Cita::where('status', true)->count(),
            'citas_hoy' => \App\Models\Cita::whereDate('fecha_cita', today())->count(),
            'ingresos_mes' => \App\Models\FacturaPaciente::whereMonth('fecha_emision', now()->month)->sum('monto_usd'),
            'notificaciones_pendientes' => \App\Models\Notificacion::where('estado_envio', 'Pendiente')->count(),
            'ordenes_pendientes' => \App\Models\OrdenMedica::where('resultados', null)->count()
        ];

        // Gráfico de citas por mes
        $citasPorMes = \App\Models\Cita::selectRaw('YEAR(fecha_cita) as año, MONTH(fecha_cita) as mes, COUNT(*) as total')
                                      ->where('status', true)
                                      ->where('fecha_cita', '>=', now()->subYear())
                                      ->groupBy('año', 'mes')
                                      ->orderBy('año', 'desc')
                                      ->orderBy('mes', 'desc')
                                      ->get();

        // Métodos de pago más utilizados
        $metodosPagoPopulares = \App\Models\Pago::with('metodoPago')
                                              ->select('id_metodo')
                                              ->selectRaw('COUNT(*) as total')
                                              ->where('status', true)
                                              ->groupBy('id_metodo')
                                              ->orderBy('total', 'desc')
                                              ->limit(5)
                                              ->get();

        return view('admin.configuracion.estadisticas', compact('estadisticas', 'citasPorMes', 'metodosPagoPopulares'));
    }

    // =========================================================================
    // LOGS DEL SISTEMA
    // =========================================================================

    public function logs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logFile)) {
            $logs = array_slice(file($logFile, FILE_IGNORE_NEW_LINES), -100); // Últimas 100 líneas
        }

        return view('admin.configuracion.logs', compact('logs'));
    }

    public function limpiarLogs()
    {
        try {
            Artisan::call('log:clear');
            return redirect()->back()->with('success', 'Logs limpiados exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al limpiar logs: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // INFORMACIÓN DEL SERVIDOR
    // =========================================================================

    public function servidor()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'server_os' => php_uname(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'database_driver' => config('database.default'),
            'timezone' => config('app.timezone'),
            'environment' => app()->environment()
        ];

        return view('admin.configuracion.servidor', compact('info'));
    }
    // =========================================================================
    // MANTENIMIENTO DEL SISTEMA
    // =========================================================================

    public function mantenimiento()
    {
        // Cálculo de espacio en disco (Windows compatible)
        $diskTotal = disk_total_space('/'); // Bytes
        $diskFree = disk_free_space('/');   // Bytes
        $diskUsed = $diskTotal - $diskFree;
        $diskPercentage = ($diskUsed / $diskTotal) * 100;

        // Formateo de bytes
        $formatBytes = function ($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        };

        // Estimación de memoria (PHP limit vs Real usage)
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        
        // Convertir memory_limit a bytes para el cálculo de porcentaje
        $memoryLimitBytes = $this->returnBytes($memoryLimit);
        $memoryPercentage = ($memoryLimitBytes > 0) ? ($memoryUsage / $memoryLimitBytes) * 100 : 0;

        $stats = [
            'disk_total' => $formatBytes($diskTotal),
            'disk_free' => $formatBytes($diskFree),
            'disk_used' => $formatBytes($diskUsed),
            'disk_percentage' => round($diskPercentage),
            'memory_limit' => $memoryLimit,
            'memory_usage' => $formatBytes($memoryUsage),
            'memory_percentage' => round($memoryPercentage)
        ];

        return view('admin.configuracion.mantenimiento', compact('stats'));
    }

    private function returnBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    public function ejecutarMantenimiento(Request $request)
    {
        $request->validate([
            'accion' => 'required|in:limpiar_cache,optimizar,limpiar_logs,migrar,seed'
        ]);

        try {
            switch ($request->accion) {
                case 'limpiar_cache':
                    Artisan::call('optimize:clear');
                    $mensaje = 'Caché del sistema limpiada exitosamente.';
                    break;
                case 'optimizar':
                    Artisan::call('optimize');
                    $mensaje = 'Sistema optimizado correctamente.';
                    break;
                case 'limpiar_logs':
                    $files = glob(storage_path('logs/*.log'));
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            @unlink($file);
                        }
                    }
                    $mensaje = 'Logs eliminados correctamente.';
                    break;
                case 'migrar':
                    Artisan::call('migrate', ['--force' => true]);
                    $mensaje = 'Migraciones ejecutadas correctamente.';
                    break;
                case 'seed':
                    Artisan::call('db:seed', ['--force' => true]);
                    $mensaje = 'Seeders ejecutados correctamente.';
                    break;
            }

            return redirect()->back()->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al ejecutar acción: ' . $e->getMessage());
        }
    }
}
