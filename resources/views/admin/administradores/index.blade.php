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
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rol / Consultorios</th>
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
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            @if($admin->tipo_admin == 'Root')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-800 w-fit">
                                    <i class="bi bi-star-fill mr-1 text-[10px]"></i> ROOT
                                </span>
                                <span class="text-xs text-gray-400 italic">Acceso Total</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-medical-100 text-medical-800 w-fit">
                                    <i class="bi bi-person-badge mr-1 text-[10px]"></i> ADMIN
                                </span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @forelse($admin->consultorios as $c)
                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] border border-gray-200" title="{{ $c->nombre }}">
                                            {{ Str::limit($c->nombre, 15) }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-amber-500 font-medium italic">Sin consultorios</span>
                                    @endforelse
                                </div>
                            @endif
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
                        <div class="flex gap-2">
                            <a href="{{ route('administradores.show', $admin->id) }}" class="btn btn-sm btn-outline" title="Ver Detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('administradores.edit', $admin->id) }}" class="btn btn-sm btn-outline" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button onclick="toggleStatus({{ $admin->id }}, {{ $admin->status }})" class="btn btn-sm btn-outline {{ $admin->status ? 'text-rose-600' : 'text-emerald-600' }}" title="{{ $admin->status ? 'Desactivar' : 'Activar' }}">
                                <i class="bi {{ $admin->status ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center bg-gray-50/50">
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

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start transition-all">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10" id="modalIconBg">
                            <i class="bi bi-exclamation-triangle text-amber-600 text-xl" id="modalIcon"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Confirmar acción</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-message">¿Estás seguro de continuar con esta acción?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="confirmButton" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition-colors">
                        Confirmar
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
let currentId = null;
const modal = document.getElementById('confirmationModal');
const backdrop = document.getElementById('modalBackdrop');
const panel = document.getElementById('modalPanel');
const confirmBtn = document.getElementById('confirmButton');
const titleElem = document.getElementById('modal-title');
const msgElem = document.getElementById('modal-message');
const iconElem = document.getElementById('modalIcon');
const iconBgElem = document.getElementById('modalIconBg');

function toggleStatus(id, currentStatus) {
    currentId = id;
    
    // Configurar modal
    if (currentStatus) {
        // Va a desactivar
        titleElem.innerText = 'Desactivar Administrador';
        msgElem.innerText = '¿Deseas desactivar este administrador? Perderá el acceso al sistema.';
        confirmBtn.className = 'inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors';
        confirmBtn.innerText = 'Sí, desactivar';
        iconBgElem.className = 'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10';
        iconElem.className = 'bi bi-person-x-fill text-red-600 text-xl';
    } else {
        // Va a activar
        titleElem.innerText = 'Activar Administrador';
        msgElem.innerText = '¿Deseas reactivar este administrador? Recobrará el acceso al sistema.';
        confirmBtn.className = 'inline-flex w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto transition-colors';
        confirmBtn.innerText = 'Sí, activar';
        iconBgElem.className = 'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10';
        iconElem.className = 'bi bi-person-check-fill text-emerald-600 text-xl';
    }

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
    backdrop.classList.add('opacity-0');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        currentId = null;
    }, 300);
}

confirmBtn.addEventListener('click', function() {
    if (currentId) {
        confirmBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Procesando...';
        confirmBtn.disabled = true;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('administradores.toggle-status', ':id') }}".replace(':id', currentId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
});

backdrop.addEventListener('click', closeModal);
</script>
@endsection
