@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                <i class="bi bi-megaphone-fill text-2xl text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mensaje Broadcast</h1>
                <p class="text-sm text-gray-600">Envía un comunicado a todos los administradores del sistema</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
        <form action="{{ route('admin.broadcast.store') }}" method="POST">
            @csrf

            <!-- Título -->
            <div class="mb-6">
                <label for="titulo" class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="bi bi-type text-medical-600"></i> Título del Mensaje
                </label>
                <input type="text" 
                       id="titulo" 
                       name="titulo" 
                       value="{{ old('titulo') }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500 focus:border-transparent transition-all"
                       placeholder="Ej: Actualización del Sistema"
                       required>
                @error('titulo')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mensaje -->
            <div class="mb-6">
                <label for="mensaje" class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="bi bi-chat-left-text text-medical-600"></i> Mensaje
                </label>
                <textarea id="mensaje" 
                          name="mensaje" 
                          rows="6"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500 focus:border-transparent transition-all resize-none"
                          placeholder="Escribe el mensaje que deseas enviar..."
                          required>{{ old('mensaje') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Máximo 1000 caracteres</p>
                @error('mensaje')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prioridad -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="bi bi-exclamation-triangle text-medical-600"></i> Prioridad
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-medical-500 transition-all">
                        <input type="radio" name="prioridad" value="normal" checked class="mr-3">
                        <div>
                            <p class="font-bold text-gray-900">Normal</p>
                            <p class="text-xs text-gray-600">Comunicado informativo</p>
                        </div>
                    </label>
                    <label class="relative flex items-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-rose-500 transition-all">
                        <input type="radio" name="prioridad" value="alta" class="mr-3">
                        <div>
                            <p class="font-bold text-gray-900">⚠️ Alta</p>
                            <p class="text-xs text-gray-600">Requiere atención inmediata</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Destinatarios -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="bi bi-people text-medical-600"></i> Destinatarios
                </label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-medical-500 transition-all">
                        <input type="radio" name="destinatarios" value="todos" checked class="mr-3" onclick="document.getElementById('admin-list').classList.add('hidden')">
                        <div>
                            <p class="font-bold text-gray-900">Todos los Administradores</p>
                            <p class="text-xs text-gray-600">Root y Locales recibirán el mensaje</p>
                        </div>
                    </label>
                    <label class="flex items-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-medical-500 transition-all">
                        <input type="radio" name="destinatarios" value="seleccionados" class="mr-3" onclick="document.getElementById('admin-list').classList.remove('hidden')">
                        <div>
                            <p class="font-bold text-gray-900">Administradores Específicos</p>
                            <p class="text-xs text-gray-600">Selecciona manualmente</p>
                        </div>
                    </label>
                </div>

                <!-- Lista de Admins Locales (Hidden by default) -->
                <div id="admin-list" class="hidden mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-bold text-gray-700 mb-3">Selecciona los administradores:</p>
                    <div class="max-h-60 overflow-y-auto space-y-2">
                        @forelse($adminLocales as $admin)
                            <label class="flex items-center p-3 rounded-lg hover:bg-white transition-colors cursor-pointer">
                                <input type="checkbox" name="admin_ids[]" value="{{ $admin->id }}" class="mr-3 rounded text-medical-600 focus:ring-medical-500">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900">{{ $admin->primer_nombre }} {{ $admin->primer_apellido }}</p>
                                    <p class="text-xs text-gray-600">{{ $admin->usuario->correo }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full bg-medical-100 text-medical-700">Local</span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">No hay administradores locales registrados</p>
                        @endforelse
                    </div>
                </div>
                @error('admin_ids')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.dashboard') }}" class="flex-1 px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold text-center hover:bg-gray-200 transition-colors">
                    <i class="bi bi-x-lg mr-2"></i>Cancelar
                </a>
                <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-gradient-to-r from-medical-500 to-medical-600 text-white font-bold hover:shadow-lg transition-all">
                    <i class="bi bi-send-fill mr-2"></i>Enviar Mensaje
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
