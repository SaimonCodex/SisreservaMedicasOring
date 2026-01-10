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
            
            // 1. Citas propias del paciente
            $citasPropias = Cita::with(['medico', 'especialidad', 'consultorio', 'paciente'])
                         ->where('paciente_id', $paciente->id)
                         ->where('status', true)
                         ->orderBy('fecha_cita', 'desc')
                         ->get()
                         ->map(function($cita) {
                             $cita->tipo_cita_display = 'propia';
                             $cita->paciente_especial_info = null;
                             return $cita;
                         });
            
            // 2. Buscar si este paciente es representante de pacientes especiales
            $representante = Representante::where('tipo_documento', $paciente->tipo_documento)
                                          ->where('numero_documento', $paciente->numero_documento)
                                          ->first();
            
            $citasTerceros = collect();
            $pacientesEspeciales = collect();
            
            if ($representante) {
                // Obtener pacientes especiales de este representante
                $pacientesEspeciales = $representante->pacientesEspeciales()->with(['paciente'])->get();
                
                // Obtener citas de los pacientes asociados a esos pacientes especiales
                $pacienteIds = $pacientesEspeciales->pluck('paciente_id')->filter();
                
                if ($pacienteIds->isNotEmpty()) {
                    $citasTerceros = Cita::with(['medico', 'especialidad', 'consultorio', 'paciente', 'paciente.pacienteEspecial'])
                                         ->whereIn('paciente_id', $pacienteIds)
                                         ->where('status', true)
                                         ->orderBy('fecha_cita', 'desc')
                                         ->get()
                                         ->map(function($cita) use ($pacientesEspeciales) {
                                             $cita->tipo_cita_display = 'terceros';
                                             // Buscar info del paciente especial
                                             $pe = $pacientesEspeciales->firstWhere('paciente_id', $cita->paciente_id);
                                             $cita->paciente_especial_info = $pe;
                                             return $cita;
                                         });
                }
            }
            
            // Combinar todas las citas
            $citas = $citasPropias->concat($citasTerceros)->sortByDesc('fecha_cita');
            
            return view('paciente.citas.index', compact('citas', 'pacientesEspeciales'));
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
        
        // Reglas de validación base
        $rules = [
            'tipo_cita' => 'required|in:propia,terceros',
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'consultorio_id' => 'required|exists:consultorios,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'tipo_consulta' => 'required|in:Consultorio,Domicilio,Presencial',
            'motivo' => 'nullable|string|max:1000',
        ];
        
        // Reglas adicionales para terceros
        if ($request->tipo_cita == 'terceros') {
            $rules = array_merge($rules, [
                // Representante
                'rep_primer_nombre' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                'rep_primer_apellido' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                'rep_tipo_documento' => 'required|in:V,E,P,J',
                'rep_numero_documento' => 'required|max:20',
                'rep_parentesco' => 'required|in:Padre,Madre,Hijo/a,Hermano/a,Tío/a,Sobrino/a,Abuelo/a,Nieto/a,Primo/a,Amigo/a,Tutor,Otro',
                // Paciente Especial
                'pac_primer_nombre' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                'pac_primer_apellido' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                'pac_tiene_documento' => 'required|in:si,no',
                'pac_tipo' => 'required|in:Menor de Edad,Discapacitado,Anciano,Incapacitado',
            ]);
            
            // Si tiene documento, validar
            if ($request->pac_tiene_documento == 'si') {
                $rules['pac_tipo_documento'] = 'required|in:V,E,P,J';
                $rules['pac_numero_documento'] = 'required|max:20';
            }
        }
        
        $messages = [
            'medico_id.required' => 'Debe seleccionar un médico',
            'especialidad_id.required' => 'Debe seleccionar una especialidad',
            'consultorio_id.required' => 'Debe seleccionar un consultorio',
            'fecha_cita.required' => 'Debe seleccionar una fecha',
            'hora_inicio.required' => 'Debe seleccionar una hora',
            'rep_primer_nombre.required' => 'El nombre del representante es requerido',
            'rep_primer_apellido.required' => 'El apellido del representante es requerido',
            'pac_primer_nombre.required' => 'El nombre del paciente es requerido',
            'pac_primer_apellido.required' => 'El apellido del paciente es requerido',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::warning('Validación fallida al crear cita', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            return DB::transaction(function () use ($request, $user) {
                $pacienteId = null;
                $pacienteEspecialId = null;
                $representanteId = null;
                
                // Mapear tipo_consulta: Consultorio -> Presencial
                $tipoConsulta = $request->tipo_consulta == 'Consultorio' ? 'Presencial' : $request->tipo_consulta;
                
                // ========================================
                // CITA PROPIA
                // ========================================
                if ($request->tipo_cita == 'propia' && $user->rol_id == 3) {
                    $pacienteId = $user->paciente->id;
                }
                
                // ========================================
                // CITA PARA TERCEROS (Paciente Especial)
                // ========================================
                if ($request->tipo_cita == 'terceros') {
                    
                    // 1. Crear o buscar REPRESENTANTE
                    $representante = Representante::firstOrCreate(
                        [
                            'tipo_documento' => $request->rep_tipo_documento,
                            'numero_documento' => $request->rep_numero_documento
                        ],
                        [
                            'primer_nombre' => $request->rep_primer_nombre,
                            'segundo_nombre' => $request->rep_segundo_nombre,
                            'primer_apellido' => $request->rep_primer_apellido,
                            'segundo_apellido' => $request->rep_segundo_apellido,
                            'prefijo_tlf' => $request->rep_prefijo_tlf,
                            'numero_tlf' => $request->rep_numero_tlf,
                            'parentesco' => $request->rep_parentesco,
                            'estado_id' => $request->rep_estado_id,
                            'municipio_id' => $request->rep_municipio_id,
                            'ciudad_id' => $request->rep_ciudad_id,
                            'parroquia_id' => $request->rep_parroquia_id,
                            'direccion_detallada' => $request->rep_direccion_detallada,
                            'status' => true
                        ]
                    );
                    $representanteId = $representante->id;
                    
                    Log::info('Representante creado/encontrado', ['id' => $representanteId]);
                    
                    // 2. Determinar documento del paciente especial
                    $tieneDocumento = $request->pac_tiene_documento == 'si';
                    $pacTipoDoc = null;
                    $pacNumeroDoc = null;
                    
                    if ($tieneDocumento) {
                        $pacTipoDoc = $request->pac_tipo_documento;
                        $pacNumeroDoc = $request->pac_numero_documento;
                    } else {
                        // Generar documento basado en representante
                        $pacTipoDoc = $request->rep_tipo_documento;
                        
                        // Contar cuántos pacientes especiales ya existen con este patrón
                        $countPacientes = PacienteEspecial::where('numero_documento', 'LIKE', $request->rep_numero_documento . '-%')
                            ->count();
                        $pacNumeroDoc = $request->rep_numero_documento . '-' . str_pad($countPacientes + 1, 2, '0', STR_PAD_LEFT);
                    }
                    
                    Log::info('Documento paciente especial', ['tipo' => $pacTipoDoc, 'numero' => $pacNumeroDoc]);
                    
                    // Determinar ubicación del paciente (propia o del representante)
                    $usarMismaDireccion = $request->misma_direccion == 'on' || $request->misma_direccion == '1';
                    $pacEstadoId = $usarMismaDireccion ? $representante->estado_id : $request->pac_estado_id;
                    $pacCiudadId = $usarMismaDireccion ? $representante->ciudad_id : $request->pac_ciudad_id;
                    $pacMunicipioId = $usarMismaDireccion ? $representante->municipio_id : $request->pac_municipio_id;
                    $pacParroquiaId = $usarMismaDireccion ? $representante->parroquia_id : $request->pac_parroquia_id;
                    $pacDireccion = $usarMismaDireccion ? $representante->direccion_detallada : $request->pac_direccion_detallada;
                    
                    // 3. Crear registro en tabla PACIENTES (datos principales)
                    $paciente = Paciente::firstOrCreate(
                        [
                            'tipo_documento' => $pacTipoDoc,
                            'numero_documento' => $pacNumeroDoc
                        ],
                        [
                            'primer_nombre' => $request->pac_primer_nombre,
                            'segundo_nombre' => $request->pac_segundo_nombre,
                            'primer_apellido' => $request->pac_primer_apellido,
                            'segundo_apellido' => $request->pac_segundo_apellido,
                            'fecha_nac' => $request->pac_fecha_nac,
                            'estado_id' => $pacEstadoId,
                            'ciudad_id' => $pacCiudadId,
                            'municipio_id' => $pacMunicipioId,
                            'parroquia_id' => $pacParroquiaId,
                            'direccion_detallada' => $pacDireccion,
                            'status' => true
                        ]
                    );
                    $pacienteId = $paciente->id;
                    
                    Log::info('Paciente creado/encontrado', ['id' => $pacienteId]);
                    
                    // 4. Crear registro en tabla PACIENTES_ESPECIALES (datos adicionales)
                    $pacienteEspecial = PacienteEspecial::firstOrCreate(
                        [
                            'paciente_id' => $pacienteId,
                            'tipo' => $request->pac_tipo
                        ],
                        [
                            'primer_nombre' => $request->pac_primer_nombre,
                            'segundo_nombre' => $request->pac_segundo_nombre,
                            'primer_apellido' => $request->pac_primer_apellido,
                            'segundo_apellido' => $request->pac_segundo_apellido,
                            'tipo_documento' => $pacTipoDoc,
                            'numero_documento' => $pacNumeroDoc,
                            'fecha_nac' => $request->pac_fecha_nac,
                            'tiene_documento' => $tieneDocumento,
                            'estado_id' => $pacEstadoId,
                            'ciudad_id' => $pacCiudadId,
                            'municipio_id' => $pacMunicipioId,
                            'parroquia_id' => $pacParroquiaId,
                            'direccion_detallada' => $pacDireccion,
                            'observaciones' => $request->pac_observaciones,
                            'status' => true
                        ]
                    );
                    $pacienteEspecialId = $pacienteEspecial->id;
                    
                    Log::info('PacienteEspecial creado/encontrado', ['id' => $pacienteEspecialId]);
                    
                    // 5. Vincular representante con paciente especial (tabla pivote)
                    if (!$pacienteEspecial->representantes()->where('representante_id', $representanteId)->exists()) {
                        $pacienteEspecial->representantes()->attach($representanteId, [
                            'tipo_responsabilidad' => 'Principal',
                            'status' => true
                        ]);
                        Log::info('Representante vinculado a paciente especial');
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
                if ($tipoConsulta == 'Domicilio' && $especialidadPivot) {
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
                    'tipo_consulta' => $tipoConsulta,
                    'tarifa' => $tarifa,
                    'tarifa_extra' => $tarifaExtra,
                    'motivo' => $request->motivo,
                    'estado_cita' => 'Programada',
                    'duracion_minutos' => 30,
                    'status' => true
                ]);

                Log::info('Cita creada exitosamente', ['cita_id' => $cita->id, 'paciente_especial_id' => $pacienteEspecialId]);

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
