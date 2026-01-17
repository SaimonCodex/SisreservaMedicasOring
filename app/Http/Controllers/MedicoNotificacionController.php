<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicoNotificacionController extends Controller
{
    /**
     * Display a listing of all notifications for the authenticated doctor.
     */
    public function index(Request $request)
    {
        $medico = Auth::user()->medico;
        
        if (!$medico) {
            abort(403, 'Acceso no autorizado');
        }

        $query = $medico->notifications();

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('type', 'like', '%' . $request->tipo . '%');
        }

        // Filtro por estado (leída/no leída)
        if ($request->filled('estado')) {
            if ($request->estado === 'no_leidas') {
                $query->whereNull('read_at');
            } elseif ($request->estado === 'leidas') {
                $query->whereNotNull('read_at');
            }
        }

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('medico.notificaciones.index', compact('notificaciones'));
    }

    /**
     * Get unread notifications for AJAX requests.
     */
    public function getUnread()
    {
        $medico = Auth::user()->medico;
        
        if (!$medico) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $notificaciones = $medico->unreadNotifications()
                                 ->orderBy('created_at', 'desc')
                                 ->limit(10)
                                 ->get();

        $count = $medico->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'notificaciones' => $notificaciones,
            'count' => $count
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $medico = Auth::user()->medico;
        
        if (!$medico) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $notification = $medico->notifications()->find($id);

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notificación no encontrada'], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true, 'message' => 'Notificación marcada como leída']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $medico = Auth::user()->medico;
        
        if (!$medico) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $medico->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Todas las notificaciones marcadas como leídas']);
    }
}
