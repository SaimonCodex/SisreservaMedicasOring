@extends('layouts.admin')

@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8 p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-medical-500/10 flex items-center justify-center text-medical-600">
                <i class="bi bi-person-circle text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Configuración de Perfil</h1>
                <p class="text-gray-500 text-sm">Gestiona tu información personal y personaliza tu entorno de trabajo</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.perfil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Información Personal -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-person-badge text-medical-600"></i>
                        Información Personal
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre</label>
                            <input type="text" name="primer_nombre" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   value="{{ old('primer_nombre', $administrador->primer_nombre) }}" required>
                            @error('primer_nombre')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                            <input type="text" name="segundo_nombre" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   value="{{ old('segundo_nombre', $administrador->segundo_nombre) }}">
                            @error('segundo_nombre')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido</label>
                            <input type="text" name="primer_apellido" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   value="{{ old('primer_apellido', $administrador->primer_apellido) }}" required>
                            @error('primer_apellido')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Apellido</label>
                            <input type="text" name="segundo_apellido" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   value="{{ old('segundo_apellido', $administrador->segundo_apellido) }}">
                            @error('segundo_apellido')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nac" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   value="{{ old('fecha_nac', $administrador->fecha_nac) }}">
                            @error('fecha_nac')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
                            <select name="genero" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all">
                                <option value="">Seleccione...</option>
                                <option value="Masculino" {{ old('genero', $administrador->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $administrador->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero', $administrador->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-telephone text-blue-600"></i>
                        Información de Contacto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prefijo Teléfono</label>
                            <select name="prefijo_tlf" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all">
                                <option value="">Seleccione...</option>
                                <option value="+58" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+58' ? 'selected' : '' }}>+58 (Venezuela)</option>
                                <option value="+57" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+57' ? 'selected' : '' }}>+57 (Colombia)</option>
                                <option value="+1" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+1' ? 'selected' : '' }}>+1 (USA/Canadá)</option>
                                <option value="+34" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+34' ? 'selected' : '' }}>+34 (España)</option>
                            </select>
                            @error('prefijo_tlf')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número de Teléfono</label>
                            <input type="text" name="numero_tlf" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   value="{{ old('numero_tlf', $administrador->numero_tlf) }}" 
                                   placeholder="4241234567">
                            @error('numero_tlf')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-purple-600"></i>
                        Ubicación
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="estado_id" id="estado_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all">
                                <option value="">Seleccione...</option>
                                @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('estado_id', $administrador->estado_id) == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                                @endforeach
                            </select>
                            @error('estado_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                            <select name="ciudad_id" id="ciudad_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all">
                                <option value="">Seleccione...</option>
                                @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id_ciudad }}" {{ old('ciudad_id', $administrador->ciudad_id) == $ciudad->id_ciudad ? 'selected' : '' }}>
                                    {{ $ciudad->ciudad }}
                                </option>
                                @endforeach
                            </select>
                            @error('ciudad_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                            <select name="municipio_id" id="municipio_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all">
                                <option value="">Seleccione...</option>
                                @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id_municipio }}" {{ old('municipio_id', $administrador->municipio_id) == $municipio->id_municipio ? 'selected' : '' }}>
                                    {{ $municipio->municipio }}
                                </option>
                                @endforeach
                            </select>
                            @error('municipio_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Parroquia</label>
                            <select name="parroquia_id" id="parroquia_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all">
                                <option value="">Seleccione...</option>
                                @foreach($parroquias as $parroquia)
                                <option value="{{ $parroquia->id_parroquia }}" {{ old('parroquia_id', $administrador->parroquia_id) == $parroquia->id_parroquia ? 'selected' : '' }}>
                                    {{ $parroquia->parroquia }}
                                </option>
                                @endforeach
                            </select>
                            @error('parroquia_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección Detallada</label>
                            <textarea name="direccion_detallada" rows="3" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                      placeholder="Calle, edificio, piso, apartamento...">{{ old('direccion_detallada', $administrador->direccion_detallada) }}</textarea>
                            @error('direccion_detallada')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Seguridad -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-shield-lock text-amber-600"></i>
                        Seguridad y Acceso
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                            <input type="password" name="password" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   placeholder="Mínimo 8 caracteres">
                            @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 outline-none transition-all" 
                                   placeholder="Repita la contraseña">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="bi bi-info-circle"></i> Deja estos campos en blanco si no deseas cambiar tu contraseña.
                    </p>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Foto de Perfil -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-camera text-medical-600"></i>
                        Foto de Perfil
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Preview -->
                        <div class="flex justify-center">
                            <div class="relative group">
                                @if($administrador->foto_perfil)
                                    <img id="preview_image" src="{{ asset('storage/' . $administrador->foto_perfil) }}" 
                                         alt="Foto de perfil" 
                                         class="w-32 h-32 rounded-full object-cover border-4 border-medical-100 shadow-lg">
                                @else
                                    <div id="preview_image" class="w-32 h-32 rounded-full bg-gradient-to-br from-medical-100 to-medical-50 flex items-center justify-center text-5xl text-medical-700 font-bold border-4 border-white shadow-lg">
                                        {{ strtoupper(substr($administrador->primer_nombre, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                    <i class="bi bi-camera text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Upload -->
                        <div>
                            <label for="foto_perfil" class="flex items-center justify-center gap-2 px-4 py-2 rounded-xl border-2 border-dashed border-gray-200 text-gray-600 hover:border-medical-500 hover:text-medical-600 hover:bg-medical-50 cursor-pointer transition-all">
                                <i class="bi bi-upload"></i> Seleccionar Foto
                            </label>
                            <input type="file" id="foto_perfil" name="foto_perfil" class="hidden" 
                                   accept="image/*" onchange="previewImage(event)">
                            @error('foto_perfil')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <p class="text-xs text-gray-500 text-center">
                            JPG, PNG o GIF. Máximo 2MB
                        </p>
                    </div>
                </div>

                <!-- Personalización de Tema -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-palette text-emerald-600"></i>
                        Personalización
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Preview Banner -->
                        <div id="banner_preview_container" class="relative h-28 rounded-2xl overflow-hidden border border-gray-100 shadow-inner group">
                            @if($administrador->banner_perfil)
                                <img id="preview_banner" src="{{ asset('storage/' . $administrador->banner_perfil) }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div id="preview_banner_color" class="w-full h-full {{ $administrador->banner_color ?? 'bg-gradient-to-r from-medical-500 to-indigo-600' }}"
                                     style="{{ str_contains($administrador->banner_color ?? '', '#') ? 'background-color: ' . $administrador->banner_color : '' }}">
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none text-white text-xs font-bold uppercase tracking-wider">
                                Vista Previa
                            </div>
                        </div>

                        <!-- Opciones de Color -->
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 block">Color del Sistema</label>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $gradients = [
                                        'bg-gradient-to-r from-medical-600 to-indigo-700',
                                        'bg-gradient-to-r from-emerald-500 to-teal-700',
                                        'bg-gradient-to-r from-blue-600 to-indigo-800',
                                        'bg-gradient-to-r from-purple-600 to-indigo-700',
                                        'bg-gradient-to-r from-rose-500 to-orange-600',
                                        'bg-gradient-to-r from-slate-700 to-slate-900',
                                    ];
                                @endphp

                                @foreach($gradients as $grad)
                                    <button type="button" onclick="setBannerColor('{{ $grad }}')" 
                                            class="w-7 h-7 rounded-lg {{ $grad }} border-2 {{ ($administrador->banner_color == $grad) ? 'border-medical-500 scale-110 shadow-lg' : 'border-white' }} transition-all">
                                    </button>
                                @endforeach

                                <div class="relative flex items-center">
                                    <input type="color" id="custom_color_picker" 
                                           onchange="setBannerColor(this.value)"
                                           class="absolute inset-0 opacity-0 w-7 h-7 cursor-pointer">
                                    <div class="w-7 h-7 rounded-lg bg-gray-50 border-2 border-gray-200 flex items-center justify-center text-gray-400 hover:text-medical-500 transition-colors">
                                        <i class="bi bi-plus-circle"></i>
                                    </div>
                                </div>

                                <input type="hidden" name="banner_color" id="banner_color_input" value="{{ $administrador->banner_color }}">
                            </div>
                        </div>

                        <!-- Tema Dinámico Toggle -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between p-3 bg-medical-50 rounded-2xl border border-medical-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white border border-medical-100 flex items-center justify-center text-medical-600 shadow-sm">
                                        <i class="bi bi-magic text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 leading-none">Tema Dinámico</h4>
                                        <p class="text-[10px] text-gray-500 mt-1">Colores personalizados en todo el panel</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="tema_dinamico" value="1" 
                                           class="sr-only peer" {{ $administrador->tema_dinamico ? 'checked' : '' }}
                                           onchange="updateDynamicPreview()">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-medical-500"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Subir Banner -->
                        <div class="pt-4 border-t border-gray-100">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 block">Imagen de Fondo</label>
                            <label for="banner_perfil" class="flex items-center justify-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 cursor-pointer transition-all text-sm">
                                <i class="bi bi-upload"></i> Subir Imagen
                            </label>
                            <input type="file" id="banner_perfil" name="banner_perfil" class="hidden" 
                                   accept="image/*" onchange="previewBanner(event)">
                            @error('banner_perfil')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                            <p class="text-[10px] text-gray-400 mt-2 italic text-center">1200x300px recomendado (Máx 3MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Info Sistema -->
                <div class="card p-5 bg-gray-50 border-none">
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between items-baseline">
                            <span class="text-gray-500">Tipo de Admin:</span>
                            <span class="font-bold text-medical-600 px-2 py-0.5 bg-medical-50 rounded-lg">{{ $administrador->tipo_admin }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID de Usuario:</span>
                            <span class="font-medium text-gray-900">#{{ auth()->user()->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Correo:</span>
                            <span class="font-medium text-gray-900">{{ auth()->user()->correo }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botones Accion -->
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-3 bg-medical-500 text-white rounded-2xl font-bold shadow-lg shadow-medical-200 hover:bg-medical-600 hover:-translate-y-0.5 transition-all">
                        <i class="bi bi-save2 mr-2"></i> Guardar Configuración
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="w-full py-3 bg-white text-gray-600 border border-gray-200 rounded-2xl font-bold text-center hover:bg-gray-50 transition-all">
                        Regresar al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview_image');
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-32 h-32 rounded-full object-cover border-4 border-medical-100 shadow-lg">`;
        }
        if (file) reader.readAsDataURL(file);
    }

    function previewBanner(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('banner_preview_container');
            container.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none text-white text-xs font-bold uppercase tracking-wider">
                    Vista Previa
                </div>
            `;
        }
        if (file) reader.readAsDataURL(file);
    }

    function setBannerColor(color) {
        document.getElementById('banner_color_input').value = color;
        const container = document.getElementById('banner_preview_container');
        container.innerHTML = `
            <div id="preview_banner_color" class="w-full h-full" style="${color.startsWith('#') ? 'background-color:'+color : ''}"></div>
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none text-white text-xs font-bold uppercase tracking-wider">
                Vista Previa
            </div>
        `;

        if (!color.startsWith('#')) {
            document.getElementById('preview_banner_color').className = 'w-full h-full ' + color;
        }

        document.querySelectorAll('[onclick^="setBannerColor"]').forEach(btn => {
            btn.classList.remove('border-medical-500', 'scale-110', 'shadow-lg');
            btn.classList.add('border-white');
        });

        if (!color.startsWith('#')) {
            const selectedBtn = Array.from(document.querySelectorAll('[onclick^="setBannerColor"]')).find(b => b.getAttribute('onclick').includes(color));
            if (selectedBtn) {
                selectedBtn.classList.remove('border-white');
                selectedBtn.classList.add('border-medical-500', 'scale-110', 'shadow-lg');
            }
        }
        updateDynamicPreview();
    }

    function updateDynamicPreview() {
        const isEnabled = document.querySelector('input[name="tema_dinamico"]').checked;
        const color = document.getElementById('banner_color_input').value;
        const root = document.documentElement;
        
        if (isEnabled && color) {
            let baseColor = '#10b981'; // Default emerald
            if (color.startsWith('#')) {
                baseColor = color;
            } else if (color.includes('from-')) {
                const match = color.match(/from-([a-z]+)-(\d+)/);
                if (match) {
                    const colors = {
                        medical: '#10b981', emerald: '#10b981', blue: '#3b82f6', 
                        teal: '#14b8a6', purple: '#a855f7', rose: '#f43f5e', 
                        slate: '#64748b', orange: '#f97316', indigo: '#6366f1'
                    };
                    baseColor = colors[match[1]] || baseColor;
                }
            }

            const hex = baseColor.replace('#', '');
            const r = parseInt(hex.length === 3 ? hex[0] + hex[0] : hex.substring(0, 2), 16);
            const g = parseInt(hex.length === 3 ? hex[1] + hex[1] : hex.substring(2, 4), 16);
            const b = parseInt(hex.length === 3 ? hex[2] + hex[2] : hex.substring(4, 6), 16);
            const luminance = (r * 0.299 + g * 0.587 + b * 0.114) / 255;
            const textColor = luminance > 0.6 ? '#0f172a' : '#ffffff';

            let styleTag = document.getElementById('dynamic-preview-style');
            if (!styleTag) {
                styleTag = document.createElement('style');
                styleTag.id = 'dynamic-preview-style';
                document.head.appendChild(styleTag);
            }
            
            styleTag.innerHTML = `
                :root {
                    --medical-500: ${baseColor};
                    --medical-600: ${baseColor}cc;
                    --medical-400: ${baseColor}eb;
                    --medical-200: ${baseColor}33;
                    --medical-50: ${baseColor}1a;
                    --text-on-medical: ${textColor};
                }
                .bg-medical-500 { background-color: var(--medical-500) !important; }
                .text-medical-500 { color: var(--medical-500) !important; }
                .text-medical-600 { color: var(--medical-600) !important; }
                .bg-medical-50 { background-color: var(--medical-50) !important; }
                .border-medical-500 { border-color: var(--medical-500) !important; }
                .shadow-medical-200 { --tw-shadow-color: var(--medical-200) !important; }
                
                @keyframes float-orb {
                    0%, 100% { transform: translate(0, 0) scale(1); }
                    33% { transform: translate(30px, -50px) scale(1.1); }
                    66% { transform: translate(-20px, 20px) scale(0.9); }
                }
                .animate-float-orb { animation: float-orb 15s ease-in-out infinite; }
                .animate-float-orb-slow { animation: float-orb 25s ease-in-out infinite reverse; }
                .animate-float-orb-delayed { animation: float-orb 20s ease-in-out infinite; animation-delay: -5s; }
            `;
        } else {
            const styleTag = document.getElementById('dynamic-preview-style');
            if (styleTag) styleTag.innerHTML = '';
        }
    }

    // AJAX Ubicación
    document.getElementById('estado_id').addEventListener('change', function() {
        const estadoId = this.value;
        if (estadoId) {
            fetch(`{{ url('ubicacion/get-ciudades') }}/${estadoId}`)
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('ciudad_id');
                    select.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(i => select.innerHTML += `<option value="${i.id_ciudad}">${i.ciudad}</option>`);
                });
            fetch(`{{ url('ubicacion/get-municipios') }}/${estadoId}`)
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('municipio_id');
                    select.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(i => select.innerHTML += `<option value="${i.id_municipio}">${i.municipio}</option>`);
                });
        }
    });

    document.getElementById('municipio_id').addEventListener('change', function() {
        const municipioId = this.value;
        if (municipioId) {
            fetch(`{{ url('ubicacion/get-parroquias') }}/${municipioId}`)
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('parroquia_id');
                    select.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(i => select.innerHTML += `<option value="${i.id_parroquia}">${i.parroquia}</option>`);
                });
        }
    });
</script>
@endpush
@endsection
