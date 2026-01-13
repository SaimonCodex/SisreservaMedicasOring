@extends('layouts.admin')

@section('title', 'Nueva Ciudad')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('ubicacion.ciudades.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Ciudad</h1>
            <p class="text-gray-600 mt-1">Registrar una nueva ciudad</p>
        </div>
    </div>

    <form action="{{ route('ubicacion.ciudades.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Básica -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-purple-600"></i>
                        Información Básica
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Estado</label>
                            <select name="id_estado" class="form-select" required>
                                <option value="">Seleccionar estado...</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('id_estado') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Nombre de la Ciudad</label>
                            <input type="text" name="ciudad" class="input" placeholder="Ej: Caracas" value="{{ old('ciudad') }}" required maxlength="200">
                        </div>

                        <div class="flex items-center gap-2 mt-4">
                            <input type="checkbox" name="capital" id="capital" value="1" class="form-checkbox" {{ old('capital') ? 'checked' : '' }}>
                            <label for="capital" class="text-gray-700 font-medium cursor-pointer">Es Capital de Estado</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estado -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estatus</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activa</p>
                                <p class="text-sm text-gray-600">Ciudad disponible</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status') == '0' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Inactiva</p>
                                <p class="text-sm text-gray-600">Ciudad deshabilitada</p>
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
                            Guardar Ciudad
                        </button>
                        <a href="{{ route('ubicacion.ciudades.index') }}" class="btn btn-outline w-full">
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
