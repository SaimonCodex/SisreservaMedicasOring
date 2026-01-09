<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UbicacionController extends Controller
{
    // Estados
    public function indexEstados()
    {
        $estados = Estado::where('status', true)->paginate(10);
        return view('shared.ubicacion.estados.index', compact('estados'));
    }

    public function createEstado()
    {
        return view('shared.ubicacion.estados.create');
    }

    public function storeEstado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|max:250|unique:estados,estado',
            'iso_3166_2' => 'nullable|max:4'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Estado::create($request->all());

        return redirect()->route('ubicacion.estados.index')->with('success', 'Estado creado exitosamente');
    }

    public function editEstado($id)
    {
        $estado = Estado::findOrFail($id);
        return view('shared.ubicacion.estados.edit', compact('estado'));
    }

    public function updateEstado(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|max:250|unique:estados,estado,' . $id . ',id_estado',
            'iso_3166_2' => 'nullable|max:4'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $estado = Estado::findOrFail($id);
        $estado->update($request->all());

        return redirect()->route('ubicacion.estados.index')->with('success', 'Estado actualizado exitosamente');
    }

    public function destroyEstado($id)
    {
        $estado = Estado::findOrFail($id);
        $estado->update(['status' => false]);

        return redirect()->route('ubicacion.estados.index')->with('success', 'Estado desactivado exitosamente');
    }

    // Ciudades
    public function indexCiudades($estadoId)
    {
        $estado = Estado::findOrFail($estadoId);
        $ciudades = Ciudad::where('id_estado', $estadoId)->where('status', true)->paginate(10);
        return view('shared.ubicacion.ciudades.index', compact('ciudades', 'estado'));
    }

    public function createCiudad($estadoId)
    {
        $estado = Estado::findOrFail($estadoId);
        return view('shared.ubicacion.ciudades.create', compact('estado'));
    }

    public function storeCiudad(Request $request, $estadoId)
    {
        $validator = Validator::make($request->all(), [
            'ciudad' => 'required|max:200',
            'capital' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Ciudad::create([
            'id_estado' => $estadoId,
            'ciudad' => $request->ciudad,
            'capital' => $request->capital ?? false
        ]);

        return redirect()->route('ubicacion.ciudades.index', $estadoId)->with('success', 'Ciudad creada exitosamente');
    }

    public function editCiudad($estadoId, $id)
    {
        $estado = Estado::findOrFail($estadoId);
        $ciudad = Ciudad::where('id_estado', $estadoId)->findOrFail($id);
        return view('shared.ubicacion.ciudades.edit', compact('estado', 'ciudad'));
    }

    public function updateCiudad(Request $request, $estadoId, $id)
    {
        $validator = Validator::make($request->all(), [
            'ciudad' => 'required|max:200',
            'capital' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ciudad = Ciudad::where('id_estado', $estadoId)->findOrFail($id);
        $ciudad->update($request->all());

        return redirect()->route('ubicacion.ciudades.index', $estadoId)->with('success', 'Ciudad actualizada exitosamente');
    }

    public function destroyCiudad($estadoId, $id)
    {
        $ciudad = Ciudad::where('id_estado', $estadoId)->findOrFail($id);
        $ciudad->update(['status' => false]);

        return redirect()->route('ubicacion.ciudades.index', $estadoId)->with('success', 'Ciudad desactivada exitosamente');
    }

    // Municipios
    public function indexMunicipios($estadoId)
    {
        $estado = Estado::findOrFail($estadoId);
        $municipios = Municipio::where('id_estado', $estadoId)->where('status', true)->paginate(10);
        return view('shared.ubicacion.municipios.index', compact('municipios', 'estado'));
    }

    public function createMunicipio($estadoId)
    {
        $estado = Estado::findOrFail($estadoId);
        return view('shared.ubicacion.municipios.create', compact('estado'));
    }

    public function storeMunicipio(Request $request, $estadoId)
    {
        $validator = Validator::make($request->all(), [
            'municipio' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Municipio::create([
            'id_estado' => $estadoId,
            'municipio' => $request->municipio
        ]);

        return redirect()->route('ubicacion.municipios.index', $estadoId)->with('success', 'Municipio creado exitosamente');
    }

    public function editMunicipio($estadoId, $id)
    {
        $estado = Estado::findOrFail($estadoId);
        $municipio = Municipio::where('id_estado', $estadoId)->findOrFail($id);
        return view('shared.ubicacion.municipios.edit', compact('estado', 'municipio'));
    }

    public function updateMunicipio(Request $request, $estadoId, $id)
    {
        $validator = Validator::make($request->all(), [
            'municipio' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $municipio = Municipio::where('id_estado', $estadoId)->findOrFail($id);
        $municipio->update($request->all());

        return redirect()->route('ubicacion.municipios.index', $estadoId)->with('success', 'Municipio actualizado exitosamente');
    }

    public function destroyMunicipio($estadoId, $id)
    {
        $municipio = Municipio::where('id_estado', $estadoId)->findOrFail($id);
        $municipio->update(['status' => false]);

        return redirect()->route('ubicacion.municipios.index', $estadoId)->with('success', 'Municipio desactivado exitosamente');
    }

    // Parroquias
    public function indexParroquias($estadoId, $municipioId)
    {
        $estado = Estado::findOrFail($estadoId);
        $municipio = Municipio::where('id_estado', $estadoId)->findOrFail($municipioId);
        $parroquias = Parroquia::where('id_municipio', $municipioId)->where('status', true)->paginate(10);
        return view('shared.ubicacion.parroquias.index', compact('parroquias', 'estado', 'municipio'));
    }

    public function createParroquia($estadoId, $municipioId)
    {
        $estado = Estado::findOrFail($estadoId);
        $municipio = Municipio::where('id_estado', $estadoId)->findOrFail($municipioId);
        return view('shared.ubicacion.parroquias.create', compact('estado', 'municipio'));
    }

    public function storeParroquia(Request $request, $estadoId, $municipioId)
    {
        $validator = Validator::make($request->all(), [
            'parroquia' => 'required|max:250'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Parroquia::create([
            'id_municipio' => $municipioId,
            'parroquia' => $request->parroquia
        ]);

        return redirect()->route('ubicacion.parroquias.index', [$estadoId, $municipioId])->with('success', 'Parroquia creada exitosamente');
    }

    public function editParroquia($estadoId, $municipioId, $id)
    {
        $estado = Estado::findOrFail($estadoId);
        $municipio = Municipio::where('id_estado', $estadoId)->findOrFail($municipioId);
        $parroquia = Parroquia::where('id_municipio', $municipioId)->findOrFail($id);
        return view('shared.ubicacion.parroquias.edit', compact('estado', 'municipio', 'parroquia'));
    }

    public function updateParroquia(Request $request, $estadoId, $municipioId, $id)
    {
        $validator = Validator::make($request->all(), [
            'parroquia' => 'required|max:250'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $parroquia = Parroquia::where('id_municipio', $municipioId)->findOrFail($id);
        $parroquia->update($request->all());

        return redirect()->route('ubicacion.parroquias.index', [$estadoId, $municipioId])->with('success', 'Parroquia actualizada exitosamente');
    }

    public function destroyParroquia($estadoId, $municipioId, $id)
    {
        $parroquia = Parroquia::where('id_municipio', $municipioId)->findOrFail($id);
        $parroquia->update(['status' => false]);

        return redirect()->route('ubicacion.parroquias.index', [$estadoId, $municipioId])->with('success', 'Parroquia desactivada exitosamente');
    }

    // Métodos para obtener datos via AJAX
    public function getCiudadesByEstado($estadoId)
    {
        $ciudades = Ciudad::where('id_estado', $estadoId)->where('status', true)->get();
        return response()->json($ciudades);
    }

    public function getMunicipiosByEstado($estadoId)
    {
        $municipios = Municipio::where('id_estado', $estadoId)->where('status', true)->get();
        return response()->json($municipios);
    }

    public function getParroquiasByMunicipio($municipioId)
    {
        $parroquias = Parroquia::where('id_municipio', $municipioId)->where('status', true)->get();
        return response()->json($parroquias);
    }
}
