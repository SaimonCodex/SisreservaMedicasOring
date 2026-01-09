<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Usuario;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Cita;
use App\Models\FacturaPaciente;
use App\Models\OrdenMedica;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdministradorController extends Controller
{
    public function dashboard()
    {
        // 1. Estadísticas Generales
        $medicos = \App\Models\Medico::where('status', true)->count();
        $medicos_activos = \App\Models\Medico::where('status', true)->count(); // Por ahora igual
        $pacientes = \App\Models\Paciente::where('status', true)->count();
        $citas_hoy = Cita::whereDate('fecha_cita', today())->where('status', true)->count();
        
        // 2. Cálculo de Ingresos
        $ingresos_mes = FacturaPaciente::whereMonth('fecha_emision', now()->month)
            ->whereYear('fecha_emision', now()->year)
            ->sum('monto_usd');
            
        $ingresos_mes_anterior = FacturaPaciente::whereMonth('fecha_emision', now()->subMonth()->month)
            ->whereYear('fecha_emision', now()->subMonth()->year)
            ->sum('monto_usd');

        $crecimiento_ingresos = $ingresos_mes_anterior > 0 
            ? round((($ingresos_mes - $ingresos_mes_anterior) / $ingresos_mes_anterior) * 100, 1)
            : 100;

        // 3. Usuarios Activos (Usuarios que han accedido recientemente o están activos)
        $usuarios_activos = Usuario::where('status', true)->count();

        // 4. Estadísticas Detalladas
        $medicos_nuevos_mes = \App\Models\Medico::whereMonth('created_at', now()->month)->count();
        $pacientes_nuevos_semana = \App\Models\Paciente::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $citas_completadas_hoy = Cita::whereDate('fecha_cita', today())
            ->where('estado_cita', 'Completada')
            ->count();

        // 5. Array de estadísticas para la vista
        $stats = [
            'medicos' => $medicos,
            'medicos_activos' => $medicos_activos,
            'medicos_nuevos_mes' => $medicos_nuevos_mes,
            'pacientes' => $pacientes,
            'total_pacientes' => $pacientes,
            'pacientes_nuevos_semana' => $pacientes_nuevos_semana,
            'citas_hoy' => $citas_hoy,
            'citas_completadas_hoy' => $citas_completadas_hoy,
            'ingresos_mes' => $ingresos_mes,
            'crecimiento_ingresos' => $crecimiento_ingresos,
            'usuarios_activos' => $usuarios_activos
        ];

        // 6. Actividad Reciente (Citas recientes y Nuevos pacientes)
        $citasRecientes = Cita::with(['paciente', 'medico'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($cita) {
                return (object)[
                    'tipo_clase' => 'bg-blue-100',
                    'icono' => 'bi-calendar-check',
                    'icono_clase' => 'text-blue-600',
                    'descripcion' => "Nueva cita agendada para " . ($cita->paciente->primer_nombre ?? 'Paciente') . " con Dr. " . ($cita->medico->primer_apellido ?? 'Médico'),
                    'created_at' => $cita->created_at
                ];
            });

        $pacientesRecientes = \App\Models\Paciente::orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($paciente) {
                return (object)[
                    'tipo_clase' => 'bg-emerald-100',
                    'icono' => 'bi-person-plus',
                    'icono_clase' => 'text-emerald-600',
                    'descripcion' => "Nuevo paciente registrado: " . $paciente->primer_nombre . " " . $paciente->primer_apellido,
                    'created_at' => $paciente->created_at
                ];
            });

        $actividadReciente = $citasRecientes->merge($pacientesRecientes)->sortByDesc('created_at')->take(5);

        // 7. Tareas Pendientes
        $tareas = [
            'citas_sin_confirmar' => Cita::where('estado_cita', 'Programada')->where('fecha_cita', '>=', now())->count(),
            // Estimar pagos pendientes (si existiera columna estado, ajustar según modelo real)
            'pagos_pendientes' => Pago::where('status', true)->where('estado', 'Pendiente')->count(),
            // Resultados pendientes (Ordenes de laboratorio sin resultados)
            'resultados_pendientes' => OrdenMedica::where('tipo_orden', 'Laboratorio')
                ->whereNull('resultados')
                ->count()
        ];

        return view('admin.dashboard', compact('stats', 'actividadReciente', 'tareas'));
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
            'tipo_admin' => 'required|in:Administrador,Root',
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
                    'rol_id' => 1, // Administrador
                    'correo' => $request->correo,
                    'password' => $request->password,
                    'status' => $request->has('status') // Checkbox for status
                ]);

                // 2. Create Administrator Profile
                $adminData = $request->except(['correo', 'password', 'password_confirmation', 'status']);
                $adminData['user_id'] = $usuario->id;
                $adminData['status'] = $request->has('status'); // Checkbox for status

                Administrador::create($adminData);
            });

            return redirect()->route('administradores.index')->with('success', 'Administrador creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el administrador: ' . $e->getMessage())->withInput();
        }
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
            'password' => 'nullable|min:8|confirmed'
            // 'user_id' removed as it should not be changed
            // 'tipo_admin' removed or assumed static for now, or add if needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $administrador = Administrador::findOrFail($id);
        
        // Actualizar datos del perfil
        $data = $request->except(['user_id', 'password', 'password_confirmation', 'correo']);
        // Manejar el checkbox de status: si no viene, es false
        $data['status'] = $request->has('status');

        $administrador->update($data);

        // Si se envió contraseña, actualizarla en el usuario
        if ($request->filled('password')) {
            $administrador->usuario->update([
                'password' => $request->password // El mutator se encarga del hash
            ]);
        }

        return redirect()->route('administradores.index')->with('success', 'Administrador actualizado exitosamente');
    }

    public function destroy($id)
    {
        // Maintain destroy for consistency with resource controller, but acts as toggle/deactivate
        // Or better, redirect to toggle function logic.
        // For strict REST, destroy should specifically 'remove' or 'soft delete'.
        // Given the requirement is "toggle", let's make destroy strictly deactivate, 
        // and add a new method for toggle.
        
        $administrador = Administrador::findOrFail($id);
        $administrador->update(['status' => false]);

        return redirect()->route('administradores.index')->with('success', 'Administrador desactivado exitosamente');
    }

    public function toggleStatus($id)
    {
        $administrador = Administrador::findOrFail($id);
        $administrador->status = !$administrador->status;
        $administrador->save();

        $message = $administrador->status ? 'Administrador activado exitosamente' : 'Administrador desactivado exitosamente';
        
        return redirect()->route('administradores.index')->with('success', $message);
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
