@extends('layouts.admin')

@section('title', 'Pacientes')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Pacientes</h2>
            <p class="text-gray-500 mt-1">Gestión de historias clínicas y datos de pacientes</p>
        </div>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Registrar Paciente
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('pacientes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Búsqueda -->
        <div class="md:col-span-2">
            <label class="form-label">Buscar Paciente</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="buscar" 
                       class="input pl-10" 
                       placeholder="Nombre, cédula, historia..."
                       value="{{ request('buscar') }}">
            </div>
        </div>

        <!-- Tipo -->
        <div>
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="regular" {{ request('tipo') == 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="especial" {{ request('tipo') == 'especial' ? 'selected' : '' }}>Especial</option>
            </select>
        </div>

        <!-- Estado -->
        <div>
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="md:col-span-4 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('pacientes.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Pacientes</p>
                <p class="text-2xl font-bold text-gray-900">1,247</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-people text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activos</p>
                <p class="text-2xl font-bold text-gray-900">1,189</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Citas Hoy</p>
                <p class="text-2xl font-bold text-gray-900">58</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-calendar-event text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Nuevos (mes)</p>
                <p class="text-2xl font-bold text-gray-900">142</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-person-plus text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Pacientes -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Paciente</th>
                    <th class="px-6 py-4 text-left font-semibold">Historia</th>
                    <th class="px-6 py-4 text-left font-semibold">Edad/Género</th>
                    <th class="px-6 py-4 text-left font-semibold">Contacto</th>
                    <th class="px-6 py-4 text-left font-semibold">Última Cita</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <!-- Fila 1 -->
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white font-bold">
                                AR
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Ana Rodríguez</p>
                                <p class="text-xs text-gray-500">V-18765432</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-medical-600 font-semibold">HC-2024-001</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">35 años</p>
                        <p class="text-xs text-gray-500">Femenino</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">0414-5678901</p>
                        <p class="text-xs text-gray-500">ana.r@example.com</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">05/01/2026</p>
                        <p class="text-xs text-gray-500">Cardiología</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pacientes.show', 1) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.historia-clinica', 1) }}" class="btn btn-sm btn-ghost text-info-600" title="Historia">
                                <i class="bi bi-file-medical"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', 1) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Fila 2 -->
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                                CM
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Carlos Martínez</p>
                                <p class="text-xs text-gray-500">V-21234567</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-medical-600 font-semibold">HC-2023-845</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">42 años</p>
                        <p class="text-xs text-gray-500">Masculino</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">0424-3456789</p>
                        <p class="text-xs text-gray-500">carlos.m@example.com</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">08/01/2026</p>
                        <p class="text-xs text-gray-500">Pediatría</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pacientes.show', 2) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.historia-clinica', 2) }}" class="btn btn-sm btn-ghost text-info-600" title="Historia">
                                <i class="bi bi-file-medical"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', 2) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Fila 3 (Especial) -->
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center text-white font-bold">
                                LS
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Lucía Sánchez</p>
                                <p class="text-xs text-gray-500">V-15987654</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-medical-600 font-semibold">HC-2024-112</span>
                            <span class="badge badge-warning text-xs">Especial</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">8 años</p>
                        <p class="text-xs text-gray-500">Femenino</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">0412-7654321</p>
                        <p class="text-xs text-gray-500">Rep: María S.</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">07/01/2026</p>
                        <p class="text-xs text-gray-500">Pediatría</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pacientes.show', 3) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.historia-clinica', 3) }}" class="btn btn-sm btn-ghost text-info-600" title="Historia">
                                <i class="bi bi-file-medical"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', 3) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Mostrando <span class="font-semibold">1</span> a <span class="font-semibold">3</span> de <span class="font-semibold">1,247</span> pacientes
            </p>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-outline" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm bg-medical-600 text-white">1</button>
                <button class="btn btn-sm btn-outline">2</button>
                <button class="btn btn-sm btn-outline">3</button>
                <button class="btn btn-sm btn-outline">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
