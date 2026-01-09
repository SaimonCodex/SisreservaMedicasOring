@extends('layouts.admin')

@section('title', 'Médicos')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Médicos</h2>
            <p class="text-gray-500 mt-1">Gestión del personal médico de la clínica</p>
        </div>
        <a href="{{ route('medicos.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Registrar Médico
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('medicos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Búsqueda -->
        <div class="md:col-span-2">
            <label class="form-label">Buscar</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="buscar" 
                       class="input pl-10" 
                       placeholder="Nombre, cédula, MPPS..."
                       value="{{ request('buscar') }}">
            </div>
        </div>

        <!-- Especialidad -->
        <div>
            <label class="form-label">Especialidad</label>
            <select name="especialidad_id" class="form-select">
                <option value="">Todas</option>
                <!-- Simulación de opciones -->
                <option value="1" {{ request('especialidad_id') == '1' ? 'selected' : '' }}>Cardiología</option>
                <option value="2" {{ request('especialidad_id') == '2' ? 'selected' : '' }}>Pediatría</option>
                <option value="3" {{ request('especialidad_id') == '3' ? 'selected' : '' }}>Traumatología</option>
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
            <a href="{{ route('medicos.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Médicos</p>
                <p class="text-2xl font-bold text-gray-900">24</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-person-badge text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activos</p>
                <p class="text-2xl font-bold text-gray-900">22</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Consultas Hoy</p>
                <p class="text-2xl font-bold text-gray-900">47</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Especialidades</p>
                <p class="text-2xl font-bold text-gray-900">12</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-bookmark text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Médicos -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Médico</th>
                    <th class="px-6 py-4 text-left font-semibold">Especialidad</th>
                    <th class="px-6 py-4 text-left font-semibold">MPPS</th>
                    <th class="px-6 py-4 text-left font-semibold">Contacto</th>
                    <th class="px-6 py-4 text-left font-semibold">Estado</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <!-- Ejemplo de fila 1 -->
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                                JP
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Dr. Juan Pérez</p>
                                <p class="text-xs text-gray-500">V-12345678</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-primary">Cardiología</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-gray-600">98765</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">0414-1234567</p>
                        <p class="text-xs text-gray-500">juan.perez@example.com</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-success">Activo</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('medicos.show', 1) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('medicos.horarios', 1) }}" class="btn btn-sm btn-ghost text-info-600" title="Horarios">
                                <i class="bi bi-clock"></i>
                            </a>
                            <a href="{{ route('medicos.edit', 1) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Ejemplo de fila 2 -->
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-danger-500 to-danger-600 flex items-center justify-center text-white font-bold">
                                MG
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Dra. María González</p>
                                <p class="text-xs text-gray-500">V-23456789</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-warning">Pediatría</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-gray-600">87654</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">0424-9876543</p>
                        <p class="text-xs text-gray-500">maria.gonzalez@example.com</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-success">Activo</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('medicos.show', 2) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('medicos.horarios', 2) }}" class="btn btn-sm btn-ghost text-info-600" title="Horarios">
                                <i class="bi bi-clock"></i>
                            </a>
                            <a href="{{ route('medicos.edit', 2) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Ejemplo de fila 3 -->
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white font-bold">
                                CL
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Dr. Carlos López</p>
                                <p class="text-xs text-gray-500">V-34567890</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-info">Traumatología</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-gray-600">76543</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">0412-8765432</p>
                        <p class="text-xs text-gray-500">carlos.lopez@example.com</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-gray">Inactivo</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('medicos.show', 3) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('medicos.horarios', 3) }}" class="btn btn-sm btn-ghost text-info-600" title="Horarios">
                                <i class="bi bi-clock"></i>
                            </a>
                            <a href="{{ route('medicos.edit', 3) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
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
                Mostrando <span class="font-semibold">1</span> a <span class="font-semibold">3</span> de <span class="font-semibold">24</span> médicos
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
