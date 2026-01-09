@extends('layouts.admin')

@section('title', 'Perfil del Médico')

@section('content')
<div class="mb-6">
    <a href="{{ route('medicos.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Médicos
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Perfil del Médico</h2>
            <p class="text-gray-500 mt-1">Información completa del profesional</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('medicos.horarios', 1) }}" class="btn btn-outline">
                <i class="bi bi-clock mr-2"></i>
                Horarios
            </a>
            <a href="{{ route('medicos.edit', 1) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Información Personal -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-medical-600 to-medical-500 p-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold border-4 border-white/30">
                        JP
                    </div>
                    <div class="text-white">
                        <h3 class="text-2xl font-bold mb-1">Dr. Juan Pérez</h3>
                        <p class="text-white/90 mb-2">Cardiología • MPPS: 98765</p>
                        <div class="flex gap-2">
                            <span class="badge bg-white/20 text-white border border-white/30">Activo</span>
                            <span class="badge bg-white/20 text-white border border-white/30">Senior</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">245</p>
                        <p class="text-sm text-gray-500">Consultas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">4.8</p>
                        <p class="text-sm text-gray-500">Calificación</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">12</p>
                        <p class="text-sm text-gray-500">Años Exp.</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">98%</p>
                        <p class="text-sm text-gray-500">Asistencia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Personales -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-circle text-medical-600"></i>
                Datos Personales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Documento de Identidad</p>
                    <p class="font-semibold text-gray-900">V-12345678</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                    <p class="font-semibold text-gray-900">15/03/1985 (38 años)</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">Masculino</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado Civil</p>
                    <p class="font-semibold text-gray-900">Casado</p>
                </div>
            </div>
        </div>

        <!-- Datos Profesionales -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-award text-success-600"></i>
                Información Profesional
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Registro MPPS</p>
                    <p class="font-semibold text-gray-900">98765</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Colegio de Médicos (CMG)</p>
                    <p class="font-semibold text-gray-900">54321</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Especialidad Principal</p>
                    <p class="font-semibold text-gray-900">Cardiología</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Subespecialidad</p>
                    <p class="font-semibold text-gray-900">Electrofisiología</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Consultorio Asignado</p>
                    <p class="font-semibold text-gray-900">Consultorio 205 - Piso 2</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-2">Biografía</p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Médico cardiólogo con más de 12 años de experiencia en diagnóstico y tratamiento de enfermedades cardiovasculares. Especializado en electrofisiología y arritmias cardíacas. Miembro activo de la Sociedad Venezolana de Cardiología.
                    </p>
                </div>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="card p-6 border-l-4 border-l-info-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-telephone text-info-600"></i>
                Contacto
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Principal</p>
                    <p class="font-semibold text-gray-900">0414-1234567</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Secundario</p>
                    <p class="font-semibold text-gray-900">0212-9876543</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Correo Electrónico</p>
                    <p class="font-semibold text-gray-900">juan.perez@clinica.com</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Dirección</p>
                    <p class="font-semibold text-gray-900">Av. Principal, Urb. Los Palos Grandes, Caracas</p>
                </div>
            </div>
        </div>

        <!-- Horario de Atención -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-calendar-week text-warning-600"></i>
                Horario de Atención
            </h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between p-3 bg-medical-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="w-12 text-sm font-semibold text-gray-700">Lunes</span>
                        <span class="text-sm text-gray-600">08:00 AM - 12:00 PM, 02:00 PM - 06:00 PM</span>
                    </div>
                    <span class="badge badge-success text-xs">Activo</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="w-12 text-sm font-semibold text-gray-700">Martes</span>
                        <span class="text-sm text-gray-600">08:00 AM - 12:00 PM, 02:00 PM - 06:00 PM</span>
                    </div>
                    <span class="badge badge-success text-xs">Activo</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="w-12 text-sm font-semibold text-gray-700">Miércoles</span>
                        <span class="text-sm text-gray-600">No disponible</span>
                    </div>
                    <span class="badge badge-gray text-xs">Inactivo</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-medical-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="w-12 text-sm font-semibold text-gray-700">Jueves</span>
                        <span class="text-sm text-gray-600">08:00 AM - 12:00 PM</span>
                    </div>
                    <span class="badge badge-success text-xs">Activo</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-medical-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="w-12 text-sm font-semibold text-gray-700">Viernes</span>
                        <span class="text-sm text-gray-600">08:00 AM - 12:00 PM, 02:00 PM - 06:00 PM</span>
                    </div>
                    <span class="badge badge-success text-xs">Activo</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones Rápidas</h4>
            <div class="space-y-2">
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-plus mr-2"></i>
                    Nueva Cita
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-file-medical mr-2"></i>
                    Ver Historias
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-clock-history mr-2"></i>
                    Historial Citas
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-graph-up mr-2"></i>
                    Estadísticas
                </button>
            </div>
        </div>

        <!-- Estado del Sistema -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Estado</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cuenta</span>
                    <span class="badge badge-success">Activa</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Verificación</span>
                    <span class="badge badge-success">Verificado</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Último acceso</span>
                    <span class="text-sm font-medium text-gray-900">Hoy, 09:15 AM</span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-sm text-gray-600">Registro</span>
                    <span class="text-xs text-gray-500">Hace 2 años</span>
                </div>
            </div>
        </div>

        <!-- Disponibilidad Hoy -->
        <div class="card p-6 bg-gradient-to-br from-medical-50 to-info-50 border-medical-200">
            <h4 class="font-bold text-gray-900 mb-4">Disponibilidad Hoy</h4>
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Citas programadas</span>
                    <span class="font-bold text-gray-900">8</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Citas completadas</span>
                    <span class="font-bold text-success-600">5</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Citas pendientes</span>
                    <span class="font-bold text-warning-600">3</span>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-medical-600 to-medical-400 h-2 rounded-full" style="width: 62%"></div>
            </div>
            <p class="text-xs text-gray-500 text-center mt-2">62% del día completado</p>
        </div>
    </div>
</div>
@endsection
