@extends('layouts.medico')

@section('title', 'Panel Médico')

@section('content')
<!-- Welcome Banner -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-blue-700 to-purple-600 shadow-xl mb-8">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/20 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="relative z-10 p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="text-white">
            <h2 class="text-3xl md:text-4xl font-display font-bold mb-2">
                ¡Bienvenido, Dr. {{ auth()->user()->nombre ?? 'Médico' }}!
            </h2>
            <p class="text-white/90 text-lg flex items-center gap-2">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ url('historia-clinica/evoluciones/create') }}" class="btn bg-white text-blue-600 hover:bg-gray-50 border-none shadow-md">
                <i class="bi bi-plus-lg"></i> Nueva Evolución
            </a>
        </div>
    </div>
</div>

<!-- Enhanced Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Citas Hoy -->
    <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-blue-700 mb-2">Citas Hoy</p>
                <h3 class="text-4xl font-display font-bold text-blue-900">{{ $stats['citas_hoy'] ?? 5 }}</h3>
                <p class="text-sm text-blue-600 mt-2">{{ $stats['completadas_hoy'] ?? 2 }} completadas</p>
            </div>
            <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-calendar-check text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-blue-200">
            <a href="{{ route('citas.index') }}" class="text-blue-700 hover:text-blue-900 font-semibold text-sm flex items-center gap-1">
                Ver agenda <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Pacientes Atendidos -->
    <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-emerald-700 mb-2">Pacientes Este Mes</p>
                <h3 class="text-4xl font-display font-bold text-emerald-900">{{ $stats['pacientes_mes'] ?? 48 }}</h3>
                <p class="text-sm text-emerald-600 mt-2 flex items-center gap-1">
                    <i class="bi bi-arrow-up"></i> +12% vs mes anterior
                </p>
            </div>
            <div class="w-14 h-14 bg-emerald-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-people text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-emerald-200">
            <span class="text-emerald-700 font-semibold text-sm">{{ $stats['pacientes_nuevos'] ?? 8 }} nuevos pacientes</span>
        </div>
    </div>

    <!-- Historias Clínicas -->
    <div class="card p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-purple-700 mb-2">Historias Clínicas</p>
                <h3 class="text-4xl font-display font-bold text-purple-900">{{ $stats['historias_pendientes'] ?? 7 }}</h3>
                <p class="text-sm text-purple-600 mt-2">Pendientes de actualizar</p>
            </div>
            <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-file-medical text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-purple-200">
            <a href="{{ url('historia-clinica/evoluciones') }}" class="text-purple-700 hover:text-purple-900 font-semibold text-sm flex items-center gap-1">
                Ver historias <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Órdenes Médicas -->
    <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-amber-700 mb-2">Órdenes Pendientes</p>
                <h3 class="text-4xl font-display font-bold text-amber-900">{{ $stats['ordenes_pendientes'] ?? 12 }}</h3>
                <p class="text-sm text-amber-600 mt-2">{{ $stats['laboratorios_pendientes'] ?? 5 }} laboratorios</p>
            </div>
            <div class="w-14 h-14 bg-amber-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-clipboard-pulse text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-amber-200">
            <a href="{{ route('ordenes-medicas.index') }}" class="text-amber-700 hover:text-amber-900 font-semibold text-sm flex items-center gap-1">
                Ver órdenes <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Main Content -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Agenda del Día -->
        <div class="card p-0 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-blue-600"></i>
                        Agenda del Día
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM') }}</p>
                </div>
                <a href="{{ route('citas.index') }}" class="btn btn-sm btn-outline">
                    Ver todas
                </a>
            </div>
            
            <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                @forelse($citasHoy ?? [] as $cita)
                <div class="p-6 hover:bg-gray-50 transition-colors group">
                    <div class="flex gap-4">
                        <!-- Time Badge -->
                        <div class="flex-shrink-0 text-center">
                            <div class="p-3 rounded-xl {{ $cita->status == 'confirmada' ? 'bg-emerald-100' : 'bg-amber-100' }}">
                                <span class="block text-2xl font-bold {{ $cita->status == 'confirmada' ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}
                                </span>
                                <span class="block text-xs {{ $cita->status == 'confirmada' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('A') }}
                                </span>
                            </div>
                        </div>

                        <!-- Patient Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">
                                        {{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}
                                    </h4>
                                    <p class="text-sm text-gray-600">{{ $cita->paciente->cedula }}</p>
                                </div>
                                @if($cita->status == 'confirmada')
                                <span class="badge badge-success">Confirmada</span>
                                @elseif($cita->status == 'pendiente')
                                <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-geo-alt text-blue-600"></i>
                                    <span>{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-telephone text-blue-600"></i>
                                    <span>{{ $cita->paciente->telefono ?? 'N/A' }}</span>
                                </div>
                            </div>

                            @if($cita->motivo ?? null)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-700"><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                            </div>
                            @endif

                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-outline">
                                    <i class="bi bi-eye"></i> Ver Detalles
                                </a>
                                <a href="{{ route('historia-clinica.evoluciones.create', ['citaId' => $cita->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-file-medical"></i> Atender
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-4">
                        <i class="bi bi-calendar-check text-5xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500 mb-2 font-medium text-lg">No tienes citas programadas hoy</p>
                    <p class="text-sm text-gray-400 mb-4">Disfruta tu día libre o revisa otras tareas pendientes</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="card">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-activity text-emerald-600"></i>
                    Actividad Reciente
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($actividadReciente ?? [] as $actividad)
                    <div class="flex gap-4 items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="bi {{ $actividad->icono ?? 'bi-check-circle' }} text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $actividad->titulo ?? 'Actividad' }}</p>
                            <p class="text-sm text-gray-600">{{ $actividad->descripcion ?? 'Descripción' }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ isset($actividad->created_at) ? \Carbon\Carbon::parse($actividad->created_at)->diffForHumans() : 'Hace unos momentos' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">No hay actividad reciente</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-6">
        <!-- Próxima Cita Destacada -->
        @if($proximaCita ?? null)
        <div class="card p-6 bg-gradient-to-br from-blue-600 to-purple-600 text-white">
            <div class="flex items-center gap-2 mb-3">
                <i class="bi bi-alarm text-2xl"></i>
                <h3 class="font-bold">Próxima Cita</h3>
            </div>
            <div class="space-y-2">
                <p class="text-3xl font-bold">{{ \Carbon\Carbon::parse($proximaCita->hora_inicio)->format('H:i A') }}</p>
                <p class="text-lg font-semibold text-blue-100">
                    {{ $proximaCita->paciente->primer_nombre }} {{ $proximaCita->paciente->primer_apellido }}
                </p>
                <p class="text-sm text-blue-100">{{ $proximaCita->motivo ?? 'Consulta general' }}</p>
            </div>
            <a href="{{ route('citas.show', $proximaCita->id) }}" class="btn bg-white text-blue-600 hover:bg-blue-50 w-full mt-4">
                Ver Detalles
            </a>
        </div>
        @endif

        <!-- Acciones Rápidas -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-lightning-charge text-amber-600"></i>
                Acciones Rápidas
            </h3>
            <div class="space-y-2">
                <a href="{{ url('historia-clinica/evoluciones/create') }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-file-medical"></i> Nueva Evolución
                </a>
                <a href="{{ route('ordenes-medicas.create') }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-prescription"></i> Nueva Receta
                </a>
                <a href="{{ route('historia-clinica.base.create', ['pacienteId' => 0]) }}" class="btn btn-outline w-full justify-start">
                    <!-- Note: Route requires patientId, checking if explicit 0 works or if we must use url -->
                    <i class="bi bi-folder-plus"></i> Nueva Historia
                </a>
                <a href="{{ url('ordenes-medicas/registrar-resultados') }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-clipboard-check"></i> Registrar Resultados
                </a>
            </div>
        </div>

        <!-- Órdenes Pendientes -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-exclamation-triangle text-amber-600"></i>
                Órdenes Pendientes
            </h3>
            <div class="space-y-3">
                @forelse($ordenesPendientes ?? [] as $orden)
                <div class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                    <p class="font-semibold text-gray-900">{{ $orden->tipo ?? 'Orden' }}</p>
                    <p class="text-sm text-gray-600">{{ $orden->paciente->nombre ?? 'Paciente' }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ isset($orden->created_at) ? \Carbon\Carbon::parse($orden->created_at)->diffForHumans() : '' }}
                    </p>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="bi bi-check-circle text-5xl text-emerald-300 mb-2"></i>
                    <p class="text-gray-500">Sin órdenes pendientes</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Notificaciones -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-bell text-blue-600"></i>
                Notificaciones
            </h3>
            <div class="space-y-3">
                @forelse($notificaciones ?? [] as $notificacion)
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm font-semibold text-gray-900">{{ $notificacion->titulo ?? 'Notificación' }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $notificacion->mensaje ?? '' }}</p>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-3xl text-gray-300 mb-2"></i>
                    <p class="text-sm text-gray-500">Sin notificaciones</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
