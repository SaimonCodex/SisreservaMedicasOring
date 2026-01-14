@extends('layouts.admin')

@section('title', 'Registrar Paciente')

@section('content')
<div class="mb-6">
    <a href="{{ route('pacientes.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Pacientes
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Registrar Nuevo Paciente</h2>
    <p class="text-gray-500 mt-1">Complete el formulario con los datos del paciente</p>
</div>

<form method="POST" action="{{ route('pacientes.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Datos Personales -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person text-medical-600"></i>
                    Datos Personales
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="primer_nombre" class="form-label form-label-required">Primer Nombre</label>
                        <input type="text" id="primer_nombre" name="primer_nombre" class="input @error('primer_nombre') border-red-500 @enderror" value="{{ old('primer_nombre') }}" required>
                        @error('primer_nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" class="input" value="{{ old('segundo_nombre') }}">
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" class="input @error('primer_apellido') border-red-500 @enderror" value="{{ old('primer_apellido') }}" required>
                        @error('primer_apellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" class="input" value="{{ old('segundo_apellido') }}">
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
                    </div>

                    <div class="form-group">
                        <label for="numero_documento" class="form-label form-label-required">Nº Documento</label>
                        <input type="text" id="numero_documento" name="numero_documento" class="input" value="{{ old('numero_documento') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_nac" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nac" name="fecha_nac" class="input" value="{{ old('fecha_nac') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select id="genero" name="genero" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <select id="estado_civil" name="estado_civil" class="form-select">
                            <option value="">Seleccione...</option>
                            <option value="soltero" {{ old('estado_civil') == 'soltero' ? 'selected' : '' }}>Soltero(a)</option>
                            <option value="casado" {{ old('estado_civil') == 'casado' ? 'selected' : '' }}>Casado(a)</option>
                            <option value="divorciado" {{ old('estado_civil') == 'divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                            <option value="viudo" {{ old('estado_civil') == 'viudo' ? 'selected' : '' }}>Viudo(a)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ocupacion" class="form-label">Ocupación</label>
                        <input type="text" id="ocupacion" name="ocupacion" class="input" value="{{ old('ocupacion') }}">
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
                            <option value="+58" {{ old('prefijo_tlf') == '+58' ? 'selected' : '' }}>+58 (VZ)</option>
                            <option value="+57" {{ old('prefijo_tlf') == '+57' ? 'selected' : '' }}>+57 (COL)</option>
                            <option value="+1" {{ old('prefijo_tlf') == '+1' ? 'selected' : '' }}>+1 (USA)</option>
                            <option value="+34" {{ old('prefijo_tlf') == '+34' ? 'selected' : '' }}>+34 (ESP)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="numero_tlf" class="form-label form-label-required">Teléfono</label>
                        <input type="tel" id="numero_tlf" name="numero_tlf" class="input @error('numero_tlf') border-red-500 @enderror" value="{{ old('numero_tlf') }}" placeholder="1234567" required>
                        @error('numero_tlf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="correo" class="form-label form-label-required">Correo Electrónico (Para iniciar sesión)</label>
                        <input type="email" id="correo" name="correo" class="input @error('correo') border-red-500 @enderror" value="{{ old('correo') }}" required>
                        @error('correo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label form-label-required">Contraseña</label>
                        <input type="password" id="password" name="password" class="input" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label form-label-required">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input" required>
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
                        <select id="estado_id" name="estado_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($estados as $est)
                            <option value="{{ $est->id_estado }}" {{ old('estado_id') == $est->id_estado ? 'selected' : '' }}>{{ $est->estado }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="municipio_id" class="form-label form-label-required">Municipio</label>
                        <select id="municipio_id" name="municipio_id" class="form-select" required>
                            <option value="">Seleccione un estado primero...</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="direccion_detallada" class="form-label form-label-required">Dirección Detallada</label>
                        <textarea id="direccion_detallada" name="direccion_detallada" rows="2" class="form-textarea" required>{{ old('direccion_detallada') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Acciones -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Finalizar Registro</h4>
                
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Registrar Paciente
                </button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline w-full mb-6">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>

                <div class="space-y-3 text-sm border-t border-gray-200 pt-4">
                    <div class="form-group">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="form-checkbox" checked>
                            <span class="text-gray-700">Activar cuenta inmediatamente</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Aquí iría la lógica AJAX para estados/municipios si fuera necesario
    // Por ahora el usuario los llenará manualmente o podemos agregar un script básico si existe la ruta de API
</script>
@endpush

@endsection
