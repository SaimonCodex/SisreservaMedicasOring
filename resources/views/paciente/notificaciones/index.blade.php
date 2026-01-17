@extends('layouts.paciente')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-display font-bold text-gray-900 mb-1">📬 Centro de Notificaciones</h1>
            <p class="text-gray-600">Revisa tus avisos, recordatorios y actualizaciones de citas</p>
        </div>
        @if($stats['no_leidas'] > 0)
        <div>
            <form action="{{ route('paciente.notificaciones.leer-todas') }}" method="POST">
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
        <form method="GET" action="{{ route('paciente.notificaciones.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input 
                    type="text" 
                    name="buscar" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition-all outline-none" 
                    placeholder="Buscar aviso..." 
                    value="{{ request('buscar') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition-all outline-none">
                    <option value="todas">Todos los tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                            @switch($tipo)
                                @case('success') Éxito @break
                                @case('warning') Alerta @break
                                @case('danger') Urgente @break
                                @case('info') Información @break
                                @default {{ ucfirst($tipo) }}
                            @endswitch
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition-all outline-none">
                    <option value="todas" {{ request('estado') == 'todas' ? 'selected' : '' }}>Todas</option>
                    <option value="no_leidas" {{ request('estado') == 'no_leidas' ? 'selected' : '' }}>Solo No Leídas</option>
                    <option value="leidas" {{ request('estado') == 'leidas' ? 'selected' : '' }}>Solo Leídas</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-search"></i>
                    <span>Filtrar</span>
                </button>
                <a href="{{ route('paciente.notificaciones.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all flex items-center justify-center" title="Limpiar filtros">
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
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="w-12 px-4 py-3">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-medical-600 focus:ring-medical-500" id="select-all">
                            </th>
                            <th class="w-12 px-4 py-3"></th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Notificación</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">Tipo</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider w-40">Fecha</th>
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
                                    'success' => 'bi-check-circle-fill text-emerald-500',
                                    'warning' => 'bi-exclamation-triangle-fill text-amber-500',
                                    'danger' => 'bi-x-circle-fill text-red-500',
                                    'info' => 'bi-info-circle-fill text-blue-500',
                                ];
                                $icon = $iconMap[$tipo] ?? 'bi-bell-fill text-gray-500';
                            @endphp
                            <tr class="group hover:bg-gray-50/80 transition-colors {{ $isUnread ? 'bg-blue-50/20' : '' }}">
                                <td class="px-4 py-5">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-medical-600 focus:ring-medical-500 notif-checkbox" value="{{ $notif->id }}">
                                </td>
                                <td class="px-4 py-5">
                                    <div class="w-10 h-10 rounded-lg bg-white shadow-sm border border-gray-100 flex items-center justify-center">
                                        <i class="bi {{ $icon }} text-xl"></i>
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if($isUnread)
                                                <span class="w-2 h-2 rounded-full bg-medical-500"></span>
                                            @endif
                                            <span class="font-bold {{ $isUnread ? 'text-gray-900' : 'text-gray-700' }}">
                                                {{ $data['titulo'] ?? 'Aviso' }}
                                            </span>
                                        </div>
                                        <p class="text-sm {{ $isUnread ? 'text-gray-700' : 'text-gray-500' }}">
                                            {{ $data['mensaje'] ?? '' }}
                                        </p>
                                        @if(isset($data['link']))
                                            <a href="{{ $data['link'] }}" class="text-xs text-medical-600 font-semibold mt-2 hover:underline flex items-center gap-1">
                                                <span>Ver más detalles</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    @switch($tipo)
                                        @case('success')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-md border border-emerald-200">Éxito</span>
                                            @break
                                        @case('warning')
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-wider rounded-md border border-amber-200">Alerta</span>
                                            @break
                                        @case('danger')
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-wider rounded-md border border-red-200">Urgente</span>
                                            @break
                                        @default
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-md border border-blue-200">Info</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-700">{{ $notif->created_at->format('d/m/Y') }}</span>
                                        <span class="text-[10px] text-gray-500">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if($isUnread)
                                            <button 
                                                type="button" 
                                                onclick="marcarComoLeida('{{ $notif->id }}')"
                                                class="w-8 h-8 flex items-center justify-center bg-medical-50 text-medical-600 border border-medical-100 rounded-lg hover:bg-medical-600 hover:text-white transition-all"
                                                title="Marcar como leída">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        @endif
                                        <form action="{{ route('paciente.notificaciones.destroy', $notif->id) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('¿Eliminar esta notificación?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-600 hover:text-white transition-all" title="Eliminar">
                                                <i class="bi bi-trash3"></i>
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
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $notificaciones->links() }}
            </div>
        @else
            <div class="text-center py-24">
                <div class="w-24 h-24 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4 border border-dashed border-gray-200">
                    <i class="bi bi-bell-slash text-4xl text-gray-200"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Sin notificaciones</h3>
                <p class="text-gray-500 mt-2">Por ahora no tienes avisos nuevos en tu historial.</p>
                <div class="mt-8">
                    <a href="{{ route('paciente.dashboard') }}" class="px-6 py-2 bg-gray-900 text-white rounded-xl font-medium hover:bg-black transition-all">
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.notif-checkbox');
        const bulkBar = document.getElementById('bulk-actions-bar');
        const selectedCount = document.getElementById('selected-count');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkBar();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateBulkBar();
                if (!this.checked && selectAll) selectAll.checked = false;
            });
        });

        function updateBulkBar() {
            const checked = document.querySelectorAll('.notif-checkbox:checked').length;
            if (checked > 0) {
                bulkBar.classList.remove('hidden');
                selectedCount.textContent = checked;
            } else {
                bulkBar.classList.add('hidden');
            }
        }
    });

    function marcarComoLeida(id) {
        fetch(`{{ url('paciente/notificaciones') }}/${id}/marcar-leida`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function eliminarSeleccionadas() {
        if (!confirm('¿Estás seguro de eliminar las notificaciones seleccionadas?')) return;

        const ids = Array.from(document.querySelectorAll('.notif-checkbox:checked')).map(cb => cb.value);
        
        fetch('{{ route('paciente.notificaciones.destroy-all') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endsection
