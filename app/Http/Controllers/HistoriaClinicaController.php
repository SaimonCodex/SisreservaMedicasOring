<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinicaBase;
use App\Models\EvolucionClinica;
use App\Models\Paciente;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HistoriaClinicaController extends Controller
{
    public function __construct()
    {
        // Bloquear acceso de escritura a admins locales (Root puede ver por el trait)
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ($user && $user->administrador) {
                $admin = $user->administrador;
                // Bloquear admins locales completamente
                if ($admin->tipo_admin !== 'Root') {
                    abort(403, 'Los administradores locales no tienen acceso a historias clínicas.');
                }
                // Root solo puede ver (index/show), no crear/editar/eliminar
                $restrictedActions = ['create', 'store', 'edit', 'update', 'destroy'];
                if (in_array($request->route()->getActionMethod(), $restrictedActions)) {
                    abort(403, 'Los administradores no pueden crear ni editar historias clínicas.');
                }
            }
            return $next($request);
        });
    }

    // =========================================================================
    // HISTORIA CLÍNICA BASE (Información médica permanente del paciente)
    // =========================================================================

    public function indexBase()
    {
        $user = Auth::user();
        
        // Administradores NO tienen acceso a historias clínicas
        if ($user->rol_id == 1) {
            abort(403, 'Los administradores no tienen acceso a historias clínicas.');
        }
        
        // Médicos: solo historias de sus pacientes
        if ($user->rol_id == 2) {
            $medico = $user->medico;
            if (!$medico) {
                return redirect()->route('medico.dashboard')->with('error', 'No se encontró el perfil de médico');
            }
            
            // Obtener IDs de pacientes atendidos por este médico
            $pacienteIds = \App\Models\Cita::where('medico_id', $medico->id)
                                          ->where('status', true)
                                          ->distinct()
                                          ->pluck('paciente_id');
            
            $historias = HistoriaClinicaBase::with('paciente')
                                           ->whereIn('paciente_id', $pacienteIds)
                                           ->where('status', true)
                                           ->paginate(10);
            
            return view('medico.historia-clinica.base.index', compact('historias'));
        }
        
        // Pacientes: solo su propia historia (implementar si es necesario)
        $historias = HistoriaClinicaBase::with('paciente')->where('status', true)->paginate(10);
        
        // Doctors use their specific view
        if (auth()->user()->rol_id == 2) {
            return view('medico.historia-clinica.base.index', compact('historias'));
        }
        
        return view('shared.historia-clinica.index', compact('historias'));
    }

    public function showBase($pacienteId)
    {
        $user = Auth::user();
        
        // Administradores NO tienen acceso
        if ($user->rol_id == 1) {
            abort(403, 'Los administradores no tienen acceso a historias clínicas.');
        }
        
        $paciente = Paciente::with(['historiaClinicaBase', 'usuario'])->findOrFail($pacienteId);
        $historia = $paciente->historiaClinicaBase;
        
        if (!$historia) {
            return redirect()->route('historia-clinica.base.create', $pacienteId)
                           ->with('info', 'El paciente no tiene historia clínica base. Por favor créela.');
        }

        // Médicos: vista específica
        if ($user->rol_id == 2) {
            return view('medico.historia-clinica.base.show', compact('paciente', 'historia'));
        }
        
        // Pacientes/Representantes: vista compartida
        return view('shared.historia-clinica.base.show', compact('paciente', 'historia'));
    }

    public function createBase($pacienteId)
    {
        if (Auth::user()->rol_id != 2) {
            abort(403, 'Solo los médicos pueden crear historias clínicas.');
        }
        
        $medico = Auth::user()->medico;
        
        // Verificar que el médico tiene al menos una cita con este paciente
        $tieneRelacion = \App\Models\Cita::where('medico_id', $medico->id)
                                         ->where('paciente_id', $pacienteId)
                                         ->where('status', true)
                                         ->exists();
        
        if (!$tieneRelacion) {
            return redirect()->back()->with('error', 'No tiene relación con este paciente. Debe tener al menos una cita agendada.');
        }
        
        // Verificar que el paciente no tenga ya una historia base
        if (HistoriaClinicaBase::where('paciente_id', $pacienteId)->exists()) {
            return redirect()->route('historia-clinica.base.edit', $pacienteId)
                           ->with('info', 'Este paciente ya tiene historia clínica base. Puede editarla.');
        }
        
        $paciente = Paciente::with('usuario')->findOrFail($pacienteId);
        return view('medico.historia-clinica.base.create', compact('paciente'));
    }

    public function storeBase(Request $request, $pacienteId)
    {
        if (Auth::user()->rol_id != 2) {
            abort(403, 'Solo los médicos pueden crear historias clínicas.');
        }
        
        $medico = Auth::user()->medico;
        
        // Verificar relación médico-paciente
        $tieneRelacion = \App\Models\Cita::where('medico_id', $medico->id)
                                         ->where('paciente_id', $pacienteId)
                                         ->where('status', true)
                                         ->exists();
        
        if (!$tieneRelacion) {
            return redirect()->back()->with('error', 'No tiene relación con este paciente.');
        }
        
        // Verificar que no exista ya
        if (HistoriaClinicaBase::where('paciente_id', $pacienteId)->exists()) {
            return redirect()->route('historia-clinica.base.edit', $pacienteId)
                           ->with('info', 'Este paciente ya tiene historia clínica base.');
        }
        
        // Validación
        $validator = Validator::make($request->all(), [
            'grupo_sanguineo' => 'nullable|in:A,B,AB,O',
            'factor_rh' => 'nullable|in:+,-',
            'no_especificado' => 'nullable|boolean',
            // Si 'No Especificado' está marcado, ignoramos grupo y factor
            'alergias' => 'nullable|string|max:2000',
            'alergias_medicamentos' => 'nullable|string|max:2000',
            'antecedentes_familiares' => 'nullable|string|max:2000',
            'antecedentes_personales' => 'nullable|string|max:2000',
            'enfermedades_cronicas' => 'nullable|string|max:2000',
            'medicamentos_actuales' => 'nullable|string|max:2000',
            'cirugias_previas' => 'nullable|string|max:2000',
            
            // Nuevos campos de hábitos
            'habito_tabaco' => 'nullable|string|max:255',
            'habito_alcohol' => 'nullable|string|max:255',
            'actividad_fisica' => 'nullable|string|max:255',
            'dieta' => 'nullable|string|max:255',
            'habitos' => 'nullable|string|max:2000', // Campo legacy o general
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Procesar Tipo de Sangre
        $tipoSangre = null;
        if ($request->has('no_especificado') && $request->no_especificado) {
            $tipoSangre = 'No Especificado';
        } elseif ($request->grupo_sanguineo && $request->factor_rh) {
            $tipoSangre = $request->grupo_sanguineo . $request->factor_rh;
        }

        // Crear la historia clínica base
        $historia = HistoriaClinicaBase::create([
            'paciente_id' => $pacienteId,
            'tipo_sangre' => $tipoSangre,
            'alergias' => $request->alergias,
            'alergias_medicamentos' => $request->alergias_medicamentos,
            'antecedentes_familiares' => $request->antecedentes_familiares,
            'antecedentes_personales' => $request->antecedentes_personales,
            'enfermedades_cronicas' => $request->enfermedades_cronicas,
            'medicamentos_actuales' => $request->medicamentos_actuales,
            'cirugias_previas' => $request->cirugias_previas,
            'habitos' => $request->habitos,
            // Nuevos campos
            'habito_tabaco' => $request->habito_tabaco,
            'habito_alcohol' => $request->habito_alcohol,
            'actividad_fisica' => $request->actividad_fisica,
            'dieta' => $request->dieta,
            'status' => true
        ]);

        // Registrar auditoría - CREACIÓN
        \App\Models\AuditoriaHistoriaBase::create([
            'historia_clinica_base_id' => $historia->id,
            'medico_id' => $medico->id,
            'tipo_accion' => 'CREACION',
            'campo_modificado' => null,
            'valor_anterior' => null,
            'valor_nuevo' => json_encode($request->except(['_token', '_method'])),
            'motivo_cambio' => 'Creación inicial de historia clínica base',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('historia-clinica.base.show', $pacienteId)
                        ->with('success', 'Historia clínica base creada exitosamente.');
    }

    public function editBase($pacienteId)
    {
        if (Auth::user()->rol_id != 2) {
            abort(403, 'Solo los médicos pueden editar historias clínicas.');
        }
        $paciente = Paciente::with(['historiaClinicaBase', 'usuario'])->findOrFail($pacienteId);
        $historia = $paciente->historiaClinicaBase;
        
        if (!$historia) {
            return redirect()->route('historia-clinica.base.create', $pacienteId);
        }

        return view('medico.historia-clinica.base.edit', compact('paciente', 'historia'));
    }

    public function updateBase(Request $request, $pacienteId)
    {
        if (Auth::user()->rol_id != 2) {
            abort(403, 'Solo los médicos pueden editar historias clínicas.');
        }
        
        $medico = Auth::user()->medico;
        $historia = HistoriaClinicaBase::where('paciente_id', $pacienteId)->firstOrFail();
        
        // Validación
        $validator = Validator::make($request->all(), [
            'grupo_sanguineo' => 'nullable|in:A,B,AB,O',
            'factor_rh' => 'nullable|in:+,-',
            'no_especificado' => 'nullable|boolean',
            'alergias' => 'nullable|string|max:2000',
            'alergias_medicamentos' => 'nullable|string|max:2000',
            'antecedentes_familiares' => 'nullable|string|max:2000',
            'antecedentes_personales' => 'nullable|string|max:2000',
            'enfermedades_cronicas' => 'nullable|string|max:2000',
            'medicamentos_actuales' => 'nullable|string|max:2000',
            'cirugias_previas' => 'nullable|string|max:2000',
            // Hábitos
            'habito_tabaco' => 'nullable|string|max:255',
            'habito_alcohol' => 'nullable|string|max:255',
            'actividad_fisica' => 'nullable|string|max:255',
            'dieta' => 'nullable|string|max:255',
            'habitos' => 'nullable|string|max:2000',
            'motivo_cambio' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Procesar Tipo de Sangre
        $tipoSangre = $historia->tipo_sangre; // Mantener valor actual por defecto
        
        if ($request->has('no_especificado') && $request->no_especificado) {
            $tipoSangre = 'No Especificado';
        } elseif ($request->filled('grupo_sanguineo') && $request->filled('factor_rh')) {
            $tipoSangre = $request->grupo_sanguineo . $request->factor_rh;
        }

        // Guardar valores anteriores para auditoría
        $valoresAnteriores = $historia->only([
            'tipo_sangre', 'alergias', 'alergias_medicamentos', 
            'antecedentes_familiares', 'antecedentes_personales',
            'enfermedades_cronicas', 'medicamentos_actuales', 
            'cirugias_previas', 'habitos', 'habito_tabaco', 'habito_alcohol',
            'actividad_fisica', 'dieta'
        ]);

        // Campos a actualizar en el modelo
        $dataToUpdate = $request->only([
            'alergias', 'alergias_medicamentos',
            'antecedentes_familiares', 'antecedentes_personales',
            'enfermedades_cronicas', 'medicamentos_actuales',
            'cirugias_previas', 'habitos',
            'habito_tabaco', 'habito_alcohol', 'actividad_fisica', 'dieta'
        ]);
        
        if ($tipoSangre) {
            $dataToUpdate['tipo_sangre'] = $tipoSangre;
        }

        // Actualizar la historia
        $historia->update($dataToUpdate);

        // Registrar auditoría para cada campo modificado
        foreach ($dataToUpdate as $campo => $valorNuevo) {
            $valorAnterior = $valoresAnteriores[$campo] ?? null;
            
            // Comparación simple, cuidado con tipos null vs empty string
            if ($valorNuevo != $valorAnterior) {
                \App\Models\AuditoriaHistoriaBase::create([
                    'historia_clinica_base_id' => $historia->id,
                    'medico_id' => $medico->id,
                    'tipo_accion' => 'EDICION',
                    'campo_modificado' => $campo,
                    'valor_anterior' => substr((string)$valorAnterior, 0, 255),
                    'valor_nuevo' => substr((string)$valorNuevo, 0, 255),
                    'motivo_cambio' => $request->motivo_cambio ?? 'Actualización de datos clínicos',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }
        }

        return redirect()->route('historia-clinica.base.show', $pacienteId)
                        ->with('success', 'Historia clínica base actualizada exitosamente.');
    }


    public function indexEvoluciones($pacienteId)
    {
        $user = Auth::user();
        
        // Administradores NO tienen acceso
        if ($user->rol_id == 1) {
            abort(403, 'Los administradores no tienen acceso a historias clínicas.');
        }
        
        $paciente = Paciente::with('usuario')->findOrFail($pacienteId);
        
        $evolucionesQuery = EvolucionClinica::with(['cita.especialidad', 'medico'])
                                     ->where('paciente_id', $pacienteId)
                                     ->where('status', true);
        
        // Médicos: solo ver sus propias evoluciones con este paciente
        if ($user->rol_id == 2 && $user->medico) {
            $evolucionesQuery->where('medico_id', $user->medico->id);
        }
        
        $evoluciones = $evolucionesQuery->orderBy('created_at', 'desc')->get();

        // Médicos: vista específica
        if ($user->rol_id == 2) {
            return view('medico.historia-clinica.evoluciones.index', compact('paciente', 'evoluciones'));
        }
        
        // Pacientes/Representantes: vista compartida
        return view('shared.historia-clinica.evoluciones.index', compact('paciente', 'evoluciones'));
    }

    public function createEvolucion($citaId)
    {
        if (Auth::user()->rol_id != 2) {
            abort(403, 'Solo los médicos pueden crear evoluciones clínicas.');
        }
        
        $cita = Cita::with(['paciente', 'medico', 'especialidad'])->findOrFail($citaId);
        $medicoId = Auth::user()->medico->id;
        
        // Verificar que la cita esté en estado Confirmada o Completada
        if (!in_array($cita->estado_cita, ['Confirmada', 'Completada'])) {
            return redirect()->back()->with('error', 'Solo se puede crear evolución clínica para citas confirmadas (pagadas) o completadas.');
        }

        // Verificar que no exista ya una evolución para esta cita
        $existeEvolucion = EvolucionClinica::where('cita_id', $citaId)->exists();
        if ($existeEvolucion) {
            return redirect()->route('citas.show', $citaId)
                           ->with('info', 'Ya existe una evolución clínica para esta cita.');
        }
        
        // Obtener la última evolución del paciente con este médico para pre-cargar datos
        $ultimaEvolucion = EvolucionClinica::where('paciente_id', $cita->paciente_id)
            ->where('medico_id', $medicoId)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('medico.historia-clinica.evoluciones.create', compact('cita', 'ultimaEvolucion'));
    }

    public function storeEvolucion(Request $request, $citaId)
    {
        if (Auth::user()->rol_id != 2) {
            abort(403, 'Solo los médicos pueden crear evoluciones clínicas.');
        }
        
        $cita = Cita::findOrFail($citaId);
        $medicoId = Auth::user()->medico->id;
        
        // Validación
        $validator = Validator::make($request->all(), [
            'motivo_consulta' => 'required|string|max:255',
            'enfermedad_actual' => 'required|string',
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string',
            'peso_kg' => 'nullable|numeric|min:0|max:500',
            'talla_cm' => 'nullable|numeric|min:0|max:300',
            'tension_sistolica' => 'nullable|integer|min:50|max:300',
            'tension_diastolica' => 'nullable|integer|min:30|max:200',
            'frecuencia_cardiaca' => 'nullable|integer|min:30|max:250',
            'temperatura_c' => 'nullable|numeric|min:30|max:45',
            'frecuencia_respiratoria' => 'nullable|integer|min:5|max:60',
            'saturacion_oxigeno' => 'nullable|numeric|min:50|max:100',
            'examen_fisico' => 'nullable|string',
            'recomendaciones' => 'nullable|string',
            'notas_adicionales' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Calcular IMC si peso y talla están presentes
        $imc = null;
        if ($request->peso_kg && $request->talla_cm) {
            $tallaMetros = $request->talla_cm / 100;
            $imc = $request->peso_kg / ($tallaMetros * $tallaMetros);
        }

        // Crear la evolución clínica
        $evolucion = EvolucionClinica::create([
            'cita_id' => $citaId,
            'paciente_id' => $cita->paciente_id,
            'medico_id' => $medicoId,
            'peso_kg' => $request->peso_kg,
            'talla_cm' => $request->talla_cm,
            'imc' => $imc,
            'tension_sistolica' => $request->tension_sistolica,
            'tension_diastolica' => $request->tension_diastolica,
            'frecuencia_cardiaca' => $request->frecuencia_cardiaca,
            'temperatura_c' => $request->temperatura_c,
            'frecuencia_respiratoria' => $request->frecuencia_respiratoria,
            'saturacion_oxigeno' => $request->saturacion_oxigeno,
            'motivo_consulta' => $request->motivo_consulta,
            'enfermedad_actual' => $request->enfermedad_actual,
            'examen_fisico' => $request->examen_fisico,
            'diagnostico' => $request->diagnostico,
            'tratamiento' => $request->tratamiento,
            'recomendaciones' => $request->recomendaciones,
            'notas_adicionales' => $request->notas_adicionales,
            'status' => true
        ]);

        // Enviar Notificación al Paciente
        try {
            $paciente = Paciente::with(['pacienteEspecial.representante'])->find($cita->paciente_id);
            
            // Recargar relaciones necesarias para la notificación
            $evolucion->load(['medico', 'cita.especialidad']);
            
            if ($paciente) {
                // Verificar si es paciente especial para notificar al representante
                $pacienteEspecial = $paciente->pacienteEspecial;
                
                if ($pacienteEspecial && $pacienteEspecial->representante) {
                    $representante = $pacienteEspecial->representante;
                    $pacienteRepresentante = Paciente::where('tipo_documento', $representante->tipo_documento)
                        ->where('numero_documento', $representante->numero_documento)
                        ->first();
                    
                    if ($pacienteRepresentante) {
                        $pacienteRepresentante->notify(new \App\Notifications\HistoriaClinicaActualizada($evolucion));
                    }
                } else {
                    $paciente->notify(new \App\Notifications\HistoriaClinicaActualizada($evolucion));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando notificación historia clínica: ' . $e->getMessage());
        }

        return redirect()->route('citas.show', $citaId)
                        ->with('success', 'Evolución clínica registrada exitosamente.');
    }
// ...
    public function showEvolucion($citaId)
    {
        $user = Auth::user();
        
        // Administradores NO tienen acceso
        if ($user->rol_id == 1) {
            abort(403, 'Los administradores no tienen acceso a historias clínicas.');
        }
        
        $cita = Cita::with(['paciente', 'medico', 'especialidad'])->findOrFail($citaId);
        $evolucion = EvolucionClinica::where('cita_id', $citaId)->firstOrFail();

        // Médicos: vista específica
        if ($user->rol_id == 2) {
            return view('medico.historia-clinica.evoluciones.show', compact('cita', 'evolucion'));
        }
        
        // Pacientes/Representantes: vista compartida
        return view('shared.historia-clinica.evoluciones.show', compact('cita', 'evolucion'));
    }

    public function editEvolucion($citaId)
    {
        if (Auth::user()->rol_id != 2) {
            abort(403, 'Solo los médicos pueden editar evoluciones clínicas.');
        }
        $cita = Cita::with(['paciente', 'medico'])->findOrFail($citaId);
        $evolucion = EvolucionClinica::where('cita_id', $citaId)->firstOrFail();

        return view('medico.historia-clinica.evoluciones.edit', compact('cita', 'evolucion'));
    }
// ...
    public function historialCompleto($pacienteId)
    {
        $paciente = Paciente::with(['usuario', 'historiaClinicaBase'])->findOrFail($pacienteId);
        $evoluciones = EvolucionClinica::with(['cita.especialidad', 'medico'])
                                     ->where('paciente_id', $pacienteId)
                                     ->where('status', true)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        $ordenesMedicas = \App\Models\OrdenMedica::with(['cita', 'medico'])
                                               ->where('paciente_id', $pacienteId)
                                               ->where('status', true)
                                               ->orderBy('fecha_emision', 'desc')
                                               ->get();

        // Use the main show view (the hub)
        return view('shared.historia-clinica.show', compact('paciente', 'evoluciones', 'ordenesMedicas'));
    }

    // =========================================================================
    // BÚSQUEDA Y FILTRADO
    // =========================================================================

    public function buscarPorFecha(Request $request, $pacienteId)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $paciente = Paciente::findOrFail($pacienteId);
        $evoluciones = EvolucionClinica::with(['cita.especialidad', 'medico'])
                                     ->where('paciente_id', $pacienteId)
                                     ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
                                     ->where('status', true)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('shared.historia-clinica.evoluciones.index', compact('paciente', 'evoluciones'))
               ->with('filtros', $request->all());
    }

    public function buscarPorDiagnostico(Request $request, $pacienteId)
    {
        $validator = Validator::make($request->all(), [
            'termino' => 'required|string|min:3'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $paciente = Paciente::findOrFail($pacienteId);
        $evoluciones = EvolucionClinica::with(['cita.especialidad', 'medico'])
                                     ->where('paciente_id', $pacienteId)
                                     ->where('diagnostico', 'LIKE', '%' . $request->termino . '%')
                                     ->where('status', true)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('shared.historia-clinica.evoluciones.index', compact('paciente', 'evoluciones'))
               ->with('termino', $request->termino);
    }

    // =========================================================================
    // IMPORTAR/EXPORTAR HISTORIAL
    // =========================================================================

    public function exportarHistorial($pacienteId)
    {
        $paciente = Paciente::with(['usuario', 'historiaClinicaBase'])->findOrFail($pacienteId);
        $evoluciones = EvolucionClinica::with(['cita.especialidad', 'medico'])
                                     ->where('paciente_id', $pacienteId)
                                     ->where('status', true)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        // Generar PDF del historial (requiere instalación de dompdf)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shared.historia-clinica.exportar.pdf', compact('paciente', 'evoluciones'));
        
        return $pdf->download('historial-clinico-' . $paciente->primer_nombre . '-' . $paciente->primer_apellido . '.pdf');
    }

    public function generarResumen($pacienteId)
    {
        $paciente = Paciente::with(['usuario', 'historiaClinicaBase'])->findOrFail($pacienteId);
        $ultimaEvolucion = EvolucionClinica::with(['cita.especialidad', 'medico'])
                                         ->where('paciente_id', $pacienteId)
                                         ->where('status', true)
                                         ->orderBy('created_at', 'desc')
                                         ->first();

        return view('shared.historia-clinica.resumen', compact('paciente', 'ultimaEvolucion'));
    }

    /**
     * Médico solicita acceso a evoluciones de un paciente creadas por otro médico.
     * El PACIENTE es quien debe aprobar esta solicitud.
     */
    public function solicitarAcceso(Request $request, $pacienteId)
    {
        // Solo médicos pueden solicitar acceso
        if (Auth::user()->rol_id != 2) {
            return redirect()->back()->with('error', 'Solo los médicos pueden solicitar acceso al historial.');
        }
        
        $medicoSolicitante = Auth::user()->medico;
        
        if (!$medicoSolicitante) {
            return redirect()->back()->with('error', 'No se encontró el perfil de médico.');
        }

        $validator = Validator::make($request->all(), [
            'medico_propietario_id' => 'required|exists:medicos,id|different:' . $medicoSolicitante->id,
            'motivo_solicitud' => 'required|in:Interconsulta,Emergencia,Segunda Opinion,Referencia',
            'evolucion_id' => 'nullable|exists:evolucion_clinica,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paciente = Paciente::with('usuario')->findOrFail($pacienteId);
        
        // Verificar que el médico propietario tenga evoluciones con este paciente
        $tieneEvoluciones = EvolucionClinica::where('paciente_id', $pacienteId)
                                           ->where('medico_id', $request->medico_propietario_id)
                                           ->where('status', true)
                                           ->exists();
        
        if (!$tieneEvoluciones) {
            return redirect()->back()->with('error', 'El médico seleccionado no tiene evoluciones registradas con este paciente.');
        }
        
        // Verificar si ya existe una solicitud pendiente para esta combinación (y evolucion específica si aplica)
        $querySolicitud = \App\Models\SolicitudHistorial::where('paciente_id', $pacienteId)
            ->where('medico_solicitante_id', $medicoSolicitante->id)
            ->where('medico_propietario_id', $request->medico_propietario_id)
            ->where('estado_permiso', 'Pendiente')
            ->where('status', true);
            
        if ($request->filled('evolucion_id')) {
            $querySolicitud->where('evolucion_id', $request->evolucion_id);
        }
        
        $solicitudExistente = $querySolicitud->first();
            
        if ($solicitudExistente) {
            return redirect()->back()->with('info', 'Ya existe una solicitud pendiente con estas características.');
        }

        // Crear la solicitud - El token se envía al PACIENTE
        $token = $this->generarToken();
        
        $solicitud = \App\Models\SolicitudHistorial::create([
            'cita_id' => $request->cita_id ?? null,
            'paciente_id' => $pacienteId,
            'medico_solicitante_id' => $medicoSolicitante->id,
            'medico_propietario_id' => $request->medico_propietario_id,
            'evolucion_id' => $request->evolucion_id ?? null,
            'token_validacion' => $token,
            'token_expira_at' => now()->addHours(24), // 24 horas para que el paciente responda
            'motivo_solicitud' => $request->motivo_solicitud,
            'observaciones' => $request->observaciones,
            'estado_permiso' => 'Pendiente',
            'status' => true
        ]);

        // Enviar notificación al PACIENTE (no al médico propietario)
        $this->enviarNotificacionSolicitudPaciente($solicitud, $paciente);

        return redirect()->back()->with('success', 'Solicitud de acceso enviada. El paciente debe aprobar la solicitud.');
    }

    public function listarSolicitudesRepresentante()
    {
        $user = Auth::user();
        
        if ($user->rol_id != 4 || !$user->representante) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado.');
        }

        $representante = $user->representante;
        
        // Obtener Ids de pacientes especiales del representante
        $pacienteIds = $representante->pacientesEspeciales()->pluck('paciente_especiales.paciente_id');

        $solicitudesPendientes = \App\Models\SolicitudHistorial::with(['medicoSolicitante.usuario', 'medicoPropietario.usuario', 'paciente'])
            ->whereIn('paciente_id', $pacienteIds)
            ->where('estado_permiso', 'Pendiente')
            ->where('token_expira_at', '>', now())
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $solicitudesHistorial = \App\Models\SolicitudHistorial::with(['medicoSolicitante.usuario', 'medicoPropietario.usuario', 'paciente'])
            ->whereIn('paciente_id', $pacienteIds)
            ->where(function($query) {
                $query->where('estado_permiso', '!=', 'Pendiente')
                      ->orWhere('token_expira_at', '<', now());
            })
            ->where('status', true)
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('representante.solicitudes.index', compact('solicitudesPendientes', 'solicitudesHistorial'));
    }

    /**
     * El PACIENTE aprueba o rechaza una solicitud de acceso a su historial.
     * Este método se llama desde el panel del paciente.
     */
    public function aprobarSolicitud(Request $request, $solicitudId)
    {
        $user = Auth::user();
        
        $solicitud = \App\Models\SolicitudHistorial::where('id', $solicitudId)
            ->where('estado_permiso', 'Pendiente')
            ->where('status', true)
            ->firstOrFail();

        // Verificar permisos
        $permisoValido = false;

        // Caso 1: Usuario es Paciente
        if ($user->rol_id == 3 && $user->paciente) {
            if ($solicitud->paciente_id == $user->paciente->id) {
                $permisoValido = true;
            }
        }
        
        // Caso 2: Usuario es Representante
        if ($user->rol_id == 4 && $user->representante) {
            // Verificar si el paciente de la solicitud es un representado de este usuario
            $esRepresentado = $user->representante->pacientesEspeciales()
                                  ->where('paciente_especiales.paciente_id', $solicitud->paciente_id)
                                  ->exists();
            if ($esRepresentado) {
                $permisoValido = true;
            }
        }

        if (!$permisoValido) {
            return redirect()->back()->with('error', 'No tiene permisos para aprobar esta solicitud.');
        }

        // Verificar que no haya expirado el tiempo para responder
        if ($solicitud->token_expira_at < now()) {
            $solicitud->update(['estado_permiso' => 'Expirado']);
            return redirect()->back()->with('error', 'La solicitud ha expirado.');
        }

        // Aprobar la solicitud
        $solicitud->update([
            'estado_permiso' => 'Aprobado',
            'acceso_valido_hasta' => now()->addHours(24) // Acceso válido por 24 horas
        ]);

        // Notificar al médico solicitante podría ir aquí (opcional)
        \Log::info("Solicitud {$solicitud->id} aprobada por usuario {$user->id} (Rol: {$user->rol_id})");

        return redirect()->back()->with('success', 'Solicitud aprobada. El médico tendrá acceso temporal por 24 horas.');
    }

    /**
     * El PACIENTE rechaza una solicitud de acceso.
     */
    public function rechazarSolicitud(Request $request, $solicitudId)
    {
        $user = Auth::user();
        
        $solicitud = \App\Models\SolicitudHistorial::where('id', $solicitudId)
            ->where('estado_permiso', 'Pendiente')
            ->where('status', true)
            ->firstOrFail();

        // Verificar permisos (lógica compartida con aprobar)
        $permisoValido = false;

        // Caso 1: Usuario es Paciente
        if ($user->rol_id == 3 && $user->paciente) {
            if ($solicitud->paciente_id == $user->paciente->id) {
                $permisoValido = true;
            }
        }
        
        // Caso 2: Usuario es Representante
        if ($user->rol_id == 4 && $user->representante) {
            $esRepresentado = $user->representante->pacientesEspeciales()
                                  ->where('paciente_especiales.paciente_id', $solicitud->paciente_id)
                                  ->exists();
            if ($esRepresentado) {
                $permisoValido = true;
            }
        }

        if (!$permisoValido) {
            return redirect()->back()->with('error', 'No tiene permisos para rechazar esta solicitud.');
        }

        $solicitud->update([
            'estado_permiso' => 'Rechazado'
        ]);

        \Log::info("Solicitud {$solicitud->id} rechazada por usuario {$user->id} (Rol: {$user->rol_id})");

        return redirect()->back()->with('success', 'Solicitud rechazada.');
    }

    /**
     * Verificar si un médico tiene acceso aprobado a las evoluciones de otro médico con un paciente.
     */
    public function tieneAccesoAprobado($medicoSolicitanteId, $pacienteId, $medicoPropietarioId)
    {
        return \App\Models\SolicitudHistorial::where('paciente_id', $pacienteId)
            ->where('medico_solicitante_id', $medicoSolicitanteId)
            ->where('medico_propietario_id', $medicoPropietarioId)
            ->where('estado_permiso', 'Aprobado')
            ->where('acceso_valido_hasta', '>', now())
            ->where('status', true)
            ->exists();
    }

    /**
     * Listar solicitudes pendientes para el paciente autenticado.
     * Este método se usa en el panel del paciente.
     */
    public function listarSolicitudesPaciente()
    {
        $user = Auth::user();
        
        if ($user->rol_id != 3) {
            abort(403, 'Solo los pacientes pueden ver sus solicitudes.');
        }
        
        $paciente = $user->paciente;
        
        $solicitudesPendientes = \App\Models\SolicitudHistorial::with(['medicoSolicitante.usuario', 'medicoPropietario.usuario'])
            ->where('paciente_id', $paciente->id)
            ->where('estado_permiso', 'Pendiente')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $solicitudesHistorial = \App\Models\SolicitudHistorial::with(['medicoSolicitante.usuario', 'medicoPropietario.usuario'])
            ->where('paciente_id', $paciente->id)
            ->whereIn('estado_permiso', ['Aprobado', 'Rechazado', 'Expirado'])
            ->where('status', true)
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();
            
        return view('paciente.solicitudes.index', compact('solicitudesPendientes', 'solicitudesHistorial', 'paciente'));
    }

    private function generarToken()
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    }

    private function enviarNotificacionSolicitudPaciente($solicitud, $paciente)
    {
        try {
            $solicitud->load(['medicoSolicitante.usuario', 'medicoPropietario.usuario']);
            
            // Obtener nombres de médicos de forma segura
            $nombreSolicitante = $solicitud->medicoSolicitante->usuario->primer_nombre ?? 'Médico';
            $nombrePropietario = $solicitud->medicoPropietario->usuario->primer_nombre ?? 'Médico';
            $motivo = $solicitud->motivo_solicitud;
            
            // Determinar si es paciente especial (verificar si tiene representante)
            $pacienteEspecial = \App\Models\PacienteEspecial::where('paciente_id', $paciente->id)
                ->where('status', true)
                ->first();
            
            if ($pacienteEspecial) {
                // Es paciente especial - notificar al representante
                $representante = $pacienteEspecial->representantes()
                    ->wherePivot('status', true)
                    ->first();
                    
                if ($representante && $representante->usuario) {
                    // Notificar al representante
                    \App\Models\Notificacion::create([
                        'receptor_id' => $representante->usuario->id,
                        'receptor_rol' => 'representante',
                        'tipo' => 'solicitud_acceso',
                        'titulo' => 'Solicitud de acceso al historial de su representado',
                        'mensaje' => "El Dr. {$nombreSolicitante} solicita ver las evoluciones clínicas de {$pacienteEspecial->primer_nombre} registradas por el Dr. {$nombrePropietario}. Motivo: {$motivo}",
                        'via' => 'sistema',
                        'estado_envio' => 'Pendiente',
                        'status' => true
                    ]);
                    
                    \Log::info("Notificación enviada al representante {$representante->id} para paciente especial {$pacienteEspecial->id} - Solicitud: {$solicitud->id}");
                    return;
                }
            }
            
            // Paciente regular - notificar directamente al paciente
            if ($paciente->usuario) {
                \App\Models\Notificacion::create([
                    'receptor_id' => $paciente->usuario->id,
                    'receptor_rol' => 'paciente',
                    'tipo' => 'solicitud_acceso',
                    'titulo' => 'Solicitud de acceso a tu historial médico',
                    'mensaje' => "El Dr. {$nombreSolicitante} solicita ver tus evoluciones clínicas registradas por el Dr. {$nombrePropietario}. Motivo: {$motivo}",
                    'via' => 'sistema',
                    'estado_envio' => 'Pendiente',
                    'status' => true
                ]);
                
                \Log::info("Notificación de solicitud enviada al paciente {$paciente->id} - Solicitud: {$solicitud->id}");
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando notificación de solicitud: ' . $e->getMessage());
        }
    }
}
