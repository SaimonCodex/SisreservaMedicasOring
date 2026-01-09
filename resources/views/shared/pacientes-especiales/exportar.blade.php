@extends('layouts.admin')

@section('title', 'Exportar Datos - Pacientes Especiales')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Exportar Datos</h2>
            <p class="text-gray-500 mt-1">Exportación de datos de pacientes especiales</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Formulario de Exportación -->
    <div class="lg:col-span-2">
        <form action="{{ route('pacientes-especiales.exportar.procesar') }}" method="POST">
            @csrf
            
            <!-- Formato de Exportación -->
            <div class="card p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-arrow-down text-warning-600"></i>
                    Formato de Exportación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="card p-6 cursor-pointer hover:border-success-500 transition-colors text-center">
                        <input type="radio" name="formato" value="excel" class="form-radio mb-3" checked>
                        <i class="bi bi-file-excel text-success-600 text-5xl block mb-2"></i>
                        <p class="font-semibold text-gray-900 mb-1">Excel (.xlsx)</p>
                        <p class="text-xs text-gray-600">Hoja de cálculo editable</p>
                    </label>

                    <label class="card p-6 cursor-pointer hover:border-info-500 transition-colors text-center">
                        <input type="radio" name="formato" value="csv" class="form-radio mb-3">
                        <i class="bi bi-filetype-csv text-info-600 text-5xl block mb-2"></i>
                        <p class="font-semibold text-gray-900 mb-1">CSV</p>
                        <p class="text-xs text-gray-600">Valores separados por comas</p>
                    </label>

                    <label class="card p-6 cursor-pointer hover:border-purple-500 transition-colors text-center">
                        <input type="radio" name="formato" value="json" class="form-radio mb-3">
                        <i class="bi bi-filetype-json text-purple-600 text-5xl block mb-2"></i>
                        <p class="font-semibold text-gray-900 mb-1">JSON</p>
                        <p class="text-xs text-gray-600">Formato para desarrolladores</p>
                    </label>
                </div>
            </div>

            <!-- Campos a Exportar -->
            <div class="card p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-list-check text-warning-600"></i>
                    Campos a Exportar
                </h3>
                
                <div class="mb-4">
                    <button type="button" class="btn btn-sm btn-outline" onclick="selectAll()">
                        <i class="bi bi-check-all mr-2"></i>
                        Seleccionar Todos
                    </button>
                    <button type="button" class="btn btn-sm btn-outline ml-2" onclick="deselectAll()">
                        <i class="bi bi-x-lg mr-2"></i>
                        Deseleccionar Todos
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Datos Básicos -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-900 mb-3">Datos Básicos</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="numero_historia" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Número de Historia</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="nombres" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Nombres Completos</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="apellidos" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Apellidos Completos</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="documento" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Documento de Identidad</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="fecha_nacimiento" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Fecha de Nacimiento</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="edad" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Edad</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="genero" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Género</span>
                            </label>
                        </div>
                    </div>

                    <!-- Condición Especial -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-900 mb-3">Condición Especial</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="tipo_condicion" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Tipo de Condición</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="observaciones_medicas" class="form-checkbox campos-check">
                                <span class="text-sm">Observaciones Médicas</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="status" class="form-checkbox campos-check">
                                <span class="text-sm">Estado (Activo/Inactivo)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Representante -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-900 mb-3">Representante Legal</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="representante_nombre" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Nombre del Representante</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="representante_documento" class="form-checkbox campos-check">
                                <span class="text-sm">Documento del Representante</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="parentesco" class="form-checkbox campos-check" checked>
                                <span class="text-sm">Parentesco</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="representante_telefono" class="form-checkbox campos-check">
                                <span class="text-sm">Teléfono del Representante</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="representante_email" class="form-checkbox campos-check">
                                <span class="text-sm">Email del Representante</span>
                            </label>
                        </div>
                    </div>

                    <!-- Fechas de Registro -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-900 mb-3">Fechas de Registro</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="created_at" class="form-checkbox campos-check">
                                <span class="text-sm">Fecha de Registro</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="campos[]" value="updated_at" class="form-checkbox campos-check">
                                <span class="text-sm">Última Modificación</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros de Datos -->
            <div class="card p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-funnel text-warning-600"></i>
                    Filtros de Datos
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tipo de Condición</label>
                        <select name="filtro_condicion" class="form-select">
                            <option value="">Todas</option>
                            <option value="menor_edad">Menor de Edad</option>
                            <option value="discapacidad">Discapacidad</option>
                            <option value="adulto_mayor">Adulto Mayor</option>
                            <option value="incapacidad_legal">Incapacidad Legal</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Estado</label>
                        <select name="filtro_status" class="form-select">
                            <option value="">Todos</option>
                            <option value="1">Solo Activos</option>
                            <option value="0">Solo Inactivos</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Fecha de Registro Desde</label>
                        <input type="date" name="fecha_desde" class="input">
                    </div>

                    <div>
                        <label class="form-label">Fecha de Registro Hasta</label>
                        <input type="date" name="fecha_hasta" class="input">
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="card p-6">
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="bi bi-download mr-2"></i>
                        Exportar Datos
                    </button>
                    <button type="reset" class="btn btn-outline">
                        <i class="bi bi-arrow-clockwise mr-2"></i>
                        Restablecer
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        
        <!-- Resumen -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-info-circle text-info-600 mr-2"></i>
                Resumen de Exportación
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total de Pacientes:</span>
                    <span class="font-bold text-gray-900" id="totalPacientes">{{ $totalPacientes ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Formato:</span>
                    <span class="font-bold text-success-600">Excel</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Campos:</span>
                    <span class="font-bold text-warning-600" id="totalCampos">7</span>
                </div>
            </div>
        </div>

        <!-- Información -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-lightbulb text-warning-600 mr-2"></i>
                Información
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Seleccione el formato de exportación deseado</p>
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Marque los campos que desea incluir</p>
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Puede aplicar filtros para exportar datos específicos</p>
                <p><i class="bi bi-check-circle text-success-600 mr-2"></i>La descarga comenzará automáticamente</p>
            </div>
        </div>

        <!-- Formatos Disponibles -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-file-earmark-text text-medical-600 mr-2"></i>
                Formatos Disponibles
            </h3>
            
            <div class="space-y-2 text-sm">
                <div class="p-3 bg-success-50 rounded-lg">
                    <p class="font-semibold text-success-900 flex items-center gap-2">
                        <i class="bi bi-file-excel"></i>
                        Excel (.xlsx)
                    </p>
                    <p class="text-gray-600 mt-1">Ideal para análisis y edición de datos</p>
                </div>

                <div class="p-3 bg-info-50 rounded-lg">
                    <p class="font-semibold text-info-900 flex items-center gap-2">
                        <i class="bi bi-filetype-csv"></i>
                        CSV
                    </p>
                    <p class="text-gray-600 mt-1">Compatible con cualquier software</p>
                </div>

                <div class="p-3 bg-purple-50 rounded-lg">
                    <p class="font-semibold text-purple-900 flex items-center gap-2">
                        <i class="bi bi-filetype-json"></i>
                        JSON
                    </p>
                    <p class="text-gray-600 mt-1">Para integración con sistemas externos</p>
                </div>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function selectAll() {
        document.querySelectorAll('.campos-check').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateCamposCount();
    }

    function deselectAll() {
        document.querySelectorAll('.campos-check').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateCamposCount();
    }

    function updateCamposCount() {
        const count = document.querySelectorAll('.campos-check:checked').length;
        document.getElementById('totalCampos').textContent = count;
    }

    // Update count on checkbox change
    document.querySelectorAll('.campos-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateCamposCount);
    });
</script>
@endpush

@endsection
