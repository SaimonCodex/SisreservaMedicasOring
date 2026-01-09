@extends('layouts.admin')

@section('title', 'Copias de Seguridad')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Copias de Seguridad</h2>
    <p class="text-gray-500 mt-1">Gestión de backups y restauración del sistema</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel de Control de Backup -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Generar Nuevo Backup -->
        <div class="card p-6 border-t-4 border-t-medical-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-cloud-arrow-up text-medical-600"></i>
                Generar Nueva Copia
            </h3>
            
            <p class="text-gray-500 text-sm mb-6">
                Puede generar una copia manual en cualquier momento. El proceso puede tardar unos minutos dependiendo del tamaño de la base de datos y los archivos adjuntos.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <form action="{{ route('configuracion.backup.generar') }}" method="POST" class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-medical-200 transition-colors text-center">
                    @csrf
                    <input type="hidden" name="tipo" value="completo">
                    <div class="mb-3">
                        <i class="bi bi-archive text-3xl text-medical-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-1">Completo</h4>
                    <p class="text-xs text-gray-500 mb-3">Base de datos + Archivos</p>
                    <button type="submit" class="btn btn-sm btn-primary w-full">Generar</button>
                </form>

                <form action="{{ route('configuracion.backup.generar') }}" method="POST" class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-medical-200 transition-colors text-center">
                    @csrf
                    <input type="hidden" name="tipo" value="solo_bd">
                    <div class="mb-3">
                        <i class="bi bi-database text-3xl text-gray-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-1">Solo Base de Datos</h4>
                    <p class="text-xs text-gray-500 mb-3">Respaldo SQL ligero</p>
                    <button type="submit" class="btn btn-sm btn-outline w-full">Generar</button>
                </form>

                <form action="{{ route('configuracion.backup.generar') }}" method="POST" class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-medical-200 transition-colors text-center">
                    @csrf
                    <input type="hidden" name="tipo" value="solo_archivos">
                    <div class="mb-3">
                        <i class="bi bi-folder text-3xl text-warning-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-1">Solo Archivos</h4>
                    <p class="text-xs text-gray-500 mb-3">Imágenes y documentos</p>
                    <button type="submit" class="btn btn-sm btn-outline w-full">Generar</button>
                </form>
            </div>
        </div>

        <!-- Historial de Backups -->
        <div class="card p-0 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Historial de Copias</h3>
                <span class="badge badge-success">3 Disponibles</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-semibold text-xs">
                        <tr>
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4">Tipo</th>
                            <th class="px-6 py-4">Tamaño</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 block">08 Ene, 2026</span>
                                <span class="text-xs text-gray-500">03:00 AM</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge badge-primary">Automático</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">45.2 MB</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="btn btn-sm btn-ghost text-medical-600 tooltip" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost text-danger-600 tooltip" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 block">07 Ene, 2026</span>
                                <span class="text-xs text-gray-500">03:00 AM</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge badge-primary">Automático</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">44.8 MB</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="btn btn-sm btn-ghost text-medical-600 tooltip" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost text-danger-600 tooltip" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 block">05 Ene, 2026</span>
                                <span class="text-xs text-gray-500">14:25 PM</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge badge-warning">Manual</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">42.1 MB</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="btn btn-sm btn-ghost text-medical-600 tooltip" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost text-danger-600 tooltip" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel Lateral Configuración -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6 border-l-4 border-l-info-500">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-gear-fill text-info-600"></i>
                Configuración Automática
            </h3>
            
            <form action="#" class="space-y-4">
                <div class="form-group">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" class="form-checkbox mt-1 text-medical-600" checked>
                        <div>
                            <span class="font-medium text-gray-900 block">Backup Diario</span>
                            <span class="text-xs text-gray-500">Realizar copia todos los días a las 03:00 AM</span>
                        </div>
                    </label>
                </div>

                <div class="form-group">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="form-checkbox text-medical-600" checked>
                        <span class="text-sm text-gray-700">Notificar por correo al finalizar</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="form-checkbox text-medical-600" checked>
                        <span class="text-sm text-gray-700">Eliminar copias antiguas (>30 días)</span>
                    </label>
                </div>

                <button class="btn btn-info text-white w-full mt-2">Guardar Preferencias</button>
            </form>
        </div>

        <div class="bg-gray-800 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-shield-lock-fill text-2xl text-success-400"></i>
                <div>
                    <h3 class="font-bold">Seguridad Total</h3>
                    <p class="text-xs text-gray-400">Sus datos están encriptados</p>
                </div>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed mb-4">
                Las copias de seguridad se almacenan en una ubicación segura y se encriptan con AES-256. Recomendamos descargar una copia local mensualmente.
            </p>
            <div class="text-xs text-gray-500 text-center pt-4 border-t border-gray-700">
                Última verificación de integridad: Hoy
            </div>
        </div>
    </div>
</div>
@endsection
