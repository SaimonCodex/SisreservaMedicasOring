@extends('layouts.admin')

@section('title', 'Liquidaciones')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-display font-bold text-gray-900">Liquidaciones</h1>
            <p class="text-gray-600 mt-1">Gestión de liquidaciones quincenales y mensuales</p>
        </div>
        <button onclick="document.getElementById('modal-nueva-liquidacion').showModal()" class="btn btn-primary shadow-lg shadow-emerald-200">
            <i class="bi bi-plus-lg mr-2"></i>
            <span>Nueva Liquidación</span>
        </button>
    </div>

    <!-- Filtros -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="label"><span class="label-text font-bold">Tipo</span></label>
                <select name="tipo" class="select select-bordered w-full">
                    <option value="">Todos</option>
                    <option value="Quincenal" {{ request('tipo') == 'Quincenal' ? 'selected' : '' }}>Quincenal</option>
                    <option value="Mensual" {{ request('tipo') == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                </select>
            </div>
            <div>
                <label class="label"><span class="label-text font-bold">Estado</span></label>
                <select name="estado" class="select select-bordered w-full">
                    <option value="">Todos</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Procesada" {{ request('estado') == 'Procesada' ? 'selected' : '' }}>Procesada</option>
                    <option value="Pagada" {{ request('estado') == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                </select>
            </div>
            <div>
                <label class="label"><span class="label-text font-bold">Período</span></label>
                <input type="month" name="periodo" class="input input-bordered w-full" value="{{ request('periodo') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary w-full">
                    <i class="bi bi-search mr-2"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Vista por Entidad -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Médicos -->
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-white border-blue-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900">Médicos</h3>
                <i class="bi bi-person-badge text-blue-600 text-2xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($totalesPorEntidad['Medico'] ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500 mt-1">Total pendiente de liquidar</p>
        </div>

        <!-- Consultorios -->
        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-white border-emerald-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900">Consultorios</h3>
                <i class="bi bi-building text-emerald-600 text-2xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($totalesPorEntidad['Consultorio'] ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500 mt-1">Total pendiente de liquidar</p>
        </div>

        <!-- Sistema -->
        <div class="card p-6 bg-gradient-to-br from-purple-50 to-white border-purple-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900">Sistema</h3>
                <i class="bi bi-gear text-purple-600 text-2xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($totalesPorEntidad['Sistema'] ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500 mt-1">Total comisiones</p>
        </div>
    </div>

    <!-- Tabla de Totales Pendientes por Entidad -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Totales Pendientes por Entidad</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Entidad</th>
                        <th>Tipo</th>
                        <th>Total USD</th>
                        <th>Total BS</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($totalesPendientes as $total)
                    <tr>
                        <td>
                            @if($total->entidad_tipo == 'Medico' && $total->medico)
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-person-badge text-blue-600"></i>
                                    <span>Dr. {{ $total->medico->primer_nombre }} {{ $total->medico->primer_apellido }}</span>
                                </div>
                            @elseif($total->entidad_tipo == 'Consultorio' && $total->consultorio)
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-building text-emerald-600"></i>
                                    <span>{{ $total->consultorio->nombre }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-gear text-purple-600"></i>
                                    <span>Sistema</span>
                                </div>
                            @endif
                        </td>
                        <td><span class="badge badge-info">{{ $total->entidad_tipo }}</span></td>
                        <td class="font-bold text-gray-900">${{ number_format($total->total_final_usd, 2) }}</td>
                        <td class="text-gray-600">Bs. {{ number_format($total->total_final_bs, 2) }}</td>
                        <td>
                            @if($total->estado_liquidacion == 'Pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                            @elseif($total->estado_liquidacion == 'Procesada')
                            <span class="badge badge-info">Procesada</span>
                            @else
                            <span class="badge badge-success">Pagada</span>
                            @endif
                        </td>
                        <td>
                            @if($total->estado_liquidacion == 'Pendiente')
                            <button onclick="generarLiquidacion('{{ $total->entidad_tipo }}', {{ $total->entidad_id ?? 'null' }})" class="btn btn-sm btn-primary">
                                <i class="bi bi-file-earmark-check"></i>
                                Generar Liquidación
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="bi bi-inbox text-6xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 font-medium">No hay totales pendientes</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nueva Liquidación -->
<dialog id="modal-nueva-liquidacion" class="modal backdrop-blur-sm">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4">Nueva Liquidación</h3>
        <form action="{{ route('facturacion.crear-liquidacion') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="label"><span class="label-text font-bold">Tipo de Período</span></label>
                    <select name="tipo_periodo" class="select select-bordered w-full" required>
                        <option value="Quincenal">Quincenal</option>
                        <option value="Mensual">Mensual</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label"><span class="label-text font-bold">Fecha Inicio</span></label>
                        <input type="date" name="fecha_inicio" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text font-bold">Fecha Fin</span></label>
                        <input type="date" name="fecha_fin" class="input input-bordered w-full" required>
                    </div>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-nueva-liquidacion').close()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Generar Liquidación</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function generarLiquidacion(tipoEntidad, entidadId) {
    Swal.fire({
        title: '¿Generar Liquidación?',
        text: `Se procesarán todos los totales pendientes para este ${tipoEntidad}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, Generar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementar lógica de generación
            Swal.fire('¡Generado!', 'La liquidación ha sido generada exitosamente', 'success');
        }
    });
}
</script>
@endsection
