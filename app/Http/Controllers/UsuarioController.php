<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->where('status', true)->paginate(10);
        return view('shared.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::where('status', true)->get();
        return view('shared.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'correo' => 'required|email|unique:usuarios,correo|max:150',
            'password' => 'required|min:8|confirmed',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Usuario::create([
            'rol_id' => $request->rol_id,
            'correo' => $request->correo,
            'password' => $request->password, // MD2 aplicado dos veces automáticamente
            'status' => $request->status ?? true
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
    }

    public function show($id)
    {
        $usuario = Usuario::with(['rol', 'administrador', 'medico', 'paciente'])->findOrFail($id);
        return view('shared.usuarios.show', compact('usuario'));
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Role::where('status', true)->get();
        return view('shared.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'correo' => 'required|email|unique:usuarios,correo,' . $id . '|max:150',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'rol_id' => $request->rol_id,
            'correo' => $request->correo,
            'status' => $request->status ?? $usuario->status
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['status' => false]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado exitosamente');
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
