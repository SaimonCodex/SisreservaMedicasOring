@extends('layouts.paciente')

@section('title', 'Mis Pagos')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-display font-bold text-gray-900">Mis Pagos</h1>
    <p class="text-gray-600 mt-1">Historial de pagos y facturas</p>
</div>

<div class="card">
    @if($pagos && $pagos->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pagos as $pago)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') }}
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
@endsection
