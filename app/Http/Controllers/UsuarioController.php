<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        // Estadísticas
        $stats = [
            'total' => Usuario::count(),
            'activos' => Usuario::where('status', true)->count(),
            'medicos' => Usuario::where('rol_id', 2)->count(),
            'pacientes' => Usuario::where('rol_id', 3)->count(),
        ];

        // Query Base
        $query = Usuario::with(['rol', 'administrador', 'medico', 'paciente']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('correo', 'like', "%{$search}%")
                  ->orWhereHas('administrador', function($q) use ($search) {
                      $q->where('primer_nombre', 'like', "%{$search}%")
                        ->orWhere('primer_apellido', 'like', "%{$search}%")
                        ->orWhere('numero_documento', 'like', "%{$search}%");
                  })
                  ->orWhereHas('medico', function($q) use ($search) {
                      $q->where('primer_nombre', 'like', "%{$search}%")
                        ->orWhere('primer_apellido', 'like', "%{$search}%")
                        ->orWhere('numero_documento', 'like', "%{$search}%");
                  })
                  ->orWhereHas('paciente', function($q) use ($search) {
                      $q->where('primer_nombre', 'like', "%{$search}%")
                        ->orWhere('primer_apellido', 'like', "%{$search}%")
                        ->orWhere('numero_documento', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('rol')) {
            if ($request->rol == 'admin') $query->where('rol_id', 1);
            if ($request->rol == 'medico') $query->where('rol_id', 2);
            if ($request->rol == 'paciente') $query->where('rol_id', 3);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('shared.usuarios.index', compact('usuarios', 'stats'));
    }

    public function create()
    {
        $roles = Role::where('status', true)->get();
        $estados = \App\Models\Estado::all(); // Cargar estados para el formulario
        return view('shared.usuarios.create', compact('roles', 'estados'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'correo' => 'required|email|unique:usuarios,correo|max:150',
            'password' => 'required|min:8|confirmed',
            'status' => 'boolean',
            // Profile fields
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'cedula' => 'nullable', // Removed requirement as we use split fields
            'tipo_documento' => 'required|string|in:V,E,J,P',
            'numero_documento' => 'required|string|max:20',
            'prefijo_tlf' => 'nullable|string|in:+58,+57,+1,+34',
            'telefono' => 'nullable|string|max:20',
            'genero' => 'nullable|string|in:masculino,femenino',
            'fecha_nacimiento' => 'nullable|date',
            // Location fields
            'estado_id' => 'nullable|exists:estados,id_estado',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'direccion_detallada' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                // 1. Create User
                $usuario = Usuario::create([
                    'rol_id' => $request->rol_id,
                    'correo' => $request->correo,
                    'password' => $request->password,
                    'status' => $request->status ?? true
                ]);

                // 2. Prepare Profile Data
                $profileData = [
                    'user_id' => $usuario->id,
                    'primer_nombre' => $request->primer_nombre,
                    'segundo_nombre' => $request->segundo_nombre,
                    'primer_apellido' => $request->primer_apellido,
                    'segundo_apellido' => $request->segundo_apellido,
                    'tipo_documento' => $request->tipo_documento,
                    'numero_documento' => $request->numero_documento,
                    'prefijo_tlf' => $request->prefijo_tlf,
                    'numero_tlf' => $request->telefono,
                    'genero' => $request->genero,
                    'fecha_nac' => $request->fecha_nacimiento,
                    'estado_id' => $request->estado_id,
                    'municipio_id' => $request->municipio_id,
                    'parroquia_id' => $request->parroquia_id,
                    'ciudad_id' => $request->ciudad_id,
                    'direccion_detallada' => $request->direccion_detallada,
                    'status' => true
                ];

                // 4. Create Specific Profile based on Role
                switch ($request->rol_id) {
                    case 1: // Administrador
                        \App\Models\Administrador::create($profileData);
                        break;
                    case 2: // Medico
                        \App\Models\Medico::create($profileData);
                        break;
                    case 3: // Paciente
                        \App\Models\Paciente::create($profileData);
                        break;
                }
            });



            // Redirección especial para Médicos si se solicitó configurar horario
            if ($request->rol_id == 2 && $request->has('configurar_horario')) {
                // Recuperar el médico recién creado. 
                // Buscamos por correo ya que es único, para obtener el ID del usuario y luego su relación medico
                $usuario = Usuario::where('correo', $request->correo)->first();
                if ($usuario && $usuario->medico) {
                    return redirect()->route('medicos.horarios', $usuario->medico->id)
                                   ->with('success', 'Médico creado. Configure los horarios de atención.');
                }
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente con su perfil.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el usuario: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $usuario = Usuario::with([
            'rol',
            'administrador.estado', 'administrador.ciudad', 'administrador.municipio', 'administrador.parroquia',
            'medico.estado', 'medico.ciudad', 'medico.municipio', 'medico.parroquia', 'medico.especialidad', 'medico.consultorio',
            'paciente.estado', 'paciente.ciudad', 'paciente.municipio', 'paciente.parroquia',
        ])->findOrFail($id);
        
        return view('shared.usuarios.show', compact('usuario'));
    }

    public function edit($id)
    {
        $usuario = Usuario::with(['administrador', 'medico', 'paciente'])->findOrFail($id);
        $roles = Role::where('status', true)->get();
        $estados = \App\Models\Estado::all();
        
        return view('shared.usuarios.edit', compact('usuario', 'roles', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validator = Validator::make($request->all(), [
            // 'correo' validation removed to prevent updates
            'password' => 'nullable|min:8|confirmed',
            'status' => 'boolean',
            // Profile fields
            'primer_nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'segundo_nombre' => 'nullable|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'primer_apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'segundo_apellido' => 'nullable|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'tipo_documento' => 'required|string|in:V,E,J,P',
            'numero_documento' => 'required|string|max:20',
            'prefijo_tlf' => 'nullable|string|in:+58,+57,+1,+34',
            'telefono' => 'nullable|string|max:20',
            'genero' => 'nullable|string|in:masculino,femenino',
            'fecha_nacimiento' => 'nullable|date',
            // Location fields
            'estado_id' => 'nullable|exists:estados,id_estado',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'direccion_detallada' => 'nullable|string',
            // Medico fields
            'nro_colegiatura' => 'nullable|string|max:50',
            'formacion_academica' => 'nullable|string',
            'experiencia_profesional' => 'nullable|string',
            // Paciente fields
            'ocupacion' => 'nullable|string|max:150',
            'estado_civil' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $usuario) {
                // 1. Update User Credentials (ONLY Password and Status)
                $userData = [
                    'status' => $request->status ?? $usuario->status
                ];
                
                // Ignore email update requests
                
                if ($request->filled('password')) {
                    $userData['password'] = $request->password; // Mutator will handle hashing
                }

                $usuario->update($userData);

                // 2. Prepare Profile Data
                $profileData = [
                    'primer_nombre' => $request->primer_nombre,
                    'segundo_nombre' => $request->segundo_nombre,
                    'primer_apellido' => $request->primer_apellido,
                    'segundo_apellido' => $request->segundo_apellido,
                    'tipo_documento' => $request->tipo_documento,
                    'numero_documento' => $request->numero_documento,
                    'prefijo_tlf' => $request->prefijo_tlf,
                    'numero_tlf' => $request->telefono,
                    'genero' => $request->genero,
                    'fecha_nac' => $request->fecha_nacimiento,
                    'estado_id' => $request->estado_id,
                    'municipio_id' => $request->municipio_id,
                    'parroquia_id' => $request->parroquia_id,
                    'ciudad_id' => $request->ciudad_id,
                    'direccion_detallada' => $request->direccion_detallada,
                ];

                // 3. Update Specific Profile
                if ($usuario->administrador) {
                    $usuario->administrador->update($profileData);
                } elseif ($usuario->medico) {
                    $profileData['nro_colegiatura'] = $request->nro_colegiatura;
                    $profileData['formacion_academica'] = $request->formacion_academica;
                    $profileData['experiencia_profesional'] = $request->experiencia_profesional;
                    $usuario->medico->update($profileData);
                } elseif ($usuario->paciente) {
                    $profileData['ocupacion'] = $request->ocupacion;
                    $profileData['estado_civil'] = $request->estado_civil;
                    $usuario->paciente->update($profileData);
                }
            });

            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el usuario: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        // Toggle status instead of straight delete for this logic
        $usuario->update(['status' => !$usuario->status]);

        return redirect()->route('usuarios.index')->with('success', 'Estado del usuario actualizado exitosamente');
    }

    public function cambiarPassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password_actual' => 'required',
            'nuevo_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $usuario = Usuario::findOrFail($id);

        // Verificar contraseña actual
        if (md5(md5($request->password_actual)) !== $usuario->password) {
            return redirect()->back()->with('error', 'La contraseña actual es incorrecta');
        }

        // Actualizar contraseña
        $usuario->update(['password' => $request->nuevo_password]);

        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente');
    }
}
