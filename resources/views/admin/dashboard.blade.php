@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')

@section('content')
<!-- Welcome Banner -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 shadow-xl mb-8">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/20 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="relative z-10 p-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-white text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-display font-bold mb-2">
                    Panel de Administración
                </h2>
                <p class="text-white/90 text-lg flex items-center gap-2 justify-center md:justify-start">
                    <i class="bi bi-calendar3"></i>
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ url('index.php/configuracion') }}" class="btn bg-white text-blue-700 hover:bg-gray-50 border-none shadow-md">
                    <i class="bi bi-gear"></i> Configuración
                </a>
            </div>
        </div>
        
        <!-- Mini Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-person-badge text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['medicos'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Médicos</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-people text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['pacientes'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Pacientes</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-calendar-check text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['citas_hoy'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Citas Hoy</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-currency-dollar text-2xl mb-2"></i>
                <p class="text-2xl font-bold">${{ number_format($stats['ingresos_mes'] ?? 0, 0) }}</p>
                <p class="text-sm text-white/80">Ingresos</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white">
                <i class="bi bi-person-check text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['usuarios_activos'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Usuarios Activos</p>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Médicos -->
    <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-blue-700 mb-2">Médicos Activos</p>
                <h3 class="text-4xl font-display font-bold text-blue-900">{{ $stats['medicos_activos'] ?? 0 }}</h3>
                <p class="text-sm text-blue-600 mt-2 flex items-center gap-1">
                    <i class="bi bi-graph-up"></i> +{{ $stats['medicos_nuevos_mes'] ?? 0 }} este mes
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-person-badge text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-blue-200">
            <a href="{{ url('index.php/shared/medicos') }}" class="text-blue-700 hover:text-blue-900 font-semibold text-sm flex items-center gap-1">
                Gestionar médicos <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Pacientes -->
    <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-emerald-700 mb-2">Total Pacientes</p>
                <h3 class="text-4xl font-display font-bold text-emerald-900">{{ $stats['total_pacientes'] ?? 0 }}</h3>
                <p class="text-sm text-emerald-600 mt-2 flex items-center gap-1">
                    <i class="bi bi-person-plus"></i> +{{ $stats['pacientes_nuevos_semana'] ?? 0 }} esta semana
                </p>
            </div>
            <div class="w-14 h-14 bg-emerald-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-people text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-emerald-200">
            <a href="{{ url('index.php/shared/pacientes') }}" class="text-emerald-700 hover:text-emerald-900 font-semibold text-sm flex items-center gap-1">
                Gestionar pacientes <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Citas -->
    <div class="card p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-purple-700 mb-2">Citas del Día</p>
                <h3 class="text-4xl font-display font-bold text-purple-900">{{ $stats['citas_hoy'] ?? 0 }}</h3>
                <p class="text-sm text-purple-600 mt-2">
                    {{ $stats['citas_completadas_hoy'] ?? 0 }} completadas
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-calendar-check text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-purple-200">
            <a href="{{ url('index.php/shared/citas') }}" class="text-purple-700 hover:text-purple-900 font-semibold text-sm flex items-center gap-1">
                Ver agenda <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Ingresos -->
    <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-amber-700 mb-2">Ingresos del Mes</p>
                <h3 class="text-4xl font-display font-bold text-amber-900">${{ number_format($stats['ingresos_mes'] ?? 0, 0) }}</h3>
                <p class="text-sm text-amber-600 mt-2 flex items-center gap-1">
                    <i class="bi bi-arrow-up"></i> +{{ $stats['crecimiento_ingresos'] ?? 0 }}% vs mes anterior
                </p>
            </div>
            <div class="w-14 h-14 bg-amber-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-currency-dollar text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-amber-200">
            <a href="#" class="text-amber-700 hover:text-amber-900 font-semibold text-sm flex items-center gap-1">
                Ver reportes <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Charts & Activity -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Activity Chart -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-graph-up text-blue-600"></i>
                        Resumen de Actividad
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Citas de los últimos 7 días</p>
                </div>
                <select class="form-select text-sm w-auto py-1.5 px-3">
                    <option>Últimos 7 días</option>
                    <option>Último mes</option>
                    <option>Este año</option>
                </select>
            </div>
            
            <!-- Chart -->
            <div class="h-64 flex items-end justify-between gap-2 px-2">
                @foreach([40, 65, 45, 80, 55, 70, 60] as $index => $h)
                <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t-lg relative group hover:from-blue-600 hover:to-blue-400 transition-all cursor-pointer" style="height: {{ $h }}%">
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                        {{ $h }} citas
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-4 text-xs text-gray-500 px-2 font-medium">
                <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
            </div>
        </div>

        <!-- System Health -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-heart-pulse text-rose-600"></i>
                Salud del Sistema
            </h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-emerald-50 rounded-xl">
                    <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-2">
                        <i class="bi bi-cloud-check text-emerald-600 text-2xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Servidor</p>
                    <p class="text-xs text-emerald-600 mt-1">Operativo</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 flex items-center justify-center mb-2">
                        <i class="bi bi-database text-blue-600 text-2xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Base de Datos</p>
                    <p class="text-xs text-blue-600 mt-1">Conectada</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-xl">
                    <div class="w-16 h-16 mx-auto rounded-full bg-purple-100 flex items-center justify-center mb-2">
                        <i class="bi bi-shield-check text-purple-600 text-2xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Seguridad</p>
                    <p class="text-xs text-purple-600 mt-1">Activa</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="card">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-activity text-emerald-600"></i>
                    Actividad Reciente
                </h3>
            </div>
            <div class="divide-y divide-gray-100 max-h-[400px] overflow-y-auto">
                @forelse($actividadReciente ?? [] as $actividad)
                <div class="p-4 hover:bg-gray-50 transition-colors flex gap-3">
                    <div class="w-10 h-10 rounded-full {{ $actividad->tipo_clase ?? 'bg-blue-100' }} flex items-center justify-center flex-shrink-0">
                        <i class="bi {{ $actividad->icono ?? 'bi-check' }} {{ $actividad->icono_clase ?? 'text-blue-600' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800">{{ $actividad->descripcion ?? 'Actividad' }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ isset($actividad->created_at) ? \Carbon\Carbon::parse($actividad->created_at)->diffForHumans() : 'Hace unos momentos' }}
                        </p>
                    </div>
                </div>
                @empty
                <!-- Placeholder activity -->
                <div class="p-4 hover:bg-gray-50 transition-colors flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-check text-emerald-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800"><span class="font-semibold">Dr. Pérez</span> completó una cita.</p>
                        <p class="text-xs text-gray-500 mt-1">Hace 5 min</p>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-person-plus text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">Nuevo paciente: <span class="font-semibold">María González</span></p>
                        <p class="text-xs text-gray-500 mt-1">Hace 15 min</p>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-calendar-plus text-amber-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">Cita agendada para <span class="font-semibold">Mañana 10:00 AM</span></p>
                        <p class="text-xs text-gray-500 mt-1">Hace 1 hora</p>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                <a href="#" class="text-sm text-blue-600 font-medium hover:text-blue-700">Ver toda la actividad →</a>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-6">
        <!-- Pending Tasks -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-list-check text-amber-600"></i>
                Tareas Pendientes
            </h3>
            <div class="space-y-3">
                <div class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                    <p class="font-semibold text-gray-900 text-sm">{{ $tareas['citas_sin_confirmar'] ?? 0 }} citas sin confirmar</p>
                    <p class="text-xs text-gray-600 mt-1">Requieren atención</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-lg border border-rose-200">
                    <p class="font-semibold text-gray-900 text-sm">{{ $tareas['pagos_pendientes'] ?? 0 }} pagos pendientes</p>
                    <p class="text-xs text-gray-600 mt-1">Por procesar</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="font-semibold text-gray-900 text-sm">{{ $tareas['resultados_pendientes'] ?? 0 }} resultados pendientes</p>
                    <p class="text-xs text-gray-600 mt-1">Laboratorios por entregar</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-lightning-charge text-amber-600"></i>
                Acciones Rápidas
            </h3>
            <div class="space-y-2">
                <a href="{{ url('index.php/shared/medicos/create') }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-person-badge"></i> Nuevo Médico
                </a>
                <a href="{{ url('index.php/shared/pacientes/create') }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-person-plus"></i> Nuevo Paciente
                </a>
                <a href="{{ url('index.php/shared/citas/create') }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-plus"></i> Agendar Cita
                </a>
                <a href="{{ url('index.php/admin/administradores/create') }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-shield-check"></i> Nuevo Admin
                </a>
            </div>
        </div>

        <!-- System Info -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información del Sistema</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <span class="text-gray-600">Versión</span>
                    <span class="font-semibold text-gray-900">1.0.0</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <span class="text-gray-600">Uptime</span>
                    <span class="font-semibold text-gray-900">99.9%</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <span class="text-gray-600">Almacenamiento</span>
                    <span class="font-semibold text-gray-900">45% usado</span>
                </div>
            </div>
        </div>

        <!-- Shortcuts Grid -->
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Enlaces Rápidos</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ url('index.php/shared/especialidades') }}" class="p-3 text-center bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                    <i class="bi bi-bookmark text-2xl text-blue-600 mb-1 block group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Especialidades</span>
                </a>
                <a href="{{ url('index.php/shared/consultorios') }}" class="p-3 text-center bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors group">
                    <i class="bi bi-building text-2xl text-purple-600 mb-1 block group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Consultorios</span>
                </a>
                <a href="{{ url('index.php/configuracion') }}" class="p-3 text-center bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors group">
                    <i class="bi bi-gear text-2xl text-emerald-600 mb-1 block group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Configuración</span>
                </a>
                <a href="#" class="p-3 text-center bg-amber-50 hover:bg-amber-100 rounded-xl transition-colors group">
                    <i class="bi bi-file-earmark-bar-graph text-2xl text-amber-600 mb-1 block group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Reportes</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
