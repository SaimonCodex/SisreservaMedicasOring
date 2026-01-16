<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteNotificationController extends Controller
{
    public function index(Request $request)
    {
        $paciente = auth()->user()->paciente;
        
        // Get query parameters for filtering
        $type = $request->get('tipo');
        $status = $request->get('estado');
        $search = $request->get('buscar');
        
        // Build query
        $query = $paciente->notifications();
        
        // Filter by type
        if ($type && $type !== 'todas') {
            $query->where('data->tipo', $type);
        }
        
        // Filter by read/unread status
        if ($status === 'leidas') {
            $query->whereNotNull('read_at');
        } elseif ($status === 'no_leidas') {
            $query->whereNull('read_at');
        }
        
        // Search by title or message
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('data->titulo', 'like', "%{$search}%")
                  ->orWhere('data->mensaje', 'like', "%{$search}%");
            });
        }
        
        // Paginate results
        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get stats
        $stats = [
            'total' => $paciente->notifications()->count(),
            'no_leidas' => $paciente->unreadNotifications()->count(),
            'leidas' => $paciente->notifications()->whereNotNull('read_at')->count(),
        ];
        
        // Get unique types for filter dropdown
        $tipos = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\Paciente')
            ->where('notifiable_id', $paciente->id)
            ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.tipo')) as tipo"))
            ->distinct()
            ->pluck('tipo')
            ->filter();
        
        return view('paciente.notificaciones.index', compact('notificaciones', 'stats', 'tipos'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->paciente->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        auth()->user()->paciente->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }
    
    public function destroy($id)
    {
        $notification = auth()->user()->paciente->notifications()->findOrFail($id);
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notificación eliminada correctamente');
    }
    
    public function destroyAll(Request $request)
    {
        $paciente = auth()->user()->paciente;
        
        if ($request->has('ids')) {
            $paciente->notifications()->whereIn('id', $request->ids)->delete();
            return response()->json(['success' => true, 'message' => 'Notificaciones eliminadas']);
        }
        
        return response()->json(['success' => false, 'message' => 'No se seleccionaron notificaciones'], 400);
    }
}
