@extends('layouts.admin')

@section('title', 'Editar Paciente Especial')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Editar Paciente Especial</h2>
            <p class="text-gray-500 mt-1">Actualice los datos del paciente y su representante legal</p>
        </div>
    </div>
</div>

<form action="{{ route('pacientes-especiales.update', $pacienteEspecial->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Datos del Paciente -->
            <div class="card p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-fill text-warning-600"></i>
                    Datos del Paciente
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Primer Nombre -->
                    <div>
                        <label class="form-label required">Primer Nombre</label>
                        <input type="text" name="primer_nombre" class="input" value="{{ old('primer_nombre', $pacienteEspecial->primer_nombre) }}" required>
                        @error('primer_nombre')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Segundo Nombre -->
                    <div>
                        <label class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre" class="input" value="{{ old('segundo_nombre', $pacienteEspecial->segundo_nombre) }}">
                    </div>

                    <!-- Primer Apellido -->
                    <div>
                        <label class="form-label required">Primer Apellido</label>
                        <input type="text" name="primer_apellido" class="input" value="{{ old('primer_apellido', $pacienteEspecial->primer_apellido) }}" required>
                        @error('primer_apellido')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Segundo Apellido -->
                    <div>
                        <label class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" class="input" value="{{ old('segundo_apellido', $pacienteEspecial->segundo_apellido) }}">
                    </div>

                    <!-- Tipo Documento -->
                    <div>
                        <label class="form-label required">Tipo de Documento</label>
                        <select name="tipo_documento" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="V" {{ old('tipo_documento', $pacienteEspecial->tipo_documento) == 'V' ? 'selected' : '' }}>V - Venezolano</option>
                            <option value="E" {{ old('tipo_documento', $pacienteEspecial->tipo_documento) == 'E' ? 'selected' : '' }}>E - Extranjero</option>
                            <option value="P" {{ old('tipo_documento', $pacienteEspecial->tipo_documento) == 'P' ? 'selected' : '' }}>P - Pasaporte</option>
                        </select>
                    </div>

                    <!-- Número Documento -->
                    <div>
                        <label class="form-label required">Número de Documento</label>
                        <input type="text" name="numero_documento" class="input" value="{{ old('numero_documento', $pacienteEspecial->numero_documento) }}" required>
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div>
                        <label class="form-label required">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="input" value="{{ old('fecha_nacimiento', $pacienteEspecial->fecha_nacimiento) }}" required>
                    </div>

                    <!-- Género -->
                    <div>
                        <label class="form-label required">Género</label>
                        <select name="genero" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino" {{ old('genero', $pacienteEspecial->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $pacienteEspecial->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="md:col-span-2">
                        <label class="form-label">Estado</label>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="1" class="form-radio" {{ old('status', $pacienteEspecial->status) == 1 ? 'checked' : '' }}>
                                <span class="text-sm">Activo</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="0" class="form-radio" {{ old('status', $pacienteEspecial->status) == 0 ? 'checked' : '' }}>
                                <span class="text-sm">Inactivo</span>
                            </label>
                        </div>
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
                            <option value="menor_edad" {{ old('tipo_condicion', $pacienteEspecial->tipo_condicion) == 'menor_edad' ? 'selected' : '' }}>Menor de Edad</option>
                            <option value="discapacidad" {{ old('tipo_condicion', $pacienteEspecial->tipo_condicion) == 'discapacidad' ? 'selected' : '' }}>Discapacidad</option>
                            <option value="adulto_mayor" {{ old('tipo_condicion', $pacienteEspecial->tipo_condicion) == 'adulto_mayor' ? 'selected' : '' }}>Adulto Mayor con Tutor</option>
                            <option value="incapacidad_legal" {{ old('tipo_condicion', $pacienteEspecial->tipo_condicion) == 'incapacidad_legal' ? 'selected' : '' }}>Incapacidad Legal</option>
                        </select>
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <label class="form-label">Observaciones Médicas</label>
                        <textarea name="observaciones_medicas" rows="4" class="input">{{ old('observaciones_medicas', $pacienteEspecial->observaciones_medicas) }}</textarea>
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
                    <!-- Representante Actual -->
                    @if($pacienteEspecial->representante)
                    <div class="bg-info-50 border border-info-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-900 mb-2">Representante Actual:</p>
                        <p class="text-gray-700">{{ $pacienteEspecial->representante->nombre_completo }}</p>
                        <p class="text-sm text-gray-600">{{ $pacienteEspecial->representante->numero_documento }} - {{ $pacienteEspecial->representante->parentesco }}</p>
                    </div>
                    @endif

                    <!-- Cambiar Representante -->
                    <div>
                        <label class="form-label">Cambiar Representante</label>
                        <select name="representante_id" class="form-select">
                            <option value="">-- Mantener representante actual --</option>
                            @foreach($representantes ?? [] as $rep)
                                <option value="{{ $rep->id }}" {{ old('representante_id') == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->nombre_completo }} - {{ $rep->numero_documento }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Documentos -->
            <div class="card p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-text text-warning-600"></i>
                    Actualizar Documentos
                </h3>
                
                <div class="space-y-4">
                    <!-- Documento Identidad -->
                    <div>
                        <label class="form-label">Nueva Cédula/Documento de Identidad</label>
                        <input type="file" name="documento_identidad" class="input" accept=".pdf,.jpg,.jpeg,.png">
                        <p class="text-xs text-gray-500 mt-1">Deje en blanco para mantener el documento actual</p>
                    </div>

                    <!-- Documento Representación -->
                    <div>
                        <label class="form-label">Nuevo Documento de Representación Legal</label>
                        <input type="file" name="documento_representacion" class="input" accept=".pdf,.jpg,.jpeg,.png">
                        <p class="text-xs text-gray-500 mt-1">Deje en blanco para mantener el documento actual</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            
            <!-- Información del Registro -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="bi bi-info-circle text-info-600 mr-2"></i>
                    Información del Registro
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Historia:</span>
                        <span class="font-semibold">{{ $pacienteEspecial->numero_historia ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Registrado:</span>
                        <span class="font-semibold">{{ $pacienteEspecial->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última modificación:</span>
                        <span class="font-semibold">{{ $pacienteEspecial->updated_at?->format('d/m/Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="bi bi-lightning text-warning-600 mr-2"></i>
                    Acciones Rápidas
                </h3>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('pacientes-especiales.show', $pacienteEspecial->id) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-eye mr-2"></i>
                        Ver Perfil
                    </a>
                    <a href="{{ route('historia-clinica.base.index', $pacienteEspecial->id) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-file-medical mr-2"></i>
                        Historia Clínica
                    </a>
                </div>
            </div>

            <!-- Acciones Principales -->
            <div class="card p-6">
                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Guardar Cambios
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

@endsection
