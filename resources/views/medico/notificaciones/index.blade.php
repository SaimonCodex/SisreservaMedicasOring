@extends('layouts.medico')

@section('title', 'Mis Notificaciones')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notificaciones</h1>
            <p class="text-sm text-gray-600 mt-1">Mantente al tanto de todos tus eventos médicos</p>
        </div>
        
        @if($notificaciones->where('read_at', null)->count() > 0)
        <form action="{{ route('medico.notificaciones.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors text-sm font-medium">
                <i class="bi bi-check2-all mr-1"></i>
                Marcar todas como leídas
            </button>
        </form>
        @endif
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
        <form method="GET" action="{{ route('medico.notificaciones.index') }}" class="flex gap-3">
            <div class="flex-1">
                <select name="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos los tipos</option>
                    <option value="NuevaCitaAsignada" {{ request('tipo') == 'NuevaCitaAsignada' ? 'selected' : '' }}>Nueva Cita</option>
                    <option value="CitaCanceladaPaciente" {{ request('tipo') == 'CitaCanceladaPaciente' ? 'selected' : '' }}>Cita Cancelada</option>
                    <option value="CitaReprogramada" {{ request('tipo') == 'CitaReprogramada' ? 'selected' : '' }}>Cita Reprogramada</option>
                    <option value="PagoConfirmadoCita" {{ request('tipo') == 'PagoConfirmadoCita' ? 'selected' : '' }}>Pago Confirmado</option>
                </select>
            </div>
            <div class="flex-1">
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todas</option>
                    <option value="no_leidas" {{ request('estado') == 'no_leidas' ? 'selected' : '' }}>No leídas</option>
                    <option value="leidas" {{ request('estado') == 'leidas' ? 'selected' : '' }}>Leídas</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium">
                <i class="bi bi-funnel mr-1"></i>
                Filtrar
            </button>
        </form>
    </div>

    <!-- Lista de Notificaciones -->
    @if($notificaciones->count() > 0)
        <div class="space-y-2">
            @foreach($notificaciones as $notificacion)
                @php
                    $data = $notificacion->data;
                    $isUnread = is_null($notificacion->read_at);
                    
                    // Determinar íconos y colores según el tipo
                    $iconClass = 'bi-bell-fill';
                    $bgClass = 'bg-gray-500';
                    
                    if(isset($data['tipo'])) {
                        switch($data['tipo']) {
                            case 'success':
                                $iconClass = 'bi-check-circle-fill';
                                $bgClass = 'bg-emerald-500';
                                break;
                            case 'info':
                                $iconClass = 'bi-info-circle-fill';
                                $bgClass = 'bg-blue-500';
                                break;
                            case 'warning':
                                $iconClass = 'bi-exclamation-triangle-fill';
                                $bgClass = 'bg-amber-500';
                                break;
                            case 'danger':
                                $iconClass = 'bi-x-circle-fill';
                                $bgClass = 'bg-rose-500';
                                break;
                        }
                    }
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow {{ $isUnread ? 'ring-2 ring-emerald-500/20 bg-emerald-50/30' : '' }}">
                    <div class="flex gap-4">
                        <!-- Ícono -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 {{ $bgClass }} rounded-xl flex items-center justify-center shadow-md">
                                <i class="bi {{ $iconClass }} text-white text-xl"></i>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                        {{ $data['titulo'] ?? 'Notificación' }}
                                        @if($isUnread)
                                            <span class="inline-block w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $data['mensaje'] ?? 'Sin descripción' }}</p>
                                    
                                    <!-- Información adicional según el tipo -->
                                    @if(isset($data['paciente_nombre']))
                                        <div class="mt-2 flex flex-wrap gap-4 text-xs text-gray-500">
                                            <span><i class="bi bi-person mr-1"></i>{{ $data['paciente_nombre'] }}</span>
                                            @if(isset($data['fecha_cita']))
                                                <span><i class="bi bi-calendar mr-1"></i>{{ \Carbon\Carbon::parse($data['fecha_cita'])->format('d/m/Y') }}</span>
                                            @endif
                                            @if(isset($data['hora_inicio']))
                                                <span><i class="bi bi-clock mr-1"></i>{{ $data['hora_inicio'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <p class="text-xs text-gray-400 mt-2">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        {{ $notificacion->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Acciones -->
                                <div class="flex items-center gap-2">
                                    @if(isset($data['link']))
                                        <a href="{{ $data['link'] }}" class="px-3 py-1.5 bg-emerald-500 text-white text-sm rounded-lg hover:bg-emerald-600 transition-colors font-medium">
                                            <i class="bi bi-arrow-right-circle mr-1"></i>
                                            Ver detalles
                                        </a>
                                    @endif
                                    
                                    @if($isUnread)
                                        <form action="{{ route('medico.notificaciones.mark-read', $notificacion->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-emerald-600 transition-colors" title="Marcar como leída">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $notificaciones->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-bell-slash text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay notificaciones</h3>
            <p class="text-gray-600">Cuando recibas notificaciones sobre tus citas y pagos, aparecerán aquí.</p>
        </div>
    @endif
</div>
@endsection
