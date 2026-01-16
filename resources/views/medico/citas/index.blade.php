@extends('layouts.medico')

@section('title', 'Mis Citas Médicas')

@section('content')
<div class="space-y-6" x-data="{
    showConfirmModal: false,
    modalTitle: '',
    modalMessage: '',
    modalActionUrl: '#',
    modalActionText: 'Confirmar',
    
    confirmarAccion(title, message, url, actionText) {
        this.modalTitle = title;
        this.modalMessage = message;
        this.modalActionUrl = url;
        this.modalActionText = actionText;
        this.showConfirmModal = true;
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Mis Citas Médicas</h1>
            <p class="text-gray-600 mt-1">Consulta tu agenda y citas programadas</p>
        </div>
        {{-- Botón oculto - Los médicos no pueden agendar citas. Descomentar si se requiere habilitar --}}
        {{-- <a href="{{ route('citas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Agendar Cita</span>
        </a> --}}
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="input" value="{{ request('fecha') }}">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="Programada" {{ request('estado') == 'Programada' ? 'selected' : '' }}>Programada</option>
                    <option value="Confirmada" {{ request('estado') == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="En Progreso" {{ request('estado') == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                    <option value="Completada" {{ request('estado') == 'Completada' ? 'selected' : '' }}>Completada</option>
                    <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                    <option value="No Asistió" {{ request('estado') == 'No Asistió' ? 'selected' : '' }}>No Asistió</option>
                </select>
            </div>
            <div>
                <label class="form-label">Paciente</label>
                <input type="text" name="buscar" class="input" placeholder="Buscar paciente..." value="{{ request('buscar') }}">
            </div>
            <div>
                <label class="form-label">Tipo Consulta</label>
                <select name="tipo_consulta" class="form-select">
                    <option value="">Todos</option>
                    <option value="Presencial" {{ request('tipo_consulta') == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                    <option value="Online" {{ request('tipo_consulta') == 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Domicilio" {{ request('tipo_consulta') == 'Domicilio' ? 'selected' : '' }}>Domicilio</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('citas.index') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-calendar-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['confirmadas_hoy'] ?? 0 }}</p>
                    <p class="text-sm text-blue-700">Confirmadas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-clock text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['pendientes_hoy'] ?? 0 }}</p>
                    <p class="text-sm text-amber-700">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['completadas_hoy'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700">Completadas (mes)</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-rose-50 to-rose-100 border-rose-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-rose-600 flex items-center justify-center">
                    <i class="bi bi-x-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-rose-900">{{ $stats['canceladas_hoy'] ?? 0 }}</p>
                    <p class="text-sm text-rose-700">Canceladas (mes)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Citas List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Fecha y Hora</th>
                        <th class="px-6 py-4 text-left font-semibold">Paciente</th>
                        <th class="px-6 py-4 text-left font-semibold">Especialidad</th>
                        <th class="px-6 py-4 text-left font-semibold">Consultorio</th>
                        <th class="px-6 py-4 text-left font-semibold">Tipo</th>
                        <th class="px-6 py-4 text-left font-semibold">Tarifa</th>
                        <th class="px-6 py-4 text-left font-semibold">Estado</th>
                        <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($citas ?? [] as $cita)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }}</span>
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($cita->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($cita->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $cita->paciente->primer_nombre ?? '' }} {{ $cita->paciente->primer_apellido ?? '' }}</p>
                                    <p class="text-sm text-gray-500">{{ $cita->paciente->tipo_documento ?? '' }}-{{ $cita->paciente->numero_documento ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">{{ $cita->especialidad->nombre ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-building text-gray-400"></i>
                                {{ $cita->consultorio->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($cita->tipo_consulta == 'Domicilio')
                                <span class="badge badge-warning">
                                    <i class="bi bi-house-door"></i> Domicilio
                                </span>
                            @elseif($cita->tipo_consulta == 'Online')
                                <span class="badge badge-info">
                                    <i class="bi bi-camera-video"></i> Online
                                </span>
                            @else
                                <span class="badge badge-primary">
                                    <i class="bi bi-building"></i> Presencial
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-900">${{ number_format($cita->tarifa_total ?? $cita->tarifa ?? 0, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $estadoClasses = [
                                    'Programada' => 'badge-warning',
                                    'Confirmada' => 'badge-success',
                                    'En Progreso' => 'badge-info',
                                    'Completada' => 'badge-primary',
                                    'Cancelada' => 'badge-danger',
                                    'No Asistió' => 'badge-danger'
                                ];
                                $estadoIcons = [
                                    'Programada' => 'bi-clock',
                                    'Confirmada' => 'bi-check-circle',
                                    'En Progreso' => 'bi-play-circle',
                                    'Completada' => 'bi-check-all',
                                    'Cancelada' => 'bi-x-circle',
                                    'No Asistió' => 'bi-person-x'
                                ];
                            @endphp
                            <span class="badge {{ $estadoClasses[$cita->estado_cita] ?? 'badge-gray' }}">
                                <i class="bi {{ $estadoIcons[$cita->estado_cita] ?? 'bi-question-circle' }}"></i>
                                {{ $cita->estado_cita }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Ver Detalles --}}
                                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-ghost text-gray-600 hover:bg-gray-50" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Historia Clínica Base (Solo Pacientes Regulares) --}}
                                @if($cita->paciente)
                                    @if($cita->paciente->historiaClinicaBase)
                                        <a href="{{ route('historia-clinica.base.show', $cita->paciente->id) }}" class="btn btn-sm btn-ghost text-info-600 hover:bg-info-50" title="Historia Clínica Base">
                                            <i class="bi bi-file-medical"></i>
                                        </a>
                                    @else
                                        <button type="button" 
                                                @click="confirmarAccion('Historia Clínica Base', 'Este paciente aún no cuenta con una historia clínica base. ¿Desea crearla?', '{{ route('historia-clinica.base.create', ['pacienteId' => $cita->paciente->id]) }}', 'Crear Historia')"
                                                class="btn btn-sm btn-ghost text-gray-400 hover:text-info-600 hover:bg-info-50" title="Crear Historia Clínica Base">
                                            <i class="bi bi-file-medical"></i>
                                        </button>
                                    @endif
                                @endif

                                {{-- Evolución Clínica --}}
                                @if($cita->evolucionClinica)
                                    {{-- Si existe evolución para esta cita --}}
                                    <a href="{{ route('historia-clinica.evoluciones.show', $cita->id) }}" class="btn btn-sm btn-ghost text-purple-600 hover:bg-purple-50" title="Ver Evolución">
                                        <i class="bi bi-journal-check"></i>
                                    </a>
                                @else
                                    {{-- Si no existe, sugerir crear --}}
                                    @if(in_array($cita->estado_cita, ['Confirmada', 'En Progreso', 'Completada']))
                                        <button type="button" 
                                                @click="confirmarAccion('Evolución Clínica', 'No existe una evolución registrada para esta cita. ¿Desea crearla?', '{{ route('historia-clinica.evoluciones.create', ['citaId' => $cita->id]) }}', 'Crear Evolución')"
                                                class="btn btn-sm btn-ghost text-gray-400 hover:text-purple-600 hover:bg-purple-50" title="Crear Evolución">
                                            <i class="bi bi-journal-plus"></i>
                                        </button>
                                    @endif
                                @endif

                                {{-- Receta / Orden Médica --}}
                                @php
                                    $orden = $cita->ordenesMedicas->first();
                                @endphp
                                @if($orden)
                                    {{-- Si existe orden para esta cita --}}
                                    <a href="{{ route('ordenes-medicas.show', $orden->id) }}" class="btn btn-sm btn-ghost text-emerald-600 hover:bg-emerald-50" title="Ver Orden Médica">
                                        <i class="bi bi-prescription2"></i>
                                    </a>
                                @else
                                    {{-- Si no existe, sugerir crear --}}
                                    @if(in_array($cita->estado_cita, ['Confirmada', 'En Progreso', 'Completada']))
                                        <button type="button" 
                                                @click="confirmarAccion('Orden Médica', 'No existe una orden médica registrada para esta cita. ¿Desea crearla?', '{{ route('ordenes-medicas.create', ['cita' => $cita->id, 'paciente' => $cita->paciente_id ?? null]) }}', 'Crear Orden')"
                                                class="btn btn-sm btn-ghost text-gray-400 hover:text-emerald-600 hover:bg-emerald-50" title="Crear Orden Médica">
                                            <i class="bi bi-prescription2"></i>
                                        </button>
                                    @endif
                                @endif

                                @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
                                    <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de cancelar esta cita?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50" title="Cancelar">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-calendar-x text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No se encontraron citas</p>
                                <p class="text-sm text-gray-400 mb-4">Intenta ajustar los filtros de búsqueda</p>
                                {{-- Botón oculto - Los médicos no pueden agendar citas
                                <a href="{{ route('citas.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Agendar Cita
                                </a>
                                --}}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($citas) && $citas->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $citas->appends(request()->query())->links('vendor.pagination.medical') }}
        </div>
        @endif
    </div>
    <!-- Modal de Confirmación -->
    <div x-show="showConfirmModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" @click.away="showConfirmModal = false">
            <h3 class="text-xl font-bold text-gray-900 mb-2" x-text="modalTitle"></h3>
            <p class="text-gray-600 mb-6" x-text="modalMessage"></p>
            
            <div class="flex justify-end gap-3">
                <button @click="showConfirmModal = false" class="btn btn-outline text-gray-600">
                    Cancelar
                </button>
                <a :href="modalActionUrl" class="btn btn-primary" x-text="modalActionText">
                    Confirmar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
