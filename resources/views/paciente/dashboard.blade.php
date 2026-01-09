@extends('layouts.paciente')

@section('title', 'Mi Portal')

@section('content')
<!-- Welcome Banner -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 via-green-600 to-blue-600 shadow-xl mb-8">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/20 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="relative z-10 p-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-white text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-display font-bold mb-2">
                    ¡Hola, {{ auth()->user()->paciente->primer_nombre ?? 'Paciente' }}!
                </h2>
                <p class="text-white/90 text-lg">¿Cómo te sientes hoy? Estamos aquí para cuidar de ti.</p>
            </div>
            <a href="{{ route('citas.create') }}" class="btn bg-white text-emerald-600 hover:bg-gray-50 border-none shadow-md">
                <i class="bi bi-plus-lg"></i> Solicitar Cita
            </a>
        </div>
        
        <!-- Health Stats Mini -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-calendar-check text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['citas_proximás'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Próximas Citas</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-file-medical text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['historias'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Historias</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-prescription text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['recetas_activas'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Recetas Activas</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-heart-pulse text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['consultas_mes'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Este Mes</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Appointments & History -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Mis Citas Próximas -->
        <div class="card p-0 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-emerald-600"></i>
                        Mis Próximas Citas
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Tus consultas programadas</p>
                </div>
                <a href="{{ route('citas.index') }}" class="btn btn-sm btn-outline">Ver todas</a>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($citas_proximas ?? [] as $cita)
                <div class="p-6 hover:bg-gray-50 transition-colors group">
                    <div class="flex gap-5">
                        <!-- Date Box -->
                        <div class="flex-shrink-0 text-center">
                            <div class="w-20 h-20 border-2 border-emerald-200 rounded-xl p-3 bg-white group-hover:border-emerald-300 group-hover:shadow-md transition-all">
                                <span class="block text-3xl font-bold text-emerald-700">
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d') }}
                                </span>
                                <span class="block text-xs uppercase font-bold text-gray-500">
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('M') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">
                                        {{ $cita->medico->especialidad->nombre ?? 'Consulta General' }}
                                    </h4>
                                    <p class="text-gray-600 flex items-center gap-2 mt-1">
                                        <i class="bi bi-person-badge text-emerald-600"></i>
                                        <span class="font-medium">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</span>
                                    </p>
                                </div>
                                <span class="badge badge-success">{{ ucfirst($cita->status) }}</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-clock text-emerald-600"></i>
                                    <span>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-building text-emerald-600"></i>
                                    <span>{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                                </div>
                            </div>

                            @if($cita->motivo ?? null)
                            <div class="p-3 bg-emerald-50 rounded-lg mb-3">
                                <p class="text-sm text-gray-700"><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                            </div>
                            @endif
                            
                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-outline">
                                    <i class="bi bi-eye"></i> Ver Detalles
                                </a>
                                @if($cita->status != 'cancelada')
                                <button class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                        <i class="bi bi-calendar-x text-5xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500 mb-2 font-medium text-lg">No tienes citas próximas agendadas</p>
                    <p class="text-gray-400 text-sm mb-4">Solicita tu primera cita médica ahora</p>
                    <a href="{{ route('citas.create') }}" class="btn btn-primary inline-flex items-center">
                        <i class="bi bi-calendar-plus"></i>
                        Agendar mi primera cita
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Historial Reciente -->
        <div class="card">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-clock-history text-blue-600"></i>
                            Historial Médico Reciente
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Tus últimas consultas y procedimientos</p>
                    </div>
                    <a href="{{ url('index.php/paciente/historial') }}" class="btn btn-sm btn-outline">Ver historial completo</a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($historial_reciente ?? [] as $registro)
                    <div class="flex gap-4 items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-file-medical text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $registro->diagnostico ?? 'Consulta Médica' }}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Dr. {{ $registro->medico->usuario->nombre ?? 'Médico' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ url('index.php/paciente/historial/' . $registro->id) }}" class="btn btn-sm btn-outline opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-folder2-open text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No hay registros en tu historial médico</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recetas Activas -->
        <div class="card">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white">
                <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-prescription text-purple-600"></i>
                    Recetas Activas
                </h3>
                <p class="text-sm text-gray-600 mt-1">Tus medicamentos actuales</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recetas_activas ?? [] as $receta)
                    <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $receta->medicamento ?? 'Medicamento' }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    <strong>Dosis:</strong> {{ $receta->dosis ?? 'N/A' }} - {{ $receta->frecuencia ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <strong>Duración:</strong> {{ $receta->duracion ?? 'N/A' }}
                                </p>
                                @if($receta->instrucciones ?? null)
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="bi bi-info-circle"></i> {{ $receta->instrucciones }}
                                </p>
                                @endif
                            </div>
                            <span class="badge badge-purple">Activa</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-prescription text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No tienes recetas activas</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Profile & Quick Menu -->
    <div class="space-y-6">
        <!-- Perfil Card -->
        <div class="card p-0 overflow-hidden">
            <div class="relative h-32 bg-gradient-to-r from-emerald-100 via-green-100 to-blue-100"></div>
            <div class="relative px-6 pb-6">
                <div class="flex flex-col items-center -mt-16">
                    <div class="inline-block p-1.5 bg-white rounded-full shadow-lg mb-3">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center text-4xl text-emerald-700 font-bold border-4 border-white">
                            {{ strtoupper(substr(auth()->user()->paciente->primer_nombre ?? 'P', 0, 1)) }}
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ auth()->user()->paciente->primer_nombre ?? 'Usuario' }}
                        {{ auth()->user()->paciente->primer_apellido ?? '' }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-2">Paciente</p>
                    
                    <!-- Health Info -->
                    <div class="w-full space-y-2 mt-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Tipo de Sangre</span>
                            <span class="font-bold text-gray-900">{{ auth()->user()->paciente->tipo_sangre ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Edad</span>
                            <span class="font-bold text-gray-900">
                                {{ isset(auth()->user()->paciente->fecha_nacimiento) ? \Carbon\Carbon::parse(auth()->user()->paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="w-full grid grid-cols-3 gap-3 text-center border-t border-gray-100 pt-5 mt-5">
                        <div>
                            <span class="block font-bold text-gray-900 text-xl">{{ $stats['total_citas'] ?? 0 }}</span>
                            <span class="text-xs text-gray-500">Citas</span>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-900 text-xl">{{ $stats['recetas_activas'] ?? 0 }}</span>
                            <span class="text-xs text-gray-500">Recetas</span>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-900 text-xl">
                                {{ isset(auth()->user()->created_at) ? \Carbon\Carbon::parse(auth()->user()->created_at)->diffInMonths(\Carbon\Carbon::now()) : 0 }}
                            </span>
                            <span class="text-xs text-gray-500">Meses</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Directos -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-grid text-emerald-600"></i>
                Menú Rápido
            </h4>
            <div class="space-y-2">
                <a href="{{ url('index.php/paciente/citas/create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-50 text-gray-600 hover:text-emerald-700 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <span class="font-medium flex-1">Agendar Cita</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ url('index.php/paciente/historial') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 text-gray-600 hover:text-blue-700 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition-colors">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <span class="font-medium flex-1">Mi Historial</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ url('index.php/paciente/pagos') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-purple-50 text-gray-600 hover:text-purple-700 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-100 transition-colors">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <span class="font-medium flex-1">Mis Pagos</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 text-gray-600 hover:text-amber-700 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition-colors">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <span class="font-medium flex-1">Actualizar Datos</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Health Tips -->
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-white border-blue-200">
            <div class="flex gap-3">
                <i class="bi bi-lightbulb text-blue-600 text-2xl"></i>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Consejo de Salud</h4>
                    <p class="text-sm text-gray-600">Recuerda beber al menos 8 vasos de agua al día y mantener una alimentación balanceada para una mejor salud.</p>
                </div>
            </div>
        </div>

        <!-- Ayuda -->
        <div class="card p-6">
            <div class="flex gap-3">
                <i class="bi bi-question-circle text-emerald-600 text-2xl"></i>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">¿Necesitas ayuda?</h4>
                    <p class="text-sm text-gray-600 mb-3">Contacta con soporte para cualquier consulta</p>
                    <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold">
                        Contactar Soporte <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
