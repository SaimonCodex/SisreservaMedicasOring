@extends('layouts.admin')

@section('title', 'Editar Paciente')

@section('content')
<div class="mb-6">
    <a href="{{ route('pacientes.show', $paciente->id) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Perfil
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Paciente</h2>
    <p class="text-gray-500 mt-1">Actualice la información de {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</p>
</div>

<form method="POST" action="{{ route('pacientes.update', $paciente->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
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
                        <input type="text" id="primer_nombre" name="primer_nombre" class="input @error('primer_nombre') border-red-500 @enderror" value="{{ old('primer_nombre', $paciente->primer_nombre) }}" required>
                        @error('primer_nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" class="input" value="{{ old('segundo_nombre', $paciente->segundo_nombre) }}">
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" class="input @error('primer_apellido') border-red-500 @enderror" value="{{ old('primer_apellido', $paciente->primer_apellido) }}" required>
                        @error('primer_apellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" class="input" value="{{ old('segundo_apellido', $paciente->segundo_apellido) }}">
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento" class="form-label form-label-required">Tipo Doc.</label>
                        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                            <option value="V" {{ old('tipo_documento', $paciente->tipo_documento) == 'V' ? 'selected' : '' }}>V - Venezolano</option>
                            <option value="E" {{ old('tipo_documento', $paciente->tipo_documento) == 'E' ? 'selected' : '' }}>E - Extranjero</option>
                            <option value="P" {{ old('tipo_documento', $paciente->tipo_documento) == 'P' ? 'selected' : '' }}>P - Pasaporte</option>
                            <option value="J" {{ old('tipo_documento', $paciente->tipo_documento) == 'J' ? 'selected' : '' }}>J - Jurídico</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="numero_documento" class="form-label form-label-required">Nº Documento</label>
                        <input type="text" id="numero_documento" name="numero_documento" class="input" value="{{ old('numero_documento', $paciente->numero_documento) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_nac" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nac" name="fecha_nac" class="input" value="{{ old('fecha_nac', $paciente->fecha_nac ? \Carbon\Carbon::parse($paciente->fecha_nac)->format('Y-m-d') : '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select id="genero" name="genero" class="form-select" required>
                            <option value="" disabled>Seleccione...</option>
                            <option value="M" {{ old('genero', $paciente->genero) == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('genero', $paciente->genero) == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <select id="estado_civil" name="estado_civil" class="form-select">
                            <option value="" disabled {{ !$paciente->estado_civil ? 'selected' : '' }}>Seleccione...</option>
                            <option value="soltero" {{ old('estado_civil', $paciente->estado_civil) == 'soltero' ? 'selected' : '' }}>Soltero(a)</option>
                            <option value="casado" {{ old('estado_civil', $paciente->estado_civil) == 'casado' ? 'selected' : '' }}>Casado(a)</option>
                            <option value="divorciado" {{ old('estado_civil', $paciente->estado_civil) == 'divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                            <option value="viudo" {{ old('estado_civil', $paciente->estado_civil) == 'viudo' ? 'selected' : '' }}>Viudo(a)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ocupacion" class="form-label">Ocupación</label>
                        <input type="text" id="ocupacion" name="ocupacion" class="input" value="{{ old('ocupacion', $paciente->ocupacion) }}">
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-telephone text-success-600"></i>
                    Información de Contacto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="prefijo_tlf" class="form-label form-label-required">Prefijo</label>
                        <select name="prefijo_tlf" id="prefijo_tlf" class="form-select" required>
                            <option value="+58" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+58' ? 'selected' : '' }}>+58 (VZ)</option>
                            <option value="+57" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+57' ? 'selected' : '' }}>+57 (COL)</option>
                            <option value="+1" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+1' ? 'selected' : '' }}>+1 (USA)</option>
                            <option value="+34" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+34' ? 'selected' : '' }}>+34 (ESP)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="numero_tlf" class="form-label form-label-required">Teléfono</label>
                        <input type="tel" id="numero_tlf" name="numero_tlf" class="input" value="{{ old('numero_tlf', $paciente->numero_tlf) }}" required>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label class="form-label">Correo Electrónico (No modificable desde aquí)</label>
                        <input type="email" class="input bg-gray-50 text-gray-500" value="{{ optional($paciente->usuario)->correo }}" disabled>
                        <p class="text-xs text-gray-400 mt-1">Para cambiar el correo, contacte al Administrador Root.</p>
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-geo-alt text-warning-600"></i>
                    Ubicación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="estado_id" class="form-label form-label-required">Estado</label>
                        <select id="estado_id" name="estado_id" class="form-select @error('estado_id') border-red-500 @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($estados as $est)
                            <option value="{{ $est->id_estado }}" {{ old('estado_id', $paciente->estado_id) == $est->id_estado ? 'selected' : '' }}>{{ $est->estado }}</option>
                            @endforeach
                        </select>
                         @error('estado_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="municipio_id" class="form-label form-label-required">Municipio</label>
                        <select id="municipio_id" name="municipio_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($municipios as $mun)
                            <option value="{{ $mun->id_municipio }}" {{ old('municipio_id', $paciente->municipio_id) == $mun->id_municipio ? 'selected' : '' }}>{{ $mun->municipio }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="parroquia_id" class="form-label form-label-required">Parroquia</label>
                        <select id="parroquia_id" name="parroquia_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($parroquias as $par)
                            <option value="{{ $par->id_parroquia }}" {{ old('parroquia_id', $paciente->parroquia_id) == $par->id_parroquia ? 'selected' : '' }}>{{ $par->parroquia }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ciudad_id" class="form-label">Ciudad</label>
                        <select id="ciudad_id" name="ciudad_id" class="form-select">
                            <option value="">Seleccione...</option>
                            @foreach($ciudades as $ciu)
                            <option value="{{ $ciu->id_ciudad }}" {{ old('ciudad_id', $paciente->ciudad_id) == $ciu->id_ciudad ? 'selected' : '' }}>{{ $ciu->ciudad }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="direccion_detallada" class="form-label form-label-required">Dirección Detallada</label>
                        <textarea id="direccion_detallada" name="direccion_detallada" rows="2" class="form-textarea" required>{{ old('direccion_detallada', $paciente->direccion_detallada) }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Foto Actual -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Foto de Perfil</h4>
                
                <div class="text-center mb-6">
                    @if($paciente->foto_perfil)
                        <img src="{{ asset('storage/' . $paciente->foto_perfil) }}" alt="Foto" class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-medical-100 mb-3">
                    @else
                        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white text-3xl font-bold mb-3">
                            {{ strtoupper(substr($paciente->primer_nombre, 0, 1) . substr($paciente->primer_apellido, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label class="btn btn-sm btn-outline cursor-pointer">
                            <i class="bi bi-upload mr-1"></i> Cambiar Foto
                            <input type="file" name="foto_perfil" accept="image/*" class="hidden">
                        </label>
                        <p class="form-help mt-2">JPG, PNG. Max 2MB</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm border-t border-gray-200 pt-4">
                    <div class="form-group">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="form-checkbox" {{ $paciente->status ? 'checked' : '' }}>
                            <span class="text-gray-700">Cuenta activa</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-outline w-full mb-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
            </div>

            <!-- Info Adicional -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Información del Registro</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Historia Clínica:</span>
                        <span class="font-mono text-medical-600 font-bold">HC-{{ \Carbon\Carbon::parse($paciente->created_at)->format('Y') }}-{{ str_pad($paciente->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Creado el:</span>
                        <span class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($paciente->created_at)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
