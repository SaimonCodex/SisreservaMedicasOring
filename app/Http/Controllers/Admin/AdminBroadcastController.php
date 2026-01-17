<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminBroadcastController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->administrador || $user->administrador->tipo_admin !== 'Root') {
                abort(403, 'Solo el Administrador Root puede enviar mensajes broadcast.');
            }
            return $next($request);
        });
    }

    public function create()
    {
        $adminLocales = Administrador::where('tipo_admin', 'Administrador')
            ->where('status', true)
            ->with('usuario')
            ->get();

        return view('admin.broadcast.create', compact('adminLocales'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string|max:1000',
            'prioridad' => 'required|in:normal,alta',
            'destinatarios' => 'required|in:todos,seleccionados',
            'admin_ids' => 'required_if:destinatarios,seleccionados|array',
            'admin_ids.*' => 'exists:administradores,id'
        ], [
            'titulo.required' => 'El título es obligatorio',
            'mensaje.required' => 'El mensaje es obligatorio',
            'admin_ids.required_if' => 'Debes seleccionar al menos un administrador'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $remitente = auth()->user()->administrador->primer_nombre . ' ' . auth()->user()->administrador->primer_apellido;

            // Determinar destinatarios
            if ($request->destinatarios === 'todos') {
                $admins = Administrador::where('status', true)->get();
            } else {
                $admins = Administrador::whereIn('id', $request->admin_ids)
                    ->where('status', true)
                    ->get();
            }

            // Enviar notificación a cada administrador
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\Admin\BroadcastMensaje(
                    $request->titulo,
                    $request->mensaje,
                    $request->prioridad,
                    $remitente
                ));
            }

            $count = $admins->count();
            return redirect()->route('admin.dashboard')
                ->with('success', "Mensaje broadcast enviado exitosamente a {$count} administrador(es).");

        } catch (\Exception $e) {
            \Log::error('Error enviando broadcast: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al enviar el mensaje: ' . $e->getMessage())
                ->withInput();
        }
    }
}
