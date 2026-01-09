@extends('layouts.admin')

@section('title', 'Administradores')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-display font-bold text-gray-900">Administradores del Sistema</h2>
        <p class="text-gray-500 mt-1">Gestiona los derechos de acceso y usuarios administrativos</p>
    </div>
    <a href="{{ route('administradores.create') }}" class="btn btn-primary shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
        <i class="bi bi-person-plus-fill mr-2"></i>
        Nuevo Administrador
    </a>
</div>

<!-- Filtros y Búsqueda -->
<div class="card p-5 mb-6 border-l-4 border-l-medical-500">
    <form method="GET" action="{{ route('administradores.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div class="md:col-span-2">
            <label class="form-label text-xs uppercase tracking-wide text-gray-400 mb-1">Búsqueda Global</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-400 group-focus-within:text-medical-500 transition-colors"></i>
                </div>
                <input type="text" name="buscar" 
                       placeholder="Nombre, documento, correo..." 
                       class="input pl-10 bg-gray-50 focus:bg-white transition-colors" 
                       value="{{ request('buscar') }}">
            </div>
        </div>
        <div>
            <label class="form-label text-xs uppercase tracking-wide text-gray-400 mb-1">Estado</label>
            <select name="status" class="form-select bg-gray-50 focus:bg-white cursor-pointer" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary w-full justify-center">
                    <i class="bi bi-funnel mr-2"></i> Filtrar
                </button>
                @if(request()->hasAny(['buscar', 'status']))
                <a href="{{ route('administradores.index') }}" class="btn btn-outline px-3" title="Limpiar Filtros">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Tabla de Administradores -->
<div class="card p-0 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perfil</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Identificación</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Registro</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($administradores ?? [] as $admin)
                <tr class="hover:bg-gray-50/80 transition-colors group {{ !$admin->status ? 'bg-gray-50 opacity-75' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full {{ $admin->status ? 'bg-gradient-to-br from-medical-500 to-medical-600' : 'bg-gray-400' }} flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ strtoupper(substr($admin->primer_nombre, 0, 1)) }}{{ strtoupper(substr($admin->primer_apellido, 0, 1)) }}
                                </div>
                                @if($admin->status)
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-success-500 border-2 border-white rounded-full"></span>
                                @else
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-500 border-2 border-white rounded-full"></span>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 {{ !$admin->status ? 'text-gray-500' : '' }}">
                                    {{ $admin->primer_nombre }} {{ $admin->primer_apellido }}
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="bi bi-envelope"></i> {{ $admin->usuario->correo }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <div class="font-medium text-gray-900">{{ $admin->tipo_documento }}-{{ $admin->numero_documento }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                <i class="bi bi-telephone text-gray-400 mr-1"></i>
                                {{ $admin->prefijo_tlf }} {{ $admin->numero_tlf }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($admin->status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800 border border-success-200">
                                <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></span>
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div class="flex flex-col">
                            <span class="font-medium">{{ $admin->created_at->format('d M, Y') }}</span>
                            <span class="text-xs">{{ $admin->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('administradores.show', $admin->id) }}" 
                               class="btn btn-sm btn-ghost hover:bg-medical-50 text-medical-600 tooltip" 
                               title="Ver Detalles">
                                <i class="bi bi-eye text-lg"></i>
                            </a>
                            <a href="{{ route('administradores.edit', $admin->id) }}" 
                               class="btn btn-sm btn-ghost hover:bg-warning-50 text-warning-600 tooltip" 
                               title="Editar">
                                <i class="bi bi-pencil text-lg"></i>
                            </a>
                            
                            @if($admin->status)
                            <form action="{{ route('administradores.destroy', $admin->id) }}" 
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-ghost hover:bg-danger-50 text-danger-600 tooltip" 
                                        title="Desactivar Cuenta"
                                        onclick="return confirm('¿Desea desactivar este administrador? No podrá acceder al sistema.')">
                                    <i class="bi bi-person-x text-lg"></i>
                                </button>
                            </form>
                            @else
                            <button type="button" 
                                    class="btn btn-sm btn-ghost hover:bg-success-50 text-success-600 tooltip" 
                                    title="Usuario Inactivo" disabled>
                                <i class="bi bi-person-slash text-lg opacity-50"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center bg-gray-50/50">
                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <i class="bi bi-search text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">No se encontraron resultados</h3>
                            <p class="text-gray-500 text-sm mb-4">No hay administradores que coincidan con los criterios de búsqueda.</p>
                            @if(request()->hasAny(['buscar', 'status']))
                                <a href="{{ route('administradores.index') }}" class="btn btn-outline btn-sm">
                                    Limpiar Filtros
                                </a>
                            @else
                                <a href="{{ route('administradores.create') }}" class="btn btn-primary btn-sm">
                                    Crear primer administrador
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($administradores) && $administradores->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $administradores->links() }}
    </div>
    @endif
</div>
@endsection
