@extends('layouts.admin')

@section('title', 'Detalles Administrador')

@section('content')
<div class="mb-6">
    <a href="{{ route('administradores.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
    </a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-gray-900">
                {{ $administrador->primer_nombre }} {{ $administrador->primer_apellido }}
            </h2>
            <p class="text-gray-500 mt-1">{{ $administrador->usuario->correo }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('administradores.edit', $administrador->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
            
            <button type="button" 
                    id="actionButton"
                    class="btn {{ $administrador->status ? 'btn-danger' : 'btn-success text-white' }}"
                    onclick="openToggleModal()">
                <i class="bi {{ $administrador->status ? 'bi-person-x' : 'bi-person-check' }} mr-2"></i>
                {{ $administrador->status ? 'Desactivar' : 'Activar' }}
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información Principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Datos Personales -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="bi bi-person-circle text-medical-600"></i>
                Información Personal
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nombre Completo</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->primer_nombre }} {{ $administrador->segundo_nombre }}
                        {{ $administrador->primer_apellido }} {{ $administrador->segundo_apellido }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Documento</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->tipo_documento }}-{{ $administrador->numero_documento }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Fecha de Nacimiento</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ \Carbon\Carbon::parse($administrador->fecha_nac)->format('d/m/Y') }}
                        <span class="text-gray-500 text-sm ml-2">
                            ({{ \Carbon\Carbon::parse($administrador->fecha_nac)->age }} años)
                        </span>
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Género</label>
                    <p class="text-gray-900 mt-1 font-medium">{{ $administrador->genero }}</p>
                </div>
            </div>
        </div>

        <!-- Datos de Contacto -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="bi bi-telephone text-medical-600"></i>
                Datos de Contacto
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Correo Electrónico</label>
                    <p class="text-gray-900 mt-1 font-medium flex items-center gap-2">
                        <i class="bi bi-envelope text-medical-400"></i>
                        {{ $administrador->usuario->correo }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Teléfono</label>
                    <p class="text-gray-900 mt-1 font-medium flex items-center gap-2">
                        <i class="bi bi-phone text-medical-400"></i>
                        {{ $administrador->prefijo_tlf }} {{ $administrador->numero_tlf }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Ubicación -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="bi bi-geo-alt text-medical-600"></i>
                Ubicación
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Estado</label>
                    <p class="text-gray-900 mt-1 font-medium">{{ $administrador->estado->estado ?? 'No especificado' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Ciudad</label>
                    <p class="text-gray-900 mt-1 font-medium">{{ $administrador->ciudad->ciudad ?? 'No especificada' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Municipio</label>
                    <p class="text-gray-900 mt-1 font-medium">{{ $administrador->municipio->municipio ?? 'No especificado' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Parroquia</label>
                    <p class="text-gray-900 mt-1 font-medium">{{ $administrador->parroquia->parroquia ?? 'No especificada' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Dirección Detallada</label>
                    <p class="text-gray-900 mt-1 font-medium">{{ $administrador->direccion_detallada ?? 'No especificada' }}</p>
                </div>
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="bi bi-gear text-medical-600"></i>
                Información del Sistema
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Fecha de Registro</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Última Actualización</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Estado -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Estado</h3>
            <div class="text-center">
                @if($administrador->status)
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-success-100 mb-3">
                        <i class="bi bi-check-circle-fill text-3xl text-success-600"></i>
                    </div>
                    <p class="font-semibold text-success-700">Activo</p>
                    <p class="text-sm text-gray-500 mt-1">El administrador puede acceder al sistema</p>
                @else
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-danger-100 mb-3">
                        <i class="bi bi-x-circle-fill text-3xl text-danger-600"></i>
                    </div>
                    <p class="font-semibold text-danger-700">Inactivo</p>
                    <p class="text-sm text-gray-500 mt-1">El administrador no puede acceder</p>
                @endif
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('administradores.edit', $administrador->id) }}" 
                   class="btn btn-outline w-full justify-center">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar Datos
                </a>
                <button type="button" class="btn btn-outline w-full justify-center">
                    <i class="bi bi-key mr-2"></i>
                    Restablecer Contraseña
                </button>
            </div>
        </div>

        <!-- Estadísticas (Opcional) -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Actividad</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Último acceso</span>
                    <span class="font-semibold text-gray-900">Hoy</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Total sesiones</span>
                    <span class="font-semibold text-gray-900">487</span>
                </div>
            </div>
        </div>
    </div>
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
const modal = document.getElementById('confirmationModal');
const backdrop = document.getElementById('modalBackdrop');
const panel = document.getElementById('modalPanel');
const confirmBtn = document.getElementById('confirmButton');
const titleElem = document.getElementById('modal-title');
const msgElem = document.getElementById('modal-message');
const iconElem = document.getElementById('modalIcon');
const iconBgElem = document.getElementById('modalIconBg');

const userId = {{ $administrador->id }};
const currentStatus = {{ $administrador->status ? 'true' : 'false' }};

function openToggleModal() {
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
    }, 300);
}

confirmBtn.addEventListener('click', function() {
    confirmBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Procesando...';
    confirmBtn.disabled = true;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('administradores.toggle-status', ':id') }}".replace(':id', userId);
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    document.body.appendChild(form);
    form.submit();
});

backdrop.addEventListener('click', closeModal);
</script>
@endsection
