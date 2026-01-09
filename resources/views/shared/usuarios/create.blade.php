@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/shared/usuarios') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nuevo Usuario</h1>
            <p class="text-gray-600 mt-1">Registrar un nuevo usuario en el sistema</p>
        </div>
    </div>

    <form action="{{ url('index.php/shared/usuarios') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Personal -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person text-blue-600"></i>
                        Información Personal
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Nombre</label>
                                <input type="text" name="nombre" class="input" value="{{ old('nombre') }}" required>
                            </div>
                            <div>
                                <label class="form-label form-label-required">Apellido</label>
                                <input type="text" name="apellido" class="input" value="{{ old('apellido') }}" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Cédula</label>
                                <input type="text" name="cedula" class="input" placeholder="V-12345678" value="{{ old('cedula') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="input" value="{{ old('fecha_nacimiento') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Género</label>
                                <select name="genero" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" class="input" placeholder="0412-1234567" value="{{ old('telefono') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credenciales -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-600"></i>
                        Credenciales de Acceso
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Email</label>
                            <input type="email" name="email" class="input" placeholder="usuario@ejemplo.com" value="{{ old('email') }}" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Contraseña</label>
                                <input type="password" name="password" class="input" placeholder="••••••••" required>
                            </div>
                            <div>
                                <label class="form-label form-label-required">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="input" placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Rol -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Rol de Usuario</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="rol_id" value="1" class="form-radio" {{ old('rol_id') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Administrador</p>
                                <p class="text-sm text-gray-600">Acceso completo</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="rol_id" value="2" class="form-radio" {{ old('rol_id', '3') == '2' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Médico</p>
                                <p class="text-sm text-gray-600">Gestión clínica</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="rol_id" value="3" class="form-radio" {{ old('rol_id', '3') == '3' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Paciente</p>
                                <p class="text-sm text-gray-600">Acceso paciente</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Estado -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activo</p>
                                <p class="text-sm text-gray-600">Usuario habilitado</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status') == '0' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Inactivo</p>
                                <p class="text-sm text-gray-600">Usuario deshabilitado</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Crear Usuario
                        </button>
                        <a href="{{ url('index.php/shared/usuarios') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
