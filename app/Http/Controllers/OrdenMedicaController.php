<?php

namespace App\Http\Controllers;

use App\Models\OrdenMedica;
use App\Models\OrdenMedicamento;
use App\Models\OrdenExamen;
use App\Models\OrdenImagen;
use App\Models\OrdenReferencia;
use App\Models\SolicitudOrden;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\PacienteEspecial;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\Representante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdenMedicaController extends Controller
{
    // =========================================================================
    // LISTADO Y BÚSQUEDA DE ÓRDENES MÉDICAS
    // =========================================================================

    public function index()
    {
        $user = Auth::user();
        $query = OrdenMedica::with(['cita.paciente', 'medico', 'cita.especialidad'])
                           ->where('status', true);

        // Filtros según el rol del usuario
        if ($user->rol_id == 2) { // Médico
            $medico = $user->medico;
            $query->where('medico_id', $medico->id);
        } elseif ($user->rol_id == 3) { // Paciente
            $paciente = $user->paciente;
            $query->where('paciente_id', $paciente->id);
        }

        $ordenes = $query->orderBy('fecha_emision', 'desc')->paginate(10);

        return view('medico.ordenes-medicas.index', compact('ordenes'));
    }

    public function buscar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_orden' => 'nullable|in:Receta,Laboratorio,Imagenologia,Referencia,Interconsulta,Procedimiento',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'paciente_id' => 'nullable|exists:pacientes,id',
            'medico_id' => 'nullable|exists:medicos,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $query = OrdenMedica::with(['cita.paciente', 'medico'])
                           ->where('status', true);

        if ($request->tipo_orden) {
            $query->where('tipo_orden', $request->tipo_orden);
        }

        if ($request->fecha_inicio) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_inicio);
        }

        if ($request->fecha_fin) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_fin);
        }

        if ($request->paciente_id) {
            $query->where('paciente_id', $request->paciente_id);
        }

        // Forzar filtro por médico si es rol médico
        $user = Auth::user();
        if ($user->rol_id == 2) {
            $query->where('medico_id', $user->medico->id);
        } elseif ($request->medico_id) {
            $query->where('medico_id', $request->medico_id);
        }

        $ordenes = $query->orderBy('fecha_emision', 'desc')->paginate(10);

        return view('medico.ordenes-medicas.index', compact('ordenes'))->with('filtros', $request->all());
    }

    // =========================================================================
    // CREACIÓN DE ÓRDENES MÉDICAS
    // =========================================================================

    public function create()
    {
        $user = Auth::user();
        $medicoIds = [];

        if ($user->rol_id == 2 && $user->medico) {
            $medicoId = $user->medico->id;
            
            // Obtener pacientes que han tenido citas con este médico
            $pacienteIds = Cita::where('medico_id', $medicoId)->where('status', true)->pluck('paciente_id')->filter()->unique();
            $pacienteEspecialIds = Cita::where('medico_id', $medicoId)->where('status', true)->pluck('paciente_especial_id')->filter()->unique();

            $pacientes = Paciente::whereIn('id', $pacienteIds)->where('status', true)->get();
            $pacientesEspeciales = PacienteEspecial::with(['paciente', 'representantes'])->whereIn('id', $pacienteEspecialIds)->where('status', true)->get();

            $citas = Cita::with(['paciente', 'medico'])
                     ->where('medico_id', $medicoId)
                     ->where('estado_cita', 'Completada')
                     ->where('status', true)
                     ->get();
        } else {
            // Admin: mostrar todos
            $pacientes = Paciente::where('status', true)->get();
            $pacientesEspeciales = PacienteEspecial::with(['paciente', 'representantes'])->where('status', true)->get();
            $citas = Cita::with(['paciente', 'medico'])
                         ->where('estado_cita', 'Completada')
                         ->where('status', true)
                         ->get();
        }

        $medicos = Medico::where('status', true)->get();

        return view('medico.ordenes-medicas.create', compact('citas', 'pacientes', 'pacientesEspeciales', 'medicos'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cita_id' => 'nullable|exists:citas,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'tipo_orden' => 'required|in:Receta,Laboratorio,Imagenologia,Referencia,Interconsulta,Procedimiento',
            'descripcion_detallada' => 'required|string',
            'indicaciones' => 'nullable|string',
            'fecha_emision' => 'required|date',
            'fecha_vigencia' => 'nullable|date|after_or_equal:fecha_emision'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar que el médico tenga permisos para crear órdenes para este paciente
        $user = Auth::user();
        if ($user->rol_id == 2) { // Médico
            $medico = $user->medico;
            if ($medico->id != $request->medico_id) {
                return redirect()->back()->with('error', 'No tiene permisos para crear órdenes médicas para otros médicos.');
            }
        }

        $orden = OrdenMedica::create($request->all());

        // Enviar notificación al paciente si tiene email
        $this->enviarNotificacionOrden($orden);

        return redirect()->route('ordenes-medicas.show', $orden->id)
                       ->with('success', 'Orden médica creada exitosamente');
    }

    // =========================================================================
    // VISTA Y EDICIÓN DE ÓRDENES MÉDICAS
    // =========================================================================

    public function show($id)
    {
        $orden = OrdenMedica::with([
            'cita.paciente.usuario', 
            'cita.especialidad',
            'medico.usuario',
            'paciente.usuario',
            'pacienteEspecial.paciente',
            'especialidad',
            'medicamentos',
            'examenes',
            'imagenes',
            'referencias'
        ])->findOrFail($id);

        $user = Auth::user();
        
        // Verificar acceso según rol
        if ($user->rol_id == 2) { // Médico
            $medicoId = $user->medico->id;
            
            // Si no es el propietario, verificar si tiene acceso aprobado
            if (!$orden->tieneAcceso($medicoId)) {
                // Mostrar vista de sin acceso con opción de solicitar
                return view('medico.ordenes-medicas.sin-acceso', compact('orden'));
            }
            
            $esPropietario = $orden->esPropietario($medicoId);
        } else {
            $esPropietario = false;
        }

        return view('medico.ordenes-medicas.show', compact('orden', 'esPropietario'));
    }

    public function edit($id)
    {
        $orden = OrdenMedica::findOrFail($id);
        $citas = Cita::with(['paciente', 'medico'])
                     ->where('estado_cita', 'Completada')
                     ->where('status', true)
                     ->get();
        $pacientes = Paciente::where('status', true)->get();
        $medicos = Medico::where('status', true)->get();

        // Verificar permisos de edición
        $user = Auth::user();
        if ($user->rol_id == 2 && $orden->medico_id != $user->medico->id) {
            abort(403, 'No tiene permisos para editar esta orden médica.');
        }

        return view('medico.ordenes-medicas.edit', compact('orden', 'citas', 'pacientes', 'medicos'));
    }

    public function update(Request $request, $id)
    {
        $orden = OrdenMedica::findOrFail($id);

        $user = Auth::user();

        // Verificar que el usuario sea Médico (Rol ID 2)
        if ($user->rol_id != 2) {
            abort(403, 'Solo los médicos pueden editar órdenes médicas.');
        }

        // Verificar permisos de edición
        $user = Auth::user();
        if ($user->rol_id == 2 && $orden->medico_id != $user->medico->id) {
            abort(403, 'No tiene permisos para editar esta orden médica.');
        }

        $validator = Validator::make($request->all(), [
            'cita_id' => 'nullable|exists:citas,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'tipo_orden' => 'required|in:Receta,Laboratorio,Imagenologia,Referencia,Interconsulta,Procedimiento',
            'descripcion_detallada' => 'required|string',
            'indicaciones' => 'nullable|string',
            'fecha_emision' => 'required|date',
            'fecha_vigencia' => 'nullable|date|after_or_equal:fecha_emision',
            'resultados' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $orden->update($request->all());

        return redirect()->route('ordenes-medicas.show', $orden->id)
                       ->with('success', 'Orden médica actualizada exitosamente');
    }

    public function destroy($id)
    {
        $orden = OrdenMedica::findOrFail($id);

        $user = Auth::user();

        // Verificar que el usuario sea Médico (Rol ID 2)
        if ($user->rol_id != 2) {
            abort(403, 'Solo los médicos pueden eliminar órdenes médicas.');
        }

        // Verificar permisos
        if ($user->rol_id == 2 && $orden->medico_id != $user->medico->id) {
            abort(403, 'No tiene permisos para eliminar esta orden médica.');
        }

        $orden->update(['status' => false]);

        return redirect()->route('ordenes-medicas.index')
                       ->with('success', 'Orden médica eliminada exitosamente');
    }

    // =========================================================================
    // REGISTRO DE RESULTADOS
    // =========================================================================

    public function registrarResultados($id)
    {
        $orden = OrdenMedica::with(['cita.paciente', 'medico'])->findOrFail($id);
        
        // Verificar que la orden sea de tipo Laboratorio o Imagenología
        if (!in_array($orden->tipo_orden, ['Laboratorio', 'Imagenologia'])) {
            return redirect()->back()->with('error', 'Solo se pueden registrar resultados para órdenes de Laboratorio o Imagenología.');
        }

        return view('medico.ordenes-medicas.registrar-resultados', compact('orden'));
    }

    public function guardarResultados(Request $request, $id)
    {
        $orden = OrdenMedica::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'resultados' => 'required|string',
            'fecha_resultado' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $orden->update([
            'resultados' => $request->resultados,
            'fecha_vigencia' => $request->fecha_resultado // Actualizar fecha de vigencia con la fecha del resultado
        ]);

        // Notificar al médico sobre los resultados
        $this->enviarNotificacionResultados($orden);

        return redirect()->route('ordenes-medicas.show', $orden->id)
                       ->with('success', 'Resultados registrados exitosamente');
    }

    // =========================================================================
    // ÓRDENES POR TIPO
    // =========================================================================

    public function recetas()
    {
        $recetas = OrdenMedica::with(['cita.paciente', 'medico'])
                             ->where('tipo_orden', 'Receta')
                             ->where('status', true)
                             ->orderBy('fecha_emision', 'desc')
                             ->get();

        return view('medico.ordenes-medicas.recetas', compact('recetas'));
    }

    public function laboratorios()
    {
        $laboratorios = OrdenMedica::with(['cita.paciente', 'medico'])
                                 ->where('tipo_orden', 'Laboratorio')
                                 ->where('status', true)
                                 ->orderBy('fecha_emision', 'desc')
                                 ->get();

        return view('medico.ordenes-medicas.laboratorios', compact('laboratorios'));
    }

    public function imagenologias()
    {
        $imagenologias = OrdenMedica::with(['cita.paciente', 'medico'])
                                  ->where('tipo_orden', 'Imagenologia')
                                  ->where('status', true)
                                  ->orderBy('fecha_emision', 'desc')
                                  ->get();

        return view('medico.ordenes-medicas.imagenologias', compact('imagenologias'));
    }

    public function referencias()
    {
        $referencias = OrdenMedica::with(['cita.paciente', 'medico'])
                                ->where('tipo_orden', 'Referencia')
                                ->where('status', true)
                                ->orderBy('fecha_emision', 'desc')
                                ->get();

        return view('medico.ordenes-medicas.referencias', compact('referencias'));
    }

    // =========================================================================
    // EXPORTACIÓN E IMPRESIÓN
    // =========================================================================

    public function imprimir($id)
    {
        $orden = OrdenMedica::with([
            'cita.paciente.usuario', 
            'cita.especialidad',
            'medico.usuario'
        ])->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medico.ordenes-medicas.imprimir', compact('orden'));
        
        return $pdf->download('orden-medica-' . $orden->tipo_orden . '-' . $orden->id . '.pdf');
    }

    public function exportarPorPeriodo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_orden' => 'nullable|in:Receta,Laboratorio,Imagenologia,Referencia,Interconsulta,Procedimiento'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $query = OrdenMedica::with(['cita.paciente', 'medico'])
                           ->whereBetween('fecha_emision', [$request->fecha_inicio, $request->fecha_fin])
                           ->where('status', true);

        if ($request->tipo_orden) {
            $query->where('tipo_orden', $request->tipo_orden);
        }

        $ordenes = $query->orderBy('fecha_emision', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medico.ordenes-medicas.exportar.periodo', compact('ordenes', 'request'));
        
        $nombreArchivo = 'ordenes-medicas-' . $request->fecha_inicio . '-a-' . $request->fecha_fin;
        if ($request->tipo_orden) {
            $nombreArchivo .= '-' . $request->tipo_orden;
        }
        $nombreArchivo .= '.pdf';

        return $pdf->download($nombreArchivo);
    }

    // =========================================================================
    // NOTIFICACIONES
    // =========================================================================

    private function enviarNotificacionOrden($orden)
    {
        try {
            $orden->load(['cita.paciente.usuario', 'medico.usuario']);
            
            if ($orden->cita->paciente->usuario->correo) {
                Mail::send('emails.orden-medica', ['orden' => $orden], function($message) use ($orden) {
                    $message->to($orden->cita->paciente->usuario->correo)
                            ->subject('Nueva Orden Médica - ' . $orden->tipo_orden);
                });
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando notificación de orden médica: ' . $e->getMessage());
        }
    }

    private function enviarNotificacionResultados($orden)
    {
        try {
            $orden->load(['cita.paciente.usuario', 'medico.usuario']);
            
            if ($orden->medico->usuario->correo) {
                Mail::send('emails.resultados-orden', ['orden' => $orden], function($message) use ($orden) {
                    $message->to($orden->medico->usuario->correo)
                            ->subject('Resultados Disponibles - Orden #' . $orden->id);
                });
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando notificación de resultados: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // ESTADÍSTICAS Y REPORTES
    // =========================================================================

    public function estadisticas()
    {
        $totalOrdenes = OrdenMedica::where('status', true)->count();
        
        $porTipo = OrdenMedica::select('tipo_orden')
                             ->selectRaw('COUNT(*) as total')
                             ->where('status', true)
                             ->groupBy('tipo_orden')
                             ->get();

        $porMes = OrdenMedica::selectRaw('YEAR(fecha_emision) as año, MONTH(fecha_emision) as mes, COUNT(*) as total')
                            ->where('status', true)
                            ->where('fecha_emision', '>=', now()->subYear())
                            ->groupBy('año', 'mes')
                            ->orderBy('año', 'desc')
                            ->orderBy('mes', 'desc')
                            ->get();

        $medicosMasActivos = OrdenMedica::with('medico.usuario')
                                      ->select('medico_id')
                                      ->selectRaw('COUNT(*) as total_ordenes')
                                      ->where('status', true)
                                      ->where('fecha_emision', '>=', now()->subMonth())
                                      ->groupBy('medico_id')
                                      ->orderBy('total_ordenes', 'desc')
                                      ->limit(10)
                                      ->get();

        return view('medico.ordenes-medicas.estadisticas', compact(
            'totalOrdenes', 
            'porTipo', 
            'porMes', 
            'medicosMasActivos'
        ));
    }

    // =========================================================================
    // SOLICITUD DE ACCESO A ÓRDENES DE OTROS MÉDICOS
    // =========================================================================

    /**
     * Médico solicita acceso a una orden de otro médico
     */
    public function solicitarAcceso(Request $request, $ordenId)
    {
        $user = Auth::user();
        
        // Solo médicos pueden solicitar acceso
        if ($user->rol_id != 2 || !$user->medico) {
            return redirect()->back()->with('error', 'Solo los médicos pueden solicitar acceso a órdenes.');
        }

        $orden = OrdenMedica::with(['paciente', 'medico'])->findOrFail($ordenId);
        $medicoSolicitanteId = $user->medico->id;
        $medicoPropietarioId = $orden->medico_id;
        $pacienteId = $orden->paciente_id;
        
        // No se puede solicitar acceso a las propias órdenes
        if ($medicoSolicitanteId == $medicoPropietarioId) {
            return redirect()->back()->with('info', 'No necesita solicitar acceso a sus propias órdenes.');
        }

        // Verificar si ya existe una solicitud pendiente o activa
        if (SolicitudOrden::existeSolicitud($medicoSolicitanteId, $ordenId)) {
            $existente = SolicitudOrden::where('medico_solicitante_id', $medicoSolicitanteId)
                ->where('orden_id', $ordenId)
                ->where('status', true)
                ->first();
                
            if ($existente->estado_permiso == 'Aprobado' && $existente->acceso_valido_hasta > now()) {
                return redirect()->back()->with('info', 'Ya tiene acceso activo a esta orden.');
            }
            if ($existente->estado_permiso == 'Pendiente') {
                return redirect()->back()->with('info', 'Ya tiene una solicitud pendiente para esta orden.');
            }
        }

        // Validar motivo
        $request->validate([
            'motivo_solicitud' => 'required|in:Interconsulta,Emergencia,Segunda Opinion,Referencia,Continuidad Tratamiento',
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Generar token único
        $token = strtoupper(Str::random(8));
        
        // Crear la solicitud
        $solicitud = SolicitudOrden::create([
            'orden_id' => $ordenId,
            'paciente_id' => $pacienteId,
            'medico_solicitante_id' => $medicoSolicitanteId,
            'medico_propietario_id' => $medicoPropietarioId,
            'token_validacion' => $token,
            'token_expira_at' => now()->addHours(48),
            'intentos_fallidos' => 0,
            'motivo_solicitud' => $request->motivo_solicitud,
            'estado_permiso' => 'Pendiente',
            'observaciones' => $request->observaciones,
            'status' => true
        ]);

        \Log::info("Solicitud de acceso a orden {$ordenId} creada por médico {$medicoSolicitanteId}. Token: {$token}");

        $nombrePaciente = $orden->nombre_paciente;
        return redirect()->back()->with('success', "Solicitud enviada al paciente {$nombrePaciente} para su aprobación.");
    }

    // =========================================================================
    // PORTAL DEL PACIENTE - ÓRDENES
    // =========================================================================
    /**
     * Listado de órdenes para el paciente autenticado
     * Incluye órdenes propias y de pacientes especiales donde es representante
     */
    public function indexPaciente(Request $request)
    {
        $user = Auth::user();
        
        if ($user->rol_id != 3 || !$user->paciente) {
            abort(403, 'Acceso no autorizado.');
        }

        $pacienteId = $user->paciente->id;
        
        // Obtener representante si existe (basado en mismo documento)
        $representante = Representante::where('numero_documento', $user->paciente->numero_documento)
                                      ->where('tipo_documento', $user->paciente->tipo_documento)
                                      ->first();
        
        // Obtener pacientes especiales donde es representante
        $pacientesEspeciales = collect();
        $pacientesEspecialesIds = [];
        if ($representante) {
            $pacientesEspeciales = $representante->pacientesEspeciales()->get();
            $pacientesEspecialesIds = $pacientesEspeciales->pluck('id')->toArray();
        }
        
        $query = OrdenMedica::with([
                'medico', 
                'especialidad', 
                'cita.consultorio',
                'medicamentos', 
                'examenes', 
                'imagenes', 
                'referencias',
                'paciente',
                'pacienteEspecial.representantes'
            ])
            ->where('status', true);

        // Aplicar filtro según selección del usuario
        $filtroSeleccionado = $request->filtro_paciente;
        
        if ($filtroSeleccionado === 'propias') {
            // Solo órdenes propias del paciente
            $query->where('paciente_id', $pacienteId);
        } elseif ($filtroSeleccionado && is_numeric($filtroSeleccionado)) {
            // Filtrar por paciente especial específico
            $peId = intval($filtroSeleccionado);
            if (in_array($peId, $pacientesEspecialesIds)) {
                $query->where('paciente_especial_id', $peId);
            } else {
                // ID no autorizado, mostrar vacío
                $query->whereRaw('1=0');
            }
        } else {
            // Sin filtro: mostrar todas (propias + pacientes especiales representados)
            $query->where(function($q) use ($pacienteId, $pacientesEspecialesIds) {
                $q->where('paciente_id', $pacienteId);
                if (!empty($pacientesEspecialesIds)) {
                    $q->orWhereIn('paciente_especial_id', $pacientesEspecialesIds);
                }
            });
        }

        // Filtro por tipo de orden
        if ($request->tipo_orden) {
            $query->where('tipo_orden', $request->tipo_orden);
        }

        $ordenes = $query->orderBy('fecha_emision', 'desc')->paginate(10);
        
        // Contar solicitudes pendientes
        $solicitudesPendientes = SolicitudOrden::where(function($q) use ($pacienteId, $pacientesEspecialesIds) {
            $q->where('paciente_id', $pacienteId);
            if (!empty($pacientesEspecialesIds)) {
                $q->orWhereHas('orden', function($sq) use ($pacientesEspecialesIds) {
                    $sq->whereIn('paciente_especial_id', $pacientesEspecialesIds);
                });
            }
        })->where('estado_permiso', 'Pendiente')->count();

        // Indicador de si es representante
        $esRepresentante = !empty($pacientesEspecialesIds);

        return view('paciente.ordenes.index', compact(
            'ordenes', 
            'solicitudesPendientes', 
            'esRepresentante',
            'pacientesEspeciales'
        ));
    }

    /**
     * Ver detalle de una orden (para paciente)
     */
    public function showPaciente($id)
    {
        $user = Auth::user();
        
        if ($user->rol_id != 3 || !$user->paciente) {
            abort(403, 'Acceso no autorizado.');
        }

        // Obtener representante si existe (basado en mismo documento)
        $representante = \App\Models\Representante::where('numero_documento', $user->paciente->numero_documento)
                                      ->where('tipo_documento', $user->paciente->tipo_documento)
                                      ->first();
        
        $pacientesEspecialesIds = [];
        if ($representante) {
            $pacientesEspecialesIds = $representante->pacientesEspeciales()->pluck('pacientes_especiales.id')->toArray();
        }

        $orden = OrdenMedica::with([
            'medico.usuario',
            'especialidad',
            'medicamentos',
            'examenes',
            'imagenes',
            'referencias',
            'paciente', // Asegurar que cargamos paciente para la vista
            'pacienteEspecial' 
        ])->where(function($query) use ($user, $pacientesEspecialesIds) {
            $query->where('paciente_id', $user->paciente->id);
            
            if (!empty($pacientesEspecialesIds)) {
                $query->orWhereIn('paciente_especial_id', $pacientesEspecialesIds);
            }
        })->findOrFail($id);

        return view('paciente.ordenes.show', compact('orden'));
    }

    /**
     * Listar solicitudes de acceso pendientes para el paciente
     */
    public function listarSolicitudesPaciente()
    {
        $user = Auth::user();
        
        if ($user->rol_id != 3 || !$user->paciente) {
            abort(403, 'Acceso no autorizado.');
        }

        // Obtener representante si existe
        $representante = \App\Models\Representante::where('numero_documento', $user->paciente->numero_documento)
                                      ->where('tipo_documento', $user->paciente->tipo_documento)
                                      ->first();
        
        $pacientesEspecialesIds = [];
        if ($representante) {
            $pacientesEspecialesIds = $representante->pacientesEspeciales()->pluck('pacientes_especiales.id')->toArray();
        }

        $solicitudes = SolicitudOrden::where(function($q) use ($user, $pacientesEspecialesIds) {
            $q->where('paciente_id', $user->paciente->id);
            if (!empty($pacientesEspecialesIds)) {
                $q->orWhereHas('orden', function($sq) use ($pacientesEspecialesIds) {
                    $sq->whereIn('paciente_especial_id', $pacientesEspecialesIds);
                });
            }
        })
        ->where('estado_permiso', 'Pendiente')
        ->where('status', true)
        ->with(['orden', 'medicoSolicitante', 'medicoPropietario'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('paciente.ordenes.solicitudes', compact('solicitudes'));
    }

    /**
     * Paciente aprueba solicitud de acceso
     */
    public function aprobarSolicitudPaciente(Request $request, $solicitudId)
    {
        $user = Auth::user();
        
        if ($user->rol_id != 3 || !$user->paciente) {
            abort(403, 'Acceso no autorizado.');
        }

        // Obtener representante si existe
        $representante = \App\Models\Representante::where('numero_documento', $user->paciente->numero_documento)
                                      ->where('tipo_documento', $user->paciente->tipo_documento)
                                      ->first();
        
        $pacientesEspecialesIds = [];
        if ($representante) {
            $pacientesEspecialesIds = $representante->pacientesEspeciales()->pluck('pacientes_especiales.id')->toArray();
        }

        $solicitud = SolicitudOrden::where(function($q) use ($user, $pacientesEspecialesIds) {
             $q->where('paciente_id', $user->paciente->id);
             if (!empty($pacientesEspecialesIds)) {
                $q->orWhereHas('orden', function($sq) use ($pacientesEspecialesIds) {
                    $sq->whereIn('paciente_especial_id', $pacientesEspecialesIds);
                });
             }
        })
        ->where('id', $solicitudId)
        ->where('estado_permiso', 'Pendiente')
        ->firstOrFail();

        // Validar duración del acceso
        $request->validate([
            'duracion_dias' => 'required|integer|min:1|max:30'
        ]);

        $solicitud->update([
            'estado_permiso' => 'Aprobado',
            'acceso_valido_hasta' => now()->addDays($request->duracion_dias)
        ]);

        \Log::info("Solicitud {$solicitudId} aprobada por paciente {$user->paciente->id}");

        return redirect()->back()->with('success', 'Solicitud aprobada. El médico ahora tiene acceso a la orden.');
    }

    /**
     * Paciente rechaza solicitud de acceso
     */
    public function rechazarSolicitudPaciente(Request $request, $solicitudId)
    {
        $user = Auth::user();
        
        if ($user->rol_id != 3 || !$user->paciente) {
            abort(403, 'Acceso no autorizado.');
        }

        // Obtener representante si existe
        $representante = \App\Models\Representante::where('numero_documento', $user->paciente->numero_documento)
                                      ->where('tipo_documento', $user->paciente->tipo_documento)
                                      ->first();
        
        $pacientesEspecialesIds = [];
        if ($representante) {
            $pacientesEspecialesIds = $representante->pacientesEspeciales()->pluck('pacientes_especiales.id')->toArray();
        }

        $solicitud = SolicitudOrden::where(function($q) use ($user, $pacientesEspecialesIds) {
             $q->where('paciente_id', $user->paciente->id);
             if (!empty($pacientesEspecialesIds)) {
                $q->orWhereHas('orden', function($sq) use ($pacientesEspecialesIds) {
                    $sq->whereIn('paciente_especial_id', $pacientesEspecialesIds);
                });
             }
        })
        ->where('id', $solicitudId)
        ->where('estado_permiso', 'Pendiente')
        ->firstOrFail();

        $solicitud->update([
            'estado_permiso' => 'Rechazado',
            'observaciones' => $solicitud->observaciones . "\n[RECHAZADO]: " . ($request->motivo_rechazo ?? 'Sin motivo especificado')
        ]);

        return redirect()->back()->with('info', 'Solicitud rechazada.');
    }

    // =========================================================================
    // CREACIÓN DE ÓRDENES CON ITEMS DETALLADOS
    // =========================================================================

    /**
     * Crear orden con items detallados desde JSON (múltiples tipos mezclados)
     */
    public function storeConItems(Request $request)
    {
        $user = Auth::user();
        
        if ($user->rol_id != 2 || !$user->medico) {
            abort(403, 'Solo los médicos pueden crear órdenes.');
        }

        $medico = $user->medico;

        // Parsear selección de paciente (formato REGULAR_ID o SPECIAL_ID)
        $seleccion = $request->input('seleccion_paciente');
        $pacienteId = $request->input('paciente_id'); // Compatibilidad directa
        $pacienteEspecialId = null;

        if ($seleccion) {
            if (Str::startsWith($seleccion, 'SPECIAL_')) {
                $pacienteEspecialId = intval(substr($seleccion, 8));
                $pacienteId = null;
            } elseif (Str::startsWith($seleccion, 'REGULAR_')) {
                $pacienteId = intval(substr($seleccion, 8));
                $pacienteEspecialId = null;
            } else {
                $pacienteId = intval($seleccion);
            }
        }

        if (!$pacienteId && !$pacienteEspecialId) {
            return redirect()->back()->with('error', 'Debe seleccionar un paciente validado.')->withInput();
        }

        // Validación base
        $validator = Validator::make($request->all(), [
            // paciente_id se valida manualmente arriba
            'cita_id' => 'nullable|exists:citas,id',
            'diagnostico_principal' => 'nullable|string',
            'indicaciones' => 'nullable|string',
            'ordenes_json' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Decodificar las órdenes del JSON
        $ordenesConfirmadas = json_decode($request->ordenes_json, true);
        
        if (empty($ordenesConfirmadas) || !is_array($ordenesConfirmadas)) {
            return redirect()->back()->with('error', 'No hay órdenes confirmadas para guardar.')->withInput();
        }

        // Agrupar por tipo para determinar el tipo de orden principal
        $tiposConteo = [];
        foreach ($ordenesConfirmadas as $orden) {
            $tipo = $orden['tipo'] ?? 'Receta';
            $tiposConteo[$tipo] = ($tiposConteo[$tipo] ?? 0) + 1;
        }
        
        // El tipo de orden será el más frecuente, o 'Mixta' si hay varios tipos
        $tiposPresententes = array_keys($tiposConteo);
        $tipoOrdenPrincipal = count($tiposPresententes) > 1 ? 'Mixta' : $tiposPresententes[0];

        DB::beginTransaction();
        try {
            // Obtener IDs de la cita si existe (la cita ya tiene todos los IDs correctos)
            $representanteId = null;
            
            if ($request->cita_id) {
                $cita = Cita::find($request->cita_id);
                if ($cita) {
                    // Si la cita tiene paciente_especial_id, usarlo directamente
                    if ($cita->paciente_especial_id) {
                        $pacienteEspecialId = $cita->paciente_especial_id;
                        $pacienteId = $cita->paciente_id;
                    }
                    // Si la cita tiene representante_id, usarlo
                    if ($cita->representante_id) {
                        $representanteId = $cita->representante_id;
                    }
                }
            }
            
            // Si no tenemos paciente_especial_id pero tenemos paciente_id, verificar si es especial
            if (!$pacienteEspecialId && $pacienteId) {
                $paciente = Paciente::find($pacienteId);
                if ($paciente && $paciente->es_especial == 1) {
                    // Buscar el registro en pacientes_especiales con este paciente_id
                    $pacienteEspecialRegistro = PacienteEspecial::with('representantes')
                                                                ->where('paciente_id', $pacienteId)
                                                                ->where('status', true)
                                                                ->first();
                    if ($pacienteEspecialRegistro) {
                        $pacienteEspecialId = $pacienteEspecialRegistro->id;
                        \Log::info('Paciente especial encontrado por es_especial=1', [
                            'paciente_id' => $pacienteId,
                            'paciente_especial_id' => $pacienteEspecialId
                        ]);
                        
                        // Buscar representante si aún no lo tenemos
                        if (!$representanteId && $pacienteEspecialRegistro->representantes->isNotEmpty()) {
                            $representanteId = $pacienteEspecialRegistro->representantes->first()->id;
                        }
                    }
                }
            }
            
            // Si tenemos paciente_especial_id pero no representante, buscarlo en la relación
            if (!$representanteId && $pacienteEspecialId) {
                $pacienteEspecial = PacienteEspecial::with('representantes')->find($pacienteEspecialId);
                if ($pacienteEspecial && $pacienteEspecial->representantes->isNotEmpty()) {
                    $representanteId = $pacienteEspecial->representantes->first()->id;
                }
            }
            
            // Crear orden principal
            $orden = OrdenMedica::create([
                'paciente_id' => $pacienteId,
                'paciente_especial_id' => $pacienteEspecialId,
                'representante_id' => $representanteId,
                'medico_id' => $medico->id,
                'cita_id' => $request->cita_id,
                'especialidad_id' => $medico->especialidades->first()?->id,
                'tipo_orden' => $tipoOrdenPrincipal,
                'descripcion_detallada' => $request->diagnostico_principal ?? 'Ver items detallados',
                'diagnostico_principal' => $request->diagnostico_principal,
                'indicaciones' => $request->indicaciones,
                'fecha_emision' => now(),
                'fecha_vigencia' => $request->fecha_vigencia ?? now()->addMonths(3),
                'estado_orden' => 'Emitida',
                'status' => true
            ]);

            // Procesar cada orden confirmada según su tipo
            foreach ($ordenesConfirmadas as $item) {
                $tipo = $item['tipo'] ?? 'Receta';
                $data = $item['data'] ?? [];

                switch ($tipo) {
                    case 'Receta':
                        if (!empty($data['medicamento'])) {
                            OrdenMedicamento::create([
                                'orden_id' => $orden->id,
                                'medicamento' => $data['medicamento'],
                                'presentacion' => $data['presentacion'] ?? null,
                                'cantidad' => $data['cantidad'] ?? 1,
                                'dosis' => $data['dosis'] ?? null,
                                'via_administracion' => $data['via_administracion'] ?? 'Oral',
                                'duracion_dias' => $data['duracion_dias'] ?? null,
                                'indicaciones' => $data['indicaciones'] ?? null,
                                'status' => true
                            ]);
                        }
                        break;

                    case 'Laboratorio':
                        if (!empty($data['nombre_examen'])) {
                            OrdenExamen::create([
                                'orden_id' => $orden->id,
                                'tipo_examen' => $data['tipo_examen'] ?? 'Otro',
                                'nombre_examen' => $data['nombre_examen'],
                                'urgente' => $data['urgente'] ?? false,
                                'indicacion_clinica' => $data['indicacion_clinica'] ?? null,
                                'status' => true
                            ]);
                        }
                        break;

                    case 'Imagenologia':
                        if (!empty($data['tipo_estudio'])) {
                            OrdenImagen::create([
                                'orden_id' => $orden->id,
                                'tipo_estudio' => $data['tipo_estudio'],
                                'region_anatomica' => $data['region_anatomica'] ?? '',
                                'proyecciones' => $data['proyecciones'] ?? null,
                                'contraste' => $data['contraste'] ?? false,
                                'urgente' => $data['urgente'] ?? false,
                                'indicacion_clinica' => $data['indicacion_clinica'] ?? null,
                                'status' => true
                            ]);
                        }
                        break;

                    case 'Referencia':
                        if (!empty($data['especialidad_destino'])) {
                            OrdenReferencia::create([
                                'orden_id' => $orden->id,
                                'especialidad_destino' => $data['especialidad_destino'],
                                'motivo_referencia' => $data['motivo_referencia'] ?? '',
                                'resumen_clinico' => $data['resumen_clinico'] ?? '',
                                'prioridad' => $data['prioridad'] ?? 'Normal',
                                'status' => true
                            ]);
                        }
                        break;
                }
            }

            DB::commit();

            // Contar items creados
            $totalItems = count($ordenesConfirmadas);

            return redirect()->route('ordenes-medicas.show', $orden->id)
                           ->with('success', 'Orden médica creada exitosamente con ' . $totalItems . ' item(s).');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creando orden con items: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear la orden: ' . $e->getMessage())->withInput();
        }
    }
}
