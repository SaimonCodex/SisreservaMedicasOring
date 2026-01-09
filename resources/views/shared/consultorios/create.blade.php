@extends('layouts.admin')

@section('title', 'Crear Consultorio')

@section('content')
<div class="mb-6">
    <a href="{{ route('consultorios.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Consultorios
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Crear Nuevo Consultorio</h2>
    <p class="text-gray-500 mt-1">Configure el espacio médico</p>
</div>

<form method="POST" action="{{ route('consultorios.store') }}">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información Básica -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-building text-medical-600"></i>
                    Información Básica
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="numero" class="form-label form-label-required">Número</label>
                        <input type="text" id="numero" name="numero" class="input" placeholder="Ej: 101" required>
                    </div>

                    <div class="form-group">
                        <label for="piso" class="form-label form-label-required">Piso</label>
                        <select id="piso" name="piso" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="1">Piso 1</option>
                            <option value="2">Piso 2</option>
                            <option value="3">Piso 3</option>
                            <option value="4">Piso 4</option>
                            <option value="5">Piso 5</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="nombre" class="form-label form-label-required">Nombre/Descripción</label>
                        <input type="text" id="nombre" name="nombre" class="input" placeholder="Ej: Consultorio Cardiología" required>
                    </div>

                    <div class="form-group">
                        <label for="area" class="form-label">Área (m²)</label>
                        <input type="number" id="area" name="area" class="input" placeholder="25" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="capacidad" class="form-label">Capacidad (personas)</label>
                        <input type="number" id="capacidad" name="capacidad" class="input" placeholder="4" min="1">
                    </div>
                </div>
            </div>

            <!-- Asignación -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person-badge text-success-600"></i>
                    Asignación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="medico_id" class="form-label">Médico Asignado</label>
                        <select id="medico_id" name="medico_id" class="form-select">
                            <option value="">Sin asignar</option>
                            <option value="1">Dr. Juan Pérez - Cardiología</option>
                            <option value="2">Dra. María González - Pediatría</option>
                            <option value="3">Dr. Carlos López - Traumatología</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="especialidad_id" class="form-label">Especialidad Sugerida</label>
                        <select id="especialidad_id" name="especialidad_id" class="form-select">
                            <option value="">Cualquiera</option>
                            <option value="1">Cardiología</option>
                            <option value="2">Pediatría</option>
                            <option value="3">Traumatología</option>
                            <option value="4">Medicina General</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Equipamiento -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-box-seam text-info-600"></i>
                    Equipamiento y Servicios
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="camilla" class="form-checkbox">
                        <span class="text-sm text-gray-700">Camilla</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="escritorio" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Escritorio</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="sillas" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Sillas</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="computadora" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Computadora</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="lavamanos" class="form-checkbox">
                        <span class="text-sm text-gray-700">Lavamanos</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="refrigerador" class="form-checkbox">
                        <span class="text-sm text-gray-700">Refrigerador</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="aire_acondicionado" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Aire Acondicionado</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="ventilador" class="form-checkbox">
                        <span class="text-sm text-gray-700">Ventilador</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="equipamiento[]" value="wifi" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">WiFi</span>
                    </label>
                </div>
            </div>

            <!-- Detalles Adicionales -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-info-circle text-warning-600"></i>
                    Detalles Adicionales
                </h3>
                
                <div class="form-group">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="4" class="form-textarea" placeholder="Notas sobre el consultorio, instrucciones especiales, etc."></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Vista Previa -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Vista Previa</h4>
                
                <div class="bg-gradient-to-br from-medical-500 to-medical-600 p-6 rounded-xl text-white mb-4">
                    <div class="mb-3">
                        <h3 class="text-4xl font-bold">###</h3>
                        <p class="text-white/80 text-sm">Piso #</p>
                    </div>
                    <p class="text-white/90 font-medium">Nombre del Consultorio</p>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Estado:</span>
                        <span class="badge badge-success">Disponible</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Área:</span>
                        <span class="font-medium">- m²</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Capacidad:</span>
                        <span class="font-medium">- personas</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Consultorio disponible</span>
                    </label>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Crear Consultorio
                </button>
                <a href="{{ route('consultorios.index') }}" class="btn btn-outline w-full">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
            </div>

            <!-- Ayuda -->
            <div class="card p-6 bg-info-50 border-info-200">
                <h4 class="font-bold text-info-900 mb-2 flex items-center gap-2">
                    <i class="bi bi-lightbulb"></i>
                    Consejo
                </h4>
                <p class="text-sm text-info-700">
                    El consultorio estará listo para asignar citas inmediatamente después de crearlo. Puede configurar horarios específicos más adelante.
                </p>
            </div>
        </div>
    </div>
</form>
@endsection
