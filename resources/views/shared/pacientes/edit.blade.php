@extends('layouts.admin')

@section('title', 'Editar Paciente')

@section('content')
<div class="mb-6">
    <a href="{{ route('pacientes.show', 1) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Perfil
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Paciente</h2>
    <p class="text-gray-500 mt-1">Actualice la información del paciente</p>
</div>

<form method="POST" action="{{ route('pacientes.update', 1) }}" enctype="multipart/form-data">
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
                        <input type="text" id="primer_nombre" name="primer_nombre" class="input" value="Ana" required>
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre" class="input" value="María">
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" class="input" value="Rodríguez" required>
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
                        <input type="text" id="documento" name="documento" class="input" value="18765432" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_nacimiento" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="input" value="1988-05-22" required>
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select id="genero" name="genero" class="form-select" required>
                            <option value="M">Masculino</option>
                            <option value="F" selected>Femenino</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <select id="estado_civil" name="estado_civil" class="form-select">
                            <option value="soltero">Soltero(a)</option>
                            <option value="casado" selected>Casado(a)</option>
                            <option value="divorciado">Divorciado(a)</option>
                            <option value="viudo">Viudo(a)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="grupo_sanguineo" class="form-label">Grupo Sanguíneo</label>
                        <select id="grupo_sanguineo" name="grupo_sanguineo" class="form-select">
                            <option value="A+">A+</option>
                            <option value="O+" selected>O+</option>
                            <option value="B+">B+</option>
                            <option value="AB+">AB+</option>
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
                        <input type="tel" id="telefono" name="telefono" class="input" value="0414-5678901" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono_secundario" class="form-label">Teléfono Secundario</label>
                        <input type="tel" id="telefono_secundario" name="telefono_secundario" class="input" value="0212-3456789">
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="input" value="ana.rodriguez@example.com">
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
                            <option value="1">Distrito Capital</option>
                            <option value="2" selected>Miranda</option>
                            <option value="3">Carabobo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="municipio_id" class="form-label form-label-required">Municipio</label>
                        <select id="municipio_id" name="municipio_id" class="form-select" required>
                            <option value="1">Libertador</option>
                            <option value="2">Chacao</option>
                            <option value="3" selected>Baruta</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="parroquia_id" class="form-label form-label-required">Parroquia</label>
                        <select id="parroquia_id" name="parroquia_id" class="form-select" required>
                            <option value="1">El Recreo</option>
                            <option value="2" selected>Las Minas</option>
                            <option value="3">San Pedro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" class="input" value="Caracas">
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="direccion" class="form-label form-label-required">Dirección Completa</label>
                        <textarea id="direccion" name="direccion" rows="2" class="form-textarea" required>Av. Principal, Urb. Los Rosales, Caracas, Miranda</textarea>
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
                        <input type="text" id="emergencia_nombre" name="emergencia_nombre" class="input" value="Pedro Rodríguez" required>
                    </div>

                    <div class="form-group">
                        <label for="emergencia_parentesco" class="form-label form-label-required">Parentesco</label>
                        <select id="emergencia_parentesco" name="emergencia_parentesco" class="form-select" required>
                            <option value="madre">Madre</option>
                            <option value="padre">Padre</option>
                            <option value="conyuge" selected>Cónyuge</option>
                            <option value="hermano">Hermano(a)</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="emergencia_telefono" class="form-label form-label-required">Teléfono</label>
                        <input type="tel" id="emergencia_telefono" name="emergencia_telefono" class="input" value="0424-9876543" required>
                    </div>
                </div>
            </div>

            <!-- Alertas Médicas -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-info-600"></i>
                    Alertas Médicas
                </h3>
                
                <div class="form-group">
                    <label for="alergias" class="form-label">Alergias</label>
                    <textarea id="alergias" name="alergias" rows="3" class="form-textarea" placeholder="Describa las alergias conocidas...">Penicilina - Reacción severa documentada</textarea>
                    <p class="form-help">Importante: Registre todas las alergias conocidas del paciente</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Foto Actual -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Foto de Perfil</h4>
                
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-3xl font-bold mb-3">
                        AR
                    </div>
                    <div class="form-group">
                        <label class="btn btn-sm btn-outline cursor-pointer">
                            <i class="bi bi-upload mr-1"></i> Cambiar Foto
                            <input type="file" name="foto" accept="image/*" class="hidden">
                        </label>
                        <p class="form-help mt-2">JPG, PNG. Max 2MB</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm border-t border-gray-200 pt-4">
                    <div class="form-group">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="form-checkbox" checked>
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
                <a href="{{ route('pacientes.show', 1) }}" class="btn btn-outline w-full mb-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <button type="button" class="btn btn-sm text-danger-600 hover:bg-danger-50 w-full" onclick="return confirm('¿Está seguro de eliminar este paciente?')">
                        <i class="bi bi-trash mr-2"></i>
                        Eliminar Paciente
                    </button>
                </div>
            </div>

            <!-- Historial -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Información del Registro</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Historia Clínica:</span>
                        <span class="font-mono text-medical-600">HC-2024-001</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Creado:</span>
                        <span class="text-gray-900">03/02/2024</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Última edición:</span>
                        <span class="text-gray-900">08/01/2026</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
