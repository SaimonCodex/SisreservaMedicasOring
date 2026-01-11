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
                    'citas_proximás' => 0,
                    'historias' => 0,
                    'recetas_activas' => 0,
                    'consultas_mes' => 0
                ]
            ]);
        }
        
        $citas_proximas = \App\Models\Cita::where('paciente_id', $paciente->id)
                                       ->where('fecha_cita', '>=', today())
                                       ->where('status', true)
                                       ->orderBy('fecha_cita')
                                       ->limit(5)
                                       ->get();

        $historial_reciente = \App\Models\Cita::where('paciente_id', $paciente->id)
                                         ->where('fecha_cita', '<', today())
                                         ->where('status', true)
                                         ->orderBy('fecha_cita', 'desc')
                                         ->limit(10)
                                         ->get();

        $recetas_activas = collect();

        // Estadísticas
        $stats = [
            'citas_proximás' => $citas_proximas->count(),
            'historias' => $historial_reciente->count(),
            'recetas_activas' => 0,
            'consultas_mes' => \App\Models\Cita::where('paciente_id', $paciente->id)
                                              ->whereMonth('fecha_cita', now()->month)
                                              ->whereYear('fecha_cita', now()->year)
                                              ->count()
        ];

        return view('paciente.dashboard', compact('citas_proximas', 'historial_reciente', 'recetas_activas', 'stats'));
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
        $pagosPropios = \App\Models\FacturaPaciente::where('paciente_id', $paciente->id)
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
                $pagosTerceros = \App\Models\FacturaPaciente::whereIn('paciente_id', $pacienteIds)
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

    public function index()
    {
        $user = auth()->user();
        
        // Si es médico, filtrar solo sus pacientes (pacientes con citas atendidas por él)
        if ($user->rol_id == 2) {
            $medico = $user->medico;
            if (!$medico) {
                return redirect()->route('medico.dashboard')->with('error', 'No se encontró el perfil de médico');
            }
            
            // Obtener IDs de pacientes únicos que han tenido citas con este médico
            $pacienteIds = \App\Models\Cita::where('medico_id', $medico->id)
                                          ->where('status', true)
                                          ->distinct()
                                          ->pluck('paciente_id');
            
            $pacientes = Paciente::with(['usuario', 'estado'])
                                ->whereIn('id', $pacienteIds)
                                ->where('status', true)
                                ->get();
            
            return view('medico.pacientes.index', compact('pacientes'));
        }
        
        // Admin: todos los pacientes
        $pacientes = Paciente::with(['usuario', 'estado'])->where('status', true)->get();
        return view('shared.pacientes.index', compact('pacientes'));
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

        // Retornar vista según el rol
        if (auth()->user()->rol_id == 2) {
            // Validar que el médico tenga relación con el paciente (opcional, pero recomendado por seguridad)
            // Por ahora solo retornamos la vista correcta
             return view('medico.pacientes.show', compact('paciente'));
        }

        return view('shared.pacientes.show', compact('paciente'));
    }

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        $usuarios = Usuario::where('status', true)->where('rol_id', 3)->get();
        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();

        return view('shared.pacientes.edit', compact('paciente', 'usuarios', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    public function update(Request $request, $id)
    {
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
            'estado_civil' => 'nullable|max:50'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paciente = Paciente::findOrFail($id);
        $paciente->update($request->all());

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
}
