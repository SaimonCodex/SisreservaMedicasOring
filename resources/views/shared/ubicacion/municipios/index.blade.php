@extends('layouts.admin')

@section('title', 'Municipios')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestión de Municipios</h1>
            <p class="text-gray-600 mt-1">Administra los municipios por estado</p>
        </div>
        <a href="{{ url('index.php/configuracion/ubicacion/municipios/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nuevo Municipio</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="input" placeholder="Nombre de municipio..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="estado_id" class="form-select" id="estado_filter">
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
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ url('index.php/configuracion/ubicacion/municipios') }}" class="btn btn-outline">
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
                        <th>Municipio</th>
                        <th>Estado</th>
                        <th class="w-32">Parroquias</th>
                        <th class="w-24">Estado</th>
                        <th class="w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($municipios ?? [] as $municipio)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-geo-alt text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $municipio->nombre }}</p>
                                    <p class="text-sm text-gray-500">{{ $municipio->codigo ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-map text-blue-600"></i>
                                <span class="text-gray-700">{{ $municipio->estado->nombre ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="text-gray-700 font-semibold">{{ $municipio->parroquias_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($municipio->status)
                            <span class="badge badge-success">Activo</span>
                            @else
                            <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ url('index.php/configuracion/ubicacion/municipios/' . $municipio->id . '/edit') }}" class="btn btn-sm btn-outline" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="if(confirm('¿Eliminar este municipio?')) { /* submit delete form */ }" class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se encontraron municipios</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($municipios) && $municipios->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $municipios->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
