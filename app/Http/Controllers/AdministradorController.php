<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Usuario;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministradorController extends Controller
{
    public function dashboard()
    {
        $estadisticas = [
            'total_medicos' => \App\Models\Medico::where('status', true)->count(),
            'total_pacientes' => \App\Models\Paciente::where('status', true)->count(),
            'citas_hoy' => \App\Models\Cita::whereDate('fecha_cita', today())->count(),
            'ingresos_mes' => \App\Models\FacturaPaciente::whereMonth('fecha_emision', now()->month)->sum('monto_usd')
        ];

        return view('admin.dashboard', compact('estadisticas'));
    }

    public function index(Request $request)
    {
        $query = Administrador::with(['usuario', 'estado', 'ciudad']);

        // Filtro por búsqueda (Nombre, Apellido, Documento, Correo)
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('primer_nombre', 'like', "%{$search}%")
                  ->orWhere('primer_apellido', 'like', "%{$search}%")
                  ->orWhere('numero_documento', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function($u) use ($search) {
                      $u->where('correo', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $administradores = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.administradores.index', compact('administradores'));
    }

    public function create()
    {
        $usuarios = Usuario::where('status', true)->whereNotIn('id', function($query) {
            $query->select('user_id')->from('administradores')->whereNotNull('user_id');
        })->get();

        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();
        
        return view('admin.administradores.create', compact('usuarios', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:usuarios,id|unique:administradores,user_id',
            'primer_nombre' => 'required|max:100',
            'primer_apellido' => 'required|max:100',
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
            'tipo_admin' => 'required|in:Administrador,Root'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Administrador::create($request->all());

        return redirect()->route('admin.administradores.index')->with('success', 'Administrador creado exitosamente');
    }

    public function show($id)
    {
        $administrador = Administrador::with(['usuario', 'estado', 'ciudad', 'municipio', 'parroquia'])->findOrFail($id);
        return view('admin.administradores.show', compact('administrador'));
    }

    public function edit($id)
    {
        $administrador = Administrador::findOrFail($id);
        $usuarios = Usuario::where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();

        return view('admin.administradores.edit', compact('administrador', 'usuarios', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:usuarios,id|unique:administradores,user_id,' . $id,
            'primer_nombre' => 'required|max:100',
            'primer_apellido' => 'required|max:100',
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
            'tipo_admin' => 'required|in:Administrador,Root'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $administrador = Administrador::findOrFail($id);
        $administrador->update($request->all());

        return redirect()->route('admin.administradores.index')->with('success', 'Administrador actualizado exitosamente');
    }

    public function destroy($id)
    {
        $administrador = Administrador::findOrFail($id);
        $administrador->update(['status' => false]);

        return redirect()->route('admin.administradores.index')->with('success', 'Administrador desactivado exitosamente');
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
