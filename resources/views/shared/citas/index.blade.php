@extends('layouts.admin')

@section('title', 'Citas Médicas')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Citas Médicas</h2>
            <p class="text-gray-500 mt-1">Gestión y calendario de citas</p>
        </div>
        <a href="{{ route('citas.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-calendar-plus mr-2"></i>
            Agendar Cita
        </a>
    </div>
</div>

<!-- Filtros (Visible solo en modo lista) -->
<div id="filters-container" class="card p-6 mb-6">
    <form method="GET" action="{{ route('citas.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Búsqueda -->
        <div class="md:col-span-2">
            <label class="form-label">Buscar</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="buscar" class="input pl-10" placeholder="Paciente, médico, cédula..." value="{{ request('buscar') }}">
            </div>
        </div>

        <!-- Fecha (Solo afecta lista) -->
        <div>
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="input" value="{{ request('fecha') }}">
        </div>

        <!-- Médico (Solo Admin) -->
        @if(auth()->user()->rol_id == 1)
        <div>
            <label class="form-label">Médico</label>
            <select name="medico_id" id="medico_filter" class="form-select">
                <option value="">Todos</option>
                @foreach($medicos as $medico)
                    <option value="{{ $medico->id }}" {{ request('medico_id') == $medico->id ? 'selected' : '' }}>
                        Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }}
                    </option>
                @endforeach
            </select>
        </div>
        @else
        <!-- Espacio vacío para layout si no es admin -->
        <div class="hidden md:block"></div>
        @endif

        <!-- Estado -->
        <div>
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="flex gap-2 items-end md:col-start-5">
            <button type="submit" class="btn btn-primary w-full justify-center">
                <i class="bi bi-funnel mr-2"></i> Filtrar
            </button>
            @if(request()->hasAny(['buscar', 'fecha', 'medico_id', 'estado']))
            <a href="{{ route('citas.index') }}" class="btn btn-outline" title="Limpiar filtros">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Estadísticas del Día (Visible en ambos modos) -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Hoy</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_hoy'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>
    <!-- ... (otros stats igual) ... -->
    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pendientes_hoy'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-clock text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
             <div>
                <p class="text-sm text-gray-500 mb-1">Confirmadas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['confirmadas_hoy'] }}</p>
            </div>
             <div class="w-12 h-12 bg-info-50 text-info-600 rounded-xl flex items-center justify-center text-2xl">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
             <div>
                <p class="text-sm text-gray-500 mb-1">Completadas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completadas_hoy'] }}</p>
            </div>
             <div class="w-12 h-12 bg-success-50 text-success-600 rounded-xl flex items-center justify-center text-2xl">
                <i class="bi bi-check-all"></i>
            </div>
        </div>
    </div>
    <div class="card p-4 border-l-4 border-l-danger-500">
        <div class="flex items-center justify-between">
             <div>
                <p class="text-sm text-gray-500 mb-1">Canceladas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['canceladas_hoy'] }}</p>
            </div>
             <div class="w-12 h-12 bg-danger-50 text-danger-600 rounded-xl flex items-center justify-center text-2xl">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Vista de Calendario/Lista -->
