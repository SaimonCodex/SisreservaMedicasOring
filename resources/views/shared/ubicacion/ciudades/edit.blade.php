@extends('layouts.admin')

@section('title', 'Editar Ciudad')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/configuracion/ubicacion/ciudades') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Editar Ciudad</h1>
            <p class="text-gray-600 mt-1">{{ $ciudad->nombre ?? 'Ciudad' }}</p>
        </div>
    </div>

    <form action="{{ url('index.php/configuracion/ubicacion/ciudades/' . $ciudad->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

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
                            <select name="estado_id" class="form-select" required>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id }}" {{ old('estado_id', $ciudad->estado_id) == $estado->id ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Nombre de la Ciudad</label>
                            <input type="text" name="nombre" class="input" value="{{ old('nombre', $ciudad->nombre) }}" required>
                        </div>

                        <div>
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="input" value="{{ old('codigo', $ciudad->codigo) }}" maxlength="10">
                        </div>

                        <div>
                            <label class="form-label">Población</label>
                            <input type="number" name="poblacion" class="input" value="{{ old('poblacion', $ciudad->poblacion) }}">
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
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', $ciudad->status) == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activa</p>
                                <p class="text-sm text-gray-600">Ciudad disponible</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status', $ciudad->status) == '0' ? 'checked' : '' }}>
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
                            Actualizar
                        </button>
                        <a href="{{ url('index.php/configuracion/ubicacion/ciudades') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estadísticas</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                            <span class="text-sm text-gray-700">Municipios</span>
                            <span class="font-bold text-amber-900">{{ $ciudad->municipios_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-gray-700">Parroquias</span>
                            <span class="font-bold text-blue-900">{{ $ciudad->parroquias_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
