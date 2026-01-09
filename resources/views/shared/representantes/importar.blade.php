@extends('layouts.admin')

@section('title', 'Importar Representantes')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('representantes.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Importar Representantes</h2>
            <p class="text-gray-500 mt-1">Cargue masiva de representantes desde archivo</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario de Importación -->
    <div class="lg:col-span-2">
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-upload text-info-600"></i>
                Cargar Archivo
            </h3>
            
            <form action="{{ route('representantes.procesar-importacion') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label class="form-label required">Archivo de Importación</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-info-500 transition-colors cursor-pointer" 
                         ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                        <input type="file" name="archivo" id="archivo" class="hidden" accept=".csv,.xlsx,.xls" required>
                        <label for="archivo" class="cursor-pointer">
                            <i class="bi bi-cloud-upload text-5xl text-gray-400 mb-3 block"></i>
                            <p class="text-gray-700 font-semibold mb-1">Click para seleccionar archivo</p>
                            <p class="text-sm text-gray-500">o arrastre el archivo aquí</p>
                            <p class="text-xs text-gray-400 mt-2">Formatos soportados: CSV, XLSX, XLS</p>
                        </label>
                    </div>
                    <div id="file-info" class="mt-3 hidden">
                        <div class="flex items-center gap-3 p-3 bg-info-50 rounded-lg border border-info-200">
                            <i class="bi bi-file-earmark-text text-info-600 text-2xl"></i>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900" id="file-name"></p>
                                <p class="text-sm text-gray-500" id="file-size"></p>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-danger-600 hover:text-danger-700">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    @error('archivo')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg mr-2"></i>
                        Importar Representantes
                    </button>
                    <a href="{{ route('representantes.index') }}" class="btn btn-outline">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Instrucciones -->
    <div class="space-y-6">
        <!-- Formato del Archivo -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-info-circle text-warning-600"></i>
                Formato Requerido
            </h3>
            
            <div class="space-y-3 text-sm">
                <p class="text-gray-600">El archivo debe contener las siguientes columnas:</p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle text-success-600 mt-1"></i>
                        <span class="text-gray-700"><strong>primer_nombre</strong> (requerido)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle text-success-600 mt-1"></i>
                        <span class="text-gray-700"><strong>primer_apellido</strong> (requerido)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-circle text-gray-400 mt-1"></i>
                        <span class="text-gray-700">segundo_nombre</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-circle text-gray-400 mt-1"></i>
                        <span class="text-gray-700">segundo_apellido</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-circle text-gray-400 mt-1"></i>
                        <span class="text-gray-700">tipo_documento (V, E, P, J)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-circle text-gray-400 mt-1"></i>
                        <span class="text-gray-700">numero_documento</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-circle text-gray-400 mt-1"></i>
                        <span class="text-gray-700">parentesco</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-circle text-gray-400 mt-1"></i>
                        <span class="text-gray-700">numero_tlf</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Descargar Plantilla -->
        <div class="card p-6 bg-gradient-to-br from-info-50 to-info-100 border border-info-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="bi bi-download text-info-600"></i>
                Plantilla
            </h3>
            <p class="text-sm text-gray-600 mb-4">Descargue la plantilla de ejemplo para facilitar la importación</p>
            <a href="#" class="btn btn-info w-full">
                <i class="bi bi-file-excel mr-2"></i>
                Descargar Plantilla Excel
            </a>
        </div>

        <!-- Importante -->
        <div class="card p-6 bg-gradient-to-br from-warning-50 to-warning-100 border border-warning-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="bi bi-exclamation-triangle text-warning-600"></i>
                Importante
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex gap-2">
                    <i class="bi bi-dot text-warning-600 text-xl"></i>
                    <span>Verifique que no haya duplicados</span>
                </li>
                <li class="flex gap-2">
                    <i class="bi bi-dot text-warning-600 text-xl"></i>
                    <span>Los campos requeridos no pueden estar vacíos</span>
                </li>
                <li class="flex gap-2">
                    <i class="bi bi-dot text-warning-600 text-xl"></i>
                    <span>Tamaño máximo: 5MB</span>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('archivo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = (file.size / 1024).toFixed(2) + ' KB';
        document.getElementById('file-info').classList.remove('hidden');
    }
});

function clearFile() {
    document.getElementById('archivo').value = '';
    document.getElementById('file-info').classList.add('hidden');
}

function dragOverHandler(ev) {
    ev.preventDefault();
}

function dropHandler(ev) {
    ev.preventDefault();
    const file = ev.dataTransfer.files[0];
    document.getElementById('archivo').files = ev.dataTransfer.files;
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = (file.size / 1024).toFixed(2) + ' KB';
    document.getElementById('file-info').classList.remove('hidden');
}
</script>
@endpush
@endsection
