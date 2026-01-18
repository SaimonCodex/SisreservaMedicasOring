<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Usuario;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacienteController extends Controller
{
    public function dashboard()
    {
        $paciente = auth()->user()->paciente;
        
        // Manejar caso donde no existe paciente aún
        if (!$paciente) {
            return view('paciente.dashboard', [
                'citas_proximas' => collect(),
                'historial_reciente' => collect(),
                'recetas_activas' => collect(),
                'stats' => [
                    'citas_proximas' => 0,
                    'historias' => 0,
                    'recetas_activas' => 0,
                    'consultas_mes' => 0,
                    'total_citas' => 0
                ]
            ]);
        }

        // Cargar paciente con historia clínica para el tipo de sangre
        $paciente->load('historiaClinicaBase');
        
        $citas_proximas = \App\Models\Cita::with(['medico','consultorio','facturaPaciente.pagos'])
                                       ->where('paciente_id', $paciente->id)
                                       ->where('fecha_cita', '>=', today())
                                       ->where('status', true)
                                       ->orderBy('fecha_cita')
                                       ->orderBy('hora_inicio')
                                       ->limit(5)
                                       ->get();

        $historial_reciente = \App\Models\Cita::with(['medico.usuario'])
                                         ->where('paciente_id', $paciente->id)
                                         ->where('fecha_cita', '<', today())
                                         ->where('status', true)
                                         ->orderBy('fecha_cita', 'desc')
                                         ->limit(10)
                                         ->get();

        // Asumimos que las recetas vienen de OrdenMedica
        $recetas_activas = \App\Models\OrdenMedica::where('paciente_id', $paciente->id)
                                             ->where('status', true) // O el campo que indique que está activa
                                             ->get();

        // Estadísticas
        $stats = [
            'citas_proximas' => $citas_proximas->count(),
            'historias' => $historial_reciente->count(),
            'recetas_activas' => $recetas_activas->count(),
            'total_citas' => \App\Models\Cita::where('paciente_id', $paciente->id)->count(),
            'consultas_mes' => \App\Models\Cita::where('paciente_id', $paciente->id)
                                              ->whereMonth('fecha_cita', now()->month)
                                              ->whereYear('fecha_cita', now()->year)
                                              ->count()
        ];

        return view('paciente.dashboard', compact('paciente', 'citas_proximas', 'historial_reciente', 'recetas_activas', 'stats'));
    }

    public function historial()
    {
        $paciente = auth()->user()->paciente;
        
        if (!$paciente) {
            return redirect()->route('paciente.dashboard')->with('error', 'No se encontró el perfil de paciente');
        }

        // 1. Historial propio
        $historialPropio = \App\Models\Cita::with(['medico', 'consultorio', 'especialidad', 'paciente'])
                                     ->where('paciente_id', $paciente->id)
                                     ->whereIn('status', [true, 1]) // Asegurar compatibilidad de status
                                     ->orderBy('fecha_cita', 'desc')
                                     ->get()
                                     ->map(function($cita) {
                                         $cita->tipo_historia_display = 'propia';
                                         $cita->paciente_especial_info = null;
                                         return $cita;
                                     });

        // 2. Buscar si este paciente es representante de pacientes especiales
        $representante = \App\Models\Representante::where('tipo_documento', $paciente->tipo_documento)
                                      ->where('numero_documento', $paciente->numero_documento)
                                      ->first();
        
        $historialTerceros = collect();
        $pacientesEspeciales = collect();
        
        if ($representante) {
            // Obtener pacientes especiales de este representante
            $pacientesEspeciales = $representante->pacientesEspeciales()->with(['paciente'])->get();
            
            // Obtener historial de los pacientes asociados
            $pacienteIds = $pacientesEspeciales->pluck('paciente_id')->filter();
            
            if ($pacienteIds->isNotEmpty()) {
                $historialTerceros = \App\Models\Cita::with(['medico', 'consultorio', 'especialidad', 'paciente', 'paciente.pacienteEspecial'])
                                     ->whereIn('paciente_id', $pacienteIds)
                                     ->whereIn('status', [true, 1])
                                     ->orderBy('fecha_cita', 'desc')
                                     ->get()
                                     ->map(function($cita) use ($pacientesEspeciales) {
                                         $cita->tipo_historia_display = 'terceros';
                                         // Buscar info del paciente especial para mostrar nombre correcto
                                         $pe = $pacientesEspeciales->firstWhere('paciente_id', $cita->paciente_id);
                                         $cita->paciente_especial_info = $pe;
                                         return $cita;
                                     });
            }
        }

        // Combinar todo
        $historial = $historialPropio->concat($historialTerceros)->sortByDesc('fecha_cita');
        
        // Mantener paginación manual si es necesario, o pasar colección completa y usar JS (como en citas)
        // El view existente usa paginación ($historial->links()). 
        // Al combinar colecciones perdemos el paginador de Eloquent directo.
        // Convertiremos la colección a paginador manual para mantener compatibilidad con la vista
        
        $page = request()->get('page', 1);
        $perPage = 20;
        $historialPaginado = new \Illuminate\Pagination\LengthAwarePaginator(
            $historial->forPage($page, $perPage),
            $historial->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('paciente.historial', [
            'historial' => $historialPaginado,
            'paciente' => $paciente,
            'pacientesEspeciales' => $pacientesEspeciales
        ]);
    }

    public function pagos()
    {
        $paciente = auth()->user()->paciente;
        
        if (!$paciente) {
            return redirect()->route('paciente.dashboard')->with('error', 'No se encontró el perfil de paciente');
        }

        // 1. Pagos propios
        $pagosPropios = \App\Models\FacturaPaciente::with(['cita.especialidad', 'pagos'])
                                            ->where('paciente_id', $paciente->id)
                                            ->orderBy('created_at', 'desc')
                                            ->get()
                                            ->map(function($pago) {
                                                $pago->tipo_pago_display = 'propia';
                                                $pago->paciente_especial_info = null;
                                                return $pago;
                                            });

        // 2. Buscar si este paciente es representante de pacientes especiales
        $representante = \App\Models\Representante::where('tipo_documento', $paciente->tipo_documento)
                                      ->where('numero_documento', $paciente->numero_documento)
                                      ->first();
        
        $pagosTerceros = collect();
        $pacientesEspeciales = collect();
        
        if ($representante) {
            // Obtener pacientes especiales de este representante
            $pacientesEspeciales = $representante->pacientesEspeciales()->with(['paciente'])->get();
            
            // Obtener pagos de los pacientes asociados
            $pacienteIds = $pacientesEspeciales->pluck('paciente_id')->filter();
            
            if ($pacienteIds->isNotEmpty()) {
                $pagosTerceros = \App\Models\FacturaPaciente::with(['cita.especialidad', 'pagos', 'paciente'])
                                     ->whereIn('paciente_id', $pacienteIds)
                                     ->orderBy('created_at', 'desc')
                                     ->get()
                                     ->map(function($pago) use ($pacientesEspeciales) {
                                         $pago->tipo_pago_display = 'terceros';
                                         // Buscar info del paciente especial
                                         $pe = $pacientesEspeciales->firstWhere('paciente_id', $pago->paciente_id);
                                         $pago->paciente_especial_info = $pe;
                                         return $pago;
                                     });
            }
        }

        // Combinar todo
        $pagos = $pagosPropios->concat($pagosTerceros)->sortByDesc('created_at');
        
        // Paginación manual
        $page = request()->get('page', 1);
        $perPage = 20;
        $pagosPaginados = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagos->forPage($page, $perPage),
            $pagos->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('paciente.pagos', [
            'pagos' => $pagosPaginados, 
            'paciente' => $paciente,
            'pacientesEspeciales' => $pacientesEspeciales
        ]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // =========================================================================
        // ESTADÍSTICAS
        // =========================================================================
        $stats = [
            'total' => 0,
            'activos' => 0,
            'citas_hoy' => 0,
            'nuevos_mes' => 0
        ];

        // =========================================================================
        // MÉDICO (Rol 2)
        // =========================================================================
        // =========================================================================
        // MÉDICO (Rol 2)
        // =========================================================================
        if ($user->rol_id == 2) {
            $medico = $user->medico;
            if (!$medico) {
                return redirect()->route('medico.dashboard')->with('error', 'No se encontró el perfil de médico');
            }
            
            // Cargar especialidades para el filtro
            $medico->load('especialidades');
            
            // Query Base de Citas para identificar pacientes del médico
            $citasQuery = \App\Models\Cita::where('medico_id', $medico->id)
                                          ->where('status', true);

            // Aplicar filtro de especialidad si se seleccionó
            if ($request->filled('especialidad')) {
                $citasQuery->where('especialidad_id', $request->especialidad);
            }
            
            // Obtener IDs de pacientes únicos
            $pacienteIds = $citasQuery->distinct()->pluck('paciente_id');
            
            // Query de Pacientes
            $query = Paciente::with(['usuario', 'estado'])
                                ->whereIn('id', $pacienteIds)
                                ->where('status', true);

            // Filtro de Búsqueda (Nombre, Documento, etc.)
            if ($request->filled('buscar')) {
                $busqueda = $request->buscar;
                $query->where(function($q) use ($busqueda) {
                    $q->where('primer_nombre', 'like', "%$busqueda%")
                      ->orWhere('segundo_nombre', 'like', "%$busqueda%")
                      ->orWhere('primer_apellido', 'like', "%$busqueda%")
                      ->orWhere('segundo_apellido', 'like', "%$busqueda%")
                      ->orWhere('numero_documento', 'like', "%$busqueda%");
                });
            }
            
            // Filtro de Tipo (Regular vs Especial - aproximación simple basada en lógica de negocio si existe)
            // Si no hay campo explícito 'tipo' en pacientes, omitimos o ajustamos.
            // Asumimos que todos son 'regular' salvo que tengan 'pacienteEspecial'.
            
            // Filtro de Estado (Activo/Inactivo)
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Estadísticas (Dinámicas basadas en el filtro actual o totales del médico si se prefiere)
            // Calculamos totales globales del médico para las tarjetas, para no confundir al filtrar?
            // El usuario pidió "datos reales solo relacionados con ese medico". 
            // Usualmente los stats del header son globales. Calcularé globales del médico.
            
            $allPacientesMedicoQuery = \App\Models\Cita::where('medico_id', $medico->id)
                                            ->where('status', true)
                                            ->distinct()
                                            ->select('paciente_id'); 
                                            
            // Si se quiere que los stats respondan al filtro de especialidad, usar $citasQuery en su lugar.
            // Usaremos $citasQuery para que sea consistente con la vista filtrada, 
            // PERO 'Citas Hoy' siempre debería ser del día.
            
            $pacientesFiltradosCount = $query->count();
            
            $stats['total'] = $pacientesFiltradosCount;
            $stats['activos'] = $query->clone()->where('status', true)->count();
            $stats['nuevos_mes'] = $query->clone()->whereMonth('created_at', now()->month)->count(); // Pacientes registrados este mes
            
            // Citas hoy (del médico, filtrado por especialidad si aplica)
            $citasHoyQuery = \App\Models\Cita::where('medico_id', $medico->id)
                                             ->whereDate('fecha_cita', now())
                                             ->where('status', true);
            if ($request->filled('especialidad')) {
                $citasHoyQuery->where('especialidad_id', $request->especialidad);
            }
            $stats['citas_hoy'] = $citasHoyQuery->count();

            // Obtener pacientes paginados
            // Eager loading de la última cita con este médico para mostrar en la tabla
            $pacientes = $query->orderBy('created_at', 'desc')->paginate(10);
            
            // Adjuntar última cita para la vista
            foreach ($pacientes as $paciente) {
                $ultimaCita = \App\Models\Cita::where('medico_id', $medico->id)
                                              ->where('paciente_id', $paciente->id)
                                              ->where('status', true)
                                              ->orderBy('fecha_cita', 'desc')
                                              ->with('especialidad')
                                              ->first();
                $paciente->ultima_cita = $ultimaCita;
            }

            return view('medico.pacientes.index', compact('pacientes', 'stats', 'medico'));
        }
        
        // =========================================================================
        // ADMIN (Rol 1)
        // =========================================================================
        
        // Base Query
        $query = Paciente::with(['usuario', 'estado', 'citas.consultorio'])->where('status', true);
        $citasQuery = \App\Models\Cita::where('status', true)->whereDate('fecha_cita', now());

        // Scope Admin Local
        if ($user->administrador && $user->administrador->tipo_admin !== 'Root') {
            $consultorioIds = $user->administrador->consultorios->pluck('id');
            
            // Filtrar pacientes que tengan historial en estos consultorios
            $query->whereHas('citas', function($q) use ($consultorioIds) {
                $q->whereIn('consultorio_id', $consultorioIds);
            });

            // Filtrar citas hoy para el stat
            $citasQuery->whereIn('consultorio_id', $consultorioIds);
        }
        
        // Calcular Stats Admin
        $stats['total'] = $query->count(); // Count total query (respetando scope)
        $stats['activos'] = $query->clone()->where('status', true)->count();
        $stats['nuevos_mes'] = $query->clone()->whereMonth('created_at', now()->month)->count();
        $stats['citas_hoy'] = $citasQuery->distinct('paciente_id')->count('paciente_id');

        // Obtener paginados
        $pacientes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('shared.pacientes.index', compact('pacientes', 'stats'));
    }

    public function create()
    {
        $usuarios = Usuario::where('status', true)->where('rol_id', 3)
                          ->whereNotIn('id', function($query) {
                              $query->select('user_id')->from('pacientes')->whereNotNull('user_id');
                          })->get();

        $estados = Estado::where('status', true)->get();
        return view('shared.pacientes.create', compact('usuarios', 'estados'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'primer_nombre' => ['required', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'primer_apellido' => ['required', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'tipo_documento' => 'nullable|in:V,E,P,J',
            'numero_documento' => 'nullable|max:20',
            'fecha_nac' => 'nullable|date',
            'estado_id' => 'nullable|exists:estados,id_estado',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'prefijo_tlf' => 'nullable|in:+58,+57,+1,+34',
            'numero_tlf' => 'nullable|max:15',
            'genero' => 'nullable|max:20',
            'ocupacion' => 'nullable|max:150',
            'estado_civil' => 'nullable|max:50',
            // User credentials
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                // 1. Create User
                $usuario = Usuario::create([
                    'rol_id' => 3, // Paciente
                    'correo' => $request->correo,
                    'password' => $request->password,
                    'status' => $request->has('status')
                ]);

                // 2. Create Paciente Profile
                $pacienteData = $request->except(['correo', 'password', 'password_confirmation', 'status']);
                $pacienteData['user_id'] = $usuario->id;
                $pacienteData['status'] = $request->has('status');

                Paciente::create($pacienteData);
            });

            return redirect()->route('pacientes.index')->with('success', 'Paciente creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el paciente: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $paciente = Paciente::with(['usuario', 'estado', 'ciudad', 'municipio', 'parroquia', 'historiaClinicaBase'])->findOrFail($id);
        $user = auth()->user();

        // Si es médico
        if ($user->rol_id == 2) {
             return view('medico.pacientes.show', compact('paciente'));
        }

        // Si es Admin Local, verificar acceso
        if ($user->administrador && $user->administrador->tipo_admin !== 'Root') {
            $consultorioIds = $user->administrador->consultorios->pluck('id');
            
            // Si tiene citas, debe pertenecer a sus sedes. Si no tiene citas, es un paciente nuevo y permitimos acceso.
            $hasCitas = $paciente->citas()->exists();
            if ($hasCitas) {
                $hasAccess = $paciente->citas()->whereIn('consultorio_id', $consultorioIds)->exists();
                if (!$hasAccess) {
                    abort(403, 'No tiene permiso para ver este paciente.');
                }
            }
        }

        return view('shared.pacientes.show', compact('paciente'));
    }

    public function edit($id)
    {
        $paciente = Paciente::with(['usuario', 'estado', 'ciudad', 'municipio', 'parroquia'])->findOrFail($id);
        $user = auth()->user();

        // Si es Admin Local, verificar acceso
        if ($user->administrador && $user->administrador->tipo_admin !== 'Root') {
            $consultorioIds = $user->administrador->consultorios->pluck('id');
            
            $hasCitas = $paciente->citas()->exists();
            if ($hasCitas) {
                $hasAccess = $paciente->citas()->whereIn('consultorio_id', $consultorioIds)->exists();
                if (!$hasAccess) {
                    abort(403, 'No tiene permiso para editar este paciente.');
                }
            }
        }

        $estados = Estado::where('status', true)->get();
        // Cargar ciudades del estado actual para el select dinámico si aplica
        $ciudades = Ciudad::where('id_estado', $paciente->estado_id)->where('status', true)->get();
        $municipios = Municipio::where('id_estado', $paciente->estado_id)->where('status', true)->get();
        $parroquias = Parroquia::where('id_municipio', $paciente->municipio_id)->where('status', true)->get();

        return view('shared.pacientes.edit', compact('paciente', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $user = auth()->user();

        // Si es Admin Local, verificar acceso antes de validar
        if ($user->administrador && $user->administrador->tipo_admin !== 'Root') {
            $consultorioIds = $user->administrador->consultorios->pluck('id');
            
            $hasCitas = $paciente->citas()->exists();
            if ($hasCitas) {
                $hasAccess = $paciente->citas()->whereIn('consultorio_id', $consultorioIds)->exists();
                if (!$hasAccess) {
                    abort(403, 'No tiene permiso para actualizar este paciente.');
                }
            }
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:usuarios,id|unique:pacientes,user_id,' . $id,
            'primer_nombre' => ['required', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'primer_apellido' => ['required', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'tipo_documento' => 'nullable|in:V,E,P,J',
            'numero_documento' => 'nullable|max:20',
            'fecha_nac' => 'nullable|date',
            'estado_id' => 'nullable|exists:estados,id_estado',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'prefijo_tlf' => 'nullable|in:+58,+57,+1,+34',
            'numero_tlf' => 'nullable|max:15',
            'genero' => 'nullable|max:20',
            'ocupacion' => 'nullable|max:150',
            'estado_civil' => 'nullable|max:50',
            'password' => 'nullable|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paciente->update($request->all());

        if ($request->filled('password')) {
            $paciente->usuario->update([
                'password' => $request->password // Mutator handles encryption
            ]);
        }

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->update(['status' => false]);

        return redirect()->route('pacientes.index')->with('success', 'Paciente desactivado exitosamente');
    }

    public function historiaClinica($id)
    {
        $paciente = Paciente::with('historiaClinicaBase')->findOrFail($id);
        return view('pacientes.historia-clinica', compact('paciente'));
    }

    public function actualizarHistoriaClinica(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alergias' => 'nullable|string',
            'alergias_medicamentos' => 'nullable|string',
            'antecedentes_familiares' => 'nullable|string',
            'antecedentes_personales' => 'nullable|string',
            'enfermedades_cronicas' => 'nullable|string',
            'medicamentos_actuales' => 'nullable|string',
            'cirugias_previas' => 'nullable|string',
            'habitos' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $paciente = Paciente::findOrFail($id);
        $historia = $paciente->historiaClinicaBase;

        if ($historia) {
            $historia->update($request->all());
        } else {
            \App\Models\HistoriaClinicaBase::create(array_merge(
                ['paciente_id' => $id],
                $request->all()
            ));
        }

        return redirect()->back()->with('success', 'Historia clínica actualizada exitosamente');
    }

    /**
     * Mostrar formulario de edición de perfil para el paciente autenticado
     */
    public function editPerfil()
    {
        $paciente = auth()->user()->paciente;
        
        if (!$paciente) {
            return redirect()->route('paciente.dashboard')->with('error', 'No se encontró el perfil de paciente');
        }

        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();

        return view('paciente.editar-perfil', compact('paciente', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    /**
     * Actualizar perfil del paciente autenticado
     */
    public function updatePerfil(Request $request)
    {
        $paciente = auth()->user()->paciente;
        
        if (!$paciente) {
            return redirect()->route('paciente.dashboard')->with('error', 'No se encontró el perfil de paciente');
        }

        $validator = Validator::make($request->all(), [
            'primer_nombre' => ['required', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'segundo_nombre' => ['nullable', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'primer_apellido' => ['required', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'segundo_apellido' => ['nullable', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'fecha_nac' => 'nullable|date|before:today',
            'estado_id' => 'nullable|exists:estados,id_estado',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'direccion_detallada' => 'nullable|string|max:500',
            'prefijo_tlf' => 'nullable|in:+58,+57,+1,+34',
            'numero_tlf' => 'nullable|max:15',
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'ocupacion' => 'nullable|max:150',
            'estado_civil' => 'nullable|in:Soltero,Casado,Divorciado,Viudo,Unión Libre',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'banner_color' => 'nullable|string|max:100',
            'tema_dinamico' => 'nullable|boolean',
            'password' => 'nullable|min:8|confirmed'
        ], [
            'primer_nombre.required' => 'El primer nombre es requerido',
            'primer_apellido.required' => 'El primer apellido es requerido',
            'fecha_nac.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'foto_perfil.image' => 'El archivo de foto debe ser una imagen',
            'foto_perfil.max' => 'La foto no debe superar los 2MB',
            'banner_perfil.image' => 'El archivo de banner debe ser una imagen',
            'banner_perfil.max' => 'El banner no debe superar los 3MB',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden'
        ]);

        if ($request->filled('password')) {
            $newPasswordHash = md5(md5($request->password));
            
            // 1. Validar que no sea la misma contraseña actual
            if ($paciente->usuario->password === $newPasswordHash) {
                return redirect()->back()->with('error_password', 'La nueva contraseña no puede ser igual a la actual.')->withInput();
            }

            // 2. Validar historial (opcional, pero buena práctica si ya tenemos la tabla)
            // En este caso, la validación estricta es contra la actual.
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $paciente) {
                $data = $request->except(['foto_perfil', 'banner_perfil', 'password', 'password_confirmation']);
                $data['tema_dinamico'] = $request->has('tema_dinamico') ? 1 : 0;

                // Manejar foto de perfil
                if ($request->hasFile('foto_perfil')) {
                    // Eliminar foto anterior si existe
                    if ($paciente->foto_perfil && \Storage::disk('public')->exists($paciente->foto_perfil)) {
                        \Storage::disk('public')->delete($paciente->foto_perfil);
                    }

                    // Guardar nueva foto
                    $file = $request->file('foto_perfil');
                    $filename = 'perfil_' . $paciente->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('perfiles', $filename, 'public');
                    $data['foto_perfil'] = $path;
                }

                // Manejar banner de perfil
                if ($request->hasFile('banner_perfil')) {
                    // Eliminar banner anterior si existe
                    if ($paciente->banner_perfil && \Storage::disk('public')->exists($paciente->banner_perfil)) {
                        \Storage::disk('public')->delete($paciente->banner_perfil);
                    }

                    // Guardar nuevo banner
                    $file = $request->file('banner_perfil');
                    $filename = 'banner_' . $paciente->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('banners', $filename, 'public');
                    $data['banner_perfil'] = $path;
                }

                // Actualizar paciente
                $paciente->update($data);

                // Actualizar contraseña si se proporcionó
                if ($request->filled('password')) {
                    $newPasswordHash = md5(md5($request->password));

                    // Desactivar historial anterior
                    \App\Models\HistorialPassword::where('user_id', $paciente->user_id)
                        ->where('status', true)
                        ->update(['status' => false]);

                    // Actualizar contraseña usuario
                    $paciente->usuario->update([
                        'password' => $request->password // El mutator en Usuario hace el hash, pero ojo, si ya lo hicimos arriba o el mutator lo hace doble.
                        // Revisando Usuario model: setPasswordAttribute hace md5(md5($value)).
                        // Así que pasamos la contraseña plana.
                    ]);

                    // Crear nuevo historial
                    \App\Models\HistorialPassword::create([
                        'user_id' => $paciente->user_id,
                        'password_hash' => $newPasswordHash,
                        'status' => true
                    ]);
                }
            });

            return redirect()->route('paciente.dashboard')->with('success', '¡Perfil actualizado exitosamente!');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar perfil del paciente: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el perfil: ' . $e->getMessage())->withInput();
        }
    }
    public function showSecurityQuestions()
    {
        $usuario = auth()->user();
        
        // Get current security questions
        $currentQuestions = \App\Models\RespuestaSeguridad::where('user_id', $usuario->id)
            ->with('pregunta')
            ->get();
        
        // Get all available questions from catalog
        $preguntasCatalogo = \App\Models\PreguntaCatalogo::where('status', true)
            ->orderBy('pregunta')
            ->get();
        
        return view('shared.perfil.security-questions', compact('currentQuestions', 'preguntasCatalogo'));
    }
    
    public function updateSecurityQuestions(Request $request)
    {
        $usuario = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'question_1' => 'required|exists:preguntas_catalogo,id',
            'answer_1' => 'required|string|min:3',
            'question_2' => 'required|exists:preguntas_catalogo,id|different:question_1',
            'answer_2' => 'required|string|min:3',
            'question_3' => 'required|exists:preguntas_catalogo,id|different:question_1|different:question_2',
            'answer_3' => 'required|string|min:3',
        ], [
            'current_password.required' => 'Debes ingresar tu contraseña actual',
            'question_1.required' => 'Debes seleccionar la pregunta 1',
            'question_2.different' => 'Las preguntas deben ser diferentes',
            'question_3.different' => 'Las preguntas deben ser diferentes',
            'answer_1.min' => 'La respuesta debe tener al menos 3 caracteres',
            'answer_2.min' => 'La respuesta debe tener al menos 3 caracteres',
            'answer_3.min' => 'La respuesta debe tener al menos 3 caracteres',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Verify current password
        $passwordHash = md5(md5($request->current_password));
        if ($usuario->password !== $passwordHash) {
            return redirect()->back()
                ->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])
                ->withInput();
        }
        
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $usuario) {
                // Delete old security questions
                \App\Models\RespuestaSeguridad::where('user_id', $usuario->id)->delete();
                
                // Create new security questions
                for ($i = 1; $i <= 3; $i++) {
                    $rawInput = $request->input("answer_{$i}");
                    // Convert to lowercase for case-insensitive comparison (matches registration format)
                    $respuesta = strtolower(trim($rawInput));
                    
                    $finalHash = md5(md5($respuesta));
                    
                    \Illuminate\Support\Facades\Log::info("Saving Security Question {$i}", [
                        'raw_input' => $rawInput,
                        'processed_input' => $respuesta,
                        'generated_hash' => $finalHash
                    ]);

                    \App\Models\RespuestaSeguridad::create([
                        'user_id' => $usuario->id,
                        'pregunta_id' => $request->input("question_{$i}"),
                        'respuesta_hash' => $finalHash
                    ]);
                }
            });
            
            \Illuminate\Support\Facades\Log::info('Security questions updated', [
                'user_id' => $usuario->id,
                'email' => $usuario->correo
            ]);
            
            return redirect()->route('paciente.perfil.edit')
                ->with('success', 'Preguntas de seguridad actualizadas exitosamente');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating security questions: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar las preguntas de seguridad')
                ->withInput();
        }
    }
}
