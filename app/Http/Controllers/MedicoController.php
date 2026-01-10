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
                $medicoData = $request->except(['correo', 'password', 'password_confirmation', 'especialidades', 'status']);
                $medicoData['user_id'] = $usuario->id;
                $medicoData['status'] = $request->has('status');

                $medico = Medico::create($medicoData);
                
                // 3. Assign Specialties
                if ($request->has('especialidades')) {
                    $medico->especialidades()->attach($request->especialidades);
                }
            });

            return redirect()->route('medicos.index')->with('success', 'Médico creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el médico: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $medico = Medico::with(['usuario', 'especialidades', 'consultorios', 'estado', 'ciudad'])->findOrFail($id);
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
            'especialidades.*' => 'exists:especialidades,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $medico = Medico::findOrFail($id);
        
        // Excluir datos sensibles y de relación de usuario
        $data = $request->except(['especialidades', 'user_id', 'correo', 'password', 'password_confirmation']);
        $data['status'] = $request->has('status'); // Si se envía status en el form

        $medico->update($data);
        
        // Sincronizar especialidades
        if ($request->has('especialidades')) {
            $medico->especialidades()->sync($request->especialidades);
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
        $consultorios = Consultorio::where('status', true)->get();
        $horarios = \App\Models\MedicoConsultorio::where('medico_id', $id)->get();

        return view('shared.medicos.horarios', compact('medico', 'consultorios', 'horarios'));
    }

    public function guardarHorario(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'consultorio_id' => 'required|exists:consultorios,id',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'turno' => 'required|in:mañana,tarde,noche,completo',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        \App\Models\MedicoConsultorio::updateOrCreate(
            [
                'medico_id' => $id,
                'consultorio_id' => $request->consultorio_id,
                'dia_semana' => $request->dia_semana
            ],
            $request->only(['turno', 'horario_inicio', 'horario_fin'])
        );

        return redirect()->back()->with('success', 'Horario guardado exitosamente');
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
