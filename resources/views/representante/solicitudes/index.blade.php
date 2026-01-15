@extends('layouts.representante')

@section('title', 'Solicitudes de Acceso - Representante')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-5">
        <h1 class="text-2xl font-bold font-display text-gray-900">Solicitudes de Acceso</h1>
        <p class="text-gray-600 mt-1">Gestione quién puede ver el historial médico de sus representados</p>
    </div>

    <!-- Solicitudes Pendientes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-amber-50">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="bi bi-bell-fill text-amber-500"></i>
                Solicitudes Pendientes
            </h2>
        </div>
        
        @if($solicitudesPendientes->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($solicitudesPendientes as $solicitud)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    Requiere Aprobación
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $solicitud->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                Solicitud para: <span class="text-blue-600">{{ $solicitud->paciente->nombre_completo ?? $solicitud->paciente->primer_nombre }}</span>
                            </h3>
                            
                            <p class="text-gray-900 font-medium">
                                El <span class="text-blue-600 font-bold">Dr. {{ $solicitud->medicoSolicitante->usuario->nombre ?? 'Desconocido' }}</span>
                                solicita acceso a las evoluciones creadas por el 
                                <span class="text-slate-600 font-bold">Dr. {{ $solicitud->medicoPropietario->usuario->nombre ?? 'Desconocido' }}</span>.
                            </p>
                            
                            <div class="mt-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <p class="text-sm text-gray-700">
                                    <span class="font-semibold">Motivo:</span> {{ $solicitud->motivo_solicitud }}
                                </p>
                                @if($solicitud->observaciones)
                                <p class="text-sm text-gray-600 mt-1">
                                    <span class="font-semibold">Nota:</span> {{ $solicitud->observaciones }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-row md:flex-col gap-2 shrink-0">
                            <form action="{{ route('representante.solicitudes.aprobar', $solicitud->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                    <i class="bi bi-check-lg mr-2"></i> Aprobar
                                </button>
                            </form>
                            
                            <form action="{{ route('representante.solicitudes.rechazar', $solicitud->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                    <i class="bi bi-x-lg mr-2"></i> Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-inbox text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No hay solicitudes pendientes</h3>
                <p class="text-gray-500 mt-1">Le notificaremos cuando un médico solicite acceso.</p>
            </div>
        @endif
    </div>

    <!-- Historial -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="bi bi-clock-history text-blue-600"></i>
                Historial de Solicitudes
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Representado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico Solicitante</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($solicitudesHistorial as $solicitud)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $solicitud->updated_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $solicitud->paciente->nombre_completo ?? $solicitud->paciente->primer_nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Dr. {{ $solicitud->medicoSolicitante->usuario->primer_nombre ?? 'Médico' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $solicitud->motivo_solicitud }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($solicitud->estado_permiso === 'Aprobado')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aprobado
                                </span>
                            @elseif($solicitud->estado_permiso === 'Rechazado')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rechazado
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $solicitud->estado_permiso }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                            No hay historial de solicitudes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
