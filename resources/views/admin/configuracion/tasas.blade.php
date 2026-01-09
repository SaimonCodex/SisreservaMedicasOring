@extends('layouts.admin')

@section('title', 'Tasas e Impuestos')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Tasas e Impuestos</h2>
    <p class="text-gray-500 mt-1">Moneda, tipos de cambio y configuración fiscal</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Tasa de Cambio -->
    <div class="card p-8 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                    <i class="bi bi-currency-dollar text-warning-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Tasa de Cambio BCV</h3>
                    <p class="text-sm text-gray-500">Actualización diaria automática</p>
                </div>
            </div>
            <span class="badge badge-success">Hoy 09:00 AM</span>
        </div>

        <form method="POST" action="{{ route('configuracion.tasas.guardar') }}">
            @csrf
            
            <div class="bg-gradient-to-br from-warning-50 via-warning-100 to-amber-50 rounded-2xl p-8 mb-6 text-center shadow-inner">
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wider mb-3">1 USD (Dólar Americano)</p>
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="text-3xl font-bold text-gray-800">Bs.</span>
                    <input type="number" name="tasa_bcv" 
                           class="bg-white/80 text-5xl font-bold text-warning-700 w-48 text-center rounded-xl border-2 border-warning-300 focus:border-warning-500 focus:ring-2 focus:ring-warning-200 transition-all px-4 py-2" 
                           value="36.87" step="0.01" min="0">
                </div>
                <p class="text-xs text-gray-600 mt-2">Bolívares Digitales</p>
            </div>

            <div class="space-y-4 mb-6">
                <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="auto_update_tasa" class="form-checkbox h-5 w-5 text-medical-600" checked>
                        <div>
                            <span class="font-semibold text-gray-900 block">Actualización Automática</span>
                            <p class="text-xs text-gray-500">Conecta al API del BCV cada día a las 9:00 AM</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-repeat text-medical-500 text-xl"></i>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-full shadow-lg">
                <i class="bi bi-arrow-clockwise mr-2"></i>
                Actualizar Tasa Manualmente
            </button>
        </form>
    </div>

    <!-- Configuración de IVA -->
    <div class="card p-8 border-l-4 border-l-danger-500">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl bg-danger-50 flex items-center justify-center">
                <i class="bi bi-percent text-danger-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Impuesto (IVA)</h3>
                <p class="text-sm text-gray-500">Porcentajes y exenciones</p>
            </div>
        </div>

        <form method="POST" action="{{ route('configuracion.general.actualizar') }}">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="form-label">Porcentaje de IVA General</label>
                <div class="relative">
                    <input type="number" name="iva_general" 
                           class="input text-4xl font-bold text-center text-danger-600 h-20" 
                           value="16.00" step="0.01" min="0" max="100">
                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                        <span class="text-3xl font-bold text-danger-400">%</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="font-bold text-gray-900 mb-4">Servicios Exentos</h4>
                
                <div class="space-y-3">
                    <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" name="exento_consultas" class="form-checkbox mt-0.5 text-medical-600" checked>
                        <div>
                            <span class="font-medium text-gray-900">Consultas Médicas</span>
                            <p class="text-xs text-gray-500">Las consultas generales no aplican IVA</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" name="exento_emergencias" class="form-checkbox mt-0.5 text-medical-600" checked>
                        <div>
                            <span class="font-medium text-gray-900">Atención de Emergencia</span>
                            <p class="text-xs text-gray-500">Urgencias médicas exentas</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" name="exento_laboratorio" class="form-checkbox mt-0.5 text-medical-600">
                        <div>
                            <span class="font-medium text-gray-900">Laboratorio Clínico</span>
                            <p class="text-xs text-gray-500">Estudios de laboratorio</p>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-outline w-full mt-6">
                <i class="bi bi-save mr-2"></i>
                Guardar Configuración
            </button>
        </form>
    </div>
</div>

<!-- Historial de Cambios -->
<div class="card p-6 mt-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900">Historial de Cambios Recientes</h3>
        <button class="btn btn-sm btn-ghost">
            <i class="bi bi-download mr-1"></i> Exportar
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Fecha y Hora</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Concepto</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Valor Anterior</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nuevo Valor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Modificado Por</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">08/01/2026 09:00</td>
                    <td class="px-4 py-3"><span class="badge badge-warning text-xs">Tasa BCV</span></td>
                    <td class="px-4 py-3 text-sm text-gray-600">Bs. 36.82</td>
                    <td class="px-4 py-3 text-sm font-bold">Bs. 36.87</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Sistema (Auto)</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">07/01/2026 09:00</td>
                    <td class="px-4 py-3"><span class="badge badge-warning text-xs">Tasa BCV</span></td>
                    <td class="px-4 py-3 text-sm text-gray-600">Bs. 36.75</td>
                    <td class="px-4 py-3 text-sm font-bold">Bs. 36.82</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Sistema (Auto)</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">05/01/2026 14:30</td>
                    <td class="px-4 py-3"><span class="badge badge-danger text-xs">IVA General</span></td>
                    <td class="px-4 py-3 text-sm text-gray-600">12.00%</td>
                    <td class="px-4 py-3 text-sm font-bold">16.00%</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Admin (Juan Pérez)</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
