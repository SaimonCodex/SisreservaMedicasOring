@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestión de Usuarios</h1>
            <p class="text-gray-600 mt-1">Administra usuarios del sistema</p>
        </div>
        <a href="{{ url('index.php/shared/usuarios/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nuevo Usuario</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-people text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Total</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-emerald-700">Activos</p>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['activos'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-person-badge text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-purple-700">Médicos</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['medicos'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-person-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-amber-700">Pacientes</p>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['pacientes'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="input" placeholder="Nombre, email, cédula..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select">
                    <option value="">Todos</option>
                    <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="medico" {{ request('rol') == 'medico' ? 'selected' : '' }}>Médico</option>
                    <option value="paciente" {{ request('rol') == 'paciente' ? 'selected' : '' }}>Paciente</option>
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
                <a href="{{ url('index.php/shared/usuarios') }}" class="btn btn-outline">
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
                        <th>Usuario</th>
                        <th>Email</th>
                        <th class="w-32">Rol</th>
                        <th class="w-32">Último Acceso</th>
                        <th class="w-24">Estado</th>
                        <th class="w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios ?? [] as $usuario)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                                    {{ substr($usuario->nombre ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $usuario->nombre_completo ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $usuario->cedula ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-700">{{ $usuario->email ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if($usuario->rol_id == 1)
                            <span class="badge badge-danger">Admin</span>
                            @elseif($usuario->rol_id == 2)
                            <span class="badge badge-info">Médico</span>
                            @else
                            <span class="badge badge-success">Paciente</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-gray-600 text-sm">{{ isset($usuario->last_login) ? \Carbon\Carbon::parse($usuario->last_login)->diffForHumans() : 'Nunca' }}</span>
                        </td>
                        <td>
                            @if($usuario->status)
                            <span class="badge badge-success">Activo</span>
                            @else
                            <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ url('index.php/shared/usuarios/' . $usuario->id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ url('index.php/shared/usuarios/' . $usuario->id . '/edit') }}" class="btn btn-sm btn-outline" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="toggleStatus({{ $usuario->id }})" class="btn btn-sm btn-outline {{ $usuario->status ? 'text-rose-600' : 'text-emerald-600' }}" title="{{ $usuario->status ? 'Desactivar' : 'Activar' }}">
                                    <i class="bi {{ $usuario->status ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se encontraron usuarios</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($usuarios) && $usuarios->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $usuarios->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function toggleStatus(id) {
    if(confirm('¿Cambiar el estado de este usuario?')) {
        // AJAX call
        console.log('Toggle status:', id);
        location.reload();
    }
}
</script>
@endsection
