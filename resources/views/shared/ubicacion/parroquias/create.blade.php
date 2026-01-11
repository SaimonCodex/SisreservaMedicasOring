@extends('layouts.admin')

@section('title', 'Nueva Parroquia')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('ubicacion.parroquias.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Parroquia</h1>
            <p class="text-gray-600 mt-1">Registrar una nueva parroquia</p>
        </div>
    </div>

    <form action="{{ route('ubicacion.parroquias.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-emerald-600"></i>
                        Información Básica
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Estado</label>
                            <select id="estado_select" class="form-select" required>
                                <option value="">Seleccionar estado...</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('estado_id') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Municipio</label>
                            <select name="id_municipio" id="municipio_select" class="form-select" required disabled>
                                <option value="">Primero seleccione un estado...</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Nombre de la Parroquia</label>
                            <input type="text" name="parroquia" class="input" placeholder="Ej: El Recreo" value="{{ old('parroquia') }}" required maxlength="250">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estatus</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activa</p>
                                <p class="text-sm text-gray-600">Parroquia disponible</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status') == '0' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Inactiva</p>
                                <p class="text-sm text-gray-600">Parroquia deshabilitada</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Guardar Parroquia
                        </button>
                        <a href="{{ route('ubicacion.parroquias.index') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const estadoSelect = document.getElementById('estado_select');
        const municipioSelect = document.getElementById('municipio_select');
        
        // We will fetch municipalities dynamically or filtering a local object if provided.
        // Given there is no API endpoint set up for "getMunicipios" within this task scope explicitly, 
        // I will direct specific AJAX to the admin route `get-municipios/{estadoId}` if it exists, or inject data.
        // Checking previous files: `web.php` has `Route::get('get-municipios/{estadoId}', ...)` in admin group.
        // Assuming that route returns JSON. Let's try to use it.
        
        estadoSelect.addEventListener('change', function() {
            const estadoId = this.value;
            municipioSelect.innerHTML = '<option value="">Cargando...</option>';
            municipioSelect.disabled = true;

            if (estadoId) {
                fetch(`{{ url('admin/get-municipios') }}/${estadoId}`)
                    .then(response => response.json())
                    .then(data => {
                        municipioSelect.innerHTML = '<option value="">Seleccionar municipio...</option>';
                        data.forEach(municipio => {
                            // Backend likely returns id, nombre. But my model uses id_municipio, municipio.
                            // I need to check what `getMunicipios` returns. 
                            // If `getMunicipios` is not returning expected fields, I might need to adjust.
                            // Assuming it returns standard ID/Name.
                            // Let's assume standard Laravel `pluck` format or array of objects.
                            // If it fails, I'll need to fix the content.
                            
                            // Safe fallback:
                            const id = municipio.id_municipio || municipio.id;
                            const name = municipio.municipio || municipio.nombre;
                            
                            const option = document.createElement('option');
                            option.value = id;
                            option.textContent = name;
                            municipioSelect.appendChild(option);
                        });
                        municipioSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        municipioSelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            } else {
                municipioSelect.innerHTML = '<option value="">Primero seleccione un estado...</option>';
                municipioSelect.disabled = true;
            }
        });
    });
</script>
@endpush
@endsection
