@extends('layouts.admin')

@section('title', 'Horarios del Consultorio')

@section('content')
<div class="mb-6">
    <a href="{{ route('consultorios.show', 1) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Consultorio
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Horarios - Consultorio 205</h2>
            <p class="text-gray-500 mt-1">Configure la disponibilidad del espacio médico</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('consultorios.horarios.actualizar', 1) }}">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Lunes -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar text-medical-600"></i>
                        Lunes
                    </h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="lunes_activo" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Mañana</label>
                        <input type="time" name="lunes_inicio_am" class="input" value="08:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Mañana</label>
                        <input type="time" name="lunes_fin_am" class="input" value="12:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Tarde</label>
                        <input type="time" name="lunes_inicio_pm" class="input" value="14:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Tarde</label>
                        <input type="time" name="lunes_fin_pm" class="input" value="18:00">
                    </div>
                </div>
            </div>

            <!-- Martes -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar text-success-600"></i>
                        Martes
                    </h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="martes_activo" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Mañana</label>
                        <input type="time" name="martes_inicio_am" class="input" value="08:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Mañana</label>
                        <input type="time" name="martes_fin_am" class="input" value="12:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Tarde</label>
                        <input type="time" name="martes_inicio_pm" class="input" value="14:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Tarde</label>
                        <input type="time" name="martes_fin_pm" class="input" value="18:00">
                    </div>
                </div>
            </div>

            <!-- Miércoles -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar text-warning-600"></i>
                        Miércoles
                    </h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="miercoles_activo" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Mañana</label>
                        <input type="time" name="miercoles_inicio_am" class="input" value="08:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Mañana</label>
                        <input type="time" name="miercoles_fin_am" class="input" value="12:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Tarde</label>
                        <input type="time" name="miercoles_inicio_pm" class="input" value="14:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Tarde</label>
                        <input type="time" name="miercoles_fin_pm" class="input" value="18:00">
                    </div>
                </div>
            </div>

            <!-- Jueves -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar text-info-600"></i>
                        Jueves
                    </h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="jueves_activo" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Mañana</label>
                        <input type="time" name="jueves_inicio_am" class="input" value="08:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Mañana</label>
                        <input type="time" name="jueves_fin_am" class="input" value="12:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Tarde</label>
                        <input type="time" name="jueves_inicio_pm" class="input" value="14:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Tarde</label>
                        <input type="time" name="jueves_fin_pm" class="input" value="18:00">
                    </div>
                </div>
            </div>

            <!-- Viernes -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar text-danger-600"></i>
                        Viernes
                    </h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="viernes_activo" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Mañana</label>
                        <input type="time" name="viernes_inicio_am" class="input" value="08:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Mañana</label>
                        <input type="time" name="viernes_fin_am" class="input" value="12:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Inicio Tarde</label>
                        <input type="time" name="viernes_inicio_pm" class="input" value="14:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hora Fin Tarde</label>
                        <input type="time" name="viernes_fin_pm" class="input" value="18:00">
                    </div>
                </div>
            </div>

            <!-- Sábado -->
            <div class="card p-6 opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar text-gray-600"></i>
                        Sábado
                    </h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="sabado_activo" value="1" class="form-checkbox">
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                
                <p class="text-sm text-gray-500">Día no laboral</p>
            </div>

            <!-- Domingo -->
            <div class="card p-6 opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar text-gray-600"></i>
                        Domingo
                    </h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="domingo_activo" value="1" class="form-checkbox">
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                </div>
                
                <p class="text-sm text-gray-500">Día no laboral</p>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Resumen -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Resumen Semanal</h4>
                
                <div class="space-y-2 text-sm mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Días activos:</span>
                        <span class="font-medium text-gray-900">5 días</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Horas/día:</span>
                        <span class="font-medium text-gray-900">8 horas</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total semanal:</span>
                        <span class="font-medium text-medical-600">40 horas</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Horarios
                </button>
                <a href="{{ route('consultorios.show', 1) }}" class="btn btn-outline w-full">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
            </div>

            <!-- Plantillas Rápidas -->
            <div class="card p-6 bg-info-50 border-info-200">
                <h4 class="font-bold text-info-900 mb-3">Plantillas Rápidas</h4>
                <div class="space-y-2">
                    <button type="button" class="btn btn-sm btn-outline w-full">
                        <i class="bi bi-lightning-fill mr-1"></i>
                        Horario Estándar
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full">
                        <i class="bi bi-sun mr-1"></i>
                        Solo Mañanas
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full">
                        <i class="bi bi-moon mr-1"></i>
                        Solo Tardes
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
