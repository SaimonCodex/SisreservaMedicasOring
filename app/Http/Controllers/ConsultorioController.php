<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use App\Models\Especialidad;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultorioController extends Controller
{
    public function index(Request $request)
    {
        // Estadísticas
        $totalConsultorios = Consultorio::count();
        $consultoriosActivos = Consultorio::where('status', true)->count();
        $totalCiudades = Consultorio::distinct('ciudad_id')->count('ciudad_id');
        
        // Total médicos con horarios asignados
        $totalMedicosAsignados = \App\Models\MedicoConsultorio::distinct('medico_id')->count('medico_id');

        // Cargar especialidades directas (pivot manual)
        $query = Consultorio::with(['estado', 'ciudad', 'especialidades'])
                            ->withCount('medicos');

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%')
                  ->orWhereHas('ciudad', function($q) use ($request) {
                      $q->where('ciudad', 'like', '%' . $request->buscar . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $consultorios = $query->paginate(10);
        
        return view('shared.consultorios.index', compact('consultorios', 'totalConsultorios', 'consultoriosActivos', 'totalCiudades', 'totalMedicosAsignados'));
    }

    public function create()
    {
        $estados = Estado::where('status', true)->get();
        $especialidades = Especialidad::where('status', true)->get();
        
        $ciudades = [];
        $municipios = [];
        $parroquias = [];

        if (old('estado_id')) {
            $ciudades = Ciudad::where('id_estado', old('estado_id'))->where('status', true)->get();
            $municipios = Municipio::where('id_estado', old('estado_id'))->where('status', true)->get();
        }

        if (old('municipio_id')) {
            $parroquias = Parroquia::where('id_municipio', old('municipio_id'))->where('status', true)->get();
        }

        return view('shared.consultorios.create', compact('estados', 'ciudades', 'municipios', 'parroquias', 'especialidades'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100|unique:consultorios,nombre',
            'descripcion' => 'nullable|string',
            'estado_id' => 'required|exists:estados,id_estado',
            'ciudad_id' => 'required|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'direccion_detallada' => 'nullable|string',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:150',
            'horario_inicio' => 'nullable|date_format:H:i',
            'horario_fin' => 'nullable|date_format:H:i|after:horario_inicio',
            'especialidades' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['especialidades']);
        $data['status'] = $request->has('status') ? 1 : 0;

        $consultorio = Consultorio::create($data);

        // Asociar especialidades seleccionadas
        if ($request->has('especialidades')) {
            $consultorio->especialidades()->attach($request->especialidades);
        }
        
        return redirect()->route('consultorios.index')->with('success', 'Consultorio creado exitosamente');
    }

    public function show($id)
    {
        $consultorio = Consultorio::with(['estado', 'ciudad', 'municipio', 'parroquia', 'especialidades'])->findOrFail($id);
        return view('shared.consultorios.show', compact('consultorio'));
    }

    public function edit($id)
    {
        $consultorio = Consultorio::findOrFail($id);
        $estados = Estado::where('status', true)->get();
        $especialidades = Especialidad::where('status', true)->get();
        
        // Cargar listas basadas en old() si existe (error de validación), o en el modelo
        $estadoId = old('estado_id', $consultorio->estado_id);
        $municipioId = old('municipio_id', $consultorio->municipio_id);

        $ciudades = Ciudad::where('id_estado', $estadoId)->where('status', true)->get();
        $municipios = Municipio::where('id_estado', $estadoId)->where('status', true)->get();
        
        $parroquias = $municipioId 
            ? Parroquia::where('id_municipio', $municipioId)->where('status', true)->get() 
            : [];

        return view('shared.consultorios.edit', compact('consultorio', 'estados', 'ciudades', 'municipios', 'parroquias', 'especialidades'));
    }

    public function update(Request $request, $id)
    {
        // Validación con sintaxis de cadena estándar
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100|unique:consultorios,nombre,' . $id,
            'descripcion' => 'nullable|string',
            'estado_id' => 'required|exists:estados,id_estado',
            'ciudad_id' => 'required|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'direccion_detallada' => 'nullable|string',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:150',
            'horario_inicio' => 'nullable', // Flexible validation
            'horario_fin' => 'nullable|after:horario_inicio',
            'especialidades' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $consultorio = Consultorio::findOrFail($id);
        
        // Preparar datos asegurando que los campos vacíos sean null
        $data = $request->except(['_token', '_method', 'status', 'especialidades']); // Excluir status para manejo manual
        
        // Convertir strings vacíos a null explícitamente para llaves foráneas opcionales
        $data['municipio_id'] = $request->input('municipio_id') ?: null;
        $data['parroquia_id'] = $request->input('parroquia_id') ?: null;
        $data['status'] = $request->has('status') ? 1 : 0;
        
        $consultorio->update($data);

        // Sincronizar especialidades
        if ($request->has('especialidades')) {
            $consultorio->especialidades()->sync($request->especialidades);
        } else {
            $consultorio->especialidades()->detach();
        }
 
        return redirect()->route('consultorios.index')->with('success', 'Consultorio actualizado exitosamente');
    }

    public function destroy($id)
    {
        $consultorio = Consultorio::findOrFail($id);
        $consultorio->update(['status' => false]);

        return redirect()->route('consultorios.index')->with('success', 'Consultorio desactivado exitosamente');
    }

    public function medicos($id)
    {
        $consultorio = Consultorio::with('medicos')->findOrFail($id);
        return view('shared.consultorios.medicos', compact('consultorio'));
    }

    public function horarios($id)
    {
        $consultorio = Consultorio::findOrFail($id);
        $medicos = \App\Models\Medico::where('status', true)->get();
        $horarios = \App\Models\MedicoConsultorio::where('consultorio_id', $id)->get();

        return view('shared.consultorios.horarios', compact('consultorio', 'medicos', 'horarios'));
    }

    public function getCiudades($estadoId)
    {
        $ciudades = Ciudad::where('id_estado', $estadoId)->where('status', true)->get();
        return response()->json($ciudades);
    }

    public function getMunicipios($estadoId)
    {
        $municipios = Municipio::where('id_estado', $estadoId)->where('status', true)->get();
        return response()->json($municipios);
    }

    public function getParroquias($municipioId)
    {
        $parroquias = Parroquia::where('id_municipio', $municipioId)->where('status', true)->get();
        return response()->json($parroquias);
    }
}
