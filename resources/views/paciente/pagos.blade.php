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

<div class="card overflow-hidden border-0 shadow-sm">
    @if($pagos && $pagos->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha / Ref</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Paciente</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Servicio</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Monto (USD)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Estado Pago</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pagos as $pago)
                    @php
                        $ultimoPago = $pago->pagos->where('status', true)->sortByDesc('created_at')->first();
                        $statusText = 'Sin Registro';
                        $statusBadge = 'gray';
                        
                        if ($ultimoPago) {
                            $statusText = $ultimoPago->estado;
                            $statusBadge = match($statusText) {
                                'Confirmado' => 'success',
                                'Pendiente' => 'warning',
                                'Rechazado' => 'danger',
                                default => 'gray'
                            };
                        } elseif ($pago->status_factura == 'Cancelada') {
                             $statusText = 'Anulada';
                             $statusBadge = 'gray';
                        } else {
                             $statusText = 'Pendiente por Pagar';
                             $statusBadge = 'danger';
                        }
                    @endphp
                    <tr class="hover:bg-emerald-50/30 transition-colors pago-row"
                        data-tipo="{{ $pago->tipo_pago_display ?? 'propia' }}"
                        data-paciente-especial="{{ $pago->paciente_especial_info->id ?? '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($pago->created_at)->format('d M, Y') }}</span>
                                <span class="text-[10px] font-mono text-gray-400">#{{ $pago->numero_factura }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($pago->tipo_pago_display ?? 'propia') == 'terceros' && $pago->paciente_especial_info)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-xs font-bold">
                                        {{ substr($pago->paciente_especial_info->primer_nombre, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $pago->paciente_especial_info->primer_nombre }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-emerald-600">
                                    <i class="bi bi-person-check-fill text-sm"></i>
                                    <span class="text-sm font-semibold italic">Titular</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $pago->cita->especialidad->nombre ?? 'Consulta Médica' }}
                            </div>
                            <div class="text-[10px] text-gray-400 capitalize">
                                {{ \App\Models\Cita::find($pago->cita_id)->tipo_consulta ?? 'Presencial' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">${{ number_format($pago->monto_usd, 2) }}</div>
                            <div class="text-[11px] text-gray-500">{{ number_format($pago->monto_bs, 2) }} Bs.</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge badge-{{ $statusBadge }} uppercase font-bold tracking-wider text-[10px] py-1 px-3">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('paciente.citas.show', $pago->cita_id) }}" class="btn btn-xs btn-ghost text-emerald-600 hover:bg-emerald-100 rounded-lg transition-all font-bold">
                                <i class="bi bi-info-circle mr-1"></i> Detalles
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-50 bg-gray-50/50">
            {{ $pagos->links() }}
        </div>
    @else
        <div class="p-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 border border-gray-100 mb-6 shadow-inner">
                <i class="bi bi-credit-card-2-back text-4xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Sin Historial de Pagos</h3>
            <p class="text-gray-500 max-w-sm mx-auto text-sm leading-relaxed">
                Aún no tienes registros de facturación. Tus pagos y facturas aparecerán aquí una vez que agendes y pagues tus consultas.
            </p>
            <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary mt-8">
                Agendar Mi Primera Cita
            </a>
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
