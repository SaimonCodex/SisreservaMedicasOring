@extends('layouts.admin')

@section('title', 'Reporte de Notificaciones')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Reporte de Notificaciones</h2>
            <p class="text-gray-500 mt-1">Análisis y estadísticas de notificaciones enviadas</p>
        </div>
        <a href="{{ route('notificaciones.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left mr-2"></i>
            Volver
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('notificaciones.reporte') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="input" value="{{ $filtros['fecha_inicio'] ?? '' }}">
        </div>

        <div>
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="input" value="{{ $filtros['fecha_fin'] ?? '' }}">
        </div>

        <div>
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="Recordatorio_Cita" {{ ($filtros['tipo'] ?? '') == 'Recordatorio_Cita' ? 'selected' : '' }}>Recordatorio Cita</option>
                <option value="Pago_Aprobado" {{ ($filtros['tipo'] ?? '') == 'Pago_Aprobado' ? 'selected' : '' }}>Pago Aprobado</option>
                <option value="Pago_Rechazado" {{ ($filtros['tipo'] ?? '') == 'Pago_Rechazado' ? 'selected' : '' }}>Pago Rechazado</option>
                <option value="Cancelacion" {{ ($filtros['tipo'] ?? '') == 'Cancelacion' ? 'selected' : '' }}>Cancelación</option>
                <option value="Alerta_Adm" {{ ($filtros['tipo'] ?? '') == 'Alerta_Adm' ? 'selected' : '' }}>Alerta Administrativa</option>
                <option value="Sistema" {{ ($filtros['tipo'] ?? '') == 'Sistema' ? 'selected' : '' }}>Sistema</option>
            </select>
        </div>

        <div>
            <label class="form-label">Vía</label>
            <select name="via" class="form-select">
                <option value="">Todas</option>
                <option value="Correo" {{ ($filtros['via'] ?? '') == 'Correo' ? 'selected' : '' }}>Correo</option>
                <option value="Sistema" {{ ($filtros['via'] ?? '') == 'Sistema' ? 'selected' : '' }}>Sistema</option>
                <option value="WhatsApp" {{ ($filtros['via'] ?? '') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                <option value="SMS" {{ ($filtros['via'] ?? '') == 'SMS' ? 'selected' : '' }}>SMS</option>
                <option value="Multiple" {{ ($filtros['via'] ?? '') == 'Multiple' ? 'selected' : '' }}>Múltiple</option>
            </select>
        </div>

        <div>
            <label class="form-label">Estado</label>
            <select name="estado_envio" class="form-select">
                <option value="">Todos</option>
                <option value="Pendiente" {{ ($filtros['estado_envio'] ?? '') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Enviado" {{ ($filtros['estado_envio'] ?? '') == 'Enviado' ? 'selected' : '' }}>Enviado</option>
                <option value="Fallido" {{ ($filtros['estado_envio'] ?? '') == 'Fallido' ? 'selected' : '' }}>Fallido</option>
                <option value="Leido" {{ ($filtros['estado_envio'] ?? '') == 'Leido' ? 'selected' : '' }}>Leído</option>
            </select>
        </div>

        <div class="md:col-span-5 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('notificaciones.reporte') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['total'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-bell text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Enviadas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['enviadas'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-danger-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Fallidas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['fallidas'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-danger-50 flex items-center justify-center">
                <i class="bi bi-x-circle text-danger-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['pendientes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-clock text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Leídas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['leidas'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-eye text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Notificaciones -->
<div class="card overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-info-600 to-info-500">
        <h3 class="text-lg font-semibold text-white">Notificaciones Filtradas</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Fecha</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Tipo</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Título</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Receptor</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Vía</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-900">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($notificaciones as $notificacion)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $notificacion->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $notificacion->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-medical">{{ str_replace('_', ' ', $notificacion->tipo) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">{{ $notificacion->titulo }}</p>
                        <p class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($notificacion->mensaje, 50) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $notificacion->receptor_rol }}</p>
                        <p class="text-xs text-gray-500">ID: {{ $notificacion->receptor_id }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-info">{{ $notificacion->via }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="badge badge-{{ $notificacion->estado_envio == 'Enviado' ? 'success' : ($notificacion->estado_envio == 'Fallido' ? 'danger' : ($notificacion->estado_envio == 'Leido' ? 'info' : 'warning')) }}">
                            {{ $notificacion->estado_envio }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No se encontraron notificaciones con los filtros aplicados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
