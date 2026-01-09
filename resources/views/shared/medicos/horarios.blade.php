@extends('layouts.admin')

@section('title', 'Horarios del Médico')

@section('content')
<div class="mb-6">
    <a href="{{ route('medicos.show', 1) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Perfil
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Horarios de Atención</h2>
            <p class="text-gray-500 mt-1">Dr. Juan Pérez - Cardiología</p>
        </div>
        <button class="btn btn-primary" onclick="alert('Función de guardado')">
            <i class="bi bi-save mr-2"></i>
            Guardar Cambios
        </button>
    </div>
</div>

<form method="POST" action="{{ route('medicos.guardar-horario', 1) }}">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuración de Horarios -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Lunes -->
            <div class="card p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="activo[lunes]" value="1" class="form-checkbox text-medical-600 w-5 h-5" checked>
                        </label>
                        <h3 class="text-lg font-bold text-gray-900">Lunes</h3>
                    </div>
                    <span class="badge badge-success">Activo</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label text-xs">Jornada Mañana</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="lunes[manana_inicio]" class="input text-sm" value="08:00">
                            <input type="time" name="lunes[manana_fin]" class="input text-sm" value="12:00">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-xs">Jornada Tarde</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="lunes[tarde_inicio]" class="input text-sm" value="14:00">
                            <input type="time" name="lunes[tarde_fin]" class="input text-sm" value="18:00">
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label text-xs">Duración Cita (min)</label>
                        <select name="lunes[duracion_cita]" class="form-select text-sm">
                            <option value="15">15 minutos</option>
                            <option value="20">20 minutos</option>
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">1 hora</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs">Cupos por hora</label>
                        <input type="number" name="lunes[cupos_hora]" class="input text-sm" value="2" min="1" max="10">
                    </div>
                </div>
            </div>

            <!-- Martes -->
            <div class="card p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="activo[martes]" value="1" class="form-checkbox text-medical-600 w-5 h-5" checked>
                        </label>
                        <h3 class="text-lg font-bold text-gray-900">Martes</h3>
                    </div>
                    <span class="badge badge-success">Activo</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label text-xs">Jornada Mañana</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="martes[manana_inicio]" class="input text-sm" value="08:00">
                            <input type="time" name="martes[manana_fin]" class="input text-sm" value="12:00">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-xs">Jornada Tarde</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="martes[tarde_inicio]" class="input text-sm" value="14:00">
                            <input type="time" name="martes[tarde_fin]" class="input text-sm" value="18:00">
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label text-xs">Duración Cita (min)</label>
                        <select name="martes[duracion_cita]" class="form-select text-sm">
                            <option value="15">15 minutos</option>
                            <option value="20">20 minutos</option>
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">1 hora</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs">Cupos por hora</label>
                        <input type="number" name="martes[cupos_hora]" class="input text-sm" value="2" min="1" max="10">
                    </div>
                </div>
            </div>

            <!-- Miércoles -->
            <div class="card p-6 hover:shadow-md transition-shadow opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="activo[miercoles]" value="1" class="form-checkbox text-medical-600 w-5 h-5">
                        </label>
                        <h3 class="text-lg font-bold text-gray-900">Miércoles</h3>
                    </div>
                    <span class="badge badge-gray">Inactivo</span>
                </div>
                <p class="text-sm text-gray-500 italic">No hay atención este día</p>
            </div>

            <!-- Jueves -->
            <div class="card p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="activo[jueves]" value="1" class="form-checkbox text-medical-600 w-5 h-5" checked>
                        </label>
                        <h3 class="text-lg font-bold text-gray-900">Jueves</h3>
                    </div>
                    <span class="badge badge-success">Activo</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label text-xs">Jornada Mañana</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="jueves[manana_inicio]" class="input text-sm" value="08:00">
                            <input type="time" name="jueves[manana_fin]" class="input text-sm" value="12:00">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-xs">Jornada Tarde</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="jueves[tarde_inicio]" class="input text-sm" value="" placeholder="--:--">
                            <input type="time" name="jueves[tarde_fin]" class="input text-sm" value="" placeholder="--:--">
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label text-xs">Duración Cita (min)</label>
                        <select name="jueves[duracion_cita]" class="form-select text-sm">
                            <option value="15">15 minutos</option>
                            <option value="20">20 minutos</option>
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">1 hora</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs">Cupos por hora</label>
                        <input type="number" name="jueves[cupos_hora]" class="input text-sm" value="2" min="1" max="10">
                    </div>
                </div>
            </div>

            <!-- Viernes -->
            <div class="card p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="activo[viernes]" value="1" class="form-checkbox text-medical-600 w-5 h-5" checked>
                        </label>
                        <h3 class="text-lg font-bold text-gray-900">Viernes</h3>
                    </div>
                    <span class="badge badge-success">Activo</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label text-xs">Jornada Mañana</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="viernes[manana_inicio]" class="input text-sm" value="08:00">
                            <input type="time" name="viernes[manana_fin]" class="input text-sm" value="12:00">
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-xs">Jornada Tarde</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" name="viernes[tarde_inicio]" class="input text-sm" value="14:00">
                            <input type="time" name="viernes[tarde_fin]" class="input text-sm" value="18:00">
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label text-xs">Duración Cita (min)</label>
                        <select name="viernes[duracion_cita]" class="form-select text-sm">
                            <option value="15">15 minutos</option>
                            <option value="20">20 minutos</option>
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">1 hora</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs">Cupos por hora</label>
                        <input type="number" name="viernes[cupos_hora]" class="input text-sm" value="2" min="1" max="10">
                    </div>
                </div>
            </div>

            <!-- Sábado y Domingo (Inactivos por defecto) -->
            <div class="card p-6 hover:shadow-md transition-shadow opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="activo[sabado]" value="1" class="form-checkbox text-medical-600 w-5 h-5">
                        </label>
                        <h3 class="text-lg font-bold text-gray-900">Sábado</h3>
                    </div>
                    <span class="badge badge-gray">Inactivo</span>
                </div>
                <p class="text-sm text-gray-500 italic">No hay atención este día</p>
            </div>

            <div class="card p-6 hover:shadow-md transition-shadow opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="activo[domingo]" value="1" class="form-checkbox text-medical-600 w-5 h-5">
                        </label>
                        <h3 class="text-lg font-bold text-gray-900">Domingo</h3>
                    </div>
                    <span class="badge badge-gray">Inactivo</span>
                </div>
                <p class="text-sm text-gray-500 italic">No hay atención este día</p>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Resumen Semanal -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Resumen Semanal</h4>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Días activos</span>
                        <span class="font-bold text-medical-600">5 de 7</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Horas semanales</span>
                        <span class="font-bold text-gray-900">36 hrs</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Cupos máximos/día</span>
                        <span class="font-bold text-success-600">~16 citas</span>
                    </div>
                </div>

                <div class="bg-medical-50 rounded-xl p-4 mb-4">
                    <p class="text-xs text-medical-700 font-medium mb-2">💡 Consejo</p>
                    <p class="text-xs text-gray-600">
                        Configure descansos de 15-30 min cada 4 horas para mantener la calidad de atención.
                    </p>
                </div>

                <button type="button" class="btn btn-outline w-full text-sm">
                    <i class="bi bi-clipboard mr-2"></i>
                    Copiar de semana anterior
                </button>
            </div>

            <!-- Plantillas Rápidas -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Plantillas Rápidas</h4>
                <div class="space-y-2">
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-clock mr-2"></i>
                        Jornada Completa (8-12, 2-6)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-sunrise mr-2"></i>
                        Solo Mañanas (8-12)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-sunset mr-2"></i>
                        Solo Tardes (2-6)
                    </button>
                </div>
            </div>

            <!-- Vista Previa Calendario -->
            <div class="card p-6 bg-gradient-to-br from-medical-50 to-info-50">
                <h4 class="font-bold text-gray-900 mb-4">Disponibilidad</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Lun-Mar</span>
                        <span class="text-xs badge badge-success">8 hrs/día</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Miércoles</span>
                        <span class="text-xs badge badge-gray">Cerrado</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Jueves</span>
                        <span class="text-xs badge badge-warning">4 hrs</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Viernes</span>
                        <span class="text-xs badge badge-success">8 hrs</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Fin de semana</span>
                        <span class="text-xs badge badge-gray">Cerrado</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
