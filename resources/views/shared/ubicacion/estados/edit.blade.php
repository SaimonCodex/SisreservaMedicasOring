@extends('layouts.admin')

@section('title', 'Editar Estado')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('ubicacion.estados.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Editar Estado</h1>
            <p class="text-gray-600 mt-1">{{ $estado->estado ?? 'Estado' }}</p>
        </div>
    </div>

    <form action="{{ route('ubicacion.estados.update', $estado->id_estado) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

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
                            <input type="text" name="estado" class="input" value="{{ old('estado', $estado->estado) }}" required maxlength="250">
                        </div>

                        <div>
                            <label class="form-label">Código ISO 3166-2</label>
                            <input type="text" name="iso_3166_2" class="input" value="{{ old('iso_3166_2', $estado->iso_3166_2) }}" maxlength="4">
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
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', $estado->status) == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activo</p>
                                <p class="text-sm text-gray-600">Estado disponible</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status', $estado->status) == '0' ? 'checked' : '' }}>
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
                            Actualizar
                        </button>
                        <a href="{{ route('ubicacion.estados.index') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
                <!-- Stats -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estadísticas</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-gray-700">Ciudades</span>
                            <span class="font-bold text-blue-900">{{ $estado->ciudades_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <span class="text-sm text-gray-700">Municipios</span>
                            <span class="font-bold text-purple-900">{{ $estado->municipios_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
