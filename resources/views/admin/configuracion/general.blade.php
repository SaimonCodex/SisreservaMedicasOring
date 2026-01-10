@extends('layouts.admin')

@section('title', 'Configuración General')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3 transition-colors">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Configuración General</h2>
            <p class="text-gray-500 mt-1">Identidad corporativa, contacto y apariencia de la aplicación</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('configuracion.general.actualizar') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Identidad Corporativa -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-medical-50 flex items-center justify-center">
                        <i class="bi bi-hospital text-medical-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Identidad de la Clínica</h3>
                </div>
                
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2 form-group">
                            <label for="app_name" class="form-label flex items-center gap-2">
                                <i class="bi bi-building text-medical-500"></i>
                                Nombre de la Institución
                                <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" name="app_name" id="app_name" 
                                   class="input focus:ring-2 focus:ring-medical-200" 
                                   value="{{ old('app_name', $configuraciones['app_name']) }}" 
                                   placeholder="Ej: Clínica Santa María" required>
                            <p class="form-help">Este nombre aparecerá en títulos, correos y facturas</p>
                        </div>

                        <div class="form-group">
                            <label for="rif" class="form-label">RIF / NIT</label>
                            <input type="text" name="rif" id="rif" 
                                   class="input" 
                                   value="{{ old('rif', $configuraciones['rif']) }}" 
                                   placeholder="J-12345678-9">
                        </div>

                        <div class="form-group">
                            <label for="telefono_principal" class="form-label">Teléfono Principal</label>
                            <input type="text" name="telefono_principal" id="telefono_principal" 
                                   class="input" 
                                   value="{{ old('telefono_principal', $configuraciones['telefono_principal']) }}" 
                                   placeholder="+58 212 1234567">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion" class="form-label">Dirección Física Completa</label>
                        <textarea name="direccion" id="direccion" rows="3" 
                                  class="textarea" 
                                  placeholder="Dirección completa de la sede principal...">{{ old('direccion', $configuraciones['direccion']) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="email_contacto" class="form-label">Email de Contacto</label>
                            <input type="email" name="email_contacto" id="email_contacto" 
                                   class="input" 
                                   value="{{ old('email_contacto', $configuraciones['email_contacto']) }}"
                                   placeholder="contacto@clinica.com">
                        </div>

                        <div class="form-group">
                            <label for="sitio_web" class="form-label">Sitio Web</label>
                            <input type="url" name="sitio_web" id="sitio_web" 
                                   class="input" 
                                   value="{{ old('sitio_web', $configuraciones['sitio_web']) }}"
                                   placeholder="https://www.clinica.com">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redes Sociales -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-info-50 flex items-center justify-center">
                        <i class="bi bi-share text-info-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Redes Sociales</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Facebook</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-facebook text-blue-600"></i>
                            </div>
                            <input type="url" name="social_facebook" 
                                   class="input pl-10" 
                                   value="{{ old('social_facebook', $configuraciones['social_facebook']) }}"
                                   placeholder="https://facebook.com/clinica">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Instagram</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-instagram text-pink-600"></i>
                            </div>
                            <input type="url" name="social_instagram" 
                                   class="input pl-10" 
                                   value="{{ old('social_instagram', $configuraciones['social_instagram']) }}"
                                   placeholder="https://instagram.com/clinica">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Twitter / X</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-twitter-x text-black"></i>
                            </div>
                            <input type="url" name="social_twitter" 
                                   class="input pl-10" 
                                   value="{{ old('social_twitter', $configuraciones['social_twitter']) }}"
                                   placeholder="https://twitter.com/clinica">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">WhatsApp Business</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-whatsapp text-green-600"></i>
                            </div>
                            <input type="text" name="social_whatsapp" 
                                   class="input pl-10" 
                                   value="{{ old('social_whatsapp', $configuraciones['social_whatsapp']) }}"
                                   placeholder="+58 412 1234567">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios de Atención -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-warning-50 flex items-center justify-center">
                        <i class="bi bi-clock text-warning-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Horarios de Atención</h3>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Lunes a Viernes</span>
                        <input type="text" name="horario_lv" class="input w-48 text-center" 
                               value="{{ old('horario_lv', $configuraciones['horario_lv'] ?? '8:00 AM - 6:00 PM') }}">
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Sábados</span>
                        <input type="text" name="horario_sab" class="input w-48 text-center" 
                               value="{{ old('horario_sab', $configuraciones['horario_sab'] ?? '9:00 AM - 2:00 PM') }}">
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">Domingos y Feriados</span>
                        <input type="text" name="horario_dom" class="input w-48 text-center" 
                               value="{{ old('horario_dom', $configuraciones['horario_dom'] ?? 'Cerrado') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Logos y Marca -->
            <div class="card p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Logos y Marca</h3>
                
                <!-- Logo Principal -->
                <div class="mb-6">
                    <label class="form-label mb-3">Logo Principal</label>
                    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-medical-300 hover:bg-medical-50/30 transition-all">
                        <div class="w-24 h-24 mx-auto bg-white rounded-xl shadow-sm flex items-center justify-center mb-3">
                            <i class="bi bi-image text-gray-300 text-3xl"></i>
                        </div>
                        <input type="file" name="logo" id="logo" class="hidden" accept="image/*">
                        <label for="logo" class="btn btn-sm btn-outline cursor-pointer">
                            <i class="bi bi-upload mr-1"></i> Subir Logo
                        </label>
                        <p class="text-xs text-gray-500 mt-2">PNG transparente, 512x512px</p>
                    </div>
                </div>

                <!-- Favicon -->
                <div class="mb-6">
                    <label class="form-label mb-3">Favicon</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                        <div class="w-12 h-12 mx-auto bg-white rounded-lg shadow-sm flex items-center justify-center mb-2">
                            <i class="bi bi-circle text-gray-300"></i>
                        </div>
                        <input type="file" name="favicon" id="favicon" class="hidden" accept="image/x-icon,image/png">
                        <label for="favicon" class="btn btn-sm btn-ghost cursor-pointer text-xs">
                            <i class="bi bi-upload mr-1"></i> Subir Favicon
                        </label>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <button type="submit" class="btn btn-primary w-full shadow-lg">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Configuración
                </button>
                
                <p class="text-center text-xs text-gray-400 mt-3">
                    Última actualización: Hace 2 días
                </p>
            </div>

            <!-- Información del Sistema -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Estado del Sistema</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Versión</span>
                        <span class="font-medium">v2.1.3</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Entorno</span>
                        <span class="badge badge-success text-xs">Producción</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Laravel</span>
                        <span class="font-medium">10.x</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
