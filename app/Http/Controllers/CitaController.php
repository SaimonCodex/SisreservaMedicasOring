<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Especialidad;
use App\Models\Consultorio;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CitaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Para paciente: vista específica
        if ($user->rol_id == 3) {
            $paciente = $user->paciente;
            
            if (!$paciente) {
                return redirect()->route('paciente.dashboard')->with('error', 'No se encontró el perfil de paciente');
            }
            
            $citas = Cita::with(['medico', 'especialidad', 'consultorio'])
                         ->where('paciente_id', $paciente->id)
                         ->where('status', true)
                         ->orderBy('fecha_cita', 'desc')
                         ->get();
            
            return view('paciente.citas.index', compact('citas'));
        }
        
        // Para médico: sus citas
        if ($user->rol_id == 2) {
            $medico = $user->medico;
            $citas = Cita::with(['paciente', 'especialidad', 'consultorio'])
                         ->where('medico_id', $medico->id)
                         ->where('status', true)
                         ->orderBy('fecha_cita', 'desc')
                         ->get();
            
            return view('shared.citas.index', compact('citas'));
        }
        
        // Para admin: todas las citas
        $citas = Cita::with(['paciente', 'medico', 'especialidad', 'consultorio'])
                     ->where('status', true)
                     ->orderBy('fecha_cita', 'desc')
                     ->get();

        return view('shared.citas.index', compact('citas'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Para paciente: vista específica con datos precargados
        if ($user->rol_id == 3) {
            $paciente = $user->paciente;
            $especialidades = Especialidad::where('status', true)->get();
            $consultorios = Consultorio::where('status', true)->get();
            $estados = Estado::where('status', true)->get();
            
            return view('paciente.citas.create', compact('paciente', 'especialidades', 'consultorios', 'estados'));
        }
        
        // Para médico y admin: vista compartida
        $medicos = Medico::with('especialidades')->where('status', true)->get();
        $pacientes = Paciente::where('status', true)->get();
        $especialidades = Especialidad::where('status', true)->get();
        $consultorios = Consultorio::where('status', true)->get();

        return view('shared.citas.create', compact('medicos', 'pacientes', 'especialidades', 'consultorios'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        Log::info('Creando cita', ['user_id' => $user->id, 'rol' => $user->rol_id, 'data' => $request->all()]);
        
        // Si es paciente y es cita propia
        if ($user->rol_id == 3 && $request->tipo_cita == 'propia') {
            $paciente = $user->paciente;
            $request->merge(['paciente_id' => $paciente->id]);
        }
        
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'consultorio_id' => 'nullable|exists:consultorios,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after:hora_inicio',
            'tipo_consulta' => 'required|in:Presencial,Telemedicina,Domicilio',
            'motivo' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ], [
            'paciente_id.required' => 'Debe seleccionar un paciente',
            'medico_id.required' => 'Debe seleccionar un médico',
            'especialidad_id.required' => 'Debe seleccionar una especialidad',
            'fecha_cita.required' => 'Debe seleccionar una fecha',
            'fecha_cita.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'hora_inicio.required' => 'Debe seleccionar una hora'
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida al crear cita', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Calcular hora fin si no se proporciona (30 minutos por defecto)
        if (!$request->hora_fin) {
            $horaInicio = \Carbon\Carbon::createFromFormat('H:i', $request->hora_inicio);
            $horaFin = $horaInicio->copy()->addMinutes(30)->format('H:i');
            $request->merge(['hora_fin' => $horaFin]);
        }

        // Verificar disponibilidad del médico
        $citaExistente = Cita::where('medico_id', $request->medico_id)
                            ->where('fecha_cita', $request->fecha_cita)
                            ->where('hora_inicio', '<', $request->hora_fin)
                            ->where('hora_fin', '>', $request->hora_inicio)
                            ->where('status', true)
                            ->exists();

        if ($citaExistente) {
            return redirect()->back()->with('error', 'El médico no está disponible en ese horario')->withInput();
        }

        try {
            $cita = Cita::create([
                'paciente_id' => $request->paciente_id,
                'medico_id' => $request->medico_id,
                'especialidad_id' => $request->especialidad_id,
                'consultorio_id' => $request->consultorio_id,
                'fecha_cita' => $request->fecha_cita,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'tipo_consulta' => $request->tipo_consulta,
                'motivo' => $request->motivo,
                'observaciones' => $request->observaciones,
                'estado_cita' => 'Programada',
                'duracion_minutos' => $this->calcularDuracion($request->hora_inicio, $request->hora_fin),
                'status' => true
            ]);

            Log::info('Cita creada exitosamente', ['cita_id' => $cita->id]);

            // Enviar notificación
            $this->enviarNotificacionCita($cita);

            // Redirigir según el rol
            if ($user->rol_id == 3) {
                return redirect()->route('paciente.citas.index')->with('success', 'Cita agendada exitosamente');
            }
            
            return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente');
            
        } catch (\Exception $e) {
            Log::error('Error al crear cita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear la cita: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $cita = Cita::with(['paciente', 'medico', 'especialidad', 'consultorio', 'evolucionClinica'])->findOrFail($id);
        return view('shared.citas.show', compact('cita'));
    }

    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $medicos = Medico::with('especialidades')->where('status', true)->get();
        $pacientes = Paciente::where('status', true)->get();
        $especialidades = Especialidad::where('status', true)->get();
        $consultorios = Consultorio::where('status', true)->get();

        return view('shared.citas.edit', compact('cita', 'medicos', 'pacientes', 'especialidades', 'consultorios'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'consultorio_id' => 'nullable|exists:consultorios,id',
            'fecha_cita' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo_consulta' => 'required|in:Presencial,Telemedicina,Domicilio',
            'estado_cita' => 'required|in:Programada,Confirmada,En Progreso,Completada,Cancelada,No Asistió',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cita = Cita::findOrFail($id);
        
        // Verificar disponibilidad (excluyendo la cita actual)
        $citaExistente = Cita::where('medico_id', $request->medico_id)
                            ->where('fecha_cita', $request->fecha_cita)
                            ->where('hora_inicio', '<', $request->hora_fin)
                            ->where('hora_fin', '>', $request->hora_inicio)
                            ->where('id', '!=', $id)
                            ->where('status', true)
                            ->exists();

        if ($citaExistente) {
            return redirect()->back()->with('error', 'El médico no está disponible en ese horario')->withInput();
        }

        $cita->update(array_merge($request->all(), [
            'duracion_minutos' => $this->calcularDuracion($request->hora_inicio, $request->hora_fin)
        ]));

        return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['status' => false]);

        return redirect()->route('citas.index')->with('success', 'Cita cancelada exitosamente');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado_cita' => 'required|in:Programada,Confirmada,En Progreso,Completada,Cancelada,No Asistió'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $cita = Cita::findOrFail($id);
        $cita->update(['estado_cita' => $request->estado_cita]);

        return redirect()->back()->with('success', 'Estado de cita actualizado exitosamente');
    }

    public function buscarDisponibilidad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'especialidad_id' => 'nullable|exists:especialidades,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $medico = Medico::find($request->medico_id);
        $horarios = \App\Models\MedicoConsultorio::where('medico_id', $request->medico_id)
                                               ->where('dia_semana', $this->obtenerDiaSemana($request->fecha))
                                               ->where('status', true)
                                               ->get();

        // Obtener citas existentes para esa fecha
        $citasOcupadas = Cita::where('medico_id', $request->medico_id)
                            ->where('fecha_cita', $request->fecha)
                            ->where('status', true)
                            ->get()
                            ->map(function($cita) {
                                return [
                                    'inicio' => $cita->hora_inicio,
                                    'fin' => $cita->hora_fin
                                ];
                            });

        return response()->json([
            'horarios' => $horarios,
            'citas_ocupadas' => $citasOcupadas
        ]);
    }
    
    // API para obtener médicos por especialidad
    public function getMedicosPorEspecialidad($especialidadId)
    {
        $medicos = Medico::whereHas('especialidades', function($q) use ($especialidadId) {
            $q->where('especialidades.id', $especialidadId);
        })->where('status', true)->get();
        
        return response()->json($medicos->map(function($medico) {
            return [
                'id' => $medico->id,
                'nombre' => 'Dr. ' . $medico->primer_nombre . ' ' . $medico->primer_apellido
            ];
        }));
    }
    
    // API para obtener consultorios por médico
    public function getConsultoriosPorMedico($medicoId)
    {
        $consultorios = Consultorio::whereHas('medicos', function($q) use ($medicoId) {
            $q->where('medicos.id', $medicoId);
        })->where('status', true)->get();
        
        return response()->json($consultorios);
    }

    private function calcularDuracion($horaInicio, $horaFin)
    {
        $inicio = \Carbon\Carbon::createFromFormat('H:i', $horaInicio);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $horaFin);
        return $inicio->diffInMinutes($fin);
    }

    private function obtenerDiaSemana($fecha)
    {
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return $dias[date('w', strtotime($fecha))];
    }

    private function enviarNotificacionCita($cita)
    {
        try {
            Mail::send('emails.cita', ['cita' => $cita], function($message) use ($cita) {
                $message->to($cita->paciente->usuario->correo)
                        ->subject('Confirmación de Cita - Sistema Médico');
            });
        } catch (\Exception $e) {
            Log::error('Error enviando notificación de cita: ' . $e->getMessage());
        }
    }
}
