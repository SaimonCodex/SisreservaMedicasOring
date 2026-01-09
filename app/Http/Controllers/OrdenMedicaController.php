<?php

namespace App\Http\Controllers;

use App\Models\OrdenMedica;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        if ($request->medico_id) {
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
        $citas = Cita::with(['paciente', 'medico'])
                     ->where('estado_cita', 'Completada')
                     ->where('status', true)
                     ->get();

        $pacientes = Paciente::where('status', true)->get();
        $medicos = Medico::where('status', true)->get();

        return view('medico.ordenes-medicas.create', compact('citas', 'pacientes', 'medicos'));
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
            'medico.usuario'
        ])->findOrFail($id);

        return view('medico.ordenes-medicas.show', compact('orden'));
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
}
