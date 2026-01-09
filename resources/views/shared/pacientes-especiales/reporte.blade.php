@extends('layouts.admin')

@section('title', 'Generar Reporte - Pacientes Especiales')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Generar Reporte</h2>
            <p class="text-gray-500 mt-1">Reportes personalizados de pacientes especiales</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Formulario de Configuración -->
    <div class="lg:col-span-2">
        <form action="{{ route('pacientes-especiales.reporte.generar') }}" method="POST" target="_blank">
            @csrf
            
            <!-- Tipo de Reporte -->
            <div class="card p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-text text-warning-600"></i>
                    Tipo de Reporte
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="card p-4 cursor-pointer hover:border-warning-500 transition-colors">
                        <input type="radio" name="tipo_reporte" value="general" class="form-radio" checked>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Reporte General</p>
                            <p class="text-sm text-gray-600">Listado completo de pacientes especiales</p>
                        </div>
                    </label>

                    <label class="card p-4 cursor-pointer hover:border-warning-500 transition-colors">
                        <input type="radio" name="tipo_reporte" value="por_condicion" class="form-radio">
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Por Condición</p>
                            <p class="text-sm text-gray-600">Agrupado por tipo de condición especial</p>
                        </div>
                    </label>

                    <label class="card p-4 cursor-pointer hover:border-warning-500 transition-colors">
                        <input type="radio" name="tipo_reporte" value="por_representante" class="form-radio">
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Por Representante</p>
                            <p class="text-sm text-gray-600">Agrupado por representante legal</p>
                        </div>
                    </label>

                    <label class="card p-4 cursor-pointer hover:border-warning-500 transition-colors">
                        <input type="radio" name="tipo_reporte" value="estadistico" class="form-radio">
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Estadístico</p>
                            <p class="text-sm text-gray-600">Análisis y métricas generales</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-funnel text-warning-600"></i>
                    Filtros de Datos
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Fecha Inicio -->
                    <div>
                        <label class="form-label">Fecha de Registro Desde</label>
                        <input type="date" name="fecha_inicio" class="input" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                    </div>

                    <!-- Fecha Fin -->
                    <div>
                        <label class="form-label">Fecha de Registro Hasta</label>
                        <input type="date" name="fecha_fin" class="input" value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <!-- Tipo de Condición -->
                    <div>
                        <label class="form-label">Tipo de Condición</label>
                        <select name="tipo_condicion" class="form-select">
                            <option value="">Todas</option>
                            <option value="menor_edad">Menor de Edad</option>
                            <option value="discapacidad">Discapacidad</option>
                            <option value="adulto_mayor">Adulto Mayor</option>
                            <option value="incapacidad_legal">Incapacidad Legal</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <!-- Género -->
                    <div>
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-select">
                            <option value="">Todos</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>

                    <!-- Rango de Edad -->
                    <div>
                        <label class="form-label">Rango de Edad</label>
                        <select name="rango_edad" class="form-select">
                            <option value="">Todos</option>
                            <option value="0-5">0-5 años</option>
                            <option value="6-12">6-12 años</option>
                            <option value="13-17">13-17 años</option>
                            <option value="18-60">18-60 años</option>
                            <option value="60+">60+ años</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Opciones de Formato -->
            <div class="card p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-arrow-down text-warning-600"></i>
                    Formato de Salida
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="card p-4 cursor-pointer hover:border-danger-500 transition-colors">
                        <input type="radio" name="formato" value="pdf" class="form-radio" checked>
                        <div class="ml-3 flex items-center gap-3">
                            <i class="bi bi-file-pdf text-danger-600 text-3xl"></i>
                            <div>
                                <p class="font-semibold text-gray-900">PDF</p>
                                <p class="text-sm text-gray-600">Documento imprimible</p>
                            </div>
                        </div>
                    </label>

                    <label class="card p-4 cursor-pointer hover:border-success-500 transition-colors">
                        <input type="radio" name="formato" value="excel" class="form-radio">
                        <div class="ml-3 flex items-center gap-3">
                            <i class="bi bi-file-excel text-success-600 text-3xl"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Excel</p>
                                <p class="text-sm text-gray-600">Hoja de cálculo</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Incluir en el Reporte -->
            <div class="card p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-list-check text-warning-600"></i>
                    Incluir en el Reporte
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="incluir[]" value="datos_personales" class="form-checkbox" checked>
                        <span class="text-sm text-gray-900">Datos Personales</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="incluir[]" value="condicion" class="form-checkbox" checked>
                        <span class="text-sm text-gray-900">Tipo de Condición</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="incluir[]" value="representante" class="form-checkbox" checked>
                        <span class="text-sm text-gray-900">Datos del Representante</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="incluir[]" value="observaciones" class="form-checkbox">
                        <span class="text-sm text-gray-900">Observaciones Médicas</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="incluir[]" value="citas" class="form-checkbox">
                        <span class="text-sm text-gray-900">Historial de Citas</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="incluir[]" value="estadisticas" class="form-checkbox">
                        <span class="text-sm text-gray-900">Estadísticas</span>
                    </label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="card p-6">
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="bi bi-file-earmark-arrow-down mr-2"></i>
                        Generar Reporte
                    </button>
                    <button type="button" class="btn btn-outline" onclick="document.querySelector('form').reset()">
                        <i class="bi bi-arrow-clockwise mr-2"></i>
                        Restablecer
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        
        <!-- Información -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-info-circle text-info-600 mr-2"></i>
                Información
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Seleccione el tipo de reporte deseado</p>
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Configure los filtros según sus necesidades</p>
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Elija el formato de salida (PDF o Excel)</p>
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>El reporte se abrirá en una nueva pestaña</p>
            </div>
        </div>

        <!-- Reportes Recientes -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-clock-history text-warning-600 mr-2"></i>
                Reportes Recientes
            </h3>
            
            <div class="space-y-2">
                @for($i = 0; $i < 3; $i++)
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-file-pdf text-danger-600 text-xl"></i>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Reporte General</p>
                            <p class="text-xs text-gray-500">{{ now()->subDays($i)->format('d/m/Y H:i') }}</p>
                        </div>
                        <i class="bi bi-download text-gray-400"></i>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Tipos de Reporte -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-journal-text text-medical-600 mr-2"></i>
                Tipos de Reporte
            </h3>
            
            <div class="space-y-2 text-sm">
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">General</p>
                    <p class="text-gray-600">Listado completo con todos los pacientes</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Por Condición</p>
                    <p class="text-gray-600">Agrupado por tipo de condición especial</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Por Representante</p>
                    <p class="text-gray-600">Agrupado por representante legal</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Estadístico</p>
                    <p class="text-gray-600">Análisis con gráficos y métricas</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
