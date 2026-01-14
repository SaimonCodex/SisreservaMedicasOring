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
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && $user->administrador && $user->administrador->tipo_admin !== 'Root') {
                // Métodos restringidos por patrón de nombre
                $restrictedPrefixes = ['create', 'store', 'edit', 'update', 'destroy'];
                $method = $request->route()->getActionMethod();
                
                foreach ($restrictedPrefixes as $prefix) {
                    if (str_starts_with($method, $prefix)) {
                        abort(403, 'Los administradores locales solo tienen permiso de lectura en esta sección.');
                    }
                }
            }
            return $next($request);
        });
    }

    // Estados
    public function indexEstados(Request $request)
    {
        $query = Estado::query()->withCount(['ciudades', 'municipios']);

        if ($request->has('search') && $request->search) {
            $query->where('estado', 'like', '%' . $request->search . '%')
                  ->orWhere('iso_3166_2', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        } else {
             // Default to showing all or just active? 
             // The previous code showed active only. 
             // User interface defaults 'Todos' to value="" which means no filter.
             // If no filter, show all? Or show active?
             // Usually index shows all or paginated. 
             // But existing code had `where('status', true)`.
             // I will remove the hardcoded status=true default if they want to manage inactive ones.
             // But to be consistent with "soft delete" style, often index shows active.
             // However, the UI has a filter for "Inactivos". So I should allow showing them.
             // So if no status is provided, I will NOT filter by status.
        }

        $data = $query->paginate(10);
        
        $stats = [
            'total' => Estado::count(),
            'activos' => Estado::where('status', true)->count(),
            'ciudades' => Ciudad::count(),
            'municipios' => Municipio::count()
        ];
        
        $activeTab = 'estados';

        return view('shared.ubicacion.index', compact('data', 'activeTab', 'stats'));
    }

    public function createEstado()
    {
        return view('shared.ubicacion.estados.create');
    }

    public function storeEstado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|max:250|unique:estados,estado',
            'iso_3166_2' => 'nullable|max:4',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Estado::create([
            'estado' => $request->estado,
            'iso_3166_2' => $request->iso_3166_2,
            'status' => $request->status ?? true
        ]);

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
            'iso_3166_2' => 'nullable|max:4',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $estado = Estado::findOrFail($id);
        $estado->update([
            'estado' => $request->estado,
            'iso_3166_2' => $request->iso_3166_2,
            'status' => $request->status
        ]);

        return redirect()->route('ubicacion.estados.index')->with('success', 'Estado actualizado exitosamente');
    }

    public function destroyEstado($id)
    {
        $estado = Estado::findOrFail($id);
        $estado->update(['status' => false]);

        return redirect()->route('ubicacion.estados.index')->with('success', 'Estado desactivado exitosamente');
    }

    // Ciudades
    public function indexCiudades(Request $request)
    {
        $query = Ciudad::query()->with('estado');

        if ($request->has('search') && $request->search) {
            $query->where('ciudad', 'like', '%' . $request->search . '%');
        }

        if ($request->has('estado_id') && $request->estado_id) {
            $query->where('id_estado', $request->estado_id);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(10);
        $estados = Estado::where('status', true)->get();
        
        $stats = [
            'total' => Ciudad::count(),
            'activos' => Ciudad::where('status', true)->count(),
            'estados' => Estado::has('ciudades')->count(),
            'municipios' => Municipio::count()
        ];
        
        $activeTab = 'ciudades';

        return view('shared.ubicacion.index', compact('data', 'estados', 'activeTab', 'stats'));
    }

    public function createCiudad()
    {
        $estados = Estado::where('status', true)->get();
        return view('shared.ubicacion.ciudades.create', compact('estados'));
    }

    public function storeCiudad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_estado' => 'required|exists:estados,id_estado',
            'ciudad' => 'required|max:200|unique:ciudades,ciudad,NULL,id_ciudad,id_estado,' . $request->id_estado,
            'capital' => 'boolean',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Ciudad::create([
            'id_estado' => $request->id_estado,
            'ciudad' => $request->ciudad,
            'capital' => $request->capital ?? false,
            'status' => $request->status ?? true
        ]);

        return redirect()->route('ubicacion.ciudades.index')->with('success', 'Ciudad creada exitosamente');
    }

    public function editCiudad($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $estados = Estado::where('status', true)->get();
        return view('shared.ubicacion.ciudades.edit', compact('ciudad', 'estados'));
    }

    public function updateCiudad(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_estado' => 'required|exists:estados,id_estado',
            'ciudad' => 'required|max:200|unique:ciudades,ciudad,' . $id . ',id_ciudad,id_estado,' . $request->id_estado,
            'capital' => 'boolean',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ciudad = Ciudad::findOrFail($id);
        $ciudad->update([
            'id_estado' => $request->id_estado,
            'ciudad' => $request->ciudad,
            'capital' => $request->capital ?? false,
            'status' => $request->status
        ]);

        return redirect()->route('ubicacion.ciudades.index')->with('success', 'Ciudad actualizada exitosamente');
    }

    public function destroyCiudad($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $ciudad->update(['status' => false]); // Soft delete logic (deactivation)

        return redirect()->route('ubicacion.ciudades.index')->with('success', 'Ciudad desactivada exitosamente');
    }

    // Municipios
    public function indexMunicipios(Request $request)
    {
        $query = Municipio::query()->with(['estado'])->withCount('parroquias');

        if ($request->has('search') && $request->search) {
            $query->where('municipio', 'like', '%' . $request->search . '%');
        }

        if ($request->has('estado_id') && $request->estado_id) {
            $query->where('id_estado', $request->estado_id);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(10);
        $estados = Estado::where('status', true)->get();
        
        $stats = [
            'total' => Municipio::count(),
            'activos' => Municipio::where('status', true)->count(),
            'estados' => Estado::has('municipios')->count(),
            'parroquias' => Parroquia::count()
        ];
        
        $activeTab = 'municipios';

        return view('shared.ubicacion.index', compact('data', 'estados', 'activeTab', 'stats'));
    }

    public function createMunicipio()
    {
        $estados = Estado::where('status', true)->get();
        return view('shared.ubicacion.municipios.create', compact('estados'));
    }

    public function storeMunicipio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_estado' => 'required|exists:estados,id_estado',
            'municipio' => 'required|max:100|unique:municipios,municipio,NULL,id_municipio,id_estado,' . $request->id_estado,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Municipio::create([
            'id_estado' => $request->id_estado,
            'municipio' => $request->municipio,
            'status' => $request->status ?? true
        ]);

        return redirect()->route('ubicacion.municipios.index')->with('success', 'Municipio creado exitosamente');
    }

    public function editMunicipio($id)
    {
        $municipio = Municipio::withCount('parroquias')->findOrFail($id);
        $estados = Estado::where('status', true)->get();
        return view('shared.ubicacion.municipios.edit', compact('municipio', 'estados'));
    }

    public function updateMunicipio(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_estado' => 'required|exists:estados,id_estado',
            'municipio' => 'required|max:100|unique:municipios,municipio,' . $id . ',id_municipio,id_estado,' . $request->id_estado,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $municipio = Municipio::findOrFail($id);
        $municipio->update([
            'id_estado' => $request->id_estado,
            'municipio' => $request->municipio,
            'status' => $request->status
        ]);

        return redirect()->route('ubicacion.municipios.index')->with('success', 'Municipio actualizado exitosamente');
    }

    public function destroyMunicipio($id)
    {
        $municipio = Municipio::findOrFail($id);
        $municipio->update(['status' => false]);

        return redirect()->route('ubicacion.municipios.index')->with('success', 'Municipio desactivado exitosamente');
    }

    // Parroquias
    public function indexParroquias(Request $request)
    {
        $query = Parroquia::query()->with(['municipio.estado']);

        if ($request->has('search') && $request->search) {
            $query->where('parroquia', 'like', '%' . $request->search . '%');
        }

        if ($request->has('municipio_id') && $request->municipio_id) {
            $query->where('id_municipio', $request->municipio_id);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(10);
        $municipios = Municipio::where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        
        $stats = [
            'total' => Parroquia::count(),
            'activos' => Parroquia::where('status', true)->count(),
            'municipios' => Municipio::has('parroquias')->count()
        ];
        
        $activeTab = 'parroquias';

        return view('shared.ubicacion.index', compact('data', 'municipios', 'estados', 'activeTab', 'stats'));
    }

    public function createParroquia()
    {
        $estados = Estado::where('status', true)->get();
        // Municipios load via AJAX usually, or pass empty.
        $municipios = [];
        return view('shared.ubicacion.parroquias.create', compact('estados', 'municipios'));
    }

    public function storeParroquia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_municipio' => 'required|exists:municipios,id_municipio',
            'parroquia' => 'required|max:250|unique:parroquias,parroquia,NULL,id_parroquia,id_municipio,' . $request->id_municipio,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Parroquia::create([
            'id_municipio' => $request->id_municipio,
            'parroquia' => $request->parroquia,
            'status' => $request->status ?? true
        ]);

        return redirect()->route('ubicacion.parroquias.index')->with('success', 'Parroquia creada exitosamente');
    }

    public function editParroquia($id)
    {
        $parroquia = Parroquia::with('municipio.estado')->findOrFail($id);
        $estados = Estado::where('status', true)->get();
        $municipios = Municipio::where('id_estado', $parroquia->municipio->id_estado)->where('status', true)->get();
        return view('shared.ubicacion.parroquias.edit', compact('parroquia', 'estados', 'municipios'));
    }

    public function updateParroquia(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_municipio' => 'required|exists:municipios,id_municipio',
            'parroquia' => 'required|max:250|unique:parroquias,parroquia,' . $id . ',id_parroquia,id_municipio,' . $request->id_municipio,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $parroquia = Parroquia::findOrFail($id);
        $parroquia->update([
            'id_municipio' => $request->id_municipio,
            'parroquia' => $request->parroquia,
            'status' => $request->status
        ]);

        return redirect()->route('ubicacion.parroquias.index')->with('success', 'Parroquia actualizada exitosamente');
    }

    public function destroyParroquia($id)
    {
        $parroquia = Parroquia::findOrFail($id);
        $parroquia->update(['status' => false]);

        return redirect()->route('ubicacion.parroquias.index')->with('success', 'Parroquia desactivada exitosamente');
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
