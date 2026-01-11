<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Usuario;
use App\Models\Especialidad;
use App\Models\Consultorio;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    public function dashboard()
    {
        $medico = auth()->user()->medico;
        $citasHoy = \App\Models\Cita::where('medico_id', $medico->id)
                                   ->whereDate('fecha_cita', today())
                                   ->where('status', true)
                                   ->get();

        $proximasCitas = \App\Models\Cita::where('medico_id', $medico->id)
                                        ->where('fecha_cita', '>=', today())
                                        ->where('status', true)
                                        ->orderBy('fecha_cita')
                                        ->limit(5)
                                        ->get();

        return view('medico.dashboard', compact('citasHoy', 'proximasCitas'));
    }

    public function index(Request $request)
    {
        $query = Medico::with(['usuario', 'especialidades', 'estado']);

        // Filtro de Búsqueda
        if ($request->filled('buscar')) {
            $busqueda = $request->buscar;
            $query->where(function($q) use ($busqueda) {
                $q->where('primer_nombre', 'like', "%$busqueda%")
                  ->orWhere('segundo_nombre', 'like', "%$busqueda%")
                  ->orWhere('primer_apellido', 'like', "%$busqueda%")
                  ->orWhere('segundo_apellido', 'like', "%$busqueda%")
                  ->orWhere('numero_documento', 'like', "%$busqueda%")
                  ->orWhere('nro_colegiatura', 'like', "%$busqueda%");
            });
        }

        // Filtro por Especialidad
        if ($request->filled('especialidad_id')) {
            $query->whereHas('especialidades', function($q) use ($request) {
                $q->where('especialidades.id', $request->especialidad_id);
            });
        }

        // Filtro por Estatus
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $medicos = $query->paginate(10)->withQueryString();
        $especialidades = Especialidad::where('status', true)->get(); // Para el dropdown de filtro

        // Estadísticas para las tarjetas
        $totalMedicos = Medico::count();
        $medicosActivos = Medico::where('status', true)->count();
        $citasHoyCount = \App\Models\Cita::whereDate('fecha_cita', now())->where('status', true)->count();
        $totalEspecialidades = Especialidad::where('status', true)->count();

        return view('shared.medicos.index', compact('medicos', 'especialidades', 'totalMedicos', 'medicosActivos', 'citasHoyCount', 'totalEspecialidades'));
    }

    public function create()
    {
        $usuarios = Usuario::where('status', true)->where('rol_id', 2)
                          ->whereNotIn('id', function($query) {
                              $query->select('user_id')->from('medicos')->whereNotNull('user_id');
                          })->get();

        $especialidades = Especialidad::where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        
        return view('shared.medicos.create', compact('usuarios', 'especialidades', 'estados'));
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
            'nro_colegiatura' => 'nullable|max:50',
            'formacion_academica' => 'nullable|string',
            'experiencia_profesional' => 'nullable|string',
            'especialidades' => 'required|array',
            'especialidades.*' => 'exists:especialidades,id',
            'especialidades_data' => 'required|array',
            'especialidades_data.*.tarifa' => 'required|numeric|min:0',
            'especialidades_data.*.anos_experiencia' => 'nullable|integer|min:0',
            'especialidades_data.*.atiende_domicilio' => 'nullable|boolean',
            'especialidades_data.*.tarifa_extra_domicilio' => 'nullable|numeric|min:0',
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
                    'rol_id' => 2, // Medico
                    'correo' => $request->correo,
                    'password' => $request->password,
                    'status' => $request->has('status')
                ]);

                // 2. Create Medico Profile
                $medicoData = $request->except(['correo', 'password', 'password_confirmation', 'especialidades', 'especialidades_data', 'status']);
                $medicoData['user_id'] = $usuario->id;
                $medicoData['status'] = $request->has('status');

                $medico = Medico::create($medicoData);
                
                // 3. Assign Specialties with Pivot Data
                if ($request->has('especialidades_data')) {
                    $syncData = [];
                    foreach ($request->especialidades_data as $id => $data) {
                        $syncData[$id] = [
                            'tarifa' => $data['tarifa'],
                            'anos_experiencia' => $data['anos_experiencia'] ?? 0,
                            'atiende_domicilio' => isset($data['atiende_domicilio']) ? 1 : 0,
                            'tarifa_extra_domicilio' => $data['tarifa_extra_domicilio'] ?? 0.00,
                            'status' => true
                        ];
                    }
                    $medico->especialidades()->attach($syncData);
                }
            });

            return redirect()->route('medicos.index')->with('success', 'Médico creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el médico: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $medico = Medico::with([
            'usuario', 
            'especialidades', 
            'consultorios', 
            'estado', 
            'ciudad',
            'horarios.consultorio',
            'horarios.especialidad'
        ])->findOrFail($id);
        return view('shared.medicos.show', compact('medico'));
    }

    public function edit($id)
    {
        $medico = Medico::findOrFail($id);
        $usuarios = Usuario::where('status', true)->where('rol_id', 2)->get();
        $especialidades = Especialidad::where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();

        return view('shared.medicos.edit', compact('medico', 'usuarios', 'especialidades', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:usuarios,id|unique:medicos,user_id,' . $id,
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
            'nro_colegiatura' => 'nullable|max:50',
            'formacion_academica' => 'nullable|string',
            'experiencia_profesional' => 'nullable|string',
            'especialidades' => 'required|array',
            'especialidades.*' => 'exists:especialidades,id',
            'especialidades_data' => 'required|array',
            'especialidades_data.*.tarifa' => 'required|numeric|min:0',
            'especialidades_data.*.anos_experiencia' => 'nullable|integer|min:0',
            'especialidades_data.*.atiende_domicilio' => 'nullable|boolean',
            'especialidades_data.*.tarifa_extra_domicilio' => 'nullable|numeric|min:0',
            'password' => 'nullable|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $medico = Medico::findOrFail($id);
        
        // Excluir datos sensibles y de relación de usuario
        $data = $request->except(['especialidades', 'especialidades_data', 'user_id', 'correo', 'password', 'password_confirmation']);
        $data['status'] = $request->has('status'); // Si se envía status en el form

        $medico->update($data);

        // Update User Password if provided
        if ($request->filled('password')) {
            $medico->usuario->update([
                'password' => $request->password // Mutator handles encryption
            ]);
        }


        
        // Sincronizar especialidades con datos pivote
        if ($request->has('especialidades_data')) {
            $syncData = [];
            foreach ($request->especialidades_data as $idEsp => $pivotData) {
                // Ensure ID corresponds to the loop key if needed, or use $pivotData['id']
                // The form sends especialidades_data[ID][field]
                $syncData[$idEsp] = [
                    'tarifa' => $pivotData['tarifa'],
                    'anos_experiencia' => $pivotData['anos_experiencia'] ?? 0,
                    'atiende_domicilio' => isset($pivotData['atiende_domicilio']) ? 1 : 0,
                    'tarifa_extra_domicilio' => $pivotData['tarifa_extra_domicilio'] ?? 0.00,
                    'status' => true
                ];
            }
            $medico->especialidades()->sync($syncData);
        }

        return redirect()->route('medicos.index')->with('success', 'Médico actualizado exitosamente');
    }

    public function destroy($id)
    {
        $medico = Medico::findOrFail($id);
        $medico->update(['status' => false]);

        return redirect()->route('medicos.index')->with('success', 'Médico desactivado exitosamente');
    }

    public function horarios($id)
    {
        $medico = Medico::findOrFail($id);
        // Eager load especialidades to use in frontend filtering
        $consultorios = Consultorio::with('especialidades')->where('status', true)->get();
        $horarios = \App\Models\MedicoConsultorio::where('medico_id', $id)->get();

        return view('shared.medicos.horarios', compact('medico', 'consultorios', 'horarios'));
    }

    public function guardarHorario(Request $request, $id)
    {
        $medico = Medico::findOrFail($id);
        
        // Validar estructura básica
        $validator = Validator::make($request->all(), [
            'dias' => 'array', // Array de días (lunes, martes...)
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        \Log::info('V2 UI Payload:', $request->all());

        $input = $request->input('horarios', []);
        $daysOfWeek = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        
        $dbDays = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo'
        ];

        // 1. Validación de Especialidades vs Consultorio
        // Recopilamos todos los IDs de consultorios involucrados para cargar sus especialidades permitidas
        $consultorioIds = [];
        foreach ($daysOfWeek as $dayKey) {
            if (!isset($input[$dayKey])) continue;
            $dayData = $input[$dayKey];

            if (isset($dayData['manana_activa']) && $dayData['manana_activa'] == '1' && !empty($dayData['manana_consultorio_id'])) {
                $consultorioIds[] = $dayData['manana_consultorio_id'];
            }
            if (isset($dayData['tarde_activa']) && $dayData['tarde_activa'] == '1' && !empty($dayData['tarde_consultorio_id'])) {
                $consultorioIds[] = $dayData['tarde_consultorio_id'];
            }
        }

        if (!empty($consultorioIds)) {
            $consultorios = Consultorio::with('especialidades')->findMany(array_unique($consultorioIds))->keyBy('id');

            foreach ($daysOfWeek as $dayKey) {
                if (!isset($input[$dayKey])) continue;
                $dayData = $input[$dayKey];
                $diaNombre = $dbDays[$dayKey];

                // Validar Turno Mañana
                if (isset($dayData['manana_activa']) && $dayData['manana_activa'] == '1') {
                    if (!empty($dayData['manana_consultorio_id']) && !empty($dayData['manana_especialidad_id'])) {
                        $consId = $dayData['manana_consultorio_id'];
                        $espId = $dayData['manana_especialidad_id'];
                        
                        if ($consultorios->has($consId)) {
                            $consultorio = $consultorios->get($consId);
                            if (!$consultorio->especialidades->contains('id', $espId)) {
                                $especialidadNombre = \App\Models\Especialidad::find($espId)->nombre ?? 'seleccionada';
                                return redirect()->back()->with('error', "Error en {$diaNombre} (Mañana): El consultorio '{$consultorio->nombre}' no admite la especialidad '{$especialidadNombre}'.");
                            }
                        }
                    }
                }

                // Validar Turno Tarde
                if (isset($dayData['tarde_activa']) && $dayData['tarde_activa'] == '1') {
                    if (!empty($dayData['tarde_consultorio_id']) && !empty($dayData['tarde_especialidad_id'])) {
                        $consId = $dayData['tarde_consultorio_id'];
                        $espId = $dayData['tarde_especialidad_id'];
                        
                        if ($consultorios->has($consId)) {
                            $consultorio = $consultorios->get($consId);
                            if (!$consultorio->especialidades->contains('id', $espId)) {
                                $especialidadNombre = \App\Models\Especialidad::find($espId)->nombre ?? 'seleccionada';
                                return redirect()->back()->with('error', "Error en {$diaNombre} (Tarde): El consultorio '{$consultorio->nombre}' no admite la especialidad '{$especialidadNombre}'.");
                            }
                        }
                    }
                }
            }
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id, $input, $daysOfWeek, $dbDays) {
                // Limpiar horarios existentes
                \App\Models\MedicoConsultorio::where('medico_id', $id)->delete();

                foreach ($daysOfWeek as $dayKey) {
                    if (!isset($input[$dayKey]) || !isset($input[$dayKey]['activo'])) {
                        continue; // Día no activo
                    }

                    $dayData = $input[$dayKey];
                    $diaSemanaDb = $dbDays[$dayKey];

                    // Procesar Turno Mañana
                    if (isset($dayData['manana_activa']) && $dayData['manana_activa'] == '1') {
                        if (!empty($dayData['manana_inicio']) && !empty($dayData['manana_fin']) && !empty($dayData['manana_consultorio_id'])) {
                            \App\Models\MedicoConsultorio::create([
                                'medico_id' => $id,
                                'consultorio_id' => $dayData['manana_consultorio_id'],
                                'especialidad_id' => $dayData['manana_especialidad_id'] ?? null,
                                'dia_semana' => $diaSemanaDb,
                                'turno' => 'mañana',
                                'horario_inicio' => $dayData['manana_inicio'],
                                'horario_fin' => $dayData['manana_fin'],
                                'status' => true
                            ]);
                        }
                    }

                    // Procesar Turno Tarde
                    if (isset($dayData['tarde_activa']) && $dayData['tarde_activa'] == '1') {
                        if (!empty($dayData['tarde_inicio']) && !empty($dayData['tarde_fin']) && !empty($dayData['tarde_consultorio_id'])) {
                             \App\Models\MedicoConsultorio::create([
                                'medico_id' => $id,
                                'consultorio_id' => $dayData['tarde_consultorio_id'],
                                'especialidad_id' => $dayData['tarde_especialidad_id'] ?? null,
                                'dia_semana' => $diaSemanaDb,
                                'turno' => 'tarde',
                                'horario_inicio' => $dayData['tarde_inicio'],
                                'horario_fin' => $dayData['tarde_fin'],
                                'status' => true
                            ]);
                        }
                    }
                }
            });

            return redirect()->back()->with('success', 'Horarios actualizados correctamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar horarios: ' . $e->getMessage());
        }
    }

    public function buscar(Request $request)
    {
        $query = Medico::with('especialidades')->where('status', true);

        if ($request->has('especialidad_id') && $request->especialidad_id) {
            $query->whereHas('especialidades', function($q) use ($request) {
                $q->where('especialidades.id', $request->especialidad_id);
            });
        }

        if ($request->has('consultorio_id') && $request->consultorio_id) {
            $query->whereHas('consultorios', function($q) use ($request) {
                $q->where('consultorios.id', $request->consultorio_id);
            });
        }

        $medicos = $query->get();

        return response()->json($medicos);
    }
}
