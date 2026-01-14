@extends('layouts.admin')

@section('title', 'Nuevo Estado')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('ubicacion.estados.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nuevo Estado</h1>
            <p class="text-gray-600 mt-1">Registrar un nuevo estado</p>
        </div>
    </div>

    <form action="{{ route('ubicacion.estados.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Básica -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-600"></i>
                        Información Básica
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Nombre del Estado</label>
                            <input type="text" name="estado" class="input" placeholder="Ej: Miranda" value="{{ old('estado') }}" required maxlength="250">
                            <p class="form-help">Ingrese el nombre oficial del estado</p>
                        </div>

                        <div>
                            <label class="form-label">Código ISO 3166-2</label>
                            <input type="text" name="iso_3166_2" class="input" placeholder="Ej: VE-M" value="{{ old('iso_3166_2') }}" maxlength="4">
                            <p class="form-help">Código ISO estándar (Ej: VE-M)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estado -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activo</p>
                                <p class="text-sm text-gray-600">Estado disponible</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status') == '0' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Inactivo</p>
                                <p class="text-sm text-gray-600">Estado deshabilitado</p>
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
                            Guardar Estado
                        </button>
                        <a href="{{ route('ubicacion.estados.index') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Info -->
                <div class="card p-6 bg-blue-50 border-blue-200">
                    <div class="flex gap-3">
                        <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Importante</h4>
                            <p class="text-sm text-gray-600">El nombre del estado debe ser único en el sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
