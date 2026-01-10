@extends('layouts.admin')

@section('title', 'Mantenimiento del Sistema')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Mantenimiento</h2>
    <p class="text-gray-500 mt-1">Herramientas de optimización y limpieza del sistema</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel Principal de Acciones -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Caché del Sistema -->
        <div class="card p-6 border-l-4 border-l-warning-500">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-warning-50 flex items-center justify-center">
                    <i class="bi bi-lightning-charge-fill text-warning-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Caché del Sistema</h3>
                    <p class="text-sm text-gray-500">Archivos temporales de configuración y rutas</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-4 text-sm text-gray-600">
                <p>Limpiar la caché puede resolver problemas de configuración no reflejada o errores en rutas recientes. Es seguro ejecutarlo en producción.</p>
            </div>

            <form action="{{ route('configuracion.mantenimiento.ejecutar') }}" method="POST">
                @csrf
                <input type="hidden" name="accion" value="limpiar_cache">
                <button type="submit" class="btn btn-warning w-full sm:w-auto">
                    <i class="bi bi-eraser-fill mr-2"></i>
                    Limpiar Caché General
                </button>
            </form>
        </div>

        <!-- Optimización -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-success-50 flex items-center justify-center">
                    <i class="bi bi-speedometer2 text-success-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Optimización</h3>
                    <p class="text-sm text-gray-500">Mejorar el rendimiento de carga</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <form action="{{ route('configuracion.mantenimiento.ejecutar') }}" method="POST" class="bg-white border border-gray-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                    @csrf
                    <input type="hidden" name="accion" value="optimizar">
                    <div class="mb-3">
                        <span class="badge badge-success mb-2">Recomendado</span>
                        <h4 class="font-bold text-gray-800">Optimizar Todo</h4>
                        <p class="text-xs text-gray-500 mt-1">Compila clases y rutas para carga rápida</p>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline w-full">Ejecutar</button>
                </form>

                <form action="{{ route('configuracion.mantenimiento.ejecutar') }}" method="POST" class="bg-white border border-gray-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                    @csrf
                    <input type="hidden" name="accion" value="limpiar_logs">
                    <div class="mb-3">
                        <span class="badge badge-gray mb-2">Espacio</span>
                        <h4 class="font-bold text-gray-800">Limpiar Logs</h4>
                        <p class="text-xs text-gray-500 mt-1">Elimina archivos de registro antiguos</p>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline w-full">Ejecutar</button>
                </form>
            </div>
        </div>

        <!-- Zona de Peligro -->
        <div class="card p-6 border-l-4 border-l-danger-500">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-danger-50 flex items-center justify-center">
                    <i class="bi bi-exclamation-octagon-fill text-danger-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Acciones Avanzadas</h3>
                    <p class="text-sm text-gray-500">Solo para desarrolladores o mantenimiento crítico</p>
                </div>
            </div>

            <div class="bg-danger-50 border border-danger-100 rounded-xl p-4 mb-4">
                <p class="text-sm text-danger-800 font-medium flex items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i>
                    Estas acciones pueden alterar la base de datos. Asegúrese de tener un backup reciente.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <form action="{{ route('configuracion.mantenimiento.ejecutar') }}" method="POST" onsubmit="return confirm('¿Está seguro? Esto modificará la estructura de la base de datos.')">
                    @csrf
                    <input type="hidden" name="accion" value="migrar">
                    <button class="btn btn-outline text-danger-600 border-danger-200 hover:bg-danger-50">
                        <i class="bi bi-database-fill-gear mr-2"></i>
                        Ejecutar Migraciones
                    </button>
                </form>
                
                <form action="{{ route('configuracion.mantenimiento.ejecutar') }}" method="POST" onsubmit="return confirm('ATENCIÓN: Esto insertará datos de prueba. ¿Desea continuar?')">
                    @csrf
                    <input type="hidden" name="accion" value="seed">
                    <button class="btn btn-outline text-gray-600 border-gray-200 hover:bg-gray-50">
                        <i class="bi bi-database-add mr-2"></i>
                        Ejecutar Seeders
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Informativo -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">Estado del Servidor</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-500">Uso de Memoria (Script)</span>
                        <span class="font-medium">{{ $stats['memory_usage'] }} / {{ $stats['memory_limit'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-success-500 h-2 rounded-full" style="width: {{ $stats['memory_percentage'] }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-500">Espacio en Disco ({{ $stats['disk_used'] }} ocupado)</span>
                        <span class="font-medium">{{ $stats['disk_percentage'] }}% Ocupado</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-medical-500 h-2 rounded-full" style="width: {{ $stats['disk_percentage'] }}%"></div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                        <i class="bi bi-check-circle text-success-500"></i>
                        <span>PHP v{{ phpversion() }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="bi bi-check-circle text-success-500"></i>
                        <span>Laravel v{{ app()->version() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-medical-600 text-white p-6">
            <h3 class="font-bold text-lg mb-2">¿Necesitas ayuda?</h3>
            <p class="text-medical-100 text-sm mb-4">Si tienes problemas persistentes después de ejecutar el mantenimiento, contacta a soporte.</p>
            <button class="btn bg-white text-medical-700 w-full hover:bg-medical-50 border-0">
                Contactar Soporte
            </button>
        </div>
    </div>
</div>
@endsection
