@extends('layouts.admin')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">
                    {{ $usuario->nombre_completo }}
                </h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="badge {{ $usuario->status ? 'badge-success' : 'badge-danger' }}">
                        {{ $usuario->status ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="text-gray-400">•</span>
                    <span class="text-gray-600">{{ $usuario->rol->nombre_rol }}</span>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i>
                <span class="hidden sm:inline ml-2">Editar</span>
            </a>
            <button type="button" onclick="confirmDelete()" class="btn btn-danger">
                <i class="bi bi-trash"></i>
                <span class="hidden sm:inline ml-2">Eliminar</span>
            </button>
        </div>
    </div>

    @php
        $perfil = $usuario->administrador ?? $usuario->medico ?? $usuario->paciente;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Personal -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="bi bi-person text-blue-600"></i>
                    Información Personal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Nombre Completo</p>
                        <p class="text-gray-900 text-lg">
                            {{ $perfil->primer_nombre }} {{ $perfil->segundo_nombre }} 
                            {{ $perfil->primer_apellido }} {{ $perfil->segundo_apellido }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Cédula / Documento</p>
                        <p class="text-gray-900 font-mono text-lg">
                            {{ $perfil->tipo_documento }}-{{ $perfil->numero_documento }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Correo Electrónico</p>
                        <div class="flex items-center gap-2">
                            <a href="mailto:{{ $usuario->correo }}" class="text-blue-600 hover:underline">
                                {{ $usuario->correo }}
                            </a>
                            @if($usuario->email_verified_at)
                                <i class="bi bi-patch-check-fill text-green-500" title="Verificado"></i>
                            @else
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full">No verificado</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Teléfono</p>
                        <p class="text-gray-900 font-mono">
                            {{ $perfil->prefijo_tlf }} {{ $perfil->numero_tlf }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Fecha de Nacimiento</p>
                        <p class="text-gray-900">
                            {{ \Carbon\Carbon::parse($perfil->fecha_nac)->format('d/m/Y') }}
                            <span class="text-sm text-gray-500 ml-1">({{ \Carbon\Carbon::parse($perfil->fecha_nac)->age }} años)</span>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Género</p>
                        <p class="text-gray-900 capitalize">{{ $perfil->genero }}</p>
                    </div>
                </div>
            </div>

            <!-- Información Específica del Rol -->
            @if($usuario->medico)
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="bi bi-heart-pulse text-indigo-600"></i>
                    Información Profesional
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Nro. Colegiatura</p>
                        <p class="text-gray-900 font-mono">{{ $perfil->nro_colegiatura ?? 'No registrado' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500 mb-1">Formación Académica</p>
                        <p class="text-gray-900 whitespace-pre-line">{{ $perfil->formacion_academica ?? 'No registrada' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500 mb-1">Experiencia Profesional</p>
                        <p class="text-gray-900 whitespace-pre-line">{{ $perfil->experiencia_profesional ?? 'No registrada' }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($usuario->paciente)
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="bi bi-person-heart text-teal-600"></i>
                    Información de Paciente
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Ocupación</p>
                        <p class="text-gray-900">{{ $perfil->ocupacion ?? 'No registrada' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Estado Civil</p>
                        <p class="text-gray-900">{{ $perfil->estado_civil ?? 'No registrado' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ubicación -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="bi bi-geo-alt text-rose-600"></i>
                    Ubicación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Estado</p>
                        <p class="text-gray-900">{{ $perfil->estado->estado ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Ciudad</p>
                        <p class="text-gray-900">{{ $perfil->ciudad->ciudad ?? 'No especificada' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Municipio</p>
                        <p class="text-gray-900">{{ $perfil->municipio->municipio ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Parroquia</p>
                        <p class="text-gray-900">{{ $perfil->parroquia->parroquia ?? 'No especificada' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500 mb-1">Dirección Detallada</p>
                        <p class="text-gray-900">{{ $perfil->direccion_detallada ?? 'No especificada' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Resumen -->
            <div class="card p-6 bg-gradient-to-br from-indigo-600 to-purple-700 text-white">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold backdrop-blur-sm">
                        {{ substr($perfil->primer_nombre, 0, 1) }}{{ substr($perfil->primer_apellido, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-white/80 text-sm">Rol actual</p>
                        <p class="font-bold text-lg">{{ $usuario->rol->nombre_rol }}</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-white/20">
                    <p class="text-white/80 text-sm mb-1">Miembro desde</p>
                    <p class="font-semibold">{{ $usuario->created_at->translatedFormat('F Y') }}</p>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Metadatos</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ID Usuario</span>
                        <span class="font-mono text-gray-900">#{{ $usuario->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Última Actualización</span>
                        <span class="text-gray-900 text-right">{{ $usuario->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email Verificado</span>
                        <span class="text-gray-900">
                            @if($usuario->email_verified_at)
                                <span class="text-green-600 font-semibold">Sí</span>
                            @else
                                <span class="text-red-500 font-semibold">No</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Admin Actions (Mockup) -->
             <div class="card p-0 overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Accesos Rápidos</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <button type="button" class="w-full text-left px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                        <i class="bi bi-key text-gray-400"></i>
                        Restablecer Contraseña (Email)
                    </button>
                    <button type="button" class="w-full text-left px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                        <i class="bi bi-envelope text-gray-400"></i>
                        Reenviar verificación
                    </button>
                </div>
             </div>
        </div>
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
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Eliminar Usuario</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">¿Estás seguro de que deseas eliminar este usuario? Esta acción es irreversible y eliminará todos los datos asociados.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="confirmButton" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">
                        Sí, eliminar usuario
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
const userId = {{ $usuario->id }};

function confirmDelete() {
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
    }, 300);
}

confirmBtn.addEventListener('click', function() {
    // Change button state
    confirmBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Eliminando...';
    confirmBtn.disabled = true;
    
    // Create form for DELETE request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('usuarios.destroy', ':id') }}".replace(':id', userId);
    
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
});

// Close on backdrop click
backdrop.addEventListener('click', closeModal);
</script>
@endsection
