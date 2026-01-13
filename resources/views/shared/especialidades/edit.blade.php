@extends('layouts.admin')

@section('title', 'Editar Especialidad')

@section('content')
<div class="mb-6">
    <a href="{{ route('especialidades.show', $especialidad->id) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Detalle
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Especialidad</h2>
    <p class="text-gray-500 mt-1">Actualice la información de la especialidad médica</p>
</div>

<form method="POST" action="{{ route('especialidades.update', $especialidad->id) }}">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información Básica -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-bookmark text-medical-600"></i>
                    Información Básica
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label for="nombre" class="form-label form-label-required">Nombre de la Especialidad</label>
                        <input type="text" id="nombre" name="nombre" class="input @error('nombre') input-error @enderror" value="{{ old('nombre', $especialidad->nombre) }}" required>
                        @error('nombre')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" id="codigo" name="codigo" class="input @error('codigo') input-error @enderror" value="{{ old('codigo', $especialidad->codigo) }}">
                        <p class="form-help">Código interno para identificación rápida</p>
                        @error('codigo')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="form-label form-label-required">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="form-textarea @error('descripcion') textarea-error @enderror" required>{{ old('descripcion', $especialidad->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-gear text-info-600"></i>
                    Configuración
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="duracion_cita_default" class="form-label">Duración por Defecto (min)</label>
                        <select id="duracion_cita_default" name="duracion_cita_default" class="form-select @error('duracion_cita_default') select-error @enderror">
                            @foreach([15, 20, 30, 45, 60] as $duration)
                                <option value="{{ $duration }}" {{ old('duracion_cita_default', $especialidad->duracion_cita_default) == $duration ? 'selected' : '' }}>{{ $duration }} minutos</option>
                            @endforeach
                        </select>
                        @error('duracion_cita_default')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="color" class="form-label">Color Identificador</label>
                        <select id="color" name="color" class="form-select @error('color') select-error @enderror">
                            @foreach(['medical' => 'Azul Médico', 'success' => 'Verde', 'warning' => 'Amarillo', 'danger' => 'Rojo', 'info' => 'Cian', 'purple' => 'Púrpura'] as $value => $label)
                                <option value="{{ $value }}" {{ old('color', $especialidad->color) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                         @error('color')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="icono" class="form-label">Ícono</label>
                        <select id="icono" name="icono" class="form-select @error('icono') select-error @enderror">
                            @foreach([
                                'heart-pulse' => 'Corazón (Cardiología)', 
                                'emoji-smile' => 'Sonrisa (Pediatría)', 
                                'activity' => 'Actividad (Traumatología)', 
                                'clipboard-pulse' => 'Tabla (Medicina General)', 
                                'eye' => 'Ojo (Oftalmología)', 
                                'droplet' => 'Gota (Dermatología)',
                                'lungs' => 'Pulmones (Neumología)',
                                'bandaid' => 'Curita (Emergencias)',
                                'brain' => 'Cerebro (Neurología)',
                                'ear' => 'Oído (Otorrino)'
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ old('icono', $especialidad->icono) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('icono')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="prioridad" class="form-label">Prioridad</label>
                        <select id="prioridad" name="prioridad" class="form-select @error('prioridad') select-error @enderror">
                            @foreach([1 => 'Alta', 2 => 'Media', 3 => 'Baja'] as $value => $label)
                                <option value="{{ $value }}" {{ old('prioridad', $especialidad->prioridad) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('prioridad')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-info-circle text-success-600"></i>
                    Detalles Adicionales
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label for="requisitos" class="form-label">Requisitos para Citas</label>
                        <textarea id="requisitos" name="requisitos" rows="3" class="form-textarea">{{ old('requisitos', $especialidad->requisitos) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="3" class="form-textarea">{{ old('observaciones', $especialidad->observaciones) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Vista Previa (Simulada con datos actuales) -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Vista Actual</h4>
                
                <div class="bg-gradient-to-br from-{{ $especialidad->color }}-500 to-{{ $especialidad->color }}-600 p-6 rounded-xl text-white mb-4">
                    <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30 mb-3">
                        <i class="bi bi-{{ $especialidad->icono }} text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-1">{{ $especialidad->nombre }}</h4>
                    <p class="text-white/80 text-sm">{{ $especialidad->codigo ?? 'N/A' }}</p>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Médicos:</span>
                        <span class="font-medium">{{ $especialidad->medicos->count() }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Citas/Mes:</span>
                        <span class="font-medium">143</span> <!-- Placeholder stats -->
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Estado:</span>
                        <span class="badge badge-success">Activa</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" class="form-checkbox" {{ old('status', $especialidad->status) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Especialidad activa</span>
                    </label>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('especialidades.show', $especialidad->id) }}" class="btn btn-outline w-full mb-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
            </div>

            <!-- Historial -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Información del Registro</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Creada:</span>
                        <span class="text-gray-900">{{ $especialidad->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Última edición:</span>
                        <span class="text-gray-900">{{ $especialidad->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    // Live Preview Elements
    const previewNombre = document.querySelector('.bg-gradient-to-br h4');
    const previewCodigo = document.querySelector('.bg-gradient-to-br p');
    const previewIcono = document.querySelector('.bg-gradient-to-br i');
    const previewCard = document.querySelector('.bg-gradient-to-br');

    const validateField = (field) => {
        const errorMsg = field.parentElement.querySelector('.form-error');
        let isValid = true;
        let message = '';

        if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            message = 'Este campo es obligatorio';
        } else if (field.name === 'nombre' && field.value.length > 100) {
            isValid = false;
            message = 'El nombre no puede exceder los 100 caracteres';
        }

        if (!isValid) {
            field.classList.add('border-danger-500', 'bg-danger-50');
            field.classList.remove('border-gray-300');
            if (errorMsg) {
                errorMsg.textContent = message;
                errorMsg.style.display = 'block';
            } else {
                const p = document.createElement('p');
                p.className = 'form-error';
                p.style.color = '#ef4444';
                p.style.fontSize = '0.75rem';
                p.style.marginTop = '0.25rem';
                p.textContent = message;
                field.parentElement.appendChild(p);
            }
        } else {
            field.classList.remove('border-danger-500', 'bg-danger-50', 'border-gray-300');
            field.classList.add('border-success-500');
            if (errorMsg) errorMsg.style.display = 'none';
        }
        return isValid;
    };

    // Real-time Validation & Preview Update
    form.addEventListener('input', (e) => {
        const field = e.target;
        
        if (field.hasAttribute('required')) {
            validateField(field);
        }

        // Live Preview Updates
        if (field.id === 'nombre') {
            previewNombre.textContent = field.value || 'Nombre';
        }
        if (field.id === 'codigo') {
            previewCodigo.textContent = field.value || 'Descripción';
        }
        if (field.id === 'icono') {
            previewIcono.className = `bi bi-${field.value} text-3xl`;
        }
        if (field.id === 'color') {
            const colors = {
                'medical': 'from-medical-500 to-medical-600',
                'success': 'from-success-500 to-success-600',
                'warning': 'from-warning-500 to-warning-600',
                'danger': 'from-danger-500 to-danger-600',
                'info': 'from-info-500 to-info-600',
                'purple': 'from-purple-500 to-purple-600'
            };
            previewCard.className = `bg-gradient-to-br ${colors[field.value]} p-6 rounded-xl text-white mb-4`;
        }
    });

    // Form Submission check
    form.addEventListener('submit', (e) => {
        let isFormValid = true;
        inputs.forEach(input => {
            if (!validateField(input)) isFormValid = false;
        });

        if (!isFormValid) {
            e.preventDefault();
            const firstError = form.querySelector('.border-danger-500');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endpush
