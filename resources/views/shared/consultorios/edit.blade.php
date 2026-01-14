@extends('layouts.admin')

@section('title', 'Editar Consultorio')

@section('content')
<div class="mb-6">
    <a href="{{ route('consultorios.show', $consultorio->id) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Detalle
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Consultorio</h2>
    <p class="text-gray-500 mt-1">Actualice la información del espacio médico</p>
</div>

<form method="POST" action="{{ route('consultorios.update', $consultorio->id) }}">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información Básica -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-building text-medical-600"></i>
                    Información Básica
                </h3>
                
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="nombre" class="form-label form-label-required">Nombre del Consultorio</label>
                        <input type="text" id="nombre" name="nombre" class="input @error('nombre') border-danger-500 @enderror" value="{{ old('nombre', $consultorio->nombre) }}" required>
                        @error('nombre')
                            <p class="text-danger-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-danger-500 text-xs mt-1 hidden" id="error-nombre">El nombre es obligatorio y debe tener menos de 100 caracteres.</p>
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="3" class="form-textarea">{{ old('descripcion', $consultorio->descripcion) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="horario_inicio" class="form-label">Apertura Habitual</label>
                            <input type="time" id="horario_inicio" name="horario_inicio" class="input" value="{{ old('horario_inicio', substr($consultorio->horario_inicio, 0, 5)) }}">
                        </div>
                        <div class="form-group">
                            <label for="horario_fin" class="form-label">Cierre Habitual</label>
                            <input type="time" id="horario_fin" name="horario_fin" class="input" value="{{ old('horario_fin', substr($consultorio->horario_fin, 0, 5)) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Especialidades Admitidas -->
            <div class="card p-6 border-l-4 border-l-purple-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-bookmark text-purple-600"></i>
                    Especialidades Admitidas
                </h3>
                
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($especialidades as $especialidad)
                    <label class="flex items-center space-x-2 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors bg-white">
                        <input type="checkbox" name="especialidades[]" value="{{ $especialidad->id }}" class="form-checkbox text-purple-600 rounded" 
                        {{ in_array($especialidad->id, old('especialidades', $consultorio->especialidades->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">{{ $especialidad->nombre }}</span>
                    </label>
                    @endforeach
                </div>
                @error('especialidades')
                    <p class="text-danger-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ubicación -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-geo-alt text-info-600"></i>
                    Ubicación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="estado_id" class="form-label form-label-required">Estado</label>
                        <select id="estado_id" name="estado_id" class="form-select @error('estado_id') border-danger-500 @enderror" required>
                            <option value="">Seleccione Estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('estado_id', $consultorio->estado_id) == $estado->id_estado ? 'selected' : '' }}>{{ $estado->estado }}</option>
                            @endforeach
                        </select>
                        @error('estado_id') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="ciudad_id" class="form-label form-label-required">Ciudad</label>
                        <select id="ciudad_id" name="ciudad_id" class="form-select @error('ciudad_id') border-danger-500 @enderror" {{ $consultorio->estado_id ? '' : 'disabled' }} required>
                            <option value="">Seleccione Ciudad</option>
                            @if($ciudades)
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id_ciudad }}" {{ old('ciudad_id', $consultorio->ciudad_id) == $ciudad->id_ciudad ? 'selected' : '' }}>{{ $ciudad->ciudad }}</option>
                                @endforeach
                            @endif
                        </select>
                         @error('ciudad_id') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="municipio_id" class="form-label">Municipio</label>
                        <select id="municipio_id" name="municipio_id" class="form-select" {{ $consultorio->estado_id ? '' : 'disabled' }}>
                            <option value="">Seleccione Municipio</option>
                            @if($municipios)
                                @foreach($municipios as $municipio)
                                    <option value="{{ $municipio->id_municipio }}" {{ old('municipio_id', $consultorio->municipio_id) == $municipio->id_municipio ? 'selected' : '' }}>{{ $municipio->municipio }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="parroquia_id" class="form-label">Parroquia</label>
                        <select id="parroquia_id" name="parroquia_id" class="form-select" {{ $consultorio->municipio_id ? '' : 'disabled' }}>
                            <option value="">Seleccione Parroquia</option>
                            @if($parroquias)
                                @foreach($parroquias as $parroquia)
                                    <option value="{{ $parroquia->id_parroquia }}" {{ old('parroquia_id', $consultorio->parroquia_id) == $parroquia->id_parroquia ? 'selected' : '' }}>{{ $parroquia->parroquia }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group md:col-span-2">
                        <label for="direccion_detallada" class="form-label">Dirección Detallada</label>
                        <input type="text" id="direccion_detallada" name="direccion_detallada" class="input" value="{{ old('direccion_detallada', $consultorio->direccion_detallada) }}">
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-telephone text-warning-600"></i>
                    Datos de Contacto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono Interno/Directo</label>
                        <input type="tel" id="telefono" name="telefono" class="input" value="{{ old('telefono', $consultorio->telefono) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email del Consultorio</label>
                        <input type="email" id="email" name="email" class="input" value="{{ old('email', $consultorio->email) }}">
                        @error('email') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Vista Actual -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Vista Previa</h4>
                
                <div class="bg-gradient-to-br from-medical-500 to-medical-600 p-6 rounded-xl text-white mb-4 shadow-lg relative overflow-hidden">
                     <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="bi bi-building text-6xl"></i>
                    </div>
                    <div class="mb-3 relative z-10">
                        <h3 class="text-2xl font-bold break-words" id="preview-nombre">{{ $consultorio->nombre }}</h3>
                    </div>
                    <p class="text-white/90 font-medium text-sm flex items-center gap-2">
                        <i class="bi bi-geo-alt"></i> <span id="preview-ciudad">{{ $consultorio->ciudad->ciudad ?? 'Ciudad' }}</span>
                    </p>
                </div>

                <div class="space-y-3 text-sm border-t border-gray-100 pt-4">
                   <p class="text-gray-600 flex items-start gap-2">
                       <i class="bi bi-card-text mt-0.5"></i>
                       <span id="preview-descripcion" class="italic text-gray-500">{{ Str::limit($consultorio->descripcion, 50) ?? 'Sin descripción' }}</span>
                   </p>
                    <p class="text-gray-600 flex items-center gap-2">
                       <i class="bi bi-telephone"></i>
                       <span id="preview-telefono">{{ $consultorio->telefono ?? 'Sin teléfono' }}</span>
                   </p>
                   <p class="text-gray-600 flex items-center gap-2">
                       <i class="bi bi-envelope"></i>
                       <span id="preview-email">{{ $consultorio->email ?? 'Sin email' }}</span>
                   </p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="flex items-center gap-2 cursor-pointer mb-4">
                        <input type="checkbox" name="status" value="1" class="form-checkbox" {{ $consultorio->status ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Consultorio disponible</span>
                    </label>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3 py-3">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('consultorios.show', $consultorio->id) }}" class="btn btn-outline w-full mb-3">
                    Cancelar
                </a>
            </div>

            <!-- Historial -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Información del Registro</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Creado:</span>
                        <span class="text-gray-900">{{ $consultorio->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Última edición:</span>
                        <span class="text-gray-900">{{ $consultorio->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const estadoSelect = document.getElementById('estado_id');
        const ciudadSelect = document.getElementById('ciudad_id');
        const municipioSelect = document.getElementById('municipio_id');
        const parroquiaSelect = document.getElementById('parroquia_id');
        
        // Vista Previa Elements
        const nombreInput = document.getElementById('nombre');
        const descripcionInput = document.getElementById('descripcion');
        const telefonoInput = document.getElementById('telefono');
        const emailInput = document.getElementById('email');
        
        const previewNombre = document.getElementById('preview-nombre');
        const previewCiudad = document.getElementById('preview-ciudad');
        const previewDescripcion = document.getElementById('preview-descripcion');
        const previewTelefono = document.getElementById('preview-telefono');
        const previewEmail = document.getElementById('preview-email');

        // Live Preview Updates
        function updatePreview(input, target, defaultText) {
            target.textContent = input.value || defaultText;
        }

        nombreInput.addEventListener('input', () => {
            updatePreview(nombreInput, previewNombre, 'Nombre Consultorio');
            validateField(nombreInput, nombreInput.value.length > 0 && nombreInput.value.length <= 100);
        });

        descripcionInput.addEventListener('input', () => updatePreview(descripcionInput, previewDescripcion, 'Sin descripción'));
        telefonoInput.addEventListener('input', () => updatePreview(telefonoInput, previewTelefono, 'Sin teléfono'));
        emailInput.addEventListener('input', () => {
            updatePreview(emailInput, previewEmail, 'Sin email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(emailInput.value.length > 0) {
                 validateField(emailInput, emailRegex.test(emailInput.value));
            } else {
                removeValidationStyles(emailInput);
            }
        });

        function validateField(element, isValid) {
            if (isValid) {
                element.classList.remove('border-danger-500', 'bg-danger-50');
                element.classList.add('border-success-500', 'bg-success-50');
            } else {
                element.classList.remove('border-success-500', 'bg-success-50');
                element.classList.add('border-danger-500', 'bg-danger-50');
            }
        }
        
        function removeValidationStyles(element) {
             element.classList.remove('border-danger-500', 'bg-danger-50', 'border-success-500', 'bg-success-50');
        }

        // Location Logic
        estadoSelect.addEventListener('change', function() {
            const estadoId = this.value;
            ciudadSelect.innerHTML = '<option value="">Cargando...</option>';
            ciudadSelect.disabled = true;
            municipioSelect.innerHTML = '<option value="">Seleccione Municipio</option>';
            municipioSelect.disabled = true;
            parroquiaSelect.innerHTML = '<option value="">Seleccione Parroquia</option>';
            parroquiaSelect.disabled = true;

            if (estadoId) {
                // Correct URLs matching web.php routes
                fetch(`{{ url('get-ciudades-consultorio') }}/${estadoId}`)
                    .then(response => response.json())
                    .then(data => {
                        ciudadSelect.innerHTML = '<option value="">Seleccione Ciudad</option>';
                        data.forEach(ciudad => {
                            ciudadSelect.innerHTML += `<option value="${ciudad.id_ciudad}">${ciudad.ciudad}</option>`;
                        });
                        ciudadSelect.disabled = false;
                        
                        fetch(`{{ url('get-municipios-consultorio') }}/${estadoId}`)
                        .then(response => response.json())
                        .then(data => {
                            municipioSelect.innerHTML = '<option value="">Seleccione Municipio</option>';
                            data.forEach(municipio => {
                                municipioSelect.innerHTML += `<option value="${municipio.id_municipio}">${municipio.municipio}</option>`;
                            });
                            municipioSelect.disabled = false;
                        });
                    })
                    .catch(error => console.error('Error loading locations:', error));
            } else {
                ciudadSelect.innerHTML = '<option value="">Seleccione Ciudad</option>';
                municipioSelect.innerHTML = '<option value="">Seleccione Municipio</option>';
            }
        });

        ciudadSelect.addEventListener('change', function() {
             const selectedOption = this.options[this.selectedIndex];
             previewCiudad.textContent = selectedOption.value ? selectedOption.text : 'Ciudad';
        });

        municipioSelect.addEventListener('change', function() {
            const municipioId = this.value;
            parroquiaSelect.innerHTML = '<option value="">Cargando...</option>';
            parroquiaSelect.disabled = true;
            
            if (municipioId) {
                fetch(`{{ url('get-parroquias-consultorio') }}/${municipioId}`)
                    .then(response => response.json())
                    .then(data => {
                        parroquiaSelect.innerHTML = '<option value="">Seleccione Parroquia</option>';
                        data.forEach(parroquia => {
                            parroquiaSelect.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.parroquia}</option>`;
                        });
                        parroquiaSelect.disabled = false;
                    })
                    .catch(error => console.error('Error loading parroquias:', error));
            } else {
                parroquiaSelect.innerHTML = '<option value="">Seleccione Parroquia</option>';
            }
        });
    });
</script>
@endpush
@endsection
