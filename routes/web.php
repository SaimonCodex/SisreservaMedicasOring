<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\ConsultorioController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\OrdenMedicaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\PacienteEspecialController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/recovery', [AuthController::class, 'showRecovery'])->name('recovery');
Route::post('/recovery', [AuthController::class, 'sendRecovery']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/password/reset', [AuthController::class, 'showRecovery'])->name('password.request');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// AJAX Recovery Routes
Route::post('/recovery/get-questions', [AuthController::class, 'getSecurityQuestions'])->name('recovery.get-questions');
Route::post('/recovery/verify-answers', [AuthController::class, 'verifySecurityAnswers'])->name('recovery.verify-answers');

// Public Location Routes (for Register)
Route::prefix('ubicacion')->group(function () {
    Route::get('get-ciudades/{estadoId}', [UbicacionController::class, 'getCiudadesByEstado'])->name('ubicacion.get-ciudades');
    Route::get('get-municipios/{estadoId}', [UbicacionController::class, 'getMunicipiosByEstado'])->name('ubicacion.get-municipios');
    Route::get('get-parroquias/{municipioId}', [UbicacionController::class, 'getParroquiasByMunicipio'])->name('ubicacion.get-parroquias');
});

// Rutas públicas para búsqueda de médicos
Route::get('buscar-medicos-publico', [MedicoController::class, 'buscar'])->name('medicos.buscar.publico');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // =========================================================================
    // DASHBOARDS SEGÚN ROL
    // =========================================================================
    
    Route::get('/admin/dashboard', [AdministradorController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/medico/dashboard', [MedicoController::class, 'dashboard'])->name('medico.dashboard');
    Route::get('/paciente/dashboard', [PacienteController::class, 'dashboard'])->name('paciente.dashboard');
    
    // =========================================================================
    // ADMINISTRACIÓN DEL SISTEMA
    // =========================================================================
    
    Route::prefix('admin')->group(function () {
        // Usuarios
        Route::resource('usuarios', UsuarioController::class);
        Route::post('usuarios/{id}/cambiar-password', [UsuarioController::class, 'cambiarPassword'])->name('usuarios.cambiar-password');
        
        // Administradores
        Route::resource('administradores', AdministradorController::class);
        
        // Ubicaciones (selects dependientes)
        Route::get('get-ciudades/{estadoId}', [AdministradorController::class, 'getCiudades'])->name('admin.get-ciudades');
        Route::get('get-municipios/{estadoId}', [AdministradorController::class, 'getMunicipios'])->name('admin.get-municipios');
        Route::get('get-parroquias/{municipioId}', [AdministradorController::class, 'getParroquias'])->name('admin.get-parroquias');
    });
    
    // =========================================================================
    // MÉDICOS
    // =========================================================================
    
    Route::resource('medicos', MedicoController::class);
    Route::get('medicos/{id}/horarios', [MedicoController::class, 'horarios'])->name('medicos.horarios');
    Route::post('medicos/{id}/guardar-horario', [MedicoController::class, 'guardarHorario'])->name('medicos.guardar-horario');
    Route::get('buscar-medicos', [MedicoController::class, 'buscar'])->name('medicos.buscar');
    
    // =========================================================================
    // PACIENTES
    // =========================================================================
    
    Route::resource('pacientes', PacienteController::class);
    Route::get('pacientes/{id}/historia-clinica', [PacienteController::class, 'historiaClinica'])->name('pacientes.historia-clinica');
    Route::post('pacientes/{id}/actualizar-historia', [PacienteController::class, 'actualizarHistoriaClinica'])->name('pacientes.actualizar-historia');
    
    // =========================================================================
    // CITAS MÉDICAS
    // =========================================================================
    
    Route::resource('citas', CitaController::class);
    Route::post('citas/{id}/cambiar-estado', [CitaController::class, 'cambiarEstado'])->name('citas.cambiar-estado');
    Route::get('buscar-disponibilidad', [CitaController::class, 'buscarDisponibilidad'])->name('citas.buscar-disponibilidad');
    
    // =========================================================================
    // ESPECIALIDADES MÉDICAS
    // =========================================================================
    
    Route::resource('especialidades', EspecialidadController::class);
    Route::get('especialidades/{id}/medicos', [EspecialidadController::class, 'medicos'])->name('especialidades.medicos');
    
    // =========================================================================
    // CONSULTORIOS
    // =========================================================================
    
    Route::resource('consultorios', ConsultorioController::class);
    Route::get('consultorios/{id}/medicos', [ConsultorioController::class, 'medicos'])->name('consultorios.medicos');
    Route::get('consultorios/{id}/horarios', [ConsultorioController::class, 'horarios'])->name('consultorios.horarios');
    Route::get('get-ciudades-consultorio/{estadoId}', [ConsultorioController::class, 'getCiudades'])->name('consultorios.get-ciudades');
    Route::get('get-municipios-consultorio/{estadoId}', [ConsultorioController::class, 'getMunicipios'])->name('consultorios.get-municipios');
    Route::get('get-parroquias-consultorio/{municipioId}', [ConsultorioController::class, 'getParroquias'])->name('consultorios.get-parroquias');
    
    // =========================================================================
    // SISTEMA DE UBICACIÓN
    // =========================================================================
    
    Route::prefix('ubicacion')->group(function () {
        // Estados
        Route::get('estados', [UbicacionController::class, 'indexEstados'])->name('ubicacion.estados.index');
        Route::get('estados/create', [UbicacionController::class, 'createEstado'])->name('ubicacion.estados.create');
        Route::post('estados', [UbicacionController::class, 'storeEstado'])->name('ubicacion.estados.store');
        Route::get('estados/{id}/edit', [UbicacionController::class, 'editEstado'])->name('ubicacion.estados.edit');
        Route::put('estados/{id}', [UbicacionController::class, 'updateEstado'])->name('ubicacion.estados.update');
        Route::delete('estados/{id}', [UbicacionController::class, 'destroyEstado'])->name('ubicacion.estados.destroy');

        // Ciudades
        Route::get('estados/{estadoId}/ciudades', [UbicacionController::class, 'indexCiudades'])->name('ubicacion.ciudades.index');
        Route::get('estados/{estadoId}/ciudades/create', [UbicacionController::class, 'createCiudad'])->name('ubicacion.ciudades.create');
        Route::post('estados/{estadoId}/ciudades', [UbicacionController::class, 'storeCiudad'])->name('ubicacion.ciudades.store');
        Route::get('estados/{estadoId}/ciudades/{id}/edit', [UbicacionController::class, 'editCiudad'])->name('ubicacion.ciudades.edit');
        Route::put('estados/{estadoId}/ciudades/{id}', [UbicacionController::class, 'updateCiudad'])->name('ubicacion.ciudades.update');
        Route::delete('estados/{estadoId}/ciudades/{id}', [UbicacionController::class, 'destroyCiudad'])->name('ubicacion.ciudades.destroy');

        // Municipios
        Route::get('estados/{estadoId}/municipios', [UbicacionController::class, 'indexMunicipios'])->name('ubicacion.municipios.index');
        Route::get('estados/{estadoId}/municipios/create', [UbicacionController::class, 'createMunicipio'])->name('ubicacion.municipios.create');
        Route::post('estados/{estadoId}/municipios', [UbicacionController::class, 'storeMunicipio'])->name('ubicacion.municipios.store');
        Route::get('estados/{estadoId}/municipios/{id}/edit', [UbicacionController::class, 'editMunicipio'])->name('ubicacion.municipios.edit');
        Route::put('estados/{estadoId}/municipios/{id}', [UbicacionController::class, 'updateMunicipio'])->name('ubicacion.municipios.update');
        Route::delete('estados/{estadoId}/municipios/{id}', [UbicacionController::class, 'destroyMunicipio'])->name('ubicacion.municipios.destroy');

        // Parroquias
        Route::get('estados/{estadoId}/municipios/{municipioId}/parroquias', [UbicacionController::class, 'indexParroquias'])->name('ubicacion.parroquias.index');
        Route::get('estados/{estadoId}/municipios/{municipioId}/parroquias/create', [UbicacionController::class, 'createParroquia'])->name('ubicacion.parroquias.create');
        Route::post('estados/{estadoId}/municipios/{municipioId}/parroquias', [UbicacionController::class, 'storeParroquia'])->name('ubicacion.parroquias.store');
        Route::get('estados/{estadoId}/municipios/{municipioId}/parroquias/{id}/edit', [UbicacionController::class, 'editParroquia'])->name('ubicacion.parroquias.edit');
        Route::put('estados/{estadoId}/municipios/{municipioId}/parroquias/{id}', [UbicacionController::class, 'updateParroquia'])->name('ubicacion.parroquias.update');
        Route::delete('estados/{estadoId}/municipios/{municipioId}/parroquias/{id}', [UbicacionController::class, 'destroyParroquia'])->name('ubicacion.parroquias.destroy');

    });
    
    // =========================================================================
    // SISTEMA DE FACTURACIÓN
    // =========================================================================
    
    Route::resource('facturacion', FacturacionController::class);
    Route::post('facturacion/{id}/enviar-recordatorio', [FacturacionController::class, 'enviarRecordatorio'])->name('facturacion.enviar-recordatorio');
    Route::get('facturacion/liquidaciones', [FacturacionController::class, 'liquidaciones'])->name('facturacion.liquidaciones');
    Route::post('facturacion/crear-liquidacion', [FacturacionController::class, 'crearLiquidacion'])->name('facturacion.crear-liquidacion');
    
    // =========================================================================
    // SISTEMA DE PAGOS
    // =========================================================================
    
    Route::resource('pagos', PagoController::class);
    Route::post('pagos/{id}/confirmar', [PagoController::class, 'confirmarPago'])->name('pagos.confirmar');
    Route::post('pagos/{id}/rechazar', [PagoController::class, 'rechazarPago'])->name('pagos.rechazar');
    Route::get('pagos/reporte', [PagoController::class, 'reportePagos'])->name('pagos.reporte');
    Route::get('mis-pagos', [PagoController::class, 'misPagos'])->name('pagos.mis-pagos');
    
    // =========================================================================
    // HISTORIA CLÍNICA
    // =========================================================================
    
    Route::prefix('historia-clinica')->group(function () {
        // Historia Clínica Base
        Route::get('base', [HistoriaClinicaController::class, 'indexBase'])->name('historia-clinica.base.index');
        Route::get('base/{pacienteId}', [HistoriaClinicaController::class, 'showBase'])->name('historia-clinica.base.show');
        Route::get('base/{pacienteId}/create', [HistoriaClinicaController::class, 'createBase'])->name('historia-clinica.base.create');
        Route::post('base/{pacienteId}', [HistoriaClinicaController::class, 'storeBase'])->name('historia-clinica.base.store');
        Route::get('base/{pacienteId}/edit', [HistoriaClinicaController::class, 'editBase'])->name('historia-clinica.base.edit');
        Route::put('base/{pacienteId}', [HistoriaClinicaController::class, 'updateBase'])->name('historia-clinica.base.update');

        // Evoluciones Clínicas
        Route::get('evoluciones/{pacienteId}', [HistoriaClinicaController::class, 'indexEvoluciones'])->name('historia-clinica.evoluciones.index');
        Route::get('evoluciones/cita/{citaId}/create', [HistoriaClinicaController::class, 'createEvolucion'])->name('historia-clinica.evoluciones.create');
        Route::post('evoluciones/cita/{citaId}', [HistoriaClinicaController::class, 'storeEvolucion'])->name('historia-clinica.evoluciones.store');
        Route::get('evoluciones/cita/{citaId}', [HistoriaClinicaController::class, 'showEvolucion'])->name('historia-clinica.evoluciones.show');
        Route::get('evoluciones/cita/{citaId}/edit', [HistoriaClinicaController::class, 'editEvolucion'])->name('historia-clinica.evoluciones.edit');
        Route::put('evoluciones/cita/{citaId}', [HistoriaClinicaController::class, 'updateEvolucion'])->name('historia-clinica.evoluciones.update');

        // Historial Completo
        Route::get('historial-completo/{pacienteId}', [HistoriaClinicaController::class, 'historialCompleto'])->name('historia-clinica.historial-completo');
        
        // Búsqueda
        Route::get('buscar/fecha/{pacienteId}', [HistoriaClinicaController::class, 'buscarPorFecha'])->name('historia-clinica.buscar.fecha');
        Route::get('buscar/diagnostico/{pacienteId}', [HistoriaClinicaController::class, 'buscarPorDiagnostico'])->name('historia-clinica.buscar.diagnostico');
        
        // Exportación
        Route::get('exportar/{pacienteId}', [HistoriaClinicaController::class, 'exportarHistorial'])->name('historia-clinica.exportar');
        Route::get('resumen/{pacienteId}', [HistoriaClinicaController::class, 'generarResumen'])->name('historia-clinica.resumen');
        
        // Permisos de acceso
        Route::post('solicitar-acceso/{pacienteId}', [HistoriaClinicaController::class, 'solicitarAcceso'])->name('historia-clinica.solicitar-acceso');
        Route::post('validar-token/{solicitudId}', [HistoriaClinicaController::class, 'validarTokenAcceso'])->name('historia-clinica.validar-token');
    });
    
    // =========================================================================
    // ÓRDENES MÉDICAS
    // =========================================================================
    
    Route::resource('ordenes-medicas', OrdenMedicaController::class);
    Route::get('ordenes-medicas/buscar', [OrdenMedicaController::class, 'buscar'])->name('ordenes-medicas.buscar');
    Route::get('ordenes-medicas/recetas', [OrdenMedicaController::class, 'recetas'])->name('ordenes-medicas.recetas');
    Route::get('ordenes-medicas/laboratorios', [OrdenMedicaController::class, 'laboratorios'])->name('ordenes-medicas.laboratorios');
    Route::get('ordenes-medicas/imagenologias', [OrdenMedicaController::class, 'imagenologias'])->name('ordenes-medicas.imagenologias');
    Route::get('ordenes-medicas/referencias', [OrdenMedicaController::class, 'referencias'])->name('ordenes-medicas.referencias');
    Route::get('ordenes-medicas/{id}/registrar-resultados', [OrdenMedicaController::class, 'registrarResultados'])->name('ordenes-medicas.registrar-resultados');
    Route::post('ordenes-medicas/{id}/guardar-resultados', [OrdenMedicaController::class, 'guardarResultados'])->name('ordenes-medicas.guardar-resultados');
    Route::get('ordenes-medicas/{id}/imprimir', [OrdenMedicaController::class, 'imprimir'])->name('ordenes-medicas.imprimir');
    Route::get('ordenes-medicas/exportar-periodo', [OrdenMedicaController::class, 'exportarPorPeriodo'])->name('ordenes-medicas.exportar-periodo');
    Route::get('ordenes-medicas/estadisticas', [OrdenMedicaController::class, 'estadisticas'])->name('ordenes-medicas.estadisticas');
    
    // =========================================================================
    // NOTIFICACIONES
    // =========================================================================
    
    Route::resource('notificaciones', NotificacionController::class);
    Route::post('notificaciones/{id}/reenviar', [NotificacionController::class, 'reenviar'])->name('notificaciones.reenviar');
    Route::post('notificaciones/masivo', [NotificacionController::class, 'enviarMasivo'])->name('notificaciones.masivo');
    Route::get('notificaciones/reporte', [NotificacionController::class, 'reporteNotificaciones'])->name('notificaciones.reporte');
    Route::get('notificaciones/estadisticas', [NotificacionController::class, 'estadisticas'])->name('notificaciones.estadisticas');
    Route::post('notificaciones/limpiar', [NotificacionController::class, 'limpiarNotificaciones'])->name('notificaciones.limpiar');
    
    // =========================================================================
    // CONFIGURACIÓN DEL SISTEMA
    // =========================================================================
    
    Route::prefix('configuracion')->group(function () {
        Route::get('/', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        
        // General
        Route::get('general', [ConfiguracionController::class, 'general'])->name('configuracion.general');
        Route::put('general', [ConfiguracionController::class, 'actualizarGeneral'])->name('configuracion.general.actualizar');
        
        // Reparto
        Route::get('reparto', [ConfiguracionController::class, 'reparto'])->name('configuracion.reparto');
        Route::post('reparto', [ConfiguracionController::class, 'guardarReparto'])->name('configuracion.reparto.guardar');
        Route::put('reparto/{id}', [ConfiguracionController::class, 'actualizarReparto'])->name('configuracion.reparto.actualizar');
        Route::delete('reparto/{id}', [ConfiguracionController::class, 'eliminarReparto'])->name('configuracion.reparto.eliminar');
        
        // Tasas
        Route::get('tasas', [ConfiguracionController::class, 'tasas'])->name('configuracion.tasas');
        Route::post('tasas', [ConfiguracionController::class, 'guardarTasa'])->name('configuracion.tasas.guardar');
        Route::put('tasas/{id}', [ConfiguracionController::class, 'actualizarTasa'])->name('configuracion.tasas.actualizar');
        Route::delete('tasas/{id}', [ConfiguracionController::class, 'eliminarTasa'])->name('configuracion.tasas.eliminar');
        
        // Métodos de Pago
        Route::get('metodos-pago', [ConfiguracionController::class, 'metodosPago'])->name('configuracion.metodos-pago');
        Route::post('metodos-pago', [ConfiguracionController::class, 'guardarMetodoPago'])->name('configuracion.metodos-pago.guardar');
        Route::put('metodos-pago/{id}', [ConfiguracionController::class, 'actualizarMetodoPago'])->name('configuracion.metodos-pago.actualizar');
        Route::delete('metodos-pago/{id}', [ConfiguracionController::class, 'eliminarMetodoPago'])->name('configuracion.metodos-pago.eliminar');
        
        // Correo
        Route::get('correo', [ConfiguracionController::class, 'correo'])->name('configuracion.correo');
        Route::put('correo', [ConfiguracionController::class, 'actualizarCorreo'])->name('configuracion.correo.actualizar');
        Route::post('correo/probar', [ConfiguracionController::class, 'probarCorreo'])->name('configuracion.correo.probar');
        
        // Mantenimiento
        Route::get('mantenimiento', [ConfiguracionController::class, 'mantenimiento'])->name('configuracion.mantenimiento');
        Route::post('mantenimiento/ejecutar', [ConfiguracionController::class, 'ejecutarMantenimiento'])->name('configuracion.mantenimiento.ejecutar');
        
        // Backup
        Route::get('backup', [ConfiguracionController::class, 'backup'])->name('configuracion.backup');
        Route::post('backup/generar', [ConfiguracionController::class, 'generarBackup'])->name('configuracion.backup.generar');
        
        // Estadísticas
        Route::get('estadisticas', [ConfiguracionController::class, 'estadisticas'])->name('configuracion.estadisticas');
        
        // Logs
        Route::get('logs', [ConfiguracionController::class, 'logs'])->name('configuracion.logs');
        Route::post('logs/limpiar', [ConfiguracionController::class, 'limpiarLogs'])->name('configuracion.logs.limpiar');
        
        // Servidor
        Route::get('servidor', [ConfiguracionController::class, 'servidor'])->name('configuracion.servidor');
    });
    
    // =========================================================================
    // REPRESENTANTES LEGALES
    // =========================================================================
    
    Route::resource('representantes', RepresentanteController::class);
    Route::post('representantes/{id}/asignar-paciente-especial', [RepresentanteController::class, 'asignarPacienteEspecial'])->name('representantes.asignar-paciente-especial');
    Route::delete('representantes/{id}/remover-paciente-especial/{pacienteEspecialId}', [RepresentanteController::class, 'removerPacienteEspecial'])->name('representantes.remover-paciente-especial');
    Route::put('representantes/{id}/actualizar-responsabilidad/{pacienteEspecialId}', [RepresentanteController::class, 'actualizarResponsabilidad'])->name('representantes.actualizar-responsabilidad');
    Route::get('representantes/buscar', [RepresentanteController::class, 'buscar'])->name('representantes.buscar');
    Route::get('representantes/reporte', [RepresentanteController::class, 'reporte'])->name('representantes.reporte');
    Route::get('representantes/estadisticas', [RepresentanteController::class, 'estadisticas'])->name('representantes.estadisticas');
    Route::get('representantes/exportar', [RepresentanteController::class, 'exportar'])->name('representantes.exportar');
    Route::get('representantes/importar', [RepresentanteController::class, 'importar'])->name('representantes.importar');
    Route::post('representantes/procesar-importacion', [RepresentanteController::class, 'procesarImportacion'])->name('representantes.procesar-importacion');
    Route::get('representantes/get-ciudades/{estadoId}', [RepresentanteController::class, 'getCiudades'])->name('representantes.get-ciudades');
    Route::get('representantes/get-municipios/{estadoId}', [RepresentanteController::class, 'getMunicipios'])->name('representantes.get-municipios');
    Route::get('representantes/get-parroquias/{municipioId}', [RepresentanteController::class, 'getParroquias'])->name('representantes.get-parroquias');
    
    // =========================================================================
    // PACIENTES ESPECIALES
    // =========================================================================
    
    Route::resource('pacientes-especiales', PacienteEspecialController::class);
    Route::post('pacientes-especiales/{id}/asignar-representante', [PacienteEspecialController::class, 'asignarRepresentante'])->name('pacientes-especiales.asignar-representante');
    Route::delete('pacientes-especiales/{id}/remover-representante/{representanteId}', [PacienteEspecialController::class, 'removerRepresentante'])->name('pacientes-especiales.remover-representante');
    Route::put('pacientes-especiales/{id}/actualizar-responsabilidad/{representanteId}', [PacienteEspecialController::class, 'actualizarResponsabilidad'])->name('pacientes-especiales.actualizar-responsabilidad');
    Route::get('pacientes-especiales/buscar', [PacienteEspecialController::class, 'buscar'])->name('pacientes-especiales.buscar');
    Route::get('pacientes-especiales/reporte', [PacienteEspecialController::class, 'reporte'])->name('pacientes-especiales.reporte');
    Route::get('pacientes-especiales/estadisticas', [PacienteEspecialController::class, 'estadisticas'])->name('pacientes-especiales.estadisticas');
    Route::get('pacientes-especiales/exportar', [PacienteEspecialController::class, 'exportar'])->name('pacientes-especiales.exportar');
    Route::get('pacientes-especiales/carnet/{id}', [PacienteEspecialController::class, 'carnet'])->name('pacientes-especiales.carnet');
    Route::get('pacientes-especiales/validar-necesidad/{pacienteId}', [PacienteEspecialController::class, 'validarNecesidadRepresentante'])->name('pacientes-especiales.validar-necesidad');
    Route::post('pacientes-especiales/registrar-automatico/{pacienteId}', [PacienteEspecialController::class, 'registrarAutomatico'])->name('pacientes-especiales.registrar-automatico');
});

/*
|--------------------------------------------------------------------------
| Rutas de Prueba y Desarrollo (Solo en entorno local)
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::get('/debug', function () {
        return view('debug');
    });
}

/*
|--------------------------------------------------------------------------
| Rutas de Fallback (404)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
