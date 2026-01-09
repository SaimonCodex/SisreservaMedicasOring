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
    public function index()
    {
        $consultorios = Consultorio::with(['estado', 'ciudad', 'especialidades'])->where('status', true)->get();
        return view('shared.consultorios.index', compact('consultorios'));
    }

    public function create()
    {
        $especialidades = Especialidad::where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        return view('shared.consultorios.create', compact('especialidades', 'estados'));
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
            'especialidades' => 'nullable|array',
            'especialidades.*' => 'exists:especialidades,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $consultorio = Consultorio::create($request->except('especialidades'));
        
        // Asignar especialidades
        if ($request->has('especialidades')) {
            $consultorio->especialidades()->attach($request->especialidades);
        }

        return redirect()->route('consultorios.index')->with('success', 'Consultorio creado exitosamente');
    }

    public function show($id)
    {
        $consultorio = Consultorio::with(['estado', 'ciudad', 'municipio', 'parroquia', 'especialidades', 'medicos'])->findOrFail($id);
        return view('shared.consultorios.show', compact('consultorio'));
    }

    public function edit($id)
    {
        $consultorio = Consultorio::findOrFail($id);
        $especialidades = Especialidad::where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();

        return view('shared.consultorios.edit', compact('consultorio', 'especialidades', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    public function update(Request $request, $id)
    {
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
            'horario_inicio' => 'nullable|date_format:H:i',
            'horario_fin' => 'nullable|date_format:H:i|after:horario_inicio',
            'especialidades' => 'nullable|array',
            'especialidades.*' => 'exists:especialidades,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $consultorio = Consultorio::findOrFail($id);
        $consultorio->update($request->except('especialidades'));
        
        // Sincronizar especialidades
        if ($request->has('especialidades')) {
            $consultorio->especialidades()->sync($request->especialidades);
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
