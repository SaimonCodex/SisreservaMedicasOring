<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\PacienteEspecial;
use App\Models\Representante;
use App\Models\Especialidad;
use App\Models\Consultorio;
use App\Models\Estado;
use App\Models\MedicoConsultorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            $especialidades = Especialidad::where('status', true)->orderBy('nombre')->get();
            $consultorios = Consultorio::where('status', true)->get();
            $estados = Estado::where('status', true)->orderBy('estado')->get();
            
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
        
        $validator = Validator::make($request->all(), [
            'tipo_cita' => 'required|in:propia,terceros',
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'consultorio_id' => 'required|exists:consultorios,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'tipo_consulta' => 'required|in:Consultorio,Domicilio',
            'motivo' => 'nullable|string|max:1000',
            // Datos para terceros
            'tercero_primer_nombre' => 'required_if:tipo_cita,terceros|max:100',
            'tercero_primer_apellido' => 'required_if:tipo_cita,terceros|max:100',
            'tercero_tipo_documento' => 'required_if:tipo_cita,terceros|in:V,E,P,J',
            'tercero_numero_documento' => 'required_if:tipo_cita,terceros|max:20',
        ], [
            'medico_id.required' => 'Debe seleccionar un médico',
            'especialidad_id.required' => 'Debe seleccionar una especialidad',
            'consultorio_id.required' => 'Debe seleccionar un consultorio',
            'fecha_cita.required' => 'Debe seleccionar una fecha',
            'fecha_cita.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'hora_inicio.required' => 'Debe seleccionar una hora',
            'tercero_primer_nombre.required_if' => 'El nombre del paciente es requerido',
            'tercero_primer_apellido.required_if' => 'El apellido del paciente es requerido',
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida al crear cita', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            return DB::transaction(function () use ($request, $user) {
                $pacienteId = null;
                $pacienteEspecialId = null;
                $representanteId = null;
                
                // Si es cita propia del paciente
                if ($request->tipo_cita == 'propia' && $user->rol_id == 3) {
                    $pacienteId = $user->paciente->id;
                }
                
                // Si es cita para terceros
                if ($request->tipo_cita == 'terceros') {
                    // Crear o buscar el paciente (como paciente normal primero)
                    $paciente = Paciente::firstOrCreate(
                        [
                            'tipo_documento' => $request->tercero_tipo_documento,
                            'numero_documento' => $request->tercero_numero_documento
                        ],
                        [
                            'primer_nombre' => $request->tercero_primer_nombre,
                            'segundo_nombre' => $request->tercero_segundo_nombre,
                            'primer_apellido' => $request->tercero_primer_apellido,
                            'segundo_apellido' => $request->tercero_segundo_apellido,
                            'prefijo_tlf' => $request->tercero_prefijo_tlf,
                            'numero_tlf' => $request->tercero_numero_tlf,
                            'status' => true
                        ]
                    );
                    
                    $pacienteId = $paciente->id;
                    
                    // Si el usuario actual es paciente, guardarlo como representante
                    if ($user->rol_id == 3 && $user->paciente) {
                        $representante = Representante::firstOrCreate(
                            [
                                'tipo_documento' => $user->paciente->tipo_documento,
                                'numero_documento' => $user->paciente->numero_documento
                            ],
                            [
                                'primer_nombre' => $user->paciente->primer_nombre,
                                'segundo_nombre' => $user->paciente->segundo_nombre,
                                'primer_apellido' => $user->paciente->primer_apellido,
                                'segundo_apellido' => $user->paciente->segundo_apellido,
                                'prefijo_tlf' => $user->paciente->prefijo_tlf,
                                'numero_tlf' => $user->paciente->numero_tlf,
                                'parentesco' => 'Representante',
                                'status' => true
                            ]
                        );
                        $representanteId = $representante->id;
                    }
                }
                
                // Obtener tarifa del médico para esta especialidad
                $medico = Medico::find($request->medico_id);
                $especialidadPivot = $medico->especialidades()
                    ->where('especialidad_id', $request->especialidad_id)
                    ->first();
                
                $tarifa = $especialidadPivot ? $especialidadPivot->pivot->tarifa : 0;
                $tarifaExtra = 0;
                
                // Si es domicilio, agregar tarifa extra
                if ($request->tipo_consulta == 'Domicilio' && $especialidadPivot) {
                    $tarifaExtra = $especialidadPivot->pivot->tarifa_extra_domicilio ?? 0;
                }
                
                // Calcular hora fin (30 minutos por defecto)
                $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
                $horaFin = $horaInicio->copy()->addMinutes(30)->format('H:i');

                // Verificar disponibilidad del médico
                $citaExistente = Cita::where('medico_id', $request->medico_id)
                                    ->where('fecha_cita', $request->fecha_cita)
                                    ->where('hora_inicio', $request->hora_inicio)
                                    ->where('status', true)
                                    ->whereNotIn('estado_cita', ['Cancelada', 'No Asistió'])
                                    ->exists();

                if ($citaExistente) {
                    throw new \Exception('El médico no está disponible en ese horario. Por favor seleccione otra hora.');
                }

                $cita = Cita::create([
                    'paciente_id' => $pacienteId,
                    'paciente_especial_id' => $pacienteEspecialId,
                    'representante_id' => $representanteId,
                    'medico_id' => $request->medico_id,
                    'especialidad_id' => $request->especialidad_id,
                    'consultorio_id' => $request->consultorio_id,
                    'fecha_cita' => $request->fecha_cita,
                    'hora_inicio' => $request->hora_inicio,
                    'hora_fin' => $horaFin,
                    'tipo_consulta' => $request->tipo_consulta,
                    'tarifa' => $tarifa,
                    'tarifa_extra' => $tarifaExtra,
                    'motivo' => $request->motivo,
                    'estado_cita' => 'Programada',
                    'duracion_minutos' => 30,
                    'status' => true
                ]);

                Log::info('Cita creada exitosamente', ['cita_id' => $cita->id]);

                // Enviar notificación
                $this->enviarNotificacionCita($cita);

                // Redirigir según el rol
                if ($user->rol_id == 3) {
                    return redirect()->route('paciente.citas.index')->with('success', '¡Cita agendada exitosamente! Tarifa: $' . number_format($tarifa + $tarifaExtra, 2));
                }
                
                return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente');
            });
            
        } catch (\Exception $e) {
            Log::error('Error al crear cita: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
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
            'tipo_consulta' => 'required|in:Consultorio,Domicilio',
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
        $cita->update(['status' => false, 'estado_cita' => 'Cancelada']);

        return redirect()->route('citas.index')->with('success', 'Cita cancelada exitosamente');
    }

    // =========================================================================
    // API ENDPOINTS
    // =========================================================================
    
    /**
     * Obtener consultorios por estado
     */
    public function getConsultoriosPorEstado($estadoId)
    {
        $consultorios = Consultorio::where('estado_id', $estadoId)
            ->where('status', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'direccion_detallada']);
        
        return response()->json($consultorios);
    }
    
    /**
     * Obtener especialidades por consultorio
     */
    public function getEspecialidadesPorConsultorio($consultorioId)
    {
        Log::info('Buscando especialidades para consultorio: ' . $consultorioId);
        
        $consultorio = Consultorio::find($consultorioId);
        
        if (!$consultorio) {
            Log::warning('Consultorio no encontrado: ' . $consultorioId);
            return response()->json([]);
        }
        
        // Obtener especialidades activas del consultorio con pivot activo
        $especialidades = $consultorio->especialidades()
            ->where('especialidad_consultorio.status', true)
            ->where('especialidades.status', true)
            ->orderBy('nombre')
            ->get(['especialidades.id', 'especialidades.nombre']);
        
        Log::info('Especialidades encontradas: ' . $especialidades->count());
        
        return response()->json($especialidades);
    }
    
    /**
     * Obtener consultorios por especialidad
     */
    public function getConsultoriosPorEspecialidad($especialidadId)
    {
        $especialidad = Especialidad::find($especialidadId);
        
        if (!$especialidad) {
            return response()->json([]);
        }
        
        $consultorios = $especialidad->consultorios()
            ->where('especialidad_consultorio.status', true)
            ->where('consultorios.status', true)
            ->orderBy('nombre')
            ->get(['consultorios.id', 'consultorios.nombre', 'consultorios.direccion_detallada', 'consultorios.estado_id']);
        
        return response()->json($consultorios);
    }
    
    /**
     * Obtener médicos por especialidad y consultorio
     */
    public function getMedicosPorEspecialidadConsultorio(Request $request)
    {
        $especialidadId = $request->especialidad_id;
        $consultorioId = $request->consultorio_id;
        
        Log::info('Buscando médicos', ['especialidad' => $especialidadId, 'consultorio' => $consultorioId]);
        
        $medicos = Medico::whereHas('especialidades', function($q) use ($especialidadId) {
                $q->where('especialidades.id', $especialidadId)
                  ->where('medico_especialidad.status', true);
            })
            ->whereHas('consultorios', function($q) use ($consultorioId) {
                $q->where('consultorios.id', $consultorioId)
                  ->where('medico_consultorio.status', true);
            })
            ->where('status', true)
            ->get();
        
        Log::info('Médicos encontrados: ' . $medicos->count());
        
        $result = $medicos->map(function($medico) use ($especialidadId) {
            $pivot = $medico->especialidades->where('id', $especialidadId)->first();
            return [
                'id' => $medico->id,
                'nombre' => 'Dr. ' . $medico->primer_nombre . ' ' . $medico->primer_apellido,
                'tarifa' => $pivot ? $pivot->pivot->tarifa : 0,
                'atiende_domicilio' => $pivot ? (bool)($pivot->pivot->atiende_domicilio ?? false) : false,
                'tarifa_extra_domicilio' => $pivot ? ($pivot->pivot->tarifa_extra_domicilio ?? 0) : 0,
            ];
        });
        
        return response()->json($result);
    }
    
    /**
     * Obtener horarios disponibles del médico para una fecha
     */
    public function getHorariosDisponibles(Request $request)
    {
        $medicoId = $request->medico_id;
        $consultorioId = $request->consultorio_id;
        $fecha = $request->fecha;
        
        if (!$medicoId || !$fecha) {
            return response()->json(['error' => 'Parámetros incompletos'], 400);
        }
        
        $diaSemana = $this->obtenerDiaSemana($fecha);
        
        // Obtener horario del médico para ese día en ese consultorio
        $horarioMedico = MedicoConsultorio::where('medico_id', $medicoId)
            ->where('consultorio_id', $consultorioId)
            ->where('dia_semana', $diaSemana)
            ->where('status', true)
            ->first();
        
        if (!$horarioMedico) {
            return response()->json([
                'disponible' => false,
                'mensaje' => 'El médico no trabaja este día en este consultorio',
                'horarios' => []
            ]);
        }
        
        // Obtener citas ya agendadas para ese día
        $citasOcupadas = Cita::where('medico_id', $medicoId)
            ->where('fecha_cita', $fecha)
            ->where('status', true)
            ->whereNotIn('estado_cita', ['Cancelada', 'No Asistió'])
            ->pluck('hora_inicio')
            ->toArray();
        
        // Generar slots de 30 minutos
        $horaInicio = Carbon::createFromFormat('H:i:s', $horarioMedico->horario_inicio);
        $horaFin = Carbon::createFromFormat('H:i:s', $horarioMedico->horario_fin);
        
        $slots = [];
        $current = $horaInicio->copy();
        
        while ($current < $horaFin) {
            $horaStr = $current->format('H:i');
            $slots[] = [
                'hora' => $horaStr,
                'disponible' => !in_array($horaStr, $citasOcupadas),
                'ocupada' => in_array($horaStr, $citasOcupadas)
            ];
            $current->addMinutes(30);
        }
        
        return response()->json([
            'disponible' => true,
            'horario_inicio' => $horarioMedico->horario_inicio,
            'horario_fin' => $horarioMedico->horario_fin,
            'horarios' => $slots
        ]);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function calcularDuracion($horaInicio, $horaFin)
    {
        $inicio = Carbon::createFromFormat('H:i', $horaInicio);
        $fin = Carbon::createFromFormat('H:i', $horaFin);
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
            if ($cita->paciente && $cita->paciente->usuario) {
                Mail::send('emails.cita', ['cita' => $cita], function($message) use ($cita) {
                    $message->to($cita->paciente->usuario->correo)
                            ->subject('Confirmación de Cita - Sistema Médico');
                });
            }
        } catch (\Exception $e) {
            Log::error('Error enviando notificación de cita: ' . $e->getMessage());
        }
    }
}
