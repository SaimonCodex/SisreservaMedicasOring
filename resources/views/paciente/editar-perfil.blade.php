@extends('layouts.paciente')

@section('title', 'Editar Mi Perfil')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('paciente.dashboard') }}" class="btn btn-outline hover:bg-gray-100">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900">Editar Mi Perfil</h1>
                <p class="text-gray-600 mt-1">Actualiza tu información personal</p>
            </div>
        </div>
    </div>

    <form action="{{ route('paciente.perfil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Información Personal -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-person-badge text-emerald-600"></i>
                        Información Personal
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label required">Primer Nombre</label>
                            <input type="text" name="primer_nombre" class="input" 
                                   value="{{ old('primer_nombre', $paciente->primer_nombre) }}" required>
                            @error('primer_nombre')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Segundo Nombre</label>
                            <input type="text" name="segundo_nombre" class="input" 
                                   value="{{ old('segundo_nombre', $paciente->segundo_nombre) }}">
                            @error('segundo_nombre')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label required">Primer Apellido</label>
                            <input type="text" name="primer_apellido" class="input" 
                                   value="{{ old('primer_apellido', $paciente->primer_apellido) }}" required>
                            @error('primer_apellido')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" name="segundo_apellido" class="input" 
                                   value="{{ old('segundo_apellido', $paciente->segundo_apellido) }}">
                            @error('segundo_apellido')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nac" class="input" 
                                   value="{{ old('fecha_nac', $paciente->fecha_nac) }}">
                            @error('fecha_nac')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Género</label>
                            <select name="genero" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="Masculino" {{ old('genero', $paciente->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $paciente->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero', $paciente->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Ocupación</label>
                            <input type="text" name="ocupacion" class="input" 
                                   value="{{ old('ocupacion', $paciente->ocupacion) }}" placeholder="Ej: Ingeniero">
                            @error('ocupacion')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Estado Civil</label>
                            <select name="estado_civil" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="Soltero" {{ old('estado_civil', $paciente->estado_civil) == 'Soltero' ? 'selected' : '' }}>Soltero(a)</option>
                                <option value="Casado" {{ old('estado_civil', $paciente->estado_civil) == 'Casado' ? 'selected' : '' }}>Casado(a)</option>
                                <option value="Divorciado" {{ old('estado_civil', $paciente->estado_civil) == 'Divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                                <option value="Viudo" {{ old('estado_civil', $paciente->estado_civil) == 'Viudo' ? 'selected' : '' }}>Viudo(a)</option>
                                <option value="Unión Libre" {{ old('estado_civil', $paciente->estado_civil) == 'Unión Libre' ? 'selected' : '' }}>Unión Libre</option>
                            </select>
                            @error('estado_civil')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-telephone text-blue-600"></i>
                        Información de Contacto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Prefijo Teléfono</label>
                            <select name="prefijo_tlf" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="+58" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+58' ? 'selected' : '' }}>+58 (Venezuela)</option>
                                <option value="+57" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+57' ? 'selected' : '' }}>+57 (Colombia)</option>
                                <option value="+1" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+1' ? 'selected' : '' }}>+1 (USA/Canadá)</option>
                                <option value="+34" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+34' ? 'selected' : '' }}>+34 (España)</option>
                            </select>
                            @error('prefijo_tlf')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Número de Teléfono</label>
                            <input type="text" name="numero_tlf" class="input" 
                                   value="{{ old('numero_tlf', $paciente->numero_tlf) }}" 
                                   placeholder="4241234567">
                            @error('numero_tlf')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-purple-600"></i>
                        Ubicación
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Estado</label>
                            <select name="estado_id" id="estado_id" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('estado_id', $paciente->estado_id) == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                                @endforeach
                            </select>
                            @error('estado_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Ciudad</label>
                            <select name="ciudad_id" id="ciudad_id" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id_ciudad }}" {{ old('ciudad_id', $paciente->ciudad_id) == $ciudad->id_ciudad ? 'selected' : '' }}>
                                    {{ $ciudad->ciudad }}
                                </option>
                                @endforeach
                            </select>
                            @error('ciudad_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Municipio</label>
                            <select name="municipio_id" id="municipio_id" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id_municipio }}" {{ old('municipio_id', $paciente->municipio_id) == $municipio->id_municipio ? 'selected' : '' }}>
                                    {{ $municipio->municipio }}
                                </option>
                                @endforeach
                            </select>
                            @error('municipio_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Parroquia</label>
                            <select name="parroquia_id" id="parroquia_id" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($parroquias as $parroquia)
                                <option value="{{ $parroquia->id_parroquia }}" {{ old('parroquia_id', $paciente->parroquia_id) == $parroquia->id_parroquia ? 'selected' : '' }}>
                                    {{ $parroquia->parroquia }}
                                </option>
                                @endforeach
                            </select>
                            @error('parroquia_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="form-label">Dirección Detallada</label>
                            <textarea name="direccion_detallada" rows="3" class="input" 
                                      placeholder="Calle, edificio, piso, apartamento...">{{ old('direccion_detallada', $paciente->direccion_detallada) }}</textarea>
                            @error('direccion_detallada')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Cambiar Contraseña (Opcional) -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-shield-lock text-amber-600"></i>
                        Cambiar Contraseña (Opcional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="input" 
                                   placeholder="Mínimo 8 caracteres">
                            @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="input" 
                                   placeholder="Repita la contraseña">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="bi bi-info-circle"></i> Deja estos campos en blanco si no deseas cambiar tu contraseña
                    </p>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Foto de Perfil -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-camera text-medical-600"></i>
                        Foto de Perfil
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Preview -->
                        <div class="flex justify-center">
                            <div class="relative group">
                                @if($paciente->foto_perfil)
                                    <img id="preview_image" src="{{ asset('storage/' . $paciente->foto_perfil) }}" 
                                         alt="Foto de perfil" 
                                         class="w-32 h-32 rounded-full object-cover border-4 border-medical-100 shadow-lg">
                                @else
                                    <div id="preview_image" class="w-32 h-32 rounded-full bg-gradient-to-br from-medical-100 to-medical-50 flex items-center justify-center text-5xl text-medical-700 font-bold border-4 border-white shadow-lg">
                                        {{ strtoupper(substr($paciente->primer_nombre, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="bi bi-camera text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Upload -->
                        <div>
                            <label for="foto_perfil" class="btn btn-outline w-full cursor-pointer hover:bg-medical-50">
                                <i class="bi bi-upload mr-2"></i> Seleccionar Foto
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

                <!-- Banner de Perfil -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-palette text-emerald-600"></i>
                        Banner de Perfil
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Preview Banner -->
                        <div id="banner_preview_container" class="relative h-28 rounded-2xl overflow-hidden border border-gray-100 shadow-inner group">
                            @if($paciente->banner_perfil)
                                <img id="preview_banner" src="{{ asset('storage/' . $paciente->banner_perfil) }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div id="preview_banner_color" class="w-full h-full {{ $paciente->banner_color ?? 'bg-gradient-to-r from-emerald-100 via-green-100 to-blue-100' }}"
                                     style="{{ str_contains($paciente->banner_color ?? '', '#') ? 'background-color: ' . $paciente->banner_color : '' }}">
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="text-white text-xs font-bold uppercase tracking-wider">Vista Previa</span>
                            </div>
                        </div>

                        <!-- Opciones de Color/Gradiente -->
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 block">Colores y Gradientes</label>
                            <div class="flex flex-wrap gap-3">
                                <!-- Gradientes Predefinidos -->
                                @php
                                    $gradients = [
                                        'bg-gradient-to-r from-emerald-100 via-green-100 to-blue-100',
                                        'bg-gradient-to-r from-blue-600 to-indigo-700',
                                        'bg-gradient-to-r from-emerald-500 to-teal-700',
                                        'bg-gradient-to-r from-purple-500 to-indigo-600',
                                        'bg-gradient-to-r from-rose-400 to-orange-500',
                                        'bg-gradient-to-r from-slate-700 to-slate-900',
                                    ];
                                @endphp

                                @foreach($gradients as $grad)
                                    <button type="button" onclick="setBannerColor('{{ $grad }}')" 
                                            class="w-8 h-8 rounded-full {{ $grad }} border-2 {{ ($paciente->banner_color == $grad) ? 'border-emerald-600 scale-110 shadow-lg' : 'border-white hover:scale-110' }} transition-all">
                                    </button>
                                @endforeach

                                <!-- Custom Color Picker -->
                                <div class="relative flex items-center">
                                    <input type="color" id="custom_color_picker" 
                                           onchange="setBannerColor(this.value)"
                                           class="absolute inset-0 opacity-0 w-8 h-8 cursor-pointer">
                                    <div class="w-8 h-8 rounded-full bg-white border-2 border-gray-200 flex items-center justify-center text-gray-400 hover:text-emerald-500 hover:border-emerald-500 transition-colors">
                                        <i class="bi bi-plus-circle"></i>
                                    </div>
                                </div>

                                <input type="hidden" name="banner_color" id="banner_color_input" value="{{ $paciente->banner_color }}">
                            </div>
                        </div>

                        <!-- Tema Dinámico Toggle -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between p-3 bg-medical-50 rounded-2xl border border-medical-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-medical-100 flex items-center justify-center text-medical-600">
                                        <i class="bi bi-magic text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Tema Dinámico</h4>
                                        <p class="text-[10px] text-gray-500">Adaptar el portal a tu color elegido</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="tema_dinamico" value="1" 
                                           class="sr-only peer" {{ $paciente->tema_dinamico ? 'checked' : '' }}
                                           onchange="updateDynamicPreview()">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-medical-500"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Subir Imagen -->
                        <div class="pt-4 border-t border-gray-100">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 block">O subir una imagen personalizada</label>
                            <div class="flex items-center gap-3">
                                <label for="banner_perfil" class="btn btn-outline flex-1 cursor-pointer hover:bg-medical-50 text-sm">
                                    <i class="bi bi-upload mr-2"></i> Seleccionar Imagen
                                </label>
                            </div>
                            @error('banner_perfil')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                            <p class="text-[10px] text-gray-500 mt-2">Recomendado: 1200x300px. Máximo 3MB. Las imágenes tienen prioridad sobre los colores.</p>
                        </div>
                    </div>
                </div>
                <!-- Información del Sistema -->
                <div class="card p-6 bg-gray-50">
                    <h4 class="text-sm font-bold text-gray-700 mb-3">Información de la Cuenta</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-900">{{ auth()->user()->correo }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Documento:</span>
                            <span class="font-medium text-gray-900">{{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Miembro desde:</span>
                            <span class="font-medium text-gray-900">{{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-emerald-200">
                        <i class="bi bi-check-lg mr-2"></i>
                        Guardar Cambios
                    </button>
                    <a href="{{ route('paciente.dashboard') }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
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
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-32 h-32 rounded-full object-cover border-4 border-emerald-100 shadow-lg">`;
        }
        
        if (file) {
            reader.readAsDataURL(file);
        }
    }

    function previewBanner(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const container = document.getElementById('banner_preview_container');
            container.innerHTML = `
                <img id="preview_banner" src="${e.target.result}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                    <span class="text-white text-xs font-bold uppercase tracking-wider">Vista Previa</span>
                </div>
            `;
        }
        
        if (file) {
            reader.readAsDataURL(file);
        }
    }

    function setBannerColor(color) {
        document.getElementById('banner_color_input').value = color;
        const container = document.getElementById('banner_preview_container');
        
        // Remover imagen si existe en la vista previa
        container.innerHTML = `
            <div id="preview_banner_color" class="w-full h-full" style="${color.startsWith('#') ? 'background-color:'+color : ''}"></div>
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                <span class="text-white text-xs font-bold uppercase tracking-wider">Vista Previa</span>
            </div>
        `;

        const colorDiv = document.getElementById('preview_banner_color');
        if (!color.startsWith('#')) {
            colorDiv.className = 'w-full h-full ' + color;
        }

        // Marcar botones de color
        document.querySelectorAll('[onclick^="setBannerColor"]').forEach(btn => {
            btn.classList.remove('border-emerald-600', 'scale-110', 'shadow-lg');
            btn.classList.add('border-white');
        });
        
        // Resaltar el botón seleccionado si es gradiente
        if (!color.startsWith('#')) {
            const selectedBtn = Array.from(document.querySelectorAll('[onclick^="setBannerColor"]')).find(b => b.getAttribute('onclick').includes(color));
            if (selectedBtn) {
                selectedBtn.classList.remove('border-white');
                selectedBtn.classList.add('border-emerald-600', 'scale-110', 'shadow-lg');
            }
        }

        updateDynamicPreview();
    }

    function updateDynamicPreview() {
        const isEnabled = document.querySelector('input[name="tema_dinamico"]').checked;
        const color = document.getElementById('banner_color_input').value;
        const root = document.documentElement;
        
        if (isEnabled && color) {
            let baseColor = '#10b981';
            let isLight = false;

            if (color.startsWith('#')) {
                baseColor = color;
            } else if (color.includes('from-')) {
                const match = color.match(/from-([a-z]+)-(\d+)/);
                if (match) {
                    const colors = {
                        emerald: '#10b981', blue: '#3b82f6', teal: '#14b8a6',
                        purple: '#a855f7', rose: '#f43f5e', slate: '#64748b',
                        orange: '#f97316', indigo: '#6366f1'
                    };
                    baseColor = colors[match[1]] || baseColor;
                }
            }
            
            // Calcular luminancia para contraste
            const hex = baseColor.replace('#', '');
            const r = parseInt(hex.length === 3 ? hex[0] + hex[0] : hex.substring(0, 2), 16);
            const g = parseInt(hex.length === 3 ? hex[1] + hex[1] : hex.substring(2, 4), 16);
            const b = parseInt(hex.length === 3 ? hex[2] + hex[2] : hex.substring(4, 6), 16);
            const luminance = (r * 0.299 + g * 0.587 + b * 0.114) / 255;
            isLight = luminance > 0.6;
            const textColor = isLight ? '#0f172a' : '#ffffff';

            // Aplicar variables CSS en tiempo real
            root.style.setProperty('--medical-500', baseColor);
            
            // Si no existen las clases dinámicas en el style tag, las inyectamos o actualizamos
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
                    --medical-200: ${baseColor}33;
                    --medical-50: ${baseColor}1a;
                    --text-on-medical: ${textColor};
                }
                .bg-medical-500 { background-color: var(--medical-500) !important; }
                .text-medical-500 { color: var(--medical-500) !important; }
                .text-medical-600 { color: var(--medical-600) !important; }
                .bg-medical-50 { background-color: var(--medical-50) !important; }
                .border-medical-500 { border-color: var(--medical-500) !important; }
                /* Forzar contraste en el preview del banner */
                #banner_preview_container { color: var(--text-on-medical) !important; }

                /* Animaciones para el preview en vivo */
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
            root.style.removeProperty('--medical-500');
            root.style.removeProperty('--text-on-medical');
        }
    }

    function removeBannerImage() {
        if(confirm('¿Deseas eliminar la imagen de banner actual? El color seleccionado se usará en su lugar.')) {
            // Esto se manejaría mejor con un campo hidden para marcar eliminación, 
            // pero por ahora limpiamos la vista previa y el backend mantendrá el color si no se sube nueva imagen.
            setBannerColor(document.getElementById('banner_color_input').value || 'bg-gradient-to-r from-emerald-100 via-green-100 to-blue-100');
            // Nota: En una app real, deberías enviar un flag al backend para borrar el archivo.
        }
    }

    // AJAX para cargar datos de ubicación
    document.getElementById('estado_id').addEventListener('change', function() {
        const estadoId = this.value;
        if (estadoId) {
            fetch(`{{ url('ubicacion/get-ciudades') }}/${estadoId}`)
                .then(response => response.json())
                .then(data => {
                    const ciudadSelect = document.getElementById('ciudad_id');
                    ciudadSelect.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(item => {
                        ciudadSelect.innerHTML += `<option value="${item.id_ciudad}">${item.ciudad}</option>`;
                    });
                });

            fetch(`{{ url('ubicacion/get-municipios') }}/${estadoId}`)
                .then(response => response.json())
                .then(data => {
                    const municipioSelect = document.getElementById('municipio_id');
                    municipioSelect.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(item => {
                        municipioSelect.innerHTML += `<option value="${item.id_municipio}">${item.municipio}</option>`;
                    });
                });
        }
    });

    document.getElementById('municipio_id').addEventListener('change', function() {
        const municipioId = this.value;
        if (municipioId) {
            fetch(`{{ url('ubicacion/get-parroquias') }}/${municipioId}`)
                .then(response => response.json())
                .then(data => {
                    const parroquiaSelect = document.getElementById('parroquia_id');
                    parroquiaSelect.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(item => {
                        parroquiaSelect.innerHTML += `<option value="${item.id_parroquia}">${item.parroquia}</option>`;
                    });
                });
        }
    });
</script>
@endpush
@endsection
