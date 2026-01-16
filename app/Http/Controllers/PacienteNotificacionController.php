<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacienteNotificacionController extends Controller
{
    /**
     * Obtener todas las notificaciones del paciente autenticado
     */
    public function index()
    {
        $notificaciones = Auth::user()->paciente->notifications()->paginate(15);
        
        return view('paciente.notificaciones.index', compact('notificaciones'));
    }

    /**
     * Marcar una notificación como leída
     */
    public function marcarComoLeida($id)
    {
        $notificacion = Auth::user()->paciente->notifications()->findOrFail($id);
        $notificacion->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasComoLeidas()
    {
        Auth::user()->paciente->unreadNotifications->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    /**
     * Obtener el conteo de notificaciones no leídas (AJAX)
     */
    public function conteoNoLeidas()
    {
        $conteo = Auth::user()->paciente->unreadNotifications->count();
        
        return response()->json(['count' => $conteo]);
    }

    /**
     * Obtener las últimas notificaciones no leídas para el dropdown (AJAX)
     */
    public function recientes()
    {
        $notificaciones = Auth::user()->paciente
            ->unreadNotifications()
            ->take(5)
            ->get();

        return response()->json(['notificaciones' => $notificaciones]);
    }
}
