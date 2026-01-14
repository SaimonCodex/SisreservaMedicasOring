@extends('layouts.admin')

@section('title', 'Registrar Paciente Especial')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Registrar Paciente Especial</h2>
            <p class="text-gray-500 mt-1">Complete los datos del paciente y su representante legal</p>
        </div>
    </div>
</div>

<form action="{{ route('pacientes-especiales.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Selección de Paciente Existente -->
            <div class="card p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-check text-info-600"></i>
                    Vincular Paciente Existente
                </h3>
                <div>
                    <label class="form-label required">Seleccionar Paciente</label>
                    <select name="paciente_id" class="form-select" id="pacienteSelect" required>
                        <option value="">-- Seleccione un paciente --</option>
                        @foreach($pacientes ?? [] as $pac)
                            <option value="{{ $pac->id }}" 
                                    data-nombre="{{ $pac->primer_nombre }}" 
                                    data-apellido="{{ $pac->primer_apellido }}"
                                    data-doc="{{ $pac->numero_documento }}"
                                    data-tipo-doc="{{ $pac->tipo_documento }}"
                                    data-fnac="{{ $pac->fecha_nac }}"
                                    data-genero="{{ $pac->genero }}"
                                    {{ old('paciente_id') == $pac->id ? 'selected' : '' }}>
                                {{ $pac->primer_nombre }} {{ $pac->primer_apellido }} - {{ $pac->numero_documento }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="bi bi-info-circle mr-1"></i>
                        Solo pacientes de su sede o nuevos están disponibles. 
                        <strong>Automáticamente se completarán los campos de abajo.</strong>
                    </p>
                </div>
            </div>

            <!-- Datos del Paciente (Solo Lectura/Informativo) -->
            <div class="card p-6 opacity-75" id="datosPacienteSection">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-fill text-warning-600"></i>
                    Verificación de Datos del Paciente
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Primer Nombre</label>
                        <input type="text" id="disp_primer_nombre" class="input bg-gray-50" readonly>
                    </div>

                    <div>
                        <label class="form-label">Primer Apellido</label>
                        <input type="text" id="disp_primer_apellido" class="input bg-gray-50" readonly>
                    </div>

                    <div>
                        <label class="form-label">Documento</label>
                        <div class="flex gap-2">
                            <input type="text" id="disp_tipo_doc" class="input bg-gray-50 w-20 text-center" readonly>
                            <input type="text" id="disp_num_doc" class="input bg-gray-50" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="text" id="disp_fecha_nac" class="input bg-gray-50" readonly>
                    </div>
                </div>
            </div>

            <!-- Condición Especial -->
            <div class="card p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-heart-pulse text-warning-600"></i>
                    Condición Especial
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tipo de Condición -->
                    <div class="md:col-span-2">
                        <label class="form-label required">Tipo de Condición</label>
                        <select name="tipo_condicion" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="menor_edad" {{ old('tipo_condicion') == 'menor_edad' ? 'selected' : '' }}>Menor de Edad</option>
                            <option value="discapacidad" {{ old('tipo_condicion') == 'discapacidad' ? 'selected' : '' }}>Discapacidad</option>
                            <option value="adulto_mayor" {{ old('tipo_condicion') == 'adulto_mayor' ? 'selected' : '' }}>Adulto Mayor con Tutor</option>
                            <option value="incapacidad_legal" {{ old('tipo_condicion') == 'incapacidad_legal' ? 'selected' : '' }}>Incapacidad Legal</option>
                        </select>
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <label class="form-label">Observaciones Médicas</label>
                        <textarea name="observaciones_medicas" rows="4" class="input">{{ old('observaciones_medicas') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Información médica relevante, alergias, condiciones especiales, etc.</p>
                    </div>
                </div>
            </div>

            <!-- Representante Legal -->
            <div class="card p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-shield-check text-warning-600"></i>
                    Representante Legal
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <!-- Seleccionar Representante Existente -->
                    <div>
                        <label class="form-label">Seleccionar Representante Existente</label>
                        <select name="representante_id" class="form-select" id="representanteSelect">
                            <option value="">-- Crear Nuevo Representante --</option>
                            @foreach($representantes ?? [] as $rep)
                                <option value="{{ $rep->id }}" {{ old('representante_id') == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->nombre_completo }} - {{ $rep->numero_documento }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">O complete los datos para crear un nuevo representante</p>
                    </div>

                    <div id="nuevoRepresentante" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre Completo -->
                        <div class="md:col-span-2">
                            <label class="form-label">Nombre Completo del Representante</label>
                            <input type="text" name="representante_nombre" class="input" value="{{ old('representante_nombre') }}">
                        </div>

                        <!-- Documento -->
                        <div>
                            <label class="form-label">Documento</label>
                            <input type="text" name="representante_documento" class="input" placeholder="V-12345678" value="{{ old('representante_documento') }}">
                        </div>

                        <!-- Parentesco -->
                        <div>
                            <label class="form-label">Parentesco</label>
                            <select name="parentesco" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="Madre" {{ old('parentesco') == 'Madre' ? 'selected' : '' }}>Madre</option>
                                <option value="Padre" {{ old('parentesco') == 'Padre' ? 'selected' : '' }}>Padre</option>
                                <option value="Tutor Legal" {{ old('parentesco') == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                                <option value="Hermano/a" {{ old('parentesco') == 'Hermano/a' ? 'selected' : '' }}>Hermano/a</option>
                                <option value="Otro" {{ old('parentesco') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="representante_telefono" class="input" placeholder="0414-1234567" value="{{ old('representante_telefono') }}">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="representante_email" class="input" value="{{ old('representante_email') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos -->
            <div class="card p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-text text-warning-600"></i>
                    Documentos de Soporte
                </h3>
                
                <div class="space-y-4">
                    <!-- Documento Identidad -->
                    <div>
                        <label class="form-label">Cédula/Documento de Identidad</label>
                        <input type="file" name="documento_identidad" class="input" accept=".pdf,.jpg,.jpeg,.png">
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG o PNG - Máx. 5MB</p>
                    </div>

                    <!-- Documento Representación -->
                    <div>
                        <label class="form-label">Documento de Representación Legal</label>
                        <input type="file" name="documento_representacion" class="input" accept=".pdf,.jpg,.jpeg,.png">
                        <p class="text-xs text-gray-500 mt-1">Partida de nacimiento, poder notariado, etc.</p>
                    </div>
                </div>
            </div>

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
                    <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Los campos marcados con * son obligatorios</p>
                    <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Los documentos son opcionales pero recomendados</p>
                    <p><i class="bi bi-check-circle text-success-600 mr-2"></i>Puede seleccionar un representante existente o crear uno nuevo</p>
                </div>
            </div>

            <!-- Tipos de Condición -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="bi bi-question-circle text-warning-600 mr-2"></i>
                    Tipos de Condición
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="p-2 bg-gray-50 rounded">
                        <p class="font-semibold text-gray-900">Menor de Edad</p>
                        <p class="text-gray-600">Pacientes menores de 18 años</p>
                    </div>
                    <div class="p-2 bg-gray-50 rounded">
                        <p class="font-semibold text-gray-900">Discapacidad</p>
                        <p class="text-gray-600">Pacientes con discapacidad física o mental</p>
                    </div>
                    <div class="p-2 bg-gray-50 rounded">
                        <p class="font-semibold text-gray-900">Adulto Mayor</p>
                        <p class="text-gray-600">Adultos mayores con tutor asignado</p>
                    </div>
                    <div class="p-2 bg-gray-50 rounded">
                        <p class="font-semibold text-gray-900">Incapacidad Legal</p>
                        <p class="text-gray-600">Personas con incapacidad legal declarada</p>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Registrar Paciente
                    </button>
                    <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>

        </div>
    </div>
</form>

@push('scripts')
<script>
    // Al cambiar el paciente seleccionado, actualizar los campos de visualización
    document.getElementById('pacienteSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        
        if (this.value) {
            document.getElementById('disp_primer_nombre').value = option.dataset.nombre || 'N/A';
            document.getElementById('disp_primer_apellido').value = option.dataset.apellido || 'N/A';
            document.getElementById('disp_tipo_doc').value = option.dataset.tipoDoc || 'N/A';
            document.getElementById('disp_num_doc').value = option.dataset.doc || 'N/A';
            document.getElementById('disp_fecha_nac').value = option.dataset.fnac || 'N/A';
        } else {
            document.getElementById('disp_primer_nombre').value = '';
            document.getElementById('disp_primer_apellido').value = '';
            document.getElementById('disp_tipo_doc').value = '';
            document.getElementById('disp_num_doc').value = '';
            document.getElementById('disp_fecha_nac').value = '';
        }
    });

    // Toggle nuevo representante fields
    document.getElementById('representanteSelect')?.addEventListener('change', function() {
        const nuevoRepDiv = document.getElementById('nuevoRepresentante');
        if (this.value) {
            nuevoRepDiv.style.opacity = '0.5';
            nuevoRepDiv.querySelectorAll('input, select').forEach(el => el.disabled = true);
        } else {
            nuevoRepDiv.style.opacity = '1';
            nuevoRepDiv.querySelectorAll('input, select').forEach(el => el.disabled = false);
        }
    });

    // Ejecutar al cargar por si hay valores previos de 'old()'
    window.addEventListener('load', function() {
        document.getElementById('pacienteSelect').dispatchEvent(new Event('change'));
        document.getElementById('representanteSelect')?.dispatchEvent(new Event('change'));
    });
</script>
@endpush

@endsection
