@extends('layouts.admin')

@section('title', 'Editar Representante')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('representantes.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Editar Representante</h2>
            <p class="text-gray-500 mt-1">Actualice la información del representante</p>
        </div>
    </div>
</div>

<form action="{{ route('representantes.update', $representante->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos Personales -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-badge text-info-600"></i>
                    Datos Personales
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Primer Nombre</label>
                        <input type="text" name="primer_nombre" class="input" value="{{ old('primer_nombre', $representante->primer_nombre) }}" required>
                        @error('primer_nombre')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre" class="input" value="{{ old('segundo_nombre', $representante->segundo_nombre) }}">
                    </div>

                    <div>
                        <label class="form-label required">Primer Apellido</label>
                        <input type="text" name="primer_apellido" class="input" value="{{ old('primer_apellido', $representante->primer_apellido) }}" required>
                        @error('primer_apellido')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" class="input" value="{{ old('segundo_apellido', $representante->segundo_apellido) }}">
                    </div>

                    <div>
                        <label class="form-label">Tipo de Documento</label>
                        <select name="tipo_documento" class="form-select">
                            <option value="">Seleccione...</option>
                            <option value="V" {{ old('tipo_documento', $representante->tipo_documento) == 'V' ? 'selected' : '' }}>V - Venezolano</option>
                            <option value="E" {{ old('tipo_documento', $representante->tipo_documento) == 'E' ? 'selected' : '' }}>E - Extranjero</option>
                            <option value="P" {{ old('tipo_documento', $representante->tipo_documento) == 'P' ? 'selected' : '' }}>P - Pasaporte</option>
                            <option value="J" {{ old('tipo_documento', $representante->tipo_documento) == 'J' ? 'selected' : '' }}>J - Jurídico</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Número de Documento</label>
                        <input type="text" name="numero_documento" class="input" value="{{ old('numero_documento', $representante->numero_documento) }}">
                    </div>

                    <div>
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac" class="input" value="{{ old('fecha_nac', $representante->fecha_nac) }}">
                    </div>

                    <div>
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-select">
                            <option value="">Seleccione...</option>
                            <option value="Masculino" {{ old('genero', $representante->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $representante->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero', $representante->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Parentesco</label>
                        <input type="text" name="parentesco" class="input" placeholder="Ej: Madre, Padre, Tutor Legal" value="{{ old('parentesco', $representante->parentesco) }}">
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-geo-alt text-warning-600"></i>
                    Ubicación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Estado</label>
                        <select name="estado_id" id="estado" class="form-select">
                            <option value="">Seleccione...</option>
                            @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}" {{ old('estado_id', $representante->estado_id) == $estado->id_estado ? 'selected' : '' }}>
                                {{ $estado->estado }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Ciudad</label>
                        <select name="ciudad_id" id="ciudad" class="form-select">
                            <option value="">Seleccione...</option>
                            @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->id_ciudad }}" {{ old('ciudad_id', $representante->ciudad_id) == $ciudad->id_ciudad ? 'selected' : '' }}>
                                {{ $ciudad->ciudad }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Municipio</label>
                        <select name="municipio_id" id="municipio" class="form-select">
                            <option value="">Seleccione...</option>
                            @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id_municipio }}" {{ old('municipio_id', $representante->municipio_id) == $municipio->id_municipio ? 'selected' : '' }}>
                                {{ $municipio->municipio }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Parroquia</label>
                        <select name="parroquia_id" id="parroquia" class="form-select">
                            <option value="">Seleccione...</option>
                            @foreach($parroquias as $parroquia)
                            <option value="{{ $parroquia->id_parroquia }}" {{ old('parroquia_id', $representante->parroquia_id) == $parroquia->id_parroquia ? 'selected' : '' }}>
                                {{ $parroquia->parroquia }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Dirección Detallada</label>
                        <textarea name="direccion_detallada" rows="3" class="input">{{ old('direccion_detallada', $representante->direccion_detallada) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-telephone text-success-600"></i>
                    Contacto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Prefijo</label>
                        <select name="prefijo_tlf" class="form-select">
                            <option value="">Seleccione...</option>
                            <option value="+58" {{ old('prefijo_tlf', $representante->prefijo_tlf) == '+58' ? 'selected' : '' }}>+58 (Venezuela)</option>
                            <option value="+57" {{ old('prefijo_tlf', $representante->prefijo_tlf) == '+57' ? 'selected' : '' }}>+57 (Colombia)</option>
                            <option value="+1" {{ old('prefijo_tlf', $representante->prefijo_tlf) == '+1' ? 'selected' : '' }}>+1 (USA)</option>
                            <option value="+34" {{ old('prefijo_tlf', $representante->prefijo_tlf) == '+34' ? 'selected' : '' }}>+34 (España)</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Número de Teléfono</label>
                        <input type="text" name="numero_tlf" class="input" placeholder="414-1234567" value="{{ old('numero_tlf', $representante->numero_tlf) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Pacientes Asignados -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-people text-medical-600"></i>
                    Pacientes Especiales
                </h3>
                
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @forelse($pacientesEspeciales as $paciente)
                    <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer border border-gray-200">
                        <input type="checkbox" 
                               name="pacientes_especiales[]" 
                               value="{{ $paciente->id }}" 
                               class="form-checkbox"
                               {{ in_array($paciente->id, old('pacientes_especiales', $representante->pacientesEspeciales->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">
                                {{ $paciente->paciente->primer_nombre }} {{ $paciente->paciente->primer_apellido }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $paciente->tipo_condicion }}</p>
                        </div>
                    </label>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No hay pacientes especiales disponibles</p>
                    @endforelse
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Actualizar Representante
                    </button>
                    <a href="{{ route('representantes.index') }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
