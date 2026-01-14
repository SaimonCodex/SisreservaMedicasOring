@extends('layouts.medico')

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
            
            <!-- Tipo de Paciente -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-circle text-info-600"></i>
                    Tipo de Paciente
                </h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <label class="card p-4 cursor-pointer hover:shadow-md transition-shadow border-2 border-medical-500">
                        <input type="radio" name="tipo_paciente" value="regular" class="form-radio" checked>
                        <span class="ml-2 font-medium">Paciente Regular</span>
                        <p class="text-xs text-gray-500 ml-6">Paciente mayor de edad</p>
                    </label>
                    <label class="card p-4 cursor-pointer hover:shadow-md transition-shadow border-2 border-transparent hover:border-warning-300">
                        <input type="radio" name="tipo_paciente" value="especial" class="form-radio">
                        <span class="ml-2 font-medium">Paciente Especial</span>
                        <p class="text-xs text-gray-500 ml-6">Menor de edad o necesita representante</p>
                    </label>
                </div>
            </div>

            <!-- Datos Personales -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person text-medical-600"></i>
                    Datos Personales
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="primer_nombre" class="form-label form-label-required">Primer Nombre</label>
                        <input type="text" id="primer_nombre" name="primer_nombre" class="input" required>
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" class="input">
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" class="input" required>
                    </div>

                    <div class="form-group">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" class="input">
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento" class="form-label form-label-required">Tipo Doc.</label>
                        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="V">V - Venezolano</option>
                            <option value="E">E - Extranjero</option>
                            <option value="P">P - Pasaporte</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="documento" class="form-label form-label-required">Nº Documento</label>
                        <input type="text" id="documento" name="documento" class="input" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_nacimiento" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="input" required>
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select id="genero" name="genero" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <select id="estado_civil" name="estado_civil" class="form-select">
                            <option value="">Seleccione...</option>
                            <option value="soltero">Soltero(a)</option>
                            <option value="casado">Casado(a)</option>
                            <option value="divorciado">Divorciado(a)</option>
                            <option value="viudo">Viudo(a)</option>
                            <option value="union">Unión Estable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="grupo_sanguineo" class="form-label">Grupo Sanguíneo</label>
                        <select id="grupo_sanguineo" name="grupo_sanguineo" class="form-select">
                            <option value="">Seleccione...</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
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
                        <label for="telefono" class="form-label form-label-required">Teléfono Principal</label>
                        <input type="tel" id="telefono" name="telefono" class="input" placeholder="0414-1234567" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono_secundario" class="form-label">Teléfono Secundario</label>
                        <input type="tel" id="telefono_secundario" name="telefono_secundario" class="input">
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="input">
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
                            <option value="1">Distrito Capital</option>
                            <option value="2">Miranda</option>
                            <option value="3">Carabobo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="municipio_id" class="form-label form-label-required">Municipio</label>
                        <select id="municipio_id" name="municipio_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="1">Libertador</option>
                            <option value="2">Chacao</option>
                            <option value="3">Baruta</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="parroquia_id" class="form-label form-label-required">Parroquia</label>
                        <select id="parroquia_id" name="parroquia_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="1">El Recreo</option>
                            <option value="2">Sabana Grande</option>
                            <option value="3">San Pedro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" class="input">
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="direccion" class="form-label form-label-required">Dirección Completa</label>
                        <textarea id="direccion" name="direccion" rows="2" class="form-textarea" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Contacto de Emergencia -->
            <div class="card p-6 border-l-4 border-l-danger-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-shield-exclamation text-danger-600"></i>
                    Contacto de Emergencia
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="emergencia_nombre" class="form-label form-label-required">Nombre Completo</label>
                        <input type="text" id="emergencia_nombre" name="emergencia_nombre" class="input" required>
                    </div>

                    <div class="form-group">
                        <label for="emergencia_parentesco" class="form-label form-label-required">Parentesco</label>
                        <select id="emergencia_parentesco" name="emergencia_parentesco" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="madre">Madre</option>
                            <option value="padre">Padre</option>
                            <option value="hijo">Hijo(a)</option>
                            <option value="conyuge">Cónyuge</option>
                            <option value="hermano">Hermano(a)</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="emergencia_telefono" class="form-label form-label-required">Teléfono</label>
                        <input type="tel" id="emergencia_telefono" name="emergencia_telefono" class="input" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Vista Previa -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Vista Previa</h4>
                
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-3xl font-bold mb-3">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="form-group">
                        <label class="btn btn-sm btn-outline cursor-pointer">
                            <i class="bi bi-upload mr-1"></i> Subir Foto
                            <input type="file" name="foto" accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Estado:</span>
                        <span class="badge badge-success">Activo</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Tipo:</span>
                        <span class="font-medium">Regular</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Historia Clínica:</span>
                        <span class="font-medium text-xs">Se generará automáticamente</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Activar paciente al registrar</span>
                    </label>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Registrar Paciente
                </button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline w-full">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
            </div>

            <!-- Ayuda -->
            <div class="card p-6 bg-info-50 border-info-200">
                <h4 class="font-bold text-info-900 mb-2 flex items-center gap-2">
                    <i class="bi bi-info-circle"></i>
                    Información
                </h4>
                <p class="text-sm text-info-700">
                    Al registrar un paciente se genera automáticamente su historia clínica con un número único.
                </p>
            </div>
        </div>
    </div>
</form>
@endsection