<div class="card overflow-hidden">
    <div class="border-b border-gray-200 bg-gray-50 px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <button id="btn-view-list" class="btn btn-sm bg-medical-600 text-white" onclick="toggleView('list')">
                    <i class="bi bi-list-ul mr-1"></i> Lista
                </button>
                <button id="btn-view-calendar" class="btn btn-sm btn-outline" onclick="toggleView('calendar')">
                    <i class="bi bi-calendar3 mr-1"></i> Calendario
                </button>
            </div>
            <p id="results-count" class="text-sm font-medium text-gray-700">Resultados: {{ $citas->total() }} citas encontradas</p>
        </div>
    </div>

    <!-- Timeline de Citas (LISTA) -->
    <div id="view-list" class="p-6">
        @if($citas->count() > 0)
            <div class="space-y-4">
                @foreach($citas as $cita)
                    @php
                        // Determinar colores según estado
                        $estadoColor = 'gray';
                        $estadoIcon = 'bi-circle';
                        
                        switch($cita->estado_cita) {
                            case 'Programada': 
                                $estadoColor = 'warning'; 
                                $estadoIcon = 'bi-clock';
                                break;
                            case 'Confirmada': 
                                $estadoColor = 'info'; 
                                $estadoIcon = 'bi-check-circle';
                                break;
                            case 'En Progreso': 
                                $estadoColor = 'primary'; 
                                $estadoIcon = 'bi-play-circle';
                                break;
                            case 'Completada': 
                                $estadoColor = 'success'; 
                                $estadoIcon = 'bi-check-all';
                                break;
                            case 'Cancelada': 
                            case 'No Asistió':
                                $estadoColor = 'danger'; 
                                $estadoIcon = 'bi-x-circle';
                                break;
                        }
                    @endphp

                    <div class="flex flex-col md:flex-row gap-4 p-4 bg-{{ $estadoColor }}-50 border-l-4 border-{{ $estadoColor }}-500 rounded-r-xl hover:shadow-md transition-shadow">
                        <!-- Fecha y Hora -->
                        <div class="flex flex-row md:flex-col items-center justify-center md:items-center md:justify-start gap-2 md:gap-0 min-w-[100px] border-b md:border-b-0 md:border-r border-{{ $estadoColor }}-200 pb-2 md:pb-0 md:pr-4">
                            <p class="text-lg font-bold text-{{ $estadoColor }}-700">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m') }}</p>
                            <div class="text-center">
                                <p class="text-xl font-bold text-{{ $estadoColor }}-700">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i') }}</p>
                                <p class="text-xs text-{{ $estadoColor }}-600">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('A') }}</p>
                            </div>
                        </div>

                        <!-- Información Principal -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">
                                        {{ $cita->paciente->primer_nombre ?? 'N/A' }} {{ $cita->paciente->primer_apellido ?? '' }}
                                        @if($cita->pacienteEspecial)
                                            <span class="text-xs font-normal text-purple-600 bg-purple-100 px-2 py-0.5 rounded-full ml-2">
                                                <i class="bi bi-person-heart"></i> {{ $cita->pacienteEspecial->primer_nombre }} (Paciente Especial)
                                            </span>
                                        @endif
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $cita->paciente->tipo_documento ?? '' }}-{{ $cita->paciente->numero_documento ?? '' }} 
                                        @if($cita->historiaClinica)
                                        • HC: {{ $cita->historiaClinica->numero_historia ?? 'N/A' }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                                    <span class="badge badge-{{ $estadoColor }} self-start">
                                        <i class="bi {{ $estadoIcon }} mr-1"></i> {{ $cita->estado_cita }}
                                    </span>

                                    @if($cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->count() > 0)
                                        @php
                                            $ultimoPago = $cita->facturaPaciente->pagos->where('status', true)->sortByDesc('created_at')->first();
                                            $pagoBadge = match($ultimoPago->estado) {
                                                'Confirmado' => 'success',
                                                'Pendiente' => 'warning',
                                                'Rechazado' => 'danger',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <a href="{{ route('pagos.index', ['buscar' => $cita->paciente->numero_documento]) }}" class="badge badge-{{ $pagoBadge }} self-start hover:scale-105 transition-transform" title="Ver detalles del pago">
                                            <i class="bi bi-credit-card mr-1"></i> Pago: {{ $ultimoPago->estado }}
                                        </a>
                                    @elseif($cita->facturaPaciente)
                                        <span class="badge badge-gray self-start opacity-75" title="No se ha registrado ningún pago para esta factura">
                                            <i class="bi bi-credit-card mr-1"></i> Sin Pago
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm mt-3">
                                <div class="flex items-center gap-2 text-gray-700 bg-white p-2 rounded-lg border border-gray-100">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Médico</p>
                                        <p class="font-medium">Dr. {{ $cita->medico->primer_nombre ?? 'N/A' }} {{ $cita->medico->primer_apellido ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700 bg-white p-2 rounded-lg border border-gray-100">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="bi bi-hospital"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Especialidad</p>
                                        <p class="font-medium">{{ $cita->especialidad->nombre ?? 'General' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700 bg-white p-2 rounded-lg border border-gray-100">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Ubicación</p>
                                        <p class="font-medium">{{ $cita->consultorio->nombre ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($cita->observaciones)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Observaciones / Motivo</p>
                                <p class="text-sm text-gray-600 italic">"{{ Str::limit($cita->observaciones, 150) }}"</p>
                            </div>
                            @endif
                            
                            <!-- Acciones -->
                            <div class="mt-4 flex flex-wrap gap-2 justify-end border-t border-{{ $estadoColor }}-200 pt-3">
                                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-outline hover:bg-white">
                                    <i class="bi bi-eye mr-1"></i> Ver Detalle
                                </a>

                                @if($cita->estado_cita == 'Programada')
                                    <form action="{{ route('citas.cambiar-estado', $cita->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="estado_cita" value="Confirmada">
                                        <button type="submit" class="btn btn-sm btn-success text-white">
                                            <i class="bi bi-check-lg mr-1"></i> Confirmar
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($cita->estado_cita, ['Confirmada', 'En Progreso']))
                                    <a href="{{ route('historia-clinica.evoluciones.create', ['citaId' => $cita->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-clipboard-pulse mr-1"></i> Atender / Evolución
                                    </a>
                                @endif

                                @if($cita->facturaPaciente && $cita->facturaPaciente->pagos && $cita->facturaPaciente->pagos->count() > 0)
                                    @php
                                        $pago = $cita->facturaPaciente->pagos->sortByDesc('created_at')->first();
                                    @endphp
                                    <a href="{{ route('pagos.show', $pago->id_pago) }}" class="btn btn-sm bg-indigo-600 text-white hover:bg-indigo-700" title="Ver detalles del pago">
                                        <i class="bi bi-receipt mr-1"></i> Ver Pago
                                    </a>
                                @endif

                                <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-sm btn-ghost text-gray-500 hover:text-blue-600" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
             <!-- Paginación -->
            <div class="mt-6">
                {{ $citas->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-calendar-x text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No se encontraron citas</h3>
                <p class="text-gray-500 mt-1">Intenta ajustar los filtros de búsqueda o agenda una nueva cita.</p>
                <a href="{{ route('citas.create') }}" class="btn btn-primary mt-4">
                    <i class="bi bi-plus-lg mr-2"></i> Agendar Nueva Cita
                </a>
            </div>
        @endif
    </div>

    <!-- CALENDARIO VIEW -->
    <div id="view-calendar" class="p-6 hidden">
        <div id="calendar"></div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar si el usuario ya prefirió calendario antes se podría hacer aquí
    });

    function toggleView(viewName) {
        const listBtn = document.getElementById('btn-view-list');
        const calBtn = document.getElementById('btn-view-calendar');
        const listView = document.getElementById('view-list');
        const calView = document.getElementById('view-calendar');
        const filters = document.getElementById('filters-container');
        const resultsCount = document.getElementById('results-count');

        if (viewName === 'list') {
            listBtn.classList.remove('btn-outline');
            listBtn.classList.add('bg-medical-600', 'text-white');
            calBtn.classList.add('btn-outline');
            calBtn.classList.remove('bg-medical-600', 'text-white');
            listView.classList.remove('hidden');
            calView.classList.add('hidden');
            filters.classList.remove('hidden');
            resultsCount.classList.remove('hidden');
        } else {
            calBtn.classList.remove('btn-outline');
            calBtn.classList.add('bg-medical-600', 'text-white');
            listBtn.classList.add('btn-outline');
            listBtn.classList.remove('bg-medical-600', 'text-white');
            calView.classList.remove('hidden');
            listView.classList.add('hidden');
            
            if (!calendar) {
                initCalendar();
            } else {
                calendar.render();
                calendar.refetchEvents();
            }
        }
    }

    function initCalendar() {
        const calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'es',
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            navLinks: true, // can click day/week names to navigate views
            businessHours: {
                 daysOfWeek: [ 1, 2, 3, 4, 5, 6 ],
                 startTime: '07:00', 
                 endTime: '19:00', 
            },
            dayMaxEvents: true, // allow "more" link when too many events
            events: {
                url: '{{ route("citas.events") }}',
                extraParams: function() {
                    return {
                        medico_id: document.getElementById('medico_filter')?.value || ''
                    };
                }
            },
            eventClick: function(info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            },
            eventContent: function(arg) {
                // Custom render for internal content styling
                let timeText = arg.timeText;
                let titleText = arg.event.title;
                let estado = arg.event.extendedProps.estado;
                
                // Icon mapping based on status
                let iconClass = 'bi-circle-fill';
                if(estado === 'Confirmada') iconClass = 'bi-check-circle-fill';
                else if(estado === 'Programada') iconClass = 'bi-clock-fill';
                else if(estado === 'En Progreso') iconClass = 'bi-play-circle-fill';
                else if(estado === 'Cancelada') iconClass = 'bi-x-circle-fill';
                else if(estado === 'Completada') iconClass = 'bi-check-all';

                let html = `
                    <div class="fc-event-custom-content flex items-center gap-1 overflow-hidden">
                        <i class="bi ${iconClass} text-[10px] opacity-75"></i>
                        <div class="flex flex-col leading-tight overflow-hidden">
                             <span class="text-xs font-bold truncate">${timeText}</span>
                             <span class="text-xs truncate">${titleText}</span>
                        </div>
                    </div>
                `;
                return { html: html };
            },
            eventDidMount: function(info) {
                // Remove default styling interference
                info.el.style.backgroundColor = info.event.backgroundColor;
                info.el.style.borderColor = info.event.borderColor;
                info.el.classList.add('shadow-sm', 'border-0', 'transition-transform', 'hover:scale-105', 'cursor-pointer');
            }
        });
        
        calendar.render();
    }
</script>

<style>
    /* FullCalendar Customization for Tailwind Look */
    #calendar {
        font-family: 'Inter', sans-serif;
        --fc-border-color: #f3f4f6;
        --fc-page-bg-color: #ffffff;
        --fc-neutral-bg-color: #f9fafb;
        --fc-list-event-hover-bg-color: #f3f4f6;
        --fc-today-bg-color: #ecfdf5; /* emerald-50 */
    }

    /* Toolbar & Title */
    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #111827 !important;
        text-transform: capitalize;
    }
    .fc-header-toolbar {
        margin-bottom: 2rem !important;
    }

    /* Buttons */
    .fc-button {
        background-color: white !important;
        border: 1px solid #e5e7eb !important; /* gray-200 */
        color: #374151 !important; /* gray-700 */
        font-weight: 500 !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s !important;
        text-transform: capitalize;
    }
    .fc-button:hover {
        background-color: #f9fafb !important; /* gray-50 */
        border-color: #d1d5db !important; /* gray-300 */
        color: #111827 !important; /* gray-900 */
    }
    .fc-button:focus {
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.3) !important; /* medical-500 ring */
    }
    .fc-button-active {
        background-color: #0ea5e9 !important; /* medical-500 */
        border-color: #0ea5e9 !important;
        color: white !important;
    }
    .fc-button-active:hover {
        background-color: #0284c7 !important; /* medical-600 */
    }

    /* Days Header */
    .fc-col-header-cell {
        background-color: #f9fafb;
        padding: 0.75rem 0 !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    .fc-col-header-cell-cushion {
        color: #6b7280; /* gray-500 */
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-decoration: none !important;
    }

    /* Grid Cells */
    .fc-daygrid-day-number {
        color: #374151; /* gray-700 */
        font-weight: 500;
        padding: 0.5rem !important;
        text-decoration: none !important;
    }
    .fc-day-today {
        background-color: #f0fdf4 !important; /* emerald-50 custom override */
    }

    /* Events */
    .fc-event {
        border-radius: 6px !important;
        padding: 2px 4px !important;
        border: none !important;
        margin-bottom: 2px !important;
    }
    .fc-event-custom-content {
        color: white; 
        text-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }
    
    /* Popover/More Links */
    .fc-popover {
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        border: 1px solid #e5e7eb !important;
        z-index: 50 !important;
    }
    .fc-popover-header {
        background-color: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding: 0.75rem !important;
        font-weight: 600 !important;
    }
</style>
@endsection
