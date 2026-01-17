@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-display font-bold text-gray-900 mb-1">📬 Centro de Notificaciones</h1>
            <p class="text-gray-600">Gestiona y revisa todas tus notificaciones del sistema</p>
        </div>
        @if($stats['no_leidas'] > 0)
        <div>
            <form action="{{ route('admin.notificaciones.leer-todas') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm flex items-center gap-2">
                    <i class="bi bi-check-all"></i>
                    <span>Marcar Todas como Leídas</span>
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class="bi bi-bell-fill text-2xl text-blue-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Total Notificaciones</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Unread -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class="bi bi-envelope-fill text-2xl text-amber-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">No Leídas</p>
                    <h3 class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['no_leidas'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Read -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="bi bi-envelope-check-fill text-2xl text-emerald-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Leídas</p>
                    <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['leidas'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <form method="GET" action="{{ route('admin.notificaciones.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input 
                    type="text" 
                    name="buscar" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition-all" 
                    placeholder="Título o mensaje..." 
                    value="{{ request('buscar') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="tipo" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition-all">
                    <option value="todas" {{ request('tipo') == 'todas' ? 'selected' : '' }}>Todas</option>
                    @foreach($tipos as $tipo)
                        @if($tipo)
                            <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition-all">
                    <option value="todas" {{ request('estado') == 'todas' ? 'selected' : '' }}>Todas</option>
                    <option value="no_leidas" {{ request('estado') == 'no_leidas' ? 'selected' : '' }}>No Leídas</option>
                    <option value="leidas" {{ request('estado') == 'leidas' ? 'selected' : '' }}>Leídas</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-medical-600 hover:bg-medical-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-search"></i>
                    <span>Filtrar</span>
                </button>
                <a href="{{ route('admin.notificaciones.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 hidden" id="bulk-actions-bar">
        <div class="flex items-center justify-between">
            <span class="text-amber-900 font-medium">
                <span id="selected-count">0</span> notificación(es) seleccionada(s)
            </span>
            <button type="button" onclick="eliminarSeleccionadas()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                <i class="bi bi-trash"></i>
                <span>Eliminar Seleccionadas</span>
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($notificaciones->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="w-12 px-4 py-3 text-left">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-medical-600 focus:ring-medical-500" id="select-all">
                            </th>
                            <th class="w-12 px-4 py-3"></th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Notificación</th>
                            @if(auth()->user()->administrador->tipo_admin === 'Root')
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-64">Detalles</th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">Tipo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-40">Fecha</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($notificaciones as $notif)
                            @php
                                $data = $notif->data;
                                $isUnread = is_null($notif->read_at);
                                $tipo = $data['tipo'] ?? 'info';
                                $iconMap = [
                                    'success' => 'bi-check-circle-fill text-emerald-600',
                                    'warning' => 'bi-exclamation-triangle-fill text-amber-600',
                                    'danger' => 'bi-x-circle-fill text-red-600',
                                    'info' => 'bi-info-circle-fill text-blue-600',
                                ];
                                $icon = $iconMap[$tipo] ?? 'bi-bell-fill text-gray-600';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors {{ $isUnread ? 'bg-blue-50/30' : '' }}">
                                <td class="px-4 py-4">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-medical-600 focus:ring-medical-500 notif-checkbox" value="{{ $notif->id }}">
                                </td>
                                <td class="px-4 py-4">
                                    <i class="bi {{ $icon }} text-2xl"></i>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="font-semibold {{ $isUnread ? 'text-medical-600' : 'text-gray-900' }} flex items-center gap-2">
                                            {{ $data['titulo'] ?? 'Notificación' }}
                                            @if($isUnread)
                                                <span class="px-2 py-0.5 bg-medical-100 text-medical-700 text-xs font-bold rounded-full">Nueva</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ \Str::limit($data['mensaje'] ?? '', 80) }}
                                        </div>
                                    </div>
                                </td>
                                @if(auth()->user()->administrador->tipo_admin === 'Root')
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col gap-1.5">
                                            @if(isset($data['consultorio_nombre']))
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-building text-medical-600"></i>
                                                    <span class="text-xs font-medium text-gray-700">{{ $data['consultorio_nombre'] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($data['paciente_nombre']))
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-person text-blue-600"></i>
                                                    <span class="text-xs text-gray-600">{{ $data['paciente_nombre'] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($data['medico_nombre']))
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-person-badge text-emerald-600"></i>
                                                    <span class="text-xs text-gray-600">{{ $data['medico_nombre'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                                <td class="px-4 py-4">
                                    @if($tipo == 'success')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">Éxito</span>
                                    @elseif($tipo == 'warning')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">Alerta</span>
                                    @elseif($tipo == 'danger')
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Urgente</span>
                                    @else
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Info</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <i class="bi bi-clock mr-1"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($isUnread)
                                            <button 
                                                type="button" 
                                                onclick="marcarComoLeida('{{ $notif->id }}')"
                                                class="p-2 bg-medical-100 hover:bg-medical-200 text-medical-700 rounded-lg transition-colors"
                                                title="Marcar como leída">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        @endif
                                        <form action="{{ route('admin.notificaciones.destroy', $notif->id) }}" 
                                            method="POST" 
                                            class="inline"
                                            onsubmit="return confirm('¿Eliminar esta notificación?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $notificaciones->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="bi bi-inbox text-gray-300" style="font-size: 5rem;"></i>
                <p class="text-gray-500 mt-4 text-lg">No hay notificaciones para mostrar</p>
            </div>
        @endif
    </div>
</div>

<script>
    // Select all functionality
    document.getElementById('select-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.notif-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActionsBar();
    });

    // Individual checkbox change
    document.querySelectorAll('.notif-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkActionsBar);
    });

    function updateBulkActionsBar() {
        const selected = document.querySelectorAll('.notif-checkbox:checked');
        const bulkBar = document.getElementById('bulk-actions-bar');
        const countSpan = document.getElementById('selected-count');
        
        if (selected.length > 0) {
            bulkBar.classList.remove('hidden');
            countSpan.textContent = selected.length;
        } else {
            bulkBar.classList.add('hidden');
        }
    }

    function marcarComoLeida(id) {
        fetch(`/admin/notificaciones/${id}/marcar-leida`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function eliminarSeleccionadas() {
        const selected = Array.from(document.querySelectorAll('.notif-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selected.length === 0) {
            alert('No hay notificaciones seleccionadas');
            return;
        }

        if (!confirm(`¿Eliminar ${selected.length} notificación(es)?`)) {
            return;
        }

        fetch('/admin/notificaciones/eliminar-multiples', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids: selected })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection
