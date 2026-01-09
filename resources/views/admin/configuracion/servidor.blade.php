@extends('layouts.admin')

@section('title', 'Información del Servidor')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Información del Servidor</h2>
    <p class="text-gray-500 mt-1">Detalles técnicos y configuración del sistema</p>
</div>

<!-- Estado del Sistema -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-hdd text-success-600 text-2xl"></i>
            </div>
            <span class="badge badge-success">Óptimo</span>
        </div>
        <p class="text-sm text-gray-500 mb-1">Estado del Sistema</p>
        <p class="text-2xl font-bold text-gray-900">Operativo</p>
    </div>

    <div class="card p-6 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-cpu text-warning-600 text-2xl"></i>
            </div>
            <span class="text-xs text-gray-500">Uso actual</span>
        </div>
        <p class="text-sm text-gray-500 mb-1">CPU</p>
        <p class="text-2xl font-bold text-gray-900">45%</p>
    </div>

    <div class="card p-6 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-memory text-info-600 text-2xl"></i>
            </div>
            <span class="text-xs text-gray-500">Uso actual</span>
        </div>
        <p class="text-sm text-gray-500 mb-1">RAM</p>
        <p class="text-2xl font-bold text-gray-900">68%</p>
    </div>

    <div class="card p-6 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-database text-medical-600 text-2xl"></i>
            </div>
            <span class="text-xs text-gray-500">Uso actual</span>
        </div>
        <p class="text-sm text-gray-500 mb-1">Disco</p>
        <p class="text-2xl font-bold text-gray-900">52%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Información del Servidor -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-server text-medical-600"></i>
            Información del Servidor
        </h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Sistema Operativo:</span>
                <span class="font-medium text-gray-900">Windows 11 Pro</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Servidor Web:</span>
                <span class="font-medium text-gray-900">Apache 2.4.54</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Versión PHP:</span>
                <span class="font-medium text-gray-900">PHP 8.2.12</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">MySQL:</span>
                <span class="font-medium text-gray-900">8.0.30</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Laravel:</span>
                <span class="font-medium text-gray-900">10.x</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Tiempo Activo:</span>
                <span class="font-medium text-gray-900">15 días, 8 horas</span>
            </div>
        </div>
    </div>

    <!-- Configuración PHP -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-code-square text-success-600"></i>
            Configuración PHP
        </h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Max Execution Time:</span>
                <span class="font-medium text-gray-900">300 seg</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Memory Limit:</span>
                <span class="font-medium text-gray-900">512M</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Upload Max Filesize:</span>
                <span class="font-medium text-gray-900">64M</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Post Max Size:</span>
                <span class="font-medium text-gray-900">128M</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Max Input Vars:</span>
                <span class="font-medium text-gray-900">5000</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Timezone:</span>
                <span class="font-medium text-gray-900">America/Caracas</span>
            </div>
        </div>
    </div>

    <!-- Base de Datos -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-database text-warning-600"></i>
            Base de Datos
        </h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Motor:</span>
                <span class="font-medium text-gray-900">MySQL 8.0.30</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Nombre BD:</span>
                <span class="font-medium text-gray-900">reserva_medica</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Tamaño:</span>
                <span class="font-medium text-gray-900">245.8 MB</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Tablas:</span>
                <span class="font-medium text-gray-900">48</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Total Registros:</span>
                <span class="font-medium text-gray-900">12,847</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Último Backup:</span>
                <span class="font-medium text-gray-900">Hoy, 02:00 AM</span>
            </div>
        </div>
    </div>

    <!-- Recursos del Sistema -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-speedometer2 text-info-600"></i>
            Recursos del Sistema
        </h3>
        
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Uso de CPU</span>
                    <span class="font-medium text-gray-900">45%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="bg-warning-500 h-3 rounded-full transition-all" style="width: 45%"></div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Uso de RAM (8GB)</span>
                    <span class="font-medium text-gray-900">5.4GB (68%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="bg-info-500 h-3 rounded-full transition-all" style="width: 68%"></div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Disco (500GB)</span>
                    <span class="font-medium text-gray-900">260GB (52%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="bg-medical-500 h-3 rounded-full transition-all" style="width: 52%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Extensiones PHP -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-puzzle text-medical-600"></i>
            Extensiones PHP Activas
        </h3>
        <div class="grid grid-cols-2 gap-2">
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">PDO</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">MySQLi</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">OpenSSL</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">Mbstring</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">Tokenizer</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">XML</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">cURL</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">GD</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">Fileinfo</span>
            </div>
            <div class="flex items-center gap-2 p-2 bg-success-50 rounded text-sm">
                <i class="bi bi-check-circle-fill text-success-600"></i>
                <span class="text-gray-700">Zip</span>
            </div>
        </div>
    </div>

    <!-- Información de Laravel -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-gear text-danger-600"></i>
            Configuración Laravel
        </h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Versión:</span>
                <span class="font-medium text-gray-900">10.x</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Entorno:</span>
                <span class="badge badge-success">production</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Debug:</span>
                <span class="badge badge-danger">Desactivado</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Caché:</span>
                <span class="badge badge-success">Activo</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Driver de Caché:</span>
                <span class="font-medium text-gray-900">file</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Driver de Sesión:</span>
                <span class="font-medium text-gray-900">database</span>
            </div>
        </div>
    </div>
</div>
@endsection
