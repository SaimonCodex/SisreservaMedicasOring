@extends('layouts.paciente')

@section('title', 'Mis Pagos')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-display font-bold text-gray-900">Mis Pagos</h1>
    <p class="text-gray-600 mt-1">Historial de pagos y facturas</p>
</div>

@if(isset($pacientesEspeciales) && $pacientesEspeciales->count() > 0)
<!-- Filtros (Solo visibles si es representante) -->
<div class="card p-4 mb-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="text-sm text-gray-600 mb-1 block">Tipo de Pago</label>
            <select id="filtro-tipo" class="form-select" onchange="filtrarPagos()">
                <option value="todas">Todos los pagos</option>
                <option value="propia">Solo pagos propios</option>
                <option value="terceros">Solo pagos de terceros</option>
            </select>
        </div>
        
        <div id="filtro-paciente-container" class="flex-1 min-w-[200px] hidden">
            <label class="text-sm text-gray-600 mb-1 block">Paciente Especial</label>
            <select id="filtro-paciente" class="form-select" onchange="filtrarPagos()">
                <option value="">Todos los pacientes</option>
                @foreach($pacientesEspeciales as $pe)
                <option value="{{ $pe->id }}">{{ $pe->primer_nombre }} {{ $pe->primer_apellido }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@endif

<div class="card">
    @if($pagos && $pagos->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pagos as $pago)
                    <tr class="hover:bg-gray-50 pago-row"
                        data-tipo="{{ $pago->tipo_pago_display ?? 'propia' }}"
                        data-paciente-especial="{{ $pago->paciente_especial_info->id ?? '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if(($pago->tipo_pago_display ?? 'propia') == 'terceros' && $pago->paciente_especial_info)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    <i class="bi bi-person-heart"></i> {{ $pago->paciente_especial_info->primer_nombre }}
                                </span>
                            @else
                                <span class="text-gray-500 italic">Yo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $pago->descripcion ?? 'Consulta Médica' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ${{ number_format($pago->monto ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge {{ $pago->status ? 'badge-success' : 'badge-warning' }}">
                                {{ $pago->status ? 'Pagado' : 'Pendiente' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button class="btn btn-sm btn-outline">
                                <i class="bi bi-eye"></i> Ver
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $pagos->links() }}
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                <i class="bi bi-wallet2 text-5xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 mb-2 font-medium text-lg">No tienes pagos registrados</p>
            <p class="text-gray-400 text-sm">Tus pagos aparecerán aquí después de tus consultas</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function filtrarPagos() {
        const tipoFiltro = document.getElementById('filtro-tipo').value;
        const pacienteFiltro = document.getElementById('filtro-paciente')?.value || '';
        const containerPaciente = document.getElementById('filtro-paciente-container');
        
        // Mostrar/ocultar filtro de paciente
        if (tipoFiltro === 'terceros') {
            containerPaciente.classList.remove('hidden');
        } else {
            containerPaciente.classList.add('hidden');
        }
        
        // Filtrar filas
        document.querySelectorAll('.pago-row').forEach(row => {
            const tipoPago = row.dataset.tipo;
            const pacienteEspecialId = row.dataset.pacienteEspecial;
            
            let mostrar = true;
            
            // Filtro por tipo
            if (tipoFiltro === 'propia' && tipoPago !== 'propia') {
                mostrar = false;
            } else if (tipoFiltro === 'terceros' && tipoPago !== 'terceros') {
                mostrar = false;
            }
            
            // Filtro por paciente especial (solo si tipo es terceros)
            if (tipoFiltro === 'terceros' && pacienteFiltro && pacienteEspecialId !== pacienteFiltro) {
                mostrar = false;
            }
            
            row.style.display = mostrar ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
