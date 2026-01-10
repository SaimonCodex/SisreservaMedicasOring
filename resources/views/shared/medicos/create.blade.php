@extends('layouts.admin')

@section('title', 'Registrar Médico')

@section('content')
<div class="mb-6">
    <a href="{{ route('medicos.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Médicos
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Registrar Nuevo Médico</h2>
    <p class="text-gray-500 mt-1">Complete el formulario con los datos del profesional médico</p>
</div>

<form id="createMedicoForm" method="POST" action="{{ route('medicos.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="rol_id" value="2"> {{-- Medico Role --}}
    
    {{-- Global Error Alerts --}}
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <div class="flex items-center gap-3">
                <i class="bi bi-exclamation-octagon-fill text-xl"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <div class="flex items-center gap-3 mb-2">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                <span class="font-semibold">Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside ml-8 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Datos Personales -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person-circle text-medical-600"></i>
                    Datos Personales
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="primer_nombre" class="form-label form-label-required">Primer Nombre</label>
                        <input type="text" id="primer_nombre" name="primer_nombre" class="input" value="{{ old('primer_nombre') }}" required>
                        @error('primer_nombre') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" class="input" value="{{ old('segundo_nombre') }}">
                        @error('segundo_nombre') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" class="input" value="{{ old('primer_apellido') }}" required>
                        @error('primer_apellido') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" class="input" value="{{ old('segundo_apellido') }}">
                        @error('segundo_apellido') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento" class="form-label form-label-required">Tipo Doc.</label>
                        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V - Venezolano</option>
                            <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E - Extranjero</option>
                            <option value="P" {{ old('tipo_documento') == 'P' ? 'selected' : '' }}>P - Pasaporte</option>
                            <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J - Jurídico</option>
                        </select>
                        @error('tipo_documento') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_documento" class="form-label form-label-required">Nº Documento</label>
                        <input type="text" id="numero_documento" name="numero_documento" class="input" value="{{ old('numero_documento') }}" required>
                        @error('numero_documento') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="fecha_nac" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nac" name="fecha_nac" class="input" value="{{ old('fecha_nac') }}" required>
                        @error('fecha_nac') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select id="genero" name="genero" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('genero') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Datos Profesionales -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-award text-success-600"></i>
                    Datos Profesionales
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Note: Controller expects 'nro_colegiatura' --}}
                    <div class="form-group md:col-span-2">
                        <label for="nro_colegiatura" class="form-label form-label-required">Registro MPPS / Colegiatura</label>
                        <input type="text" id="nro_colegiatura" name="nro_colegiatura" class="input" placeholder="Ej: 123456" value="{{ old('nro_colegiatura') }}" required>
                        <p class="form-help">Número de registro del Ministerio del Poder Popular para la Salud o Colegio de Médicos</p>
                        @error('nro_colegiatura') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label class="form-label form-label-required">Especialidades y Tarifas</label>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            
                            <div class="flex gap-2 mb-4">
                                <div class="flex-1">
                                    <select id="selectEspecialidad" class="form-select">
                                        <option value="">Seleccione una especialidad...</option>
                                        @foreach($especialidades as $especialidad)
                                            <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="btnAddSpecialty" class="btn btn-secondary">
                                    <i class="bi bi-plus-lg"></i> Agregar
                                </button>
                            </div>

                            <!-- Tabla de Especialidades Seleccionadas -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th class="px-3 py-2">Especialidad</th>
                                            <th class="px-3 py-2 w-24">Tarifa ($)</th>
                                            <th class="px-3 py-2 w-24">Exp (Años)</th>
                                            <th class="px-3 py-2 text-center">¿Domicilio?</th>
                                            <th class="px-3 py-2 w-24">Extra ($)</th>
                                            <th class="px-3 py-2 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="specialtiesTableBody">
                                        {{-- Dynamic Rows Here --}}
                                        <tr id="noSpecialtiesRow">
                                            <td colspan="6" class="px-3 py-4 text-center text-gray-400">
                                                No se han asignado especialidades
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Hidden Inputs Container -->
                            <div id="specialtiesHiddenInputs"></div>

                            @error('especialidades') <p class="form-error mt-2">{{ $message }}</p> @enderror
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const btnAdd = document.getElementById('btnAddSpecialty');
                                const select = document.getElementById('selectEspecialidad');
                                const tbody = document.getElementById('specialtiesTableBody');
                                const noRow = document.getElementById('noSpecialtiesRow');
                                const hiddenContainer = document.getElementById('specialtiesHiddenInputs');
                                
                                let selectedSpecialties = new Set();

                                // Recover old data if validation failed
                                const oldData = @json(old('especialidades_data', []));
                                if(Object.keys(oldData).length > 0) {
                                    Object.values(oldData).forEach(data => addRow(data.id, data.nombre, data));
                                }

                                btnAdd.addEventListener('click', function() {
                                    const id = select.value;
                                    const name = select.options[select.selectedIndex].text;

                                    if(!id) return;
                                    if(selectedSpecialties.has(id)) {
                                        alert('Esta especialidad ya ha sido agregada');
                                        return;
                                    }

                                    addRow(id, name);
                                    select.value = '';
                                });

                                function addRow(id, name, data = null) {
                                    if(selectedSpecialties.size === 0) noRow.classList.add('hidden');
                                    selectedSpecialties.add(id);

                                    const tr = document.createElement('tr');
                                    tr.className = 'bg-white border-b hover:bg-gray-50';
                                    tr.dataset.id = id;
                                    
                                    // Default values or old values
                                    const tarifa = data ? data.tarifa : '0.00';
                                    const exp = data ? data.anos_experiencia : '0';
                                    const hasDom = data && data.atiende_domicilio == '1';
                                    const extra = data ? data.tarifa_extra_domicilio : '0.00';

                                    tr.innerHTML = `
                                        <td class="px-3 py-2 font-medium text-gray-900">${name}</td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" class="input py-1 px-2 text-right" 
                                                name="especialidades_data[${id}][tarifa]" value="${tarifa}" required>
                                            <input type="hidden" name="especialidades_data[${id}][id]" value="${id}">
                                            <input type="hidden" name="especialidades[]" value="${id}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" class="input py-1 px-2 text-right" 
                                                name="especialidades_data[${id}][anos_experiencia]" value="${exp}">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <input type="checkbox" class="form-checkbox text-medical-600 rounded" 
                                                name="especialidades_data[${id}][atiende_domicilio]" value="1" 
                                                ${hasDom ? 'checked' : ''}
                                                onchange="toggleExtra(this)">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" class="input py-1 px-2 text-right ${hasDom ? '' : 'bg-gray-100 text-gray-400'}" 
                                                name="especialidades_data[${id}][tarifa_extra_domicilio]" 
                                                value="${extra}" ${hasDom ? '' : 'readonly'}>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this, '${id}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    `;
                                    tbody.appendChild(tr);
                                }

                                window.removeRow = function(btn, id) {
                                    btn.closest('tr').remove();
                                    selectedSpecialties.delete(id);
                                    if(selectedSpecialties.size === 0) noRow.classList.remove('hidden');
                                };

                                window.toggleExtra = function(checkbox) {
                                    const row = checkbox.closest('tr');
                                    const inputExtra = row.querySelector('input[name*="[tarifa_extra_domicilio]"]');
                                    if(checkbox.checked) {
                                        inputExtra.readOnly = false;
                                        inputExtra.classList.remove('bg-gray-100', 'text-gray-400');
                                        if(inputExtra.value == '0.00' || inputExtra.value == '') inputExtra.value = '0.00';
                                    } else {
                                        inputExtra.readOnly = true;
                                        inputExtra.classList.add('bg-gray-100', 'text-gray-400');
                                        inputExtra.value = '0.00';
                                    }
                                };
                            });
                        </script>
                    </div>

                    {{-- Note: Consultorio assignment is handled in a separate view/relationship usually, but checking controller... 
                         Controller Update/Store does NOT handle consultorio_id directly in Medico table, it uses pivot 'medico_consultorio'. 
                         The original view bad a consultorio select but store method didn't use it. 
                         I will omit it here to avoid confusion or add it if logic exists... 
                         Checking Controller Store: 
                         $medicoData = $request->except(...) -> consultorio_id is not used.
                         So I will remove it to avoid false expectations. --}}

                    <div class="form-group md:col-span-2">
                        <label for="formacion_academica" class="form-label">Formación Académica</label>
                        <textarea id="formacion_academica" name="formacion_academica" rows="3" class="form-textarea" placeholder="Títulos, postgrados, cursos realizados...">{{ old('formacion_academica') }}</textarea>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="experiencia_profesional" class="form-label">Biografía Profesional / Experiencia</label>
                        <textarea id="experiencia_profesional" name="experiencia_profesional" rows="4" class="form-textarea" placeholder="Experiencia, logros, áreas de interés...">{{ old('experiencia_profesional') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-telephone text-info-600"></i>
                    Información de Contacto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     <div class="form-group">
                        <label for="prefijo_tlf" class="form-label form-label-required">Prefijo</label>
                        <select name="prefijo_tlf" id="prefijo_tlf" class="form-select" required>
                            <option value="+58" {{ old('prefijo_tlf') == '+58' ? 'selected' : '' }}>+58</option>
                            <option value="+57" {{ old('prefijo_tlf') == '+57' ? 'selected' : '' }}>+57</option>
                            <option value="+1" {{ old('prefijo_tlf') == '+1' ? 'selected' : '' }}>+1</option>
                        </select>
                        @error('prefijo_tlf') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_tlf" class="form-label form-label-required">Número de Teléfono</label>
                        <input type="text" id="numero_tlf" name="numero_tlf" class="input" placeholder="Ej: 4141234567" value="{{ old('numero_tlf') }}" required>
                        @error('numero_tlf') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="correo" class="form-label form-label-required">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="input" value="{{ old('correo') }}" required>
                        @error('correo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Información de Ubicación -->
            <div class="card p-6 border-l-4 border-l-purple-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-geo-alt text-purple-600"></i>
                    Información de Ubicación
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="estado_id" class="form-label form-label-required">Estado</label>
                        <select name="estado_id" id="estado_id" class="form-select @error('estado_id') input-error @enderror">
                            <option value="">Seleccionar Estado...</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('estado_id') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="ciudad_id" class="form-label form-label-required">Ciudad</label>
                        <select name="ciudad_id" id="ciudad_id" class="form-select @error('ciudad_id') input-error @enderror" disabled>
                            <option value="">Seleccione un Estado primero</option>
                        </select>
                        @error('ciudad_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="municipio_id" class="form-label form-label-required">Municipio</label>
                        <select name="municipio_id" id="municipio_id" class="form-select @error('municipio_id') input-error @enderror" disabled>
                             <option value="">Seleccione un Estado primero</option>
                        </select>
                        @error('municipio_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="parroquia_id" class="form-label form-label-required">Parroquia</label>
                        <select name="parroquia_id" id="parroquia_id" class="form-select @error('parroquia_id') input-error @enderror" disabled>
                             <option value="">Seleccione un Municipio primero</option>
                        </select>
                        @error('parroquia_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="direccion_detallada" class="form-label">Dirección Detallada</label>
                        <textarea name="direccion_detallada" id="direccion_detallada" class="input resize-none" rows="2" placeholder="Avenida, Calle, Nro. Casa/Edificio">{{ old('direccion_detallada') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Configuración de Acceso -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-key text-warning-600"></i>
                    Acceso al Sistema
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="password" class="form-label form-label-required">Contraseña</label>
                        <input type="password" id="password" name="password" class="input" required>
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label form-label-required">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Vista Previa -->
            <div class="card p-6 sticky top-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white text-3xl font-bold mb-3">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <p class="text-sm text-gray-500">Foto de perfil</p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Activar médico al registrar</span>
                    </label>
                </div>
                
                 <div class="mt-4 pt-4 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                        <i class="bi bi-save mr-2"></i>
                        Registrar Médico
                    </button>
                    <a href="{{ route('medicos.index') }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="errorModalBackdrop"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="errorModalPanel">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">No se puede crear el médico</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">
                                    Por favor, corrija los siguientes errores antes de continuar:
                                </p>
                                <ul id="errorList" class="text-sm text-red-600 list-disc list-inside space-y-1 bg-red-50 p-3 rounded-lg border border-red-100">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="closeErrorModal" class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black sm:ml-3 sm:w-auto transition-colors">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Location Logic
        const estadoSelect = document.getElementById('estado_id');
        const ciudadSelect = document.getElementById('ciudad_id');
        const municipioSelect = document.getElementById('municipio_id');
        const parroquiaSelect = document.getElementById('parroquia_id');

        if(estadoSelect) {
            estadoSelect.addEventListener('change', function() {
                const estadoId = this.value;
                ciudadSelect.innerHTML = '<option value="">Cargando...</option>';
                municipioSelect.innerHTML = '<option value="">Cargando...</option>';
                parroquiaSelect.innerHTML = '<option value="">Seleccione un Municipio primero</option>';
                ciudadSelect.disabled = true;
                municipioSelect.disabled = true;
                parroquiaSelect.disabled = true;

                if (estadoId) {
                    fetch(`{{ url('admin/get-ciudades') }}/${estadoId}`)
                        .then(r => r.json())
                        .then(d => {
                            ciudadSelect.innerHTML = '<option value="">Seleccionar Ciudad...</option>';
                            d.forEach(i => ciudadSelect.innerHTML += `<option value="${i.id_ciudad}">${i.ciudad}</option>`);
                            ciudadSelect.disabled = false;
                        });

                    fetch(`{{ url('admin/get-municipios') }}/${estadoId}`)
                        .then(r => r.json())
                        .then(d => {
                            municipioSelect.innerHTML = '<option value="">Seleccionar Municipio...</option>';
                            d.forEach(i => municipioSelect.innerHTML += `<option value="${i.id_municipio}">${i.municipio}</option>`);
                            municipioSelect.disabled = false;
                        });
                } else {
                    ciudadSelect.innerHTML = '<option value="">Seleccione un Estado primero</option>';
                    municipioSelect.innerHTML = '<option value="">Seleccione un Estado primero</option>';
                }
            });

            municipioSelect.addEventListener('change', function() {
                const municipioId = this.value;
                parroquiaSelect.innerHTML = '<option value="">Cargando...</option>';
                parroquiaSelect.disabled = true;
                if (municipioId) {
                    fetch(`{{ url('admin/get-parroquias') }}/${municipioId}`)
                        .then(r => r.json())
                        .then(d => {
                            parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia...</option>';
                            d.forEach(i => parroquiaSelect.innerHTML += `<option value="${i.id_parroquia}">${i.parroquia}</option>`);
                            parroquiaSelect.disabled = false;
                        });
                } else {
                    parroquiaSelect.innerHTML = '<option value="">Seleccione un Municipio primero</option>';
                }
            });
        }

        // Validation Logic
        const form = document.getElementById('createMedicoForm');
        const errorModal = document.getElementById('errorModal');
        const errorModalBackdrop = document.getElementById('errorModalBackdrop');
        const errorModalPanel = document.getElementById('errorModalPanel');
        const errorList = document.getElementById('errorList');
        const closeErrorModalBtn = document.getElementById('closeErrorModal');

        // Validation Rules
        const validations = {
            primer_nombre: { 
                required: true, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'Ingrese un nombre válido (solo letras)' 
            },
            segundo_nombre: { 
                required: false, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'El segundo nombre solo debe contener letras' 
            },
            primer_apellido: { 
                required: true, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'Ingrese un apellido válido (solo letras)' 
            },
            segundo_apellido: { 
                required: false, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'El segundo apellido solo debe contener letras' 
            },
            tipo_documento: { required: true, message: 'Seleccione un tipo de documento' },
            numero_documento: { 
                required: true, 
                pattern: /^\d+$/, 
                message: 'El número de documento debe contener solo dígitos' 
            },
            fecha_nac: { required: true, message: 'Ingrese la fecha de nacimiento' },
            genero: { required: true, message: 'Seleccione el género' },
            prefijo_tlf: { required: true, message: 'Seleccione un prefijo' },
            numero_tlf: {
                required: true,
                pattern: /^\d+$/, 
                message: 'El teléfono debe contener solo dígitos'
            },
            estado_id: { required: true, message: 'Seleccione un estado' },
            ciudad_id: { required: true, message: 'Seleccione una ciudad' },
            municipio_id: { required: true, message: 'Seleccione un municipio' },
            parroquia_id: { required: true, message: 'Seleccione una parroquia' },
            nro_colegiatura: { required: true, message: 'Ingrese el número de registro' },
            'especialidades[]': { required: true, message: 'Seleccione al menos una especialidad' },
            
            correo: { 
                required: true, 
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, 
                message: 'Ingrese un correo electrónico válido' 
            },
            password: { 
                required: true, 
                minLength: 8, 
                message: 'La contraseña debe tener al menos 8 caracteres' 
            },
            password_confirmation: { 
                required: true, 
                custom: (val) => val === form.querySelector('[name="password"]').value, 
                message: 'Las contraseñas no coinciden' 
            }
        };

        // Helper to find where to append error message
        function getErrorContainer(input) {
            const parent = input.parentElement;
            if (parent.classList.contains('flex') || parent.classList.contains('gap-2')) {
                return parent.parentElement;
            }
            return parent;
        }

        // Real-time validation
        Object.keys(validations).forEach(fieldName => {
            const input = form.querySelector(`[name="${fieldName}"]`);
            if (!input) return;

            const events = input.tagName === 'SELECT' ? ['change', 'blur'] : ['input', 'blur'];

            events.forEach(event => {
                input.addEventListener(event, () => validateField(input, validations[fieldName]));
            });
        });

        function validateField(input, rules) {
            const value = input.value.trim();
            let isValid = true;
            let errorMessage = '';

            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            
            const errorContainer = getErrorContainer(input);
            const existingError = errorContainer.querySelector('.validation-error-msg');
            if (existingError) existingError.remove();

            if (rules.required && !value) {
                isValid = false;
                errorMessage = rules.message || 'Este campo es obligatorio';
            } else if (value && rules.pattern && !rules.pattern.test(value)) {
                isValid = false;
                errorMessage = rules.message;
            } else if (value && rules.minLength && value.length < rules.minLength) {
                isValid = false;
                errorMessage = rules.message;
            } else if (rules.custom && !rules.custom(value)) {
                isValid = false;
                errorMessage = rules.message;
            }

            if (!isValid) {
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                const msg = document.createElement('p');
                msg.className = 'validation-error-msg text-xs text-red-500 mt-1 font-semibold animate-pulse';
                msg.textContent = errorMessage;
                errorContainer.appendChild(msg);
            }
            return isValid;
        }

        form.addEventListener('submit', function(e) {
            let errors = [];
            
            Object.keys(validations).forEach(fieldName => {
                const input = form.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    const rule = validations[fieldName];
                    if (!input.disabled) {
                         if (!validateField(input, rule)) {
                            let msg = rule.message || 'Error de validación';
                            const label = input.closest('.form-group')?.querySelector('label')?.textContent || fieldName;
                            if(rule.required && !input.value.trim()) msg = `El campo ${label} es obligatorio`;
                            errors.push(msg);
                        }
                    }
                }
            });

            if (errors.length > 0) {
                e.preventDefault();
                showErrorModal(errors);
            }
        });

        function showErrorModal(errors) {
            errorList.innerHTML = '';
            const uniqueErrors = [...new Set(errors)];
            uniqueErrors.forEach(err => {
                const li = document.createElement('li');
                li.textContent = err;
                errorList.appendChild(li);
            });

            errorModal.classList.remove('hidden');
            setTimeout(() => {
                errorModalBackdrop.classList.remove('opacity-0');
                errorModalPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                errorModalPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }

        function closeModal() {
            errorModalBackdrop.classList.add('opacity-0');
            errorModalPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            errorModalPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => errorModal.classList.add('hidden'), 300);
        }

        closeErrorModalBtn.addEventListener('click', closeModal);
        errorModalBackdrop.addEventListener('click', closeModal);
    });
</script>
@endpush


