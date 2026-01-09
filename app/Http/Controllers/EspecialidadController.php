<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspecialidadController extends Controller
{
    public function index()
    {
        $especialidades = Especialidad::where('status', true)->get();
        return view('shared.especialidades.index', compact('especialidades'));
    }

    public function create()
    {
        return view('shared.especialidades.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100|unique:especialidades,nombre',
            'descripcion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Especialidad::create($request->all());

        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada exitosamente');
    }

    public function show($id)
    {
        $especialidad = Especialidad::with(['medicos', 'consultorios'])->findOrFail($id);
        return view('shared.especialidades.show', compact('especialidad'));
    }

    public function edit($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        return view('shared.especialidades.edit', compact('especialidad'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100|unique:especialidades,nombre,' . $id,
            'descripcion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $especialidad = Especialidad::findOrFail($id);
        $especialidad->update($request->all());

        return redirect()->route('especialidades.index')->with('success', 'Especialidad actualizada exitosamente');
    }

    public function destroy($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        $especialidad->update(['status' => false]);

        return redirect()->route('especialidades.index')->with('success', 'Especialidad desactivada exitosamente');
    }

    public function medicos($id)
    {
        $especialidad = Especialidad::with('medicos')->findOrFail($id);
        return view('shared.especialidades.medicos', compact('especialidad'));
    }
}
