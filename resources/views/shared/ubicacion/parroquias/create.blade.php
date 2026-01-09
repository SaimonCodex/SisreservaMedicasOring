@extends('layouts.admin')

@section('title', 'Nueva Parroquia')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/configuracion/ubicacion/parroquias') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Parroquia</h1>
            <p class="text-gray-600 mt-1">Registrar una nueva parroquia</p>
        </div>
    </div>

    <form action="{{ url('index.php/configuracion/ubicacion/parroquias') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-emerald-600"></i>
                        Información Básica
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Estado</label>
                            <select name="estado_id" class="form-select" id="estado_select" required>
                                <option value="">Seleccionar estado...</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id }}" {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Municipio</label>
                            <select name="municipio_id" class="form-select" id="municipio_select" required>
                                <option value="">Primero seleccione un estado...</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Nombre de la Parroquia</label>
                            <input type="text" name="nombre" class="input" placeholder="Ej: El Recreo" value="{{ old('nombre') }}" required>
                        </div>

                        <div>
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="input" placeholder="Ej: REC" value="{{ old('codigo') }}" maxlength="10">
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
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activa</p>
                                <p class="text-sm text-gray-600">Parroquia disponible</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status') == '0' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Inactiva</p>
                                <p class="text-sm text-gray-600">Parroquia deshabilitada</p>
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
                            Guardar Parroquia
                        </button>
                        <a href="{{ url('index.php/configuracion/ubicacion/parroquias') }}" class="btn btn-outline w-full">
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
