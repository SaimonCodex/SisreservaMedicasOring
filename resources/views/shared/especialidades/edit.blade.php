@extends('layouts.admin')

@section('title', 'Editar Especialidad')

@section('content')
<div class="mb-6">
    <a href="{{ route('especialidades.show', 1) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Detalle
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Especialidad</h2>
    <p class="text-gray-500 mt-1">Actualice la información de la especialidad médica</p>
</div>

<form method="POST" action="{{ route('especialidades.update', 1) }}">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información Básica -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-bookmark text-medical-600"></i>
                    Información Básica
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label for="nombre" class="form-label form-label-required">Nombre de la Especialidad</label>
                        <input type="text" id="nombre" name="nombre" class="input" value="Cardiología" required>
                    </div>

                    <div class="form-group">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" id="codigo" name="codigo" class="input" value="CARD-01">
                        <p class="form-help">Código interno para identificación rápida</p>
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="form-label form-label-required">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="form-textarea" required>La cardiología es la rama de la medicina que se ocupa de las afecciones del corazón y del aparato circulatorio.</textarea>
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-gear text-info-600"></i>
                    Configuración
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="duracion_cita_default" class="form-label">Duración por Defecto (min)</label>
                        <select id="duracion_cita_default" name="duracion_cita_default" class="form-select">
                            <option value="15">15 minutos</option>
                            <option value="20">20 minutos</option>
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">1 hora</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="color" class="form-label">Color Identificador</label>
                        <select id="color" name="color" class="form-select">
                            <option value="medical" selected>Azul Médico</option>
                            <option value="success">Verde</option>
                            <option value="warning">Amarillo</option>
                            <option value="danger">Rojo</option>
                            <option value="info">Cian</option>
                            <option value="purple">Púrpura</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="icono" class="form-label">Ícono</label>
                        <select id="icono" name="icono" class="form-select">
                            <option value="heart-pulse" selected>Corazón (Cardiología)</option>
                            <option value="emoji-smile">Sonrisa (Pediatría)</option>
                            <option value="activity">Actividad (Traumatología)</option>
                            <option value="clipboard-pulse">Tabla (Medicina General)</option>
                            <option value="eye">Ojo (Oftalmología)</option>
                            <option value="droplet">Gota (Dermatología)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prioridad" class="form-label">Prioridad</label>
                        <select id="prioridad" name="prioridad" class="form-select">
                            <option value="1" selected>Alta</option>
                            <option value="2">Media</option>
                            <option value="3">Baja</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-info-circle text-success-600"></i>
                    Detalles Adicionales
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label for="requisitos" class="form-label">Requisitos para Citas</label>
                        <textarea id="requisitos" name="requisitos" rows="3" class="form-textarea">• Traer electrocardiograma reciente
• Lista de medicamentos actuales
• Resultados de exámenes previos</textarea>
                    </div>

                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="3" class="form-textarea">Especialidad en alta demanda. Priorizar citas urgentes.</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Vista Previa -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Vista Actual</h4>
                
                <div class="bg-gradient-to-br from-medical-500 to-medical-600 p-6 rounded-xl text-white mb-4">
                    <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30 mb-3">
                        <i class="bi bi-heart-pulse text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-1">Cardiología</h4>
                    <p class="text-white/80 text-sm">CARD-01</p>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Médicos:</span>
                        <span class="font-medium">5</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Citas/Mes:</span>
                        <span class="font-medium">143</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Estado:</span>
                        <span class="badge badge-success">Activa</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Especialidad activa</span>
                    </label>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('especialidades.show', 1) }}" class="btn btn-outline w-full mb-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <button type="button" class="btn btn-sm text-danger-600 hover:bg-danger-50 w-full" onclick="return confirm('¿Está seguro de eliminar esta especialidad? Esta acción no se puede deshacer.')">
                        <i class="bi bi-trash mr-2"></i>
                        Eliminar Especialidad
                    </button>
                </div>
            </div>

            <!-- Historial -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Información del Registro</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Creada:</span>
                        <span class="text-gray-900">15/01/2024</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Última edición:</span>
                        <span class="text-gray-900">08/01/2026</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Editado por:</span>
                        <span class="text-gray-900">Admin</span>
                    </div>
                </div>
            </div>

            <!-- Advertencia -->
            <div class="card p-6 bg-warning-50 border-warning-200">
                <h4 class="font-bold text-warning-900 mb-2 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle"></i>
                    Advertencia
                </h4>
                <p class="text-sm text-warning-700">
                    Los cambios afectarán las nuevas citas que se agenden. Las citas existentes no se modificarán.
                </p>
            </div>
        </div>
    </div>
</form>
@endsection
