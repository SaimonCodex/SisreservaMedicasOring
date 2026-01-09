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
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
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
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline">
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
                                    {{ substr($usuario->nombre_completo ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $usuario->nombre_completo }}</p>
                                    <p class="text-sm text-gray-500">{{ $usuario->cedula }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-700">{{ $usuario->correo ?? 'N/A' }}</span>
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
                                <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-outline" title="Editar">
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

<!-- Custom Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start transition-all">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bi bi-exclamation-triangle text-amber-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Confirmar acción</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">¿Estás seguro de que deseas cambiar el estado de este usuario? Esta acción afectará su acceso al sistema.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="confirmButton" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition-colors">
                        Confirmar cambio
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentUserId = null;
const modal = document.getElementById('confirmationModal');
const backdrop = document.getElementById('modalBackdrop');
const panel = document.getElementById('modalPanel');
const confirmBtn = document.getElementById('confirmButton');

function toggleStatus(id) {
    currentUserId = id;
    
    // Show Modal
    modal.classList.remove('hidden');
    
    // Animate in
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);
}

function closeModal() {
    // Animate out
    backdrop.classList.add('opacity-0');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        currentUserId = null;
    }, 300);
}

confirmBtn.addEventListener('click', function() {
    if (currentUserId) {
        // Change button state
        confirmBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Procesando...';
        confirmBtn.disabled = true;
        
        // Create form for DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('usuarios.destroy', ':id') }}".replace(':id', currentUserId);
        
        // CSRF Token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Method Spoofing
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
});

// Close on backdrop click
backdrop.addEventListener('click', closeModal);
</script>
@endsection
