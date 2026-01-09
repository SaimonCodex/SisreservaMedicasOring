@extends('layouts.admin')

@section('title', 'Importar Datos - Pacientes Especiales')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Importar Datos</h2>
            <p class="text-gray-500 mt-1">Importación masiva de pacientes especiales desde archivo</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Formulario de Importación -->
    <div class="lg:col-span-2">
        
        <!-- Paso 1: Subir Archivo -->
        <div class="card p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-warning-600 text-white flex items-center justify-center text-sm font-bold">1</span>
                Subir Archivo
            </h3>
            
            <form action="{{ route('pacientes-especiales.importar.procesar') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                
                <!-- Seleccionar Formato -->
                <div class="mb-6">
                    <label class="form-label">Formato de Archivo</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="card p-4 cursor-pointer hover:border-success-500 transition-colors">
                            <input type="radio" name="formato" value="excel" class="form-radio" checked>
                            <div class="ml-3 flex items-center gap-3">
                                <i class="bi bi-file-excel text-success-600 text-3xl"></i>
                                <div>
                                    <p class="font-semibold text-gray-900">Excel (.xlsx, .xls)</p>
                                    <p class="text-sm text-gray-600">Formato recomendado</p>
                                </div>
                            </div>
                        </label>

                        <label class="card p-4 cursor-pointer hover:border-info-500 transition-colors">
                            <input type="radio" name="formato" value="csv" class="form-radio">
                            <div class="ml-3 flex items-center gap-3">
                                <i class="bi bi-filetype-csv text-info-600 text-3xl"></i>
                                <div>
                                    <p class="font-semibold text-gray-900">CSV</p>
                                    <p class="text-sm text-gray-600">Valores separados por comas</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Área de Carga -->
                <div class="mb-6">
                    <label class="form-label">Seleccionar Archivo</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-warning-500 transition-colors cursor-pointer" onclick="document.getElementById('fileInput').click()">
                        <i class="bi bi-cloud-upload text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-900 font-semibold mb-2">Haga clic para seleccionar o arrastre el archivo aquí</p>
                        <p class="text-sm text-gray-500 mb-4">Archivos Excel (.xlsx, .xls) o CSV - Máx. 10MB</p>
                        <input type="file" name="archivo" id="fileInput" class="hidden" accept=".xlsx,.xls,.csv" required onchange="showFileName(this)">
                        <div id="fileName" class="hidden mt-4 p-3 bg-success-50 rounded-lg">
                            <p class="text-success-900 font-semibold flex items-center justify-center gap-2">
                                <i class="bi bi-file-check"></i>
                                <span id="fileNameText"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Opciones de Importación -->
                <div class="mb-6">
                    <label class="form-label">Opciones de Importación</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <input type="checkbox" name="actualizar_existentes" class="form-checkbox" checked>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Actualizar registros existentes</p>
                                <p class="text-xs text-gray-600">Los pacientes con el mismo documento serán actualizados</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <input type="checkbox" name="omitir_errores" class="form-checkbox">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Omitir filas con errores</p>
                                <p class="text-xs text-gray-600">Continuar la importación aunque haya errores en algunas filas</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <input type="checkbox" name="enviar_notificaciones" class="form-checkbox">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Enviar notificaciones</p>
                                <p class="text-xs text-gray-600">Notificar por email a los representantes creados</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="bi bi-upload mr-2"></i>
                        Iniciar Importación
                    </button>
                    <button type="reset" class="btn btn-outline" onclick="resetForm()">
                        <i class="bi bi-arrow-clockwise mr-2"></i>
                        Limpiar
                    </button>
                </div>
            </form>
        </div>

        <!-- Paso 2: Mapeo de Columnas (Se muestra después de subir) -->
        <div class="card p-6 mb-6" id="mappingSection" style="display: none;">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-warning-600 text-white flex items-center justify-center text-sm font-bold">2</span>
                Mapeo de Columnas
            </h3>
            
            <p class="text-gray-600 mb-4">Asigne las columnas de su archivo a los campos del sistema</p>
            
            <div class="space-y-3">
                <!-- Ejemplo de mapeo -->
                <div class="grid grid-cols-2 gap-4 p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-500">Columna del Archivo</p>
                        <p class="font-semibold text-gray-900">Nombre Completo</p>
                    </div>
                    <div>
                        <select class="form-select">
                            <option value="">-- Seleccionar campo --</option>
                            <option value="primer_nombre" selected>Primer Nombre</option>
                            <option value="primer_apellido">Primer Apellido</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paso 3: Vista Previa (Se muestra antes de confirmar) -->
        <div class="card p-6" id="previewSection" style="display: none;">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-warning-600 text-white flex items-center justify-center text-sm font-bold">3</span>
                Vista Previa
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Fila</th>
                            <th class="px-4 py-2 text-left">Nombres</th>
                            <th class="px-4 py-2 text-left">Apellidos</th>
                            <th class="px-4 py-2 text-left">Documento</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                <i class="bi bi-inbox text-3xl mb-2"></i>
                                <p>Suba un archivo para ver la vista previa</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" class="btn btn-success flex-1">
                    <i class="bi bi-check-lg mr-2"></i>
                    Confirmar Importación
                </button>
                <button type="button" class="btn btn-outline">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </button>
            </div>
        </div>

    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        
        <!-- Plantilla -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-download text-success-600 mr-2"></i>
                Plantilla de Importación
            </h3>
            
            <p class="text-sm text-gray-600 mb-4">Descargue la plantilla para asegurar el formato correcto de los datos</p>
            
            <div class="space-y-2">
                <a href="{{ route('pacientes-especiales.plantilla', ['formato' => 'excel']) }}" class="btn btn-outline w-full text-left">
                    <i class="bi bi-file-excel text-success-600 mr-2"></i>
                    Descargar Plantilla Excel
                </a>
                <a href="{{ route('pacientes-especiales.plantilla', ['formato' => 'csv']) }}" class="btn btn-outline w-full text-left">
                    <i class="bi bi-filetype-csv text-info-600 mr-2"></i>
                    Descargar Plantilla CSV
                </a>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-list-ol text-info-600 mr-2"></i>
                Instrucciones
            </h3>
            
            <ol class="space-y-3 text-sm text-gray-600">
                <li class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-info-100 text-info-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">1</span>
                    <span>Descargue la plantilla en el formato deseado</span>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-info-100 text-info-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">2</span>
                    <span>Complete los datos respetando el formato de cada columna</span>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-info-100 text-info-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">3</span>
                    <span>Suba el archivo completado</span>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-info-100 text-info-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">4</span>
                    <span>Revise la vista previa y confirme</span>
                </li>
            </ol>
        </div>

        <!-- Campos Requeridos -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-asterisk text-danger-600 mr-2"></i>
                Campos Requeridos
            </h3>
            
            <div class="space-y-2 text-sm">
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Primer Nombre</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Primer Apellido</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Tipo de Documento</p>
                    <p class="text-xs text-gray-600">V, E, o P</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Número de Documento</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Fecha de Nacimiento</p>
                    <p class="text-xs text-gray-600">Formato: DD/MM/AAAA</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Género</p>
                    <p class="text-xs text-gray-600">Masculino o Femenino</p>
                </div>
                <div class="p-2 bg-gray-50 rounded">
                    <p class="font-semibold text-gray-900">Tipo de Condición</p>
                    <p class="text-xs text-gray-600">menor_edad, discapacidad, adulto_mayor, incapacidad_legal</p>
                </div>
            </div>
        </div>

        <!-- Advertencias -->
        <div class="card p-6 border-l-4 border-l-warning-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-exclamation-triangle text-warning-600 mr-2"></i>
                Advertencias
            </h3>
            
            <div class="space-y-2 text-sm text-gray-600">
                <p><i class="bi bi-dot"></i> Los campos requeridos deben estar completos</p>
                <p><i class="bi bi-dot"></i> El formato de fechas debe ser DD/MM/AAAA</p>
                <p><i class="bi bi-dot"></i> Los documentos duplicados pueden ser omitidos o actualizados</p>
                <p><i class="bi bi-dot"></i> Revise cuidadosamente antes de confirmar</p>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function showFileName(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('fileName').classList.remove('hidden');
            document.getElementById('fileNameText').textContent = fileName;
        }
    }

    function resetForm() {
        document.getElementById('fileName').classList.add('hidden');
        document.getElementById('fileInput').value = '';
    }
</script>
@endpush

@endsection
