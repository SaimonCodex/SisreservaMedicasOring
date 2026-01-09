@extends('layouts.admin')

@section('title', 'Editar Médico')

@section('content')
<div class="mb-6">
    <a href="{{ route('medicos.show', 1) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Perfil
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Médico</h2>
    <p class="text-gray-500 mt-1">Actualice la información del profesional médico</p>
</div>

<form method="POST" action="{{ route('medicos.update', 1) }}" enctype="multipart/form-data">
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
                        <input type="text" id="primer_nombre" name="primer_nombre" class="input" value="Juan" required>
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" class="input" value="Carlos">
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" class="input" value="Pérez" required>
                    </div>

                    <div class="form-group">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" class="input" value="González">
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento" class="form-label form-label-required">Tipo Doc.</label>
                        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                            <option value="V" selected>V - Venezolano</option>
                            <option value="E">E - Extranjero</option>
                            <option value="P">P - Pasaporte</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="documento" class="form-label form-label-required">Nº Documento</label>
                        <input type="text" id="documento" name="documento" class="input" value="12345678" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_nacimiento" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="input" value="1985-03-15" required>
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select id="genero" name="genero" class="form-select" required>
                            <option value="M" selected>Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
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
                    <div class="form-group md:col-span-2">
                        <label for="mpps" class="form-label form-label-required">Registro MPPS</label>
                        <input type="text" id="mpps" name="mpps" class="input" value="98765" required>
                        <p class="form-help">Número de registro del Ministerio del Poder Popular para la Salud</p>
                    </div>

                    <div class="form-group">
                        <label for="cmg" class="form-label">CMG</label>
                        <input type="text" id="cmg" name="cmg" class="input" value="54321">
                    </div>

                    <div class="form-group">
                        <label for="especialidad_id" class="form-label form-label-required">Especialidad Principal</label>
                        <select id="especialidad_id" name="especialidad_id" class="form-select" required>
                            <option value="1" selected>Cardiología</option>
                            <option value="2">Pediatría</option>
                            <option value="3">Traumatología</option>
                            <option value="4">Medicina General</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="consultorio_id" class="form-label">Consultorio Asignado</label>
                        <select id="consultorio_id" name="consultorio_id" class="form-select">
                            <option value="1">Consultorio 101 - Piso 1</option>
                            <option value="2" selected>Consultorio 205 - Piso 2</option>
                            <option value="3">Consultorio 310 - Piso 3</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="biografia" class="form-label">Biografía Profesional</label>
                        <textarea id="biografia" name="biografia" rows="4" class="form-textarea">Médico cardiólogo con más de 12 años de experiencia en diagnóstico y tratamiento de enfermedades cardiovasculares. Especializado en electrofisiología y arritmias cardíacas.</textarea>
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
                        <label for="telefono" class="form-label form-label-required">Teléfono Principal</label>
                        <input type="tel" id="telefono" name="telefono" class="input" value="0414-1234567" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono_secundario" class="form-label">Teléfono Secundario</label>
                        <input type="tel" id="telefono_secundario" name="telefono_secundario" class="input" value="0212-9876543">
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="correo" class="form-label form-label-required">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="input" value="juan.perez@clinica.com" required>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea id="direccion" name="direccion" rows="2" class="form-textarea">Av. Principal, Urb. Los Palos Grandes, Caracas</textarea>
                    </div>
                </div>
            </div>

            <!-- Datos de Acceso -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-key text-warning-600"></i>
                    Cambiar Credenciales
                </h3>
                
                <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-warning-800 flex items-center gap-2">
                        <i class="bi bi-info-circle-fill"></i>
                        Solo complete estos campos si desea cambiar la contraseña
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" class="input" placeholder="Dejar en blanco para mantener">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input">
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
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white text-3xl font-bold mb-3">
                        JP
                    </div>
                    <div class="form-group">
                        <label class="btn btn-sm btn-outline cursor-pointer">
                            <i class="bi bi-upload mr-1"></i> Cambiar Foto
                            <input type="file" name="foto" accept="image/*" class="hidden">
                        </label>
                        <p class="form-help mt-2">JPG, PNG o GIF. Max 2MB</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm border-t border-gray-200 pt-4">
                    <div class="form-group">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="form-checkbox" checked>
                            <span class="text-gray-700">Cuenta activa</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="verificado" value="1" class="form-checkbox" checked>
                            <span class="text-gray-700">Médico verificado</span>
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
                <a href="{{ route('medicos.show', 1) }}" class="btn btn-outline w-full mb-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <button type="button" class="btn btn-sm text-danger-600 hover:bg-danger-50 w-full" onclick="return confirm('¿Está seguro de eliminar este médico?')">
                        <i class="bi bi-trash mr-2"></i>
                        Eliminar Médico
                    </button>
                </div>
            </div>

            <!-- Historial -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Información del Registro</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Creado:</span>
                        <span class="text-gray-900">15/01/2022</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Última edición:</span>
                        <span class="text-gray-900">08/01/2026</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Editado por:</span>
                        <span class="text-gray-900">Admin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
