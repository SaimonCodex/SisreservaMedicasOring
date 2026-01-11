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
use App\Models\FacturaCabecera;
use App\Models\Notificacion;
use App\Models\Usuario;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // =========================================================================
        // PARA PACIENTE (ROL 3)
        // =========================================================================
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
        
        // =========================================================================
        // PARA MÉDICO (ROL 2) Y ADMIN (ROL 1)
        // =========================================================================
        
        $query = Cita::with(['paciente', 'medico', 'especialidad', 'consultorio'])
                     ->where('status', true);

        // Filtro por Rol Médico (solo ve sus citas)
        if ($user->rol_id == 2) {
            $medico = $user->medico;
            if ($medico) {
                $query->where('medico_id', $medico->id);
            }
        }

        // Filtro por Búsqueda (Paciente, Médico, Cédula)
        if ($request->filled('buscar')) {
            $busqueda = $request->buscar;
            $query->where(function($q) use ($busqueda) {
                // Buscar por Paciente
                $q->whereHas('paciente', function($qPac) use ($busqueda) {
                    $qPac->where('primer_nombre', 'like', "%$busqueda%")
                         ->orWhere('primer_apellido', 'like', "%$busqueda%")
                         ->orWhere('numero_documento', 'like', "%$busqueda%");
                })
                // Buscar por Médico
                ->orWhereHas('medico', function($qMed) use ($busqueda) {
                    $qMed->where('primer_nombre', 'like', "%$busqueda%")
                         ->orWhere('primer_apellido', 'like', "%$busqueda%");
                });
            });
        }

        // Filtro por Fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_cita', $request->fecha);
        }

        // Filtro por Médico (Select en Admin)
        if ($request->filled('medico_id') && $user->rol_id == 1) { // Solo admin puede filtrar médicos arbitrarios
            $query->where('medico_id', $request->medico_id);
        }

        // Filtro por Estado de Cita
        if ($request->filled('estado')) {
            // Mapeo simple de valores del select a valores de la BD si es necesario,
            // asumiendo que el value del select coincide con la BD o hacemos un map.
            // Valores BD esperados: 'Programada', 'Confirmada', 'En Progreso', 'Completada', 'Cancelada', 'No Asistió'
            $estadoMap = [
                'pendiente' => ['Programada'],
                'confirmada' => ['Confirmada'],
                'completada' => ['Completada'],
                'cancelada' => ['Cancelada', 'No Asistió']
            ];
            
            if (array_key_exists($request->estado, $estadoMap)) {
                $query->whereIn('estado_cita', $estadoMap[$request->estado]);
            }
        }

        // Ordenamiento
        $citas = $query->orderBy('fecha_cita', 'desc')
                       ->orderBy('hora_inicio', 'asc')
                       ->paginate(10)
                       ->withQueryString();

        // =========================================================================
        // ESTADÍSTICAS (Para las tarjetas superiores)
        // =========================================================================
        
        // Base query para stats (mismas restricciones de rol base, pero sin filtros de búsqueda/fecha para totales globales del día o generales)
        $statsQuery = Cita::where('status', true);
        if ($user->rol_id == 2 && $user->medico) {
            $statsQuery->where('medico_id', $user->medico->id);
        }
        
        $hoy = Carbon::today();
        
        $stats = [
            'total_hoy' => (clone $statsQuery)->whereDate('fecha_cita', $hoy)->count(),
            // Pendientes: Programadas desde hoy en adelante
            'pendientes_hoy' => (clone $statsQuery)->whereDate('fecha_cita', '>=', $hoy)->where('estado_cita', 'Programada')->count(),
            // Confirmadas: Confirmadas desde hoy en adelante
            'confirmadas_hoy' => (clone $statsQuery)->whereDate('fecha_cita', '>=', $hoy)->where('estado_cita', 'Confirmada')->count(),
            // Completadas: Mes actual (histórico reciente)
            'completadas_hoy' => (clone $statsQuery)->whereMonth('fecha_cita', $hoy->month)->whereYear('fecha_cita', $hoy->year)->where('estado_cita', 'Completada')->count(),
            // Canceladas: Mes actual (histórico reciente)
            'canceladas_hoy' => (clone $statsQuery)->whereMonth('fecha_cita', $hoy->month)->whereYear('fecha_cita', $hoy->year)->whereIn('estado_cita', ['Cancelada', 'No Asistió'])->count(),
        ];
        
        // Datos para combos de filtros
        $medicos = [];
        if ($user->rol_id == 1) {
            $medicos = Medico::where('status', true)->orderBy('primer_nombre')->get();
        }

        return view('shared.citas.index', compact('citas', 'stats', 'medicos'));
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
        $estados = Estado::where('status', true)->orderBy('estado')->get();

        return view('shared.citas.create', compact('medicos', 'pacientes', 'especialidades', 'consultorios', 'estados'));
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
        
        // Reglas adicionales para terceros (Paciente - rol 3)
        if ($request->tipo_cita == 'terceros' && $user->rol_id == 3) {
            $rules = array_merge($rules, [
                // Representante
                'rep_primer_nombre' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                'rep_primer_apellido' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                'rep_tipo_documento' => 'required|in:V,E,P,J',
                'rep_numero_documento' => 'required|max:20',
                'rep_fecha_nac' => 'required|date|before:today',
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
        
        // Reglas adicionales para terceros (Admin/Médico - roles 1 y 2)
        if ($request->tipo_cita == 'terceros' && in_array($user->rol_id, [1, 2])) {
            // Si representante NO es existente, validar campos de nuevo representante
            if ($request->representante_existente != '1') {
                $rules = array_merge($rules, [
                    'rep_primer_nombre' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\\s]+$/',
                    'rep_primer_apellido' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\\s]+$/',
                    'rep_tipo_documento' => 'required|in:V,E,P,J',
                    'rep_numero_documento' => 'required|max:20',
                    'rep_fecha_nac' => 'required|date|before:today',
                    'rep_parentesco' => 'required|in:Padre,Madre,Hijo/a,Hermano/a,Tío/a,Sobrino/a,Abuelo/a,Nieto/a,Primo/a,Amigo/a,Tutor,Otro',
                ]);
            }
            
            // Si paciente especial NO es existente, validar campos de nuevo paciente especial
            if (!$request->paciente_especial_id) {
                $rules = array_merge($rules, [
                    'pac_esp_primer_nombre' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\\s]+$/',
                    'pac_esp_primer_apellido' => 'required|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\\s]+$/',
                    'pac_esp_tipo' => 'required|in:Menor de Edad,Discapacitado,Anciano,Otro',
                    'pac_esp_fecha_nac' => 'required|date',
                    'pac_esp_genero' => 'required|in:Masculino,Femenino',
                ]);
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
            'rep_fecha_nac.required' => 'La fecha de nacimiento del representante es requerida',
            'pac_primer_nombre.required' => 'El nombre del paciente es requerido',
            'pac_primer_apellido.required' => 'El apellido del paciente es requerido',
            'pac_esp_primer_nombre.required' => 'El nombre del paciente especial es requerido',
            'pac_esp_primer_apellido.required' => 'El apellido del paciente especial es requerido',
            'pac_esp_tipo.required' => 'Debe seleccionar el tipo de paciente especial',
            'pac_esp_fecha_nac.required' => 'La fecha de nacimiento del paciente especial es requerida',
            'pac_esp_genero.required' => 'Debe seleccionar el género del paciente especial',
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
                // CITA PROPIA (Admin/Médico)
                // ========================================
                if ($request->tipo_cita == 'propia' && in_array($user->rol_id, [1, 2])) {
                    
                    // Si se seleccionó un paciente existente
                    if ($request->paciente_existente == '1' && $request->paciente_id) {
                        $pacienteId = $request->paciente_id;
                        Log::info('Admin: Usando paciente existente', ['paciente_id' => $pacienteId]);
                    } 
                    // Si se marca como nuevo paciente (checkbox no registrado)
                    else {
                        $userId = null;
                        
                        // Si se marcó registrar usuario, crearlo PRIMERO
                        if ($request->registrar_usuario == '1' && $request->pac_correo) {
                            $usuario = Usuario::create([
                                'correo' => $request->pac_correo,
                                'password' => md5(md5($request->pac_password)), // Doble MD5
                                'rol_id' => 3, // Rol paciente
                                'status' => true
                            ]);
                            $userId = $usuario->id;
                            Log::info('Admin: Usuario creado para paciente', ['usuario_id' => $userId]);
                        }
                        
                        // Crear paciente (con o sin user_id)
                        $paciente = Paciente::create([
                            'user_id' => $userId,
                            'primer_nombre' => $request->pac_primer_nombre,
                            'segundo_nombre' => $request->pac_segundo_nombre,
                            'primer_apellido' => $request->pac_primer_apellido,
                            'segundo_apellido' => $request->pac_segundo_apellido,
                            'tipo_documento' => $request->pac_tipo_documento ?? 'V',
                            'numero_documento' => $request->pac_numero_documento,
                            'fecha_nac' => $request->pac_fecha_nac,
                            'genero' => $request->pac_genero,
                            'prefijo_tlf' => $request->pac_prefijo_tlf,
                            'numero_tlf' => $request->pac_numero_tlf,
                            'estado_id' => $request->pac_estado_id,
                            'ciudad_id' => $request->pac_ciudad_id,
                            'municipio_id' => $request->pac_municipio_id,
                            'parroquia_id' => $request->pac_parroquia_id,
                            'direccion_detallada' => $request->pac_direccion_detallada,
                            'status' => true
                        ]);
                        
                        $pacienteId = $paciente->id;
                        Log::info('Admin: Paciente nuevo creado', ['paciente_id' => $pacienteId, 'user_id' => $userId]);
                    }
                }
                
                // ========================================
                // CITA PARA TERCEROS (Paciente Especial)
                // ========================================
                if ($request->tipo_cita == 'terceros') {
                    
                    // Admin/Médico: Verificar si representante es existente o nuevo
                    if (in_array($user->rol_id, [1, 2])) {
                        
                        // REPRESENTANTE
                        if ($request->representante_existente == '1' && $request->representante_id) {
                            $representanteId = $request->representante_id;
                            Log::info('Admin: Usando representante existente', ['representante_id' => $representanteId]);
                        } else {
                            // Crear nuevo representante
                            $representante = Representante::create([
                                'primer_nombre' => $request->rep_primer_nombre,
                                'segundo_nombre' => $request->rep_segundo_nombre,
                                'primer_apellido' => $request->rep_primer_apellido,
                                'segundo_apellido' => $request->rep_segundo_apellido,
                                'tipo_documento' => $request->rep_tipo_documento ?? 'V',
                                'numero_documento' => $request->rep_numero_documento,
                                'fecha_nac' => $request->rep_fecha_nac,
                                'prefijo_tlf' => $request->rep_prefijo_tlf,
                                'numero_tlf' => $request->rep_numero_tlf,
                                'parentesco' => $request->rep_parentesco,
                                'estado_id' => $request->rep_estado_id,
                                'municipio_id' => $request->rep_municipio_id,
                                'ciudad_id' => $request->rep_ciudad_id,
                                'parroquia_id' => $request->rep_parroquia_id,
                                'direccion_detallada' => $request->rep_direccion_detallada,
                                'status' => true
                            ]);
                            $representanteId = $representante->id;
                            Log::info('Admin: Representante nuevo creado', ['representante_id' => $representanteId]);
                            
                            // Si se marcó registrar usuario para representante
                            if ($request->has('chk_registrar_representante') && $request->rep_correo) {
                                $usuarioRep = Usuario::create([
                                    'correo' => $request->rep_correo,
                                    'password' => md5(md5($request->rep_password)),
                                    'rol_id' => 3,
                                    'status' => true
                                ]);
                                
                                // Crear perfil de Paciente para este usuario (para que pueda hacer login)
                                Paciente::create([
                                    'user_id' => $usuarioRep->id,
                                    'primer_nombre' => $request->rep_primer_nombre,
                                    'segundo_nombre' => $request->rep_segundo_nombre,
                                    'primer_apellido' => $request->rep_primer_apellido,
                                    'segundo_apellido' => $request->rep_segundo_apellido,
                                    'tipo_documento' => $request->rep_tipo_documento,
                                    'numero_documento' => $request->rep_numero_documento,
                                    'fecha_nac' => $request->rep_fecha_nac,
                                    'prefijo_tlf' => $request->rep_prefijo_tlf,
                                    'numero_tlf' => $request->rep_numero_tlf,
                                    'estado_id' => $request->rep_estado_id,
                                    'ciudad_id' => $request->rep_ciudad_id,
                                    'municipio_id' => $request->rep_municipio_id,
                                    'parroquia_id' => $request->rep_parroquia_id,
                                    'direccion_detallada' => $request->rep_direccion_detallada,
                                    'status' => true
                                ]);
                                
                                Log::info('Admin: Usuario y perfil Paciente creado para representante', ['usuario_id' => $usuarioRep->id]);
                            }
                        }
                        
                        // PACIENTE ESPECIAL
                        if ($request->paciente_especial_id) {
                            $pacienteEspecialId = $request->paciente_especial_id;
                            $pacEspecial = PacienteEspecial::find($pacienteEspecialId);
                            $pacienteId = $pacEspecial->paciente_id ?? null;
                            Log::info('Admin: Usando paciente especial existente', ['pac_especial_id' => $pacienteEspecialId]);

                            // Vincular representante con paciente especial si no existe el vínculo
                            if ($pacEspecial && $representanteId && !$pacEspecial->representantes()->where('representante_id', $representanteId)->exists()) {
                                $pacEspecial->representantes()->attach($representanteId, [
                                    'tipo_responsabilidad' => 'Secundario', // Opcional: podrías determinarlo
                                    'status' => true
                                ]);
                                Log::info('Admin: Representante vinculado a paciente especial existente');
                            }
                        } else {
                            // Crear nuevo paciente especial
                            $representanteDoc = $request->rep_numero_documento;
                            $pacNumeroDoc = $this->calculateNextId($representanteDoc);
                            
                            // Crear en tabla pacientes primero
                            $paciente = Paciente::create([
                                'primer_nombre' => $request->pac_esp_primer_nombre,
                                'segundo_nombre' => $request->pac_esp_segundo_nombre,
                                'primer_apellido' => $request->pac_esp_primer_apellido,
                                'segundo_apellido' => $request->pac_esp_segundo_apellido,
                                'tipo_documento' => $request->rep_tipo_documento ?? 'V',
                                'numero_documento' => $pacNumeroDoc,
                                'fecha_nac' => $request->pac_esp_fecha_nac,
                                'genero' => $request->pac_esp_genero,
                                'estado_id' => $request->pac_esp_estado_id,
                                'ciudad_id' => $request->pac_esp_ciudad_id,
                                'municipio_id' => $request->pac_esp_municipio_id,
                                'parroquia_id' => $request->pac_esp_parroquia_id,
                                'direccion_detallada' => $request->pac_esp_direccion_detallada,
                                'status' => true
                            ]);
                            $pacienteId = $paciente->id;
                            
                            // Crear en tabla pacientes_especiales
                            $pacEspecial = PacienteEspecial::create([
                                'paciente_id' => $pacienteId,
                                'primer_nombre' => $request->pac_esp_primer_nombre,
                                'segundo_nombre' => $request->pac_esp_segundo_nombre,
                                'primer_apellido' => $request->pac_esp_primer_apellido,
                                'segundo_apellido' => $request->pac_esp_segundo_apellido,
                                'tipo_documento' => $request->rep_tipo_documento ?? 'V',
                                'numero_documento' => $pacNumeroDoc,
                                'fecha_nac' => $request->pac_esp_fecha_nac,
                                'tipo' => $request->pac_esp_tipo,
                                'tiene_documento' => false,
                                'estado_id' => $request->pac_esp_estado_id,
                                'ciudad_id' => $request->pac_esp_ciudad_id,
                                'municipio_id' => $request->pac_esp_municipio_id,
                                'parroquia_id' => $request->pac_esp_parroquia_id,
                                'direccion_detallada' => $request->pac_esp_direccion_detallada,
                                'status' => true
                            ]);
                            $pacienteEspecialId = $pacEspecial->id;
                            Log::info('Admin: Paciente especial nuevo creado', ['pac_especial_id' => $pacienteEspecialId]);
                            
                            // Vincular representante con paciente especial
                            $pacEspecial->representantes()->attach($representanteId, [
                                'tipo_responsabilidad' => 'Principal',
                                'status' => true
                            ]);
                        }
                        
                    } else {
                        // Paciente (rol 3): Lógica original
                        $pacienteUsuario = $user->paciente;

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
                                'estado_id' => $request->rep_estado_id ?? $pacienteUsuario->estado_id,
                                'municipio_id' => $request->rep_municipio_id ?? $pacienteUsuario->municipio_id,
                                'ciudad_id' => $request->rep_ciudad_id ?? $pacienteUsuario->ciudad_id,
                                'parroquia_id' => $request->rep_parroquia_id ?? $pacienteUsuario->parroquia_id,
                                'direccion_detallada' => $request->rep_direccion_detallada ?? $pacienteUsuario->direccion_detallada,
                                'status' => true
                            ]
                        );

                        // Si el representante ya existía pero tenía datos de ubicación nulos, actualizarlos
                        if (!$representante->wasRecentlyCreated && is_null($representante->estado_id) && $pacienteUsuario) {
                            $representante->update([
                                'estado_id' => $pacienteUsuario->estado_id,
                                'municipio_id' => $pacienteUsuario->municipio_id,
                                'ciudad_id' => $pacienteUsuario->ciudad_id,
                                'parroquia_id' => $pacienteUsuario->parroquia_id,
                                'direccion_detallada' => $pacienteUsuario->direccion_detallada
                            ]);
                        }
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
                            $pacNumeroDoc = $this->calculateNextId($request->rep_numero_documento);
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
                    } // Fin else rol 3
                } // Fin terceros
                
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
        $cita = Cita::with([
            'paciente', 
            'medico',
            'especialidad', 
            'consultorio' => function($q) { $q->with(['estado', 'ciudad', 'municipio', 'parroquia']); },
            'pacienteEspecial',
            'pacienteEspecial.representantes', 
            'evolucionClinica'
        ])->findOrFail($id);

        // Si es paciente, mostrar vista personalizada
        if (auth()->user()->rol_id == 3) {
            // Verificar que la cita pertenezca al paciente o a uno de sus representados
            $pacienteUsuario = auth()->user()->paciente;
            $esPropia = $cita->paciente_id == $pacienteUsuario->id;
            
            // Si no es propia, verificar si es de un paciente especial representado por este usuario
            $esTercero = false;
            if (!$esPropia && $cita->paciente_especial_id) {
                // Verificar si el representante logueado tiene relación con este paciente especial
                $representante = Representante::where('numero_documento', $pacienteUsuario->numero_documento)
                                            ->where('tipo_documento', $pacienteUsuario->tipo_documento)
                                            ->first();
                if ($representante) {
                    $esTercero = DB::table('representante_paciente_especial')
                                ->where('representante_id', $representante->id)
                                ->where('paciente_especial_id', $cita->paciente_especial_id)
                                ->exists();
                }
            }

            // Si es admin o medico bypass this check, but here we are inside if rol_id == 3
            // Validacion básica de seguridad
            // if (!$esPropia && !$esTercero) {
            //     abort(403, 'No autorizado para ver esta cita.');
            // }

            return view('paciente.citas.show', compact('cita'));
        }

        return view('shared.citas.show', compact('cita'));
    }

    public function solicitarCancelacion(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $cita = Cita::findOrFail($id);
            
            // Validar
            $request->validate([
                'motivo_cancelacion' => 'required|string',
                'explicacion' => 'required|string|max:500'
            ]);

            // No cambiar el estado 'status' o 'estado_cita' directamente si no queremos afectar la lógica admin
            // pero podemos usar el campo observaciones para guardar temporalmente la solicitud o un estado intermedio
            
            // Opción: Agregar prefijo a la observación existente
            $nuevaObservacion = "SOLICITUD CANCELACIÓN [" . date('d/m/Y H:i') . "]: \n" .
                                "Motivo: " . $request->motivo_cancelacion . "\n" .
                                "Detalle: " . $request->explicacion . "\n";
            
            if ($cita->observaciones) {
                $nuevaObservacion .= "\n--- Obs. Anteriores ---\n" . $cita->observaciones;
            }
            
            $cita->observaciones = $nuevaObservacion;
            // Opcional: Podríamos tener un estado 'solicitud_cancelacion' si el enum lo permite.
            // Si no, lo dejamos en pendiente pero notificamos.
            $cita->save();

            // Crear Notificación para Admin
            // Buscamos un usuario admin (rol_id = 1) o genérico
            // Asumiremos rol 'admin' para el receptor
            Notificacion::create([
                'receptor_rol' => 'admin', // Sistema debe tener listener o panel para ver notificaciones 'admin'
                'tipo' => 'solicitud',
                'titulo' => 'Solicitud de Cancelación - Cita #' . $cita->id,
                'mensaje' => "Paciente solicitó cancelar cita del " . Carbon::parse($cita->fecha_cita)->format('d/m/Y') . ".\nMotivo: " . $request->motivo_cancelacion . " - " . $request->explicacion,
                'via' => 'sistema', // interna
                'estado_envio' => 'pendiente',
                'status' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de cancelación enviada correctamente. El administrador revisará su caso.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error solicitando cancelación cita $id: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
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

    public function cambiarEstado(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'estado_cita' => 'required|in:Programada,Confirmada,En Progreso,Completada,Cancelada,No Asistió',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error', 'Estado inválido');
        }

        $cita->estado_cita = $request->estado_cita;
        
        // Si viene un motivo/observación (ej: al cancelar), lo guardamos
        if ($request->filled('observaciones')) {
            $cita->observaciones = $request->observaciones;
        }

        $cita->save();

        // Si se confirma la cita, se podría enviar una notificación (código comentado opcional)
        // if ($request->estado_cita == 'Confirmada') {
        //     $this->enviarNotificacionCita($cita);
        // }

        return redirect()->back()->with('success', 'Estado de la cita actualizado correctamente');
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
        return $fin->diffInMinutes($inicio);
    }
    
    // API Public para obtener siguiente secuencia
    public function getNextSequence($numero_documento)
    {
        $nextId = $this->calculateNextId($numero_documento);
        // Extraer solo la secuencia (últimos 2 dígitos)
        $parts = explode('-', $nextId);
        $sequence = end($parts);
        
        return response()->json([
            'sequence' => $sequence,
            'full_id' => $nextId
        ]);
    }

    private function calculateNextId($baseDocumento)
    {
        // Buscar todos los documentos que empiecen con el documento base y tengan un sufijo
        // Formato esperado en DB: 12345678-01, 12345678-02, etc.
        $existing = PacienteEspecial::where('numero_documento', 'LIKE', $baseDocumento . '-%')
                                    ->pluck('numero_documento');
        
        if ($existing->isEmpty()) {
            return $baseDocumento . '-01';
        }

        $maxSequence = 0;
        foreach ($existing as $doc) {
            // Intentar extraer el sufijo numérico al final
            if (preg_match('/-(\d+)$/', $doc, $matches)) {
                $seq = intval($matches[1]);
                if ($seq > $maxSequence) {
                    $maxSequence = $seq;
                }
            }
        }

        return $baseDocumento . '-' . str_pad($maxSequence + 1, 2, '0', STR_PAD_LEFT);
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

    /**
     * Buscar pacientes y pacientes especiales para admin
     */
    public function buscarPaciente(Request $request)
    {
        $query = $request->get('q', '');
        $tipoCita = $request->get('tipo_cita', 'propia'); // propia o terceros
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Buscar en pacientes normales
        $pacientes = Paciente::where(function($q) use ($query) {
            $q->where('numero_documento', 'LIKE', "%{$query}%")
              ->orWhere('primer_nombre', 'LIKE', "%{$query}%")
              ->orWhere('primer_apellido', 'LIKE', "%{$query}%")
              ->orWhereRaw("CONCAT(primer_nombre, ' ', primer_apellido) LIKE ?", ["%{$query}%"]);
        })
        ->where('status', true)
        ->limit(10)
        ->get();

        foreach ($pacientes as $pac) {
            // Verificar si es paciente especial (tiene sufijo en documento)
            $esEspecial = preg_match('/-\d+$/', $pac->numero_documento);
            
            $results[] = [
                'id' => $pac->id,
                'tipo' => $esEspecial ? 'especial' : 'paciente',
                'nombre' => trim($pac->primer_nombre . ' ' . ($pac->segundo_nombre ?? '') . ' ' . $pac->primer_apellido . ' ' . ($pac->segundo_apellido ?? '')),
                'documento' => $pac->tipo_documento . '-' . $pac->numero_documento,
                'telefono' => ($pac->prefijo_tlf ?? '') . ' ' . ($pac->numero_tlf ?? ''),
                'fecha_nac' => $pac->fecha_nac,
                'genero' => $pac->genero,
                'estado_id' => $pac->estado_id,
                'ciudad_id' => $pac->ciudad_id,
                'municipio_id' => $pac->municipio_id,
                'parroquia_id' => $pac->parroquia_id,
                'direccion_detallada' => $pac->direccion_detallada,
                'tipo_documento' => $pac->tipo_documento,
                'numero_documento' => $pac->numero_documento,
                'primer_nombre' => $pac->primer_nombre,
                'segundo_nombre' => $pac->segundo_nombre,
                'primer_apellido' => $pac->primer_apellido,
                'segundo_apellido' => $pac->segundo_apellido,
                'prefijo_tlf' => $pac->prefijo_tlf,
                'numero_tlf' => $pac->numero_tlf,
                'tiene_usuario' => $pac->user_id != null,
            ];
        }

        // Buscar en pacientes especiales (para citas terceros)
        $especiales = PacienteEspecial::where(function($q) use ($query) {
            $q->where('numero_documento', 'LIKE', "%{$query}%")
              ->orWhere('primer_nombre', 'LIKE', "%{$query}%")
              ->orWhere('primer_apellido', 'LIKE', "%{$query}%")
              ->orWhereRaw("CONCAT(primer_nombre, ' ', primer_apellido) LIKE ?", ["%{$query}%"]);
        })
        ->where('status', true)
        ->limit(10)
        ->get();

        foreach ($especiales as $esp) {
            $results[] = [
                'id' => $esp->id,
                'paciente_id' => $esp->paciente_id,
                'tipo' => 'especial',
                'nombre' => trim($esp->primer_nombre . ' ' . ($esp->segundo_nombre ?? '') . ' ' . $esp->primer_apellido . ' ' . ($esp->segundo_apellido ?? '')),
                'documento' => ($esp->tipo_documento ?? 'V') . '-' . $esp->numero_documento,
                'telefono' => '',
                'fecha_nac' => $esp->fecha_nac,
                'genero' => $esp->genero,
                'estado_id' => $esp->estado_id,
                'ciudad_id' => $esp->ciudad_id,
                'municipio_id' => $esp->municipio_id,
                'parroquia_id' => $esp->parroquia_id,
                'direccion_detallada' => $esp->direccion_detallada,
                'tipo_documento' => $esp->tipo_documento ?? 'V',
                'numero_documento' => $esp->numero_documento,
                'primer_nombre' => $esp->primer_nombre,
                'segundo_nombre' => $esp->segundo_nombre,
                'primer_apellido' => $esp->primer_apellido,
                'segundo_apellido' => $esp->segundo_apellido,
                'prefijo_tlf' => '',
                'numero_tlf' => '',
                'tiene_usuario' => false,
                'es_paciente_especial_tabla' => true,
            ];
        }

        // Buscar representantes existentes (para citas terceros)
        if ($tipoCita === 'terceros') {
            $representantes = Representante::where(function($q) use ($query) {
                $q->where('numero_documento', 'LIKE', "%{$query}%")
                  ->orWhere('primer_nombre', 'LIKE', "%{$query}%")
                  ->orWhere('primer_apellido', 'LIKE', "%{$query}%");
            })
            ->where('status', true)
            ->limit(10)
            ->get();

            foreach ($representantes as $rep) {
                $results[] = [
                    'id' => $rep->id,
                    'tipo' => 'representante',
                    'nombre' => trim($rep->primer_nombre . ' ' . ($rep->segundo_nombre ?? '') . ' ' . $rep->primer_apellido . ' ' . ($rep->segundo_apellido ?? '')),
                    'documento' => ($rep->tipo_documento ?? 'V') . '-' . $rep->numero_documento,
                    'telefono' => ($rep->prefijo_tlf ?? '') . ' ' . ($rep->numero_tlf ?? ''),
                    'estado_id' => $rep->estado_id,
                    'ciudad_id' => $rep->ciudad_id,
                    'municipio_id' => $rep->municipio_id,
                    'parroquia_id' => $rep->parroquia_id,
                    'direccion_detallada' => $rep->direccion_detallada,
                    'tipo_documento' => $rep->tipo_documento ?? 'V',
                    'numero_documento' => $rep->numero_documento,
                    'primer_nombre' => $rep->primer_nombre,
                    'segundo_nombre' => $rep->segundo_nombre,
                    'primer_apellido' => $rep->primer_apellido,
                    'segundo_apellido' => $rep->segundo_apellido,
                    'prefijo_tlf' => $rep->prefijo_tlf,
                    'numero_tlf' => $rep->numero_tlf,
                ];
            }
        }

        return response()->json(['results' => $results]);
    }

    /**
     * Verificar si un correo ya existe en el sistema
     */
    public function verificarCorreo(Request $request)
    {
        $correo = $request->get('correo', '');
        
        if (empty($correo)) {
            return response()->json(['existe' => false]);
        }

        $existe = Usuario::where('correo', $correo)->exists();
        
        return response()->json(['existe' => $existe]);
    }


    // =========================================================================
    // API PARA FULLCALENDAR
    // =========================================================================
    public function events(Request $request)
    {
        $user = auth()->user();
        
        $start = $request->query('start');
        $end = $request->query('end');

        // Base query
        $query = Cita::with(['paciente', 'medico', 'consultorio', 'especialidad'])
                     ->where('status', true)
                     ->whereBetween('fecha_cita', [substr($start, 0, 10), substr($end, 0, 10)]);

        // Filtros de Rol
        if ($user->rol_id == 3) {
             // Paciente: ver sus citas (propia y terceros)
             $paciente = $user->paciente;
             if (!$paciente) return response()->json([]);
             
             // Buscar citas directas
             $idsPropios = Cita::where('paciente_id', $paciente->id)->pluck('id');
             
             // Buscar citas como representante
             $representante = Representante::where('tipo_documento', $paciente->tipo_documento)
                                           ->where('numero_documento', $paciente->numero_documento)
                                           ->first();
             $idsTerceros = collect();
             if ($representante) {
                 $pacientesEspeciales = $representante->pacientesEspeciales; // Get the collection
                 if ($pacientesEspeciales) {
                    $pacientesEspecialesIds = $pacientesEspeciales->pluck('paciente_id');
                    $idsTerceros = Cita::whereIn('paciente_id', $pacientesEspecialesIds)->pluck('id');
                 }
             }
             
             $query->whereIn('id', $idsPropios->concat($idsTerceros));
             
        } elseif ($user->rol_id == 2) {
            // Médico: ver sus citas
            if ($user->medico) {
                $query->where('medico_id', $user->medico->id);
            }
        }
        
        // Filtros adicionales (si vienen del frontend)
        if ($request->filled('medico_id') && $user->rol_id == 1) {
            $query->where('medico_id', $request->medico_id);
        }

        $citas = $query->get();

        $events = $citas->map(function($cita) {
            $color = match($cita->estado_cita) {
                'Confirmada' => '#10b981', // emerald-500
                'Programada' => '#f59e0b', // amber-500
                'En Progreso' => '#3b82f6', // blue-500
                'Completada' => '#6b7280', // gray-500
                'Cancelada', 'No Asistió' => '#ef4444', // red-500
                default => '#6b7280'
            };

            $title = $cita->paciente->primer_nombre . ' ' . $cita->paciente->primer_apellido;
            // Para admin/medico mostrar paciente, para paciente mostrar medico/especialidad
            if (auth()->user()->rol_id == 3) {
                 $title = $cita->especialidad->nombre . ' - Dr. ' . $cita->medico->primer_apellido;
            } else {
                 // Info extra para admin/medico
                 $title .= ' (' . $cita->especialidad->nombre . ')';
            }

            return [
                'id' => $cita->id,
                'title' => $title,
                'start' => $cita->fecha_cita . 'T' . $cita->hora_inicio,
                'end' => $cita->fecha_cita . 'T' . $cita->hora_fin, 
                'backgroundColor' => $color,
                'borderColor' => $color,
                'url' => route('citas.show', $cita->id),
                'extendedProps' => [
                    'estado' => $cita->estado_cita,
                    'consultorio' => $cita->consultorio->nombre ?? 'N/A'
                ]
            ];
        });

        return response()->json($events);
    }
}
