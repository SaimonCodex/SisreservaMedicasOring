<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user()->administrador;
        
        // Get query parameters for filtering
        $type = $request->get('tipo');
        $status = $request->get('estado');
        $search = $request->get('buscar');
        
        // Build query
        $query = $admin->notifications();
        
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
            'total' => $admin->notifications()->count(),
            'no_leidas' => $admin->unreadNotifications()->count(),
            'leidas' => $admin->notifications()->whereNotNull('read_at')->count(),
        ];
        
        // Get unique types for filter dropdown
        $tipos = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\Administrador')
            ->where('notifiable_id', $admin->id)
            ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.tipo')) as tipo"))
            ->distinct()
            ->pluck('tipo')
            ->filter();
        
        return view('admin.notificaciones.index', compact('notificaciones', 'stats', 'tipos'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->administrador->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        auth()->user()->administrador->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }
    
    public function destroy($id)
    {
        $notification = auth()->user()->administrador->notifications()->findOrFail($id);
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notificación eliminada correctamente');
    }
    
    public function destroyAll(Request $request)
    {
        $admin = auth()->user()->administrador;
        
        if ($request->has('ids')) {
            $admin->notifications()->whereIn('id', $request->ids)->delete();
            return response()->json(['success' => true, 'message' => 'Notificaciones eliminadas']);
        }
        
        return response()->json(['success' => false, 'message' => 'No se seleccionaron notificaciones'], 400);
    }
}
