<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspecialidadController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && $user->administrador && $user->administrador->tipo_admin !== 'Root') {
                $restrictedActions = ['create', 'store', 'edit', 'update', 'destroy'];
                if (in_array($request->route()->getActionMethod(), $restrictedActions)) {
                    abort(403, 'Los administradores locales solo tienen permiso de lectura en esta sección.');
                }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $isLocalAdmin = $user && $user->administrador && $user->administrador->tipo_admin !== 'Root';
        $consultorioIds = [];

        if ($isLocalAdmin) {
            $consultorioIds = $user->administrador->consultorios()->pluck('consultorios.id');
        }

        // Estadísticas Generales (Filtradas si es admin local)
        $especialidadesQuery = Especialidad::query();
        $medicosQuery = \App\Models\Medico::query();
        $citasQuery = \App\Models\Cita::whereMonth('fecha_cita', now()->month)
                                    ->whereYear('fecha_cita', now()->year);

        if ($isLocalAdmin) {
            $especialidadesQuery->whereHas('consultorios', function($q) use ($consultorioIds) {
                $q->whereIn('consultorios.id', $consultorioIds);
            });
            
            // Filtrar médicos que trabajan en esos consultorios
            $medicosQuery->whereHas('consultorios', function($q) use ($consultorioIds) {
                $q->whereIn('consultorios.id', $consultorioIds);
            });

            // Filtrar citas en esos consultorios
            $citasQuery->whereIn('consultorio_id', $consultorioIds);
        }

        $totalEspecialidades = $especialidadesQuery->count();
        $especialidadesActivas = $especialidadesQuery->where('status', true)->count();
        $totalMedicos = $medicosQuery->count();
        $citasMes = $citasQuery->count();

        $query = Especialidad::withCount('medicos');

        // Aplicar filtro principal
        if ($isLocalAdmin) {
            $query->whereHas('consultorios', function($q) use ($consultorioIds) {
                $q->whereIn('consultorios.id', $consultorioIds);
            });
        }

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $especialidades = $query->get();
        return view('shared.especialidades.index', compact('especialidades', 'totalEspecialidades', 'especialidadesActivas', 'totalMedicos', 'citasMes'));
    }

    public function create()
    {
        return view('shared.especialidades.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100|unique:especialidades,nombre',
            'codigo' => 'nullable|max:50',
            'descripcion' => 'required|string',
            'duracion_cita_default' => 'required|integer|min:15|max:120',
            'color' => 'nullable|string|max:50',
            'icono' => 'nullable|string|max:50',
            'prioridad' => 'required|integer',
            'requisitos' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Especialidad::create($request->all());

        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada exitosamente');
    }

    public function show($id)
    {
        $especialidad = Especialidad::with(['medicos', 'consultorios'])
            ->withCount([
                'citas as total_citas',
                'citas as citas_pendientes' => function ($query) {
                    $query->whereIn('estado_cita', ['programada', 'confirmada', 'pendiente']);
                }
            ])
            ->findOrFail($id);
            
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
            'codigo' => 'nullable|max:50',
            'descripcion' => 'required|string',
            'duracion_cita_default' => 'required|integer|min:15|max:120',
            'color' => 'nullable|string|max:50',
            'icono' => 'nullable|string|max:50',
            'prioridad' => 'required|integer',
            'requisitos' => 'nullable|string',
            'observaciones' => 'nullable|string',
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
