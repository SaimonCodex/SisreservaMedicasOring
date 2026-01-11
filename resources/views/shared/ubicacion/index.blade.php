@extends('layouts.admin')

@section('title', 'Gestión de Ubicaciones')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 p-8 shadow-lg">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="text-white">
                <h1 class="text-3xl font-display font-bold">Gestión de Ubicaciones</h1>
                <p class="mt-2 text-indigo-100 text-lg opacity-90">
                    @switch($activeTab)
                        @case('estados') Administración de estados y entidades federales @break
                        @case('ciudades') Gestión de ciudades y capitales @break
                        @case('municipios') Control de municipios y divisiones administrativas @break
                        @case('parroquias') Administración de parroquias locales @break
                    @endswitch
                </p>
            </div>
            <div>
                @switch($activeTab)
                    @case('estados')
                        <a href="{{ route('ubicacion.estados.create') }}" class="btn bg-white text-indigo-700 hover:bg-indigo-50 border-none shadow-md transition-all hover:scale-105 font-semibold">
                            <i class="bi bi-plus-lg mr-2"></i> Nuevo Estado
                        </a>
                        @break
                    @case('ciudades')
                        <a href="{{ route('ubicacion.ciudades.create') }}" class="btn bg-white text-indigo-700 hover:bg-indigo-50 border-none shadow-md transition-all hover:scale-105 font-semibold">
                            <i class="bi bi-plus-lg mr-2"></i> Nueva Ciudad
                        </a>
                        @break
                    @case('municipios')
                        <a href="{{ route('ubicacion.municipios.create') }}" class="btn bg-white text-indigo-700 hover:bg-indigo-50 border-none shadow-md transition-all hover:scale-105 font-semibold">
                            <i class="bi bi-plus-lg mr-2"></i> Nuevo Municipio
                        </a>
                        @break
                    @case('parroquias')
                        <a href="{{ route('ubicacion.parroquias.create') }}" class="btn bg-white text-indigo-700 hover:bg-indigo-50 border-none shadow-md transition-all hover:scale-105 font-semibold">
                            <i class="bi bi-plus-lg mr-2"></i> Nueva Parroquia
                        </a>
                        @break
                @endswitch
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @if(isset($stats))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($stats as $key => $value)
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($value) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                    @if($key == 'total') <i class="bi bi-database text-xl"></i>
                    @elseif($key == 'activos' || $key == 'activas') <i class="bi bi-check-circle text-xl"></i>
                    @elseif($key == 'estados') <i class="bi bi-map text-xl"></i>
                    @elseif($key == 'ciudades') <i class="bi bi-buildings text-xl"></i>
                    @elseif($key == 'municipios') <i class="bi bi-geo-alt text-xl"></i>
                    @else <i class="bi bi-list-ul text-xl"></i>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-xl p-2 shadow-sm border border-gray-100 inline-flex overflow-x-auto max-w-full">
        <nav class="flex space-x-2" aria-label="Tabs">
            <a href="{{ route('ubicacion.estados.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTab === 'estados' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="bi bi-map"></i>
                Estados
            </a>
            <a href="{{ route('ubicacion.ciudades.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTab === 'ciudades' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="bi bi-buildings"></i>
                Ciudades
            </a>
            <a href="{{ route('ubicacion.municipios.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTab === 'municipios' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="bi bi-geo-alt"></i>
                Municipios
            </a>
            <a href="{{ route('ubicacion.parroquias.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTab === 'parroquias' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="bi bi-signpost-2"></i>
                Parroquias
            </a>
        </nav>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters Header -->
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            @switch($activeTab)
                @case('estados')
                    <form action="{{ route('ubicacion.estados.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Buscar</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="search" class="input pl-10" placeholder="Nombre del estado..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Estatus</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activos</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="btn btn-primary h-10 w-full justify-center">Filtrar</button>
                            <a href="{{ route('ubicacion.estados.index') }}" class="btn btn-outline h-10 w-12 flex items-center justify-center text-gray-500 hover:text-gray-700" title="Limpiar">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </form>
                    @break

                @case('ciudades')
                    <form action="{{ route('ubicacion.ciudades.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Buscar</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="search" class="input pl-10" placeholder="Nombre de ciudad..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div>
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Estado</label>
                            <select name="estado_id" class="form-select">
                                <option value="">Todos los estados</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id_estado }}" {{ request('estado_id') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Estatus</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activas</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivas</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="btn btn-primary h-10 w-full justify-center">Filtrar</button>
                            <a href="{{ route('ubicacion.ciudades.index') }}" class="btn btn-outline h-10 w-12 flex items-center justify-center text-gray-500 hover:text-gray-700" title="Limpiar">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </form>
                    @break

                @case('municipios')
                    <form action="{{ route('ubicacion.municipios.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Buscar</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="search" class="input pl-10" placeholder="Nombre de municipio..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div>
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Estado</label>
                            <select name="estado_id" class="form-select">
                                <option value="">Todos los estados</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id_estado }}" {{ request('estado_id') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Estatus</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activos</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="btn btn-primary h-10 w-full justify-center">Filtrar</button>
                            <a href="{{ route('ubicacion.municipios.index') }}" class="btn btn-outline h-10 w-12 flex items-center justify-center text-gray-500 hover:text-gray-700" title="Limpiar">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </form>
                    @break

                @case('parroquias')
                    <form action="{{ route('ubicacion.parroquias.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-2">
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Buscar</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="search" class="input pl-10" placeholder="Nombre..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div>
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Estado</label>
                            <select name="estado_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Todos</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id_estado }}" {{ request('estado_id') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Municipio</label>
                             <select name="municipio_id" class="form-select">
                                <option value="">Todos</option>
                                @foreach($municipios ?? [] as $municipio)
                                <option value="{{ $municipio->id_municipio }}" {{ request('municipio_id') == $municipio->id_municipio ? 'selected' : '' }}>
                                    {{ $municipio->municipio }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <label class="form-label text-xs uppercase text-gray-500 font-semibold mb-1">Estatus</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activas</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivas</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="btn btn-primary h-10 w-full justify-center">Filtrar</button>
                            <a href="{{ route('ubicacion.parroquias.index') }}" class="btn btn-outline h-10 w-12 flex items-center justify-center text-gray-500 hover:text-gray-700" title="Limpiar">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </form>
                    @break
            @endswitch
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            @switch($activeTab)
                @case('estados')
                    @include('shared.ubicacion.partials.table_estados')
                    @break
                @case('ciudades')
                    @include('shared.ubicacion.partials.table_ciudades')
                    @break
                @case('municipios')
                    @include('shared.ubicacion.partials.table_municipios')
                    @break
                @case('parroquias')
                    @include('shared.ubicacion.partials.table_parroquias')
                    @break
            @endswitch
        </div>

        <!-- Pagination -->
        @if(isset($data) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->hasPages())
        <div class="p-6 border-t border-gray-100 bg-gray-50/50">
            {{ $data->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
