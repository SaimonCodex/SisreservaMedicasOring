@extends('layouts.admin')

@section('title', 'Ciudades')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestión de Ciudades</h1>
            <p class="text-gray-600 mt-1">Administra las ciudades por estado</p>
        </div>
        <a href="{{ url('index.php/configuracion/ubicacion/ciudades/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Ciudad</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-building text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-purple-700">Total Ciudades</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['total'] ?? 335 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-emerald-700">Activas</p>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['activas'] ?? 335 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-map text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Estados</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['estados'] ?? 24 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-geo-alt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-amber-700">Municipios</p>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['municipios'] ?? 335 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="input" placeholder="Nombre de ciudad..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="estado_id" class="form-select">
                    <option value="">Todos los estados</option>
                    @foreach($estados ?? [] as $estado)
                    <option value="{{ $estado->id }}" {{ request('estado_id') == $estado->id ? 'selected' : '' }}>
                        {{ $estado->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activas</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ url('index.php/configuracion/ubicacion/ciudades') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th class="w-32">Municipios</th>
                        <th class="w-32">Población</th>
                        <th class="w-24">Estado</th>
                        <th class="w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ciudades ?? [] as $ciudad)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-building text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $ciudad->nombre }}</p>
                                    <p class="text-sm text-gray-500">{{ $ciudad->codigo ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-map text-blue-600"></i>
                                <span class="text-gray-700">{{ $ciudad->estado->nombre ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="text-gray-700 font-semibold">{{ $ciudad->municipios_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ isset($ciudad->poblacion) ? number_format($ciudad->poblacion) : 'N/A' }}</span>
                        </td>
                        <td>
                            @if($ciudad->status)
                            <span class="badge badge-success">Activa</span>
                            @else
                            <span class="badge badge-danger">Inactiva</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ url('index.php/configuracion/ubicacion/ciudades/' . $ciudad->id . '/edit') }}" class="btn btn-sm btn-outline" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="if(confirm('¿Eliminar esta ciudad?')) { /* submit delete form */ }" class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se encontraron ciudades</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($ciudades) && $ciudades->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $ciudades->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
