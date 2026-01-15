@extends('layouts.paciente')

@section('title', 'Solicitudes de Acceso a mi Historial')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-display font-bold text-gray-900">Solicitudes de Acceso</h1>
        <p class="text-gray-600 mt-1">Gestiona quién puede ver tu historial médico</p>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-emerald-600"></i>
        <span class="text-emerald-800">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
        <i class="bi bi-exclamation-circle-fill text-red-600"></i>
        <span class="text-red-800">{{ session('error') }}</span>
    </div>
    @endif

    @if(session('info'))
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-3">
        <i class="bi bi-info-circle-fill text-blue-600"></i>
        <span class="text-blue-800">{{ session('info') }}</span>
    </div>
    @endif

    <!-- Info Card -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex gap-3">
            <i class="bi bi-shield-check text-blue-600 text-xl"></i>
            <div>
                <h3 class="font-semibold text-blue-900">Tu privacidad es importante</h3>
                <p class="text-sm text-blue-700 mt-1">
                    Cuando otro médico solicita acceso a tus evoluciones clínicas, tú decides si aprobar o rechazar.
                    El acceso aprobado es temporal (24 horas) y solo de lectura.
                </p>
            </div>
        </div>
    </div>

    <!-- Solicitudes Pendientes -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                <i class="bi bi-bell text-amber-500"></i>
                Solicitudes Pendientes
                @if($solicitudesPendientes->count() > 0)
                <span class="badge badge-warning">{{ $solicitudesPendientes->count() }}</span>
                @endif
            </h2>
        </div>
        
        @if($solicitudesPendientes->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($solicitudesPendientes as $solicitud)
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-person-badge text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">
                                Dr. {{ $solicitud->medicoSolicitante->usuario->primer_nombre ?? '' }} 
                                {{ $solicitud->medicoSolicitante->usuario->primer_apellido ?? 'Médico' }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Solicita ver evoluciones registradas por 
                                <strong>Dr. {{ $solicitud->medicoPropietario->usuario->primer_nombre ?? '' }} {{ $solicitud->medicoPropietario->usuario->primer_apellido ?? '' }}</strong>
                            </p>
                            
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">
                                    <i class="bi bi-tag"></i>
                                    {{ $solicitud->motivo_solicitud }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">
                                    <i class="bi bi-clock"></i>
                                    {{ \Carbon\Carbon::parse($solicitud->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            
                            @if($solicitud->observaciones)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 mb-1">Mensaje del médico:</p>
                                <p class="text-sm text-gray-700">{{ $solicitud->observaciones }}</p>
                            </div>
                            @endif
                            
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="bi bi-hourglass-split"></i>
                                Expira: {{ \Carbon\Carbon::parse($solicitud->token_expira_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 ml-16 md:ml-0">
                        <form action="{{ route('paciente.solicitudes.aprobar', $solicitud->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full sm:w-auto">
                                <i class="bi bi-check-lg"></i> Aprobar
                            </button>
                        </form>
                        <form action="{{ route('paciente.solicitudes.rechazar', $solicitud->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-outline w-full sm:w-auto text-red-600 border-red-300 hover:bg-red-50">
                                <i class="bi bi-x-lg"></i> Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <i class="bi bi-inbox text-4xl text-gray-300"></i>
            <p class="text-gray-500 mt-3">No tienes solicitudes pendientes</p>
        </div>
        @endif
    </div>

    <!-- Historial de Solicitudes -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                <i class="bi bi-clock-history text-gray-500"></i>
                Historial de Solicitudes
            </h2>
        </div>
        
        @if($solicitudesHistorial->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider px-6 py-3">Médico Solicitante</th>
                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider px-6 py-3">Motivo</th>
                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider px-6 py-3">Estado</th>
                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider px-6 py-3">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($solicitudesHistorial as $solicitud)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="bi bi-person text-gray-500"></i>
                                </div>
                                <span class="text-sm text-gray-900">
                                    Dr. {{ $solicitud->medicoSolicitante->usuario->primer_nombre ?? '' }} 
                                    {{ $solicitud->medicoSolicitante->usuario->primer_apellido ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $solicitud->motivo_solicitud }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($solicitud->estado_permiso)
                                @case('Aprobado')
                                    <span class="badge badge-success">
                                        <i class="bi bi-check-circle"></i> Aprobado
                                    </span>
                                    @break
                                @case('Rechazado')
                                    <span class="badge badge-danger">
                                        <i class="bi bi-x-circle"></i> Rechazado
                                    </span>
                                    @break
                                @case('Expirado')
                                    <span class="badge badge-secondary">
                                        <i class="bi bi-hourglass-bottom"></i> Expirado
                                    </span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">{{ $solicitud->estado_permiso }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($solicitud->updated_at)->format('d/m/Y H:i') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <i class="bi bi-folder2-open text-4xl text-gray-300"></i>
            <p class="text-gray-500 mt-3">No hay solicitudes anteriores</p>
        </div>
        @endif
    </div>
</div>
@endsection
