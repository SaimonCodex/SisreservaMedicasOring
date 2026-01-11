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
        $paciente = Paciente::with('usuario')->findOrFail($pacienteId);
        return view('medico.historia-clinica.base.create', compact('paciente'));
    }
// ...
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
// ...
    public function indexEvoluciones($pacienteId)
    {
        $user = Auth::user();
        
        // Administradores NO tienen acceso
        if ($user->rol_id == 1) {
            abort(403, 'Los administradores no tienen acceso a historias clínicas.');
        }
        
        $paciente = Paciente::with('usuario')->findOrFail($pacienteId);
        $evoluciones = EvolucionClinica::with(['cita', 'medico'])
                                     ->where('paciente_id', $pacienteId)
                                     ->where('status', true)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

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
        
        // Verificar que la cita esté en estado completada
        if ($cita->estado_cita !== 'Completada') {
            return redirect()->back()->with('error', 'Solo se puede crear evolución clínica para citas completadas.');
        }

        // Verificar que no exista ya una evolución para esta cita
        $existeEvolucion = EvolucionClinica::where('cita_id', $citaId)->exists();
        if ($existeEvolucion) {
            return redirect()->route('historia-clinica.evoluciones.show', $citaId)
                           ->with('error', 'Ya existe una evolución clínica para esta cita.');
        }

        return view('medico.historia-clinica.evoluciones.create', compact('cita'));
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

    // =========================================================================
    // SISTEMA DE PERMISOS PARA COMPARTIR HISTORIAL
    // =========================================================================

    public function solicitarAcceso(Request $request, $pacienteId)
    {
        $validator = Validator::make($request->all(), [
            'medico_solicitante_id' => 'required|exists:medicos,id',
            'motivo_solicitud' => 'required|in:Interconsulta,Emergencia,Segunda Opinion,Referencia',
            'cita_id' => 'nullable|exists:citas,id',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $paciente = Paciente::findOrFail($pacienteId);
        $medicoPropietario = Auth::user()->medico;

        if (!$medicoPropietario) {
            return redirect()->back()->with('error', 'Solo los médicos pueden solicitar acceso al historial.');
        }

        $solicitud = \App\Models\SolicitudHistorial::create([
            'cita_id' => $request->cita_id,
            'paciente_id' => $pacienteId,
            'medico_solicitante_id' => $request->medico_solicitante_id,
            'medico_propietario_id' => $medicoPropietario->id,
            'token_validacion' => $this->generarToken(),
            'token_expira_at' => now()->addMinutes(15),
            'motivo_solicitud' => $request->motivo_solicitud,
            'observaciones' => $request->observaciones,
            'status' => true
        ]);

        // Enviar notificación al médico propietario
        $this->enviarNotificacionSolicitud($solicitud);

        return redirect()->back()->with('success', 'Solicitud de acceso enviada exitosamente.');
    }

    public function validarTokenAcceso(Request $request, $solicitudId)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Token inválido'], 400);
        }

        $solicitud = \App\Models\SolicitudHistorial::findOrFail($solicitudId);

        if ($solicitud->token_validacion !== $request->token) {
            $solicitud->increment('intentos_fallidos');
            
            if ($solicitud->intentos_fallidos >= 3) {
                $solicitud->update(['estado_permiso' => 'Expirado']);
                return response()->json(['error' => 'Demasiados intentos fallidos. La solicitud ha expirado.'], 400);
            }
            
            return response()->json(['error' => 'Token incorrecto. Intentos restantes: ' . (3 - $solicitud->intentos_fallidos)], 400);
        }

        if ($solicitud->token_expira_at < now()) {
            $solicitud->update(['estado_permiso' => 'Expirado']);
            return response()->json(['error' => 'El token ha expirado.'], 400);
        }

        $solicitud->update([
            'estado_permiso' => 'Aprobado',
            'acceso_valido_hasta' => now()->addHours(24)
        ]);

        return response()->json(['success' => 'Acceso autorizado. Válido por 24 horas.']);
    }

    private function generarToken()
    {
        return strtoupper(substr(md5(uniqid()), 0, 6));
    }

    private function enviarNotificacionSolicitud($solicitud)
    {
        // Implementar notificación (email, sistema interno, etc.)
        try {
            $solicitud->load(['medicoPropietario.usuario', 'medicoSolicitante.usuario', 'paciente.usuario']);
            
            // Aquí iría el código para enviar la notificación
            \Log::info("Solicitud de acceso al historial creada: {$solicitud->id}");
        } catch (\Exception $e) {
            \Log::error('Error enviando notificación de solicitud: ' . $e->getMessage());
        }
    }
}
