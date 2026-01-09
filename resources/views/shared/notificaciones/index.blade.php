@extends('layouts.admin')

@section('title', 'Notificaciones')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Notificaciones</h1>
            <p class="text-gray-600 mt-1">Centro de notificaciones del sistema</p>
        </div>
        <div class="flex gap-2">
            <button onclick="marcarTodasLeidas()" class="btn btn-outline">
                <i class="bi bi-check-all"></i> Marcar todas como leídas
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-bell text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Total</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-envelope-open text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-amber-700">No Leídas</p>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['no_leidas'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-emerald-700">Leídas</p>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['leidas'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-rose-50 to-rose-100 border-rose-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-rose-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-rose-700">Importantes</p>
                    <p class="text-2xl font-bold text-rose-900">{{ $stats['importantes'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <div class="flex items-center gap-3">
            <button class="btn {{ !request('tipo') ? 'btn-primary' : 'btn-outline' }}" onclick="window.location.href='{{ url('index.php/shared/notificaciones') }}'">
                Todas
            </button>
            <button class="btn {{ request('tipo') == 'citas' ? 'btn-primary' : 'btn-outline' }}" onclick="window.location.href='{{ url('index.php/shared/notificaciones?tipo=citas') }}'">
                <i class="bi bi-calendar"></i> Citas
            </button>
            <button class="btn {{ request('tipo') == 'pagos' ? 'btn-primary' : 'btn-outline' }}" onclick="window.location.href='{{ url('index.php/shared/notificaciones?tipo=pagos') }}'">
                <i class="bi bi-cash"></i> Pagos
            </button>
            <button class="btn {{ request('tipo') == 'sistema' ? 'btn-primary' : 'btn-outline' }}" onclick="window.location.href='{{ url('index.php/shared/notificaciones?tipo=sistema') }}'">
                <i class="bi bi-gear"></i> Sistema
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notificaciones ?? [] as $notificacion)
        <div class="card {{ $notificacion->leida ? 'bg-white' : 'bg-blue-50 border-blue-200' }} hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full {{ $notificacion->tipo_clase ?? 'bg-blue-100' }} flex items-center justify-center">
                            <i class="bi {{ $notificacion->icono ?? 'bi-bell' }} {{ $notificacion->icono_clase ?? 'text-blue-600' }} text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $notificacion->titulo ?? 'Notificación' }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $notificacion->mensaje ?? '' }}</p>
                            </div>
                            @if(!$notificacion->leida)
                            <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-xs text-gray-500">
                                <i class="bi bi-clock"></i> {{ isset($notificacion->created_at) ? \Carbon\Carbon::parse($notificacion->created_at)->diffForHumans() : 'Hace unos momentos' }}
                            </span>
                            @if($notificacion->tipo)
                            <span class="badge badge-info">{{ ucfirst($notificacion->tipo) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if(!$notificacion->leida)
                        <button onclick="marcarLeida({{ $notificacion->id }})" class="btn btn-sm btn-outline" title="Marcar como leída">
                            <i class="bi bi-check"></i>
                        </button>
                        @endif
                        @if($notificacion->url)
                        <a href="{{ $notificacion->url }}" class="btn btn-sm btn-outline" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        @endif
                        <button onclick="eliminarNotificacion({{ $notificacion->id }})" class="btn btn-sm btn-outline text-rose-600" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card p-12 text-center">
            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No hay notificaciones</p>
        </div>
        @endforelse
    </div>

    @if(isset($notificaciones) && $notificaciones->hasPages())
    <div class="flex justify-center">
        {{ $notificaciones->links() }}
    </div>
    @endif
</div>

<script>
function marcarLeida(id) {
    // AJAX call to mark as read
    console.log('Marcar como leída:', id);
    location.reload();
}

function marcarTodasLeidas() {
    if(confirm('¿Marcar todas las notificaciones como leídas?')) {
        // AJAX call
        console.log('Marcar todas como leídas');
        location.reload();
    }
}

function eliminarNotificacion(id) {
    if(confirm('¿Eliminar esta notificación?')) {
        // AJAX call
        console.log('Eliminar notificación:', id);
        location.reload();
    }
}
</script>
@endsection
