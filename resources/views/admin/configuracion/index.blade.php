@extends('layouts.admin')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-display font-bold text-gray-900">Configuración del Sistema</h2>
    <p class="text-gray-500 mt-2">Centro de control para parámetros globales, seguridad y ajustes avanzados</p>
</div>

<!-- Grid de Módulos de Configuración -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <!-- General -->
    <a href="{{ route('configuracion.general') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-medical-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-gear-fill text-2xl text-white"></i>
            </div>
            <span class="badge badge-success text-xs">Activo</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-medical-600 transition-colors mb-2">Configuración General</h3>
        <p class="text-sm text-gray-500 mb-4">Identidad, logos, redes sociales y datos de contacto</p>
        <div class="flex items-center text-medical-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Configurar</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Reparto de Ganancias -->
    <a href="{{ route('configuracion.reparto') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-success-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-pie-chart-fill text-2xl text-white"></i>
            </div>
            <span class="badge badge-warning text-xs">Importante</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-success-600 transition-colors mb-2">Reparto de Ganancias</h3>
        <p class="text-sm text-gray-500 mb-4">Porcentajes de distribución entre médicos y clínica</p>
        <div class="flex items-center text-success-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Configurar</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Tasas de Cambio -->
    <a href="{{ route('configuracion.tasas') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-warning-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-currency-exchange text-2xl text-white"></i>
            </div>
            <span class="badge badge-info text-xs">Auto</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-warning-600 transition-colors mb-2">Tasas e Impuestos</h3>
        <p class="text-sm text-gray-500 mb-4">Control de divisas, IVA y tipos de cambio</p>
        <div class="flex items-center text-warning-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Configurar</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Métodos de Pago -->
    <a href="{{ route('configuracion.metodos-pago') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-info-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-info-500 to-info-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-credit-card-fill text-2xl text-white"></i>
            </div>
            <span class="badge badge-medical text-xs">3 Activos</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-info-600 transition-colors mb-2">Métodos de Pago</h3>
        <p class="text-sm text-gray-500 mb-4">Bancos, pasarelas y formas de pago aceptadas</p>
        <div class="flex items-center text-info-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Configurar</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Correo SMTP -->
    <a href="{{ route('configuracion.correo') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-premium-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-premium-500 to-premium-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-envelope-at-fill text-2xl text-white"></i>
            </div>
            <span class="badge badge-success text-xs">Conectado</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-premium-600 transition-colors mb-2">Configuración de Correo</h3>
        <p class="text-sm text-gray-500 mb-4">Servidor SMTP y plantillas de notificaciones</p>
        <div class="flex items-center text-premium-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Configurar</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Mantenimiento -->
    <a href="{{ route('configuracion.mantenimiento') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-danger-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-danger-500 to-danger-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-tools text-2xl text-white"></i>
            </div>
            <span class="badge badge-danger text-xs">Crítico</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-danger-600 transition-colors mb-2">Modo Mantenimiento</h3>
        <p class="text-sm text-gray-500 mb-4">Activar downtime, limpiar caché y optimización</p>
        <div class="flex items-center text-danger-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Configurar</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Copias de Seguridad -->
    <a href="{{ route('configuracion.backup') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-gray-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-hdd-network-fill text-2xl text-white"></i>
            </div>
            <span class="badge badge-gray text-xs">Ultimo: Ayer</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-gray-700 transition-colors mb-2">Copias de Seguridad</h3>
        <p class="text-sm text-gray-500 mb-4">Backups automáticos y restauración de BD</p>
        <div class="flex items-center text-gray-700 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Configurar</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Logs del Sistema -->
    <a href="{{ route('configuracion.logs') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-amber-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-file-earmark-text-fill text-2xl text-white"></i>
            </div>
            <span class="badge badge-warning text-xs">2 Nuevos</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-amber-600 transition-colors mb-2">Registros del Sistema</h3>
        <p class="text-sm text-gray-500 mb-4">Visor de logs, errores y actividad del sistema</p>
        <div class="flex items-center text-amber-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Ver Logs</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Estadísticas del Sistema -->
    <a href="{{ route('configuracion.estadisticas') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-medical-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-graph-up-arrow text-2xl text-white"></i>
            </div>
            <span class="badge badge-info text-xs">Mes Actual</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-medical-600 transition-colors mb-2">Estadísticas</h3>
        <p class="text-sm text-gray-500 mb-4">Métricas, KPIs y análisis de rendimiento</p>
        <div class="flex items-center text-medical-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Ver Dashboard</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    <!-- Información del Servidor -->
    <a href="{{ route('configuracion.servidor') }}" class="group card p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-purple-500">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <i class="bi bi-server text-2xl text-white"></i>
            </div>
            <span class="badge badge-success text-xs">Online</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors mb-2">Info del Servidor</h3>
        <p class="text-sm text-gray-500 mb-4">Detalles técnicos y configuración del sistema</p>
        <div class="flex items-center text-purple-600 font-medium text-sm group-hover:gap-2 transition-all">
            <span>Ver Detalles</span>
            <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

</div>

<!-- Alertas de Estado -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-success-50 border border-success-200 rounded-xl p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-success-500 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-check-circle-fill text-white"></i>
        </div>
        <div>
            <p class="font-bold text-success-800">Sistema Operativo</p>
            <p class="text-xs text-success-600">Todos los servicios funcionando correctamente</p>
        </div>
    </div>
    
    <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-warning-500 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill text-white"></i>
        </div>
        <div>
            <p class="font-bold text-warning-800">Backup Pendiente</p>
            <p class="text-xs text-warning-600">Último respaldo hace 25 horas</p>
        </div>
    </div>
    
    <div class="bg-info-50 border border-info-200 rounded-xl p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-info-500 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-info-circle-fill text-white"></i>
        </div>
        <div>
            <p class="font-bold text-info-800">Actualización Disponible</p>
            <p class="text-xs text-info-600">Versión 2.1.4 disponible</p>
        </div>
    </div>
</div>
@endsection
