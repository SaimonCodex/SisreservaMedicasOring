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
            
            <div class="bg-gradient-to-br from-warning-50 via-warning-100 to-amber-50 rounded-2xl p-8 mb-6 text-center shadow-inner relative overflow-hidden group">
                <div class="absolute inset-0 bg-white/20 transform -skew-x-12 translate-x-full group-hover:translate-x-[-200%] transition-transform duration-1000 ease-in-out z-0"></div>
                <p class="text-sm font-bold text-warning-800 uppercase tracking-widest mb-4 relative z-10">Tasa de Cambio Oficial (BCV)</p>
                <div class="flex flex-col items-center justify-center gap-2 mb-4 relative z-10">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-extrabold text-warning-900">Bs.</span>
                        <input type="number" name="tasa_bcv" 
                               class="bg-transparent text-7xl font-black text-warning-700 w-full text-center border-none focus:ring-0 p-0 tracking-tight" 
                               style="-moz-appearance: textfield;"
                               value="{{ $tasas->first() ? $tasas->first()->valor : '0.00000000' }}" step="0.00000001" min="0" placeholder="0.00000000">
                    </div>
                </div>
                <p class="text-xs text-warning-600 font-medium relative z-10">Bolívares Digitales por Dólar Americano</p>
                
                <div class="mt-6 relative z-10">
                     <a href="{{ route('configuracion.tasas.sincronizar') }}" class="btn btn-warning btn-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all text-warning-900">
                        <i class="bi bi-cloud-arrow-down mr-2"></i> Sincronizar Ahora
                     </a>
                </div>
            </div>

            <form method="POST" action="{{ route('configuracion.tasas.settings') }}" id="form-auto-update">
                @csrf
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-medical-100 flex items-center justify-center text-medical-600 shadow-sm">
                                <i class="bi bi-clock-history text-xl"></i>
                            </div>
                            <div>
                                <span class="font-bold text-gray-900 block">Actualización Automática</span>
                                <p class="text-xs text-gray-500">Sincronización con BCV</p>
                            </div>
                        </div>
                        <label class="cursor-pointer flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Activar</span>
                            <input type="checkbox" name="auto_update_tasa" value="1" class="toggle toggle-success toggle-sm" {{ isset($autoUpdate) && $autoUpdate == '1' ? 'checked' : '' }} onchange="document.getElementById('form-auto-update').submit()">
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                            <div class="absolute right-0 top-0 p-1 opacity-10">
                                <i class="bi bi-sun-fill text-4xl text-warning-500"></i>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-warning-50 flex items-center justify-center text-warning-600">
                                <i class="bi bi-sunrise-fill"></i>
                            </div>
                            <div>
                                <span class="font-mono font-bold text-gray-800 text-lg leading-none block">09:00</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Mañana</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                            <div class="absolute right-0 top-0 p-1 opacity-10">
                                <i class="bi bi-moon-stars-fill text-4xl text-indigo-500"></i>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i class="bi bi-sunset-fill"></i>
                            </div>
                            <div>
                                 <span class="font-mono font-bold text-gray-800 text-lg leading-none block">17:00</span>
                                 <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tarde (5 PM)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-save mr-1"></i> Guardar Preferencias
                        </button>
                    </div>
                </div>
            </form>

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

        <form method="POST" action="{{ route('configuracion.impuestos.actualizar') }}">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="form-label">Porcentaje de IVA General</label>
                <div class="relative">
                    <input type="number" name="iva_general" 
                           class="input text-4xl font-bold text-center text-danger-600 h-20" 
                           value="{{ $impuestos['iva_general'] }}" step="0.01" min="0" max="100">
                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                        <span class="text-3xl font-bold text-danger-400">%</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="font-bold text-gray-900 mb-4">Servicios Exentos</h4>
                
                <div class="space-y-3">
                    <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" name="exento_consultas" class="form-checkbox mt-0.5 text-medical-600" {{ $impuestos['exento_consultas'] == '1' ? 'checked' : '' }}>
                        <div>
                            <span class="font-medium text-gray-900">Consultas Médicas</span>
                            <p class="text-xs text-gray-500">Las consultas generales no aplican IVA</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" name="exento_emergencias" class="form-checkbox mt-0.5 text-medical-600" {{ $impuestos['exento_emergencias'] == '1' ? 'checked' : '' }}>
                        <div>
                            <span class="font-medium text-gray-900">Atención de Emergencia</span>
                            <p class="text-xs text-gray-500">Urgencias médicas exentas</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" name="exento_laboratorio" class="form-checkbox mt-0.5 text-medical-600" {{ $impuestos['exento_laboratorio'] == '1' ? 'checked' : '' }}>
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
                @foreach($tasas as $index => $tasa)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($tasa->fecha_tasa)->format('d/m/Y H:i A') }}</td>
                    <td class="px-4 py-3"><span class="badge badge-warning text-xs">Tasa {{ $tasa->fuente }}</span></td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        @if(isset($tasas[$index + 1]))
                            Bs. {{ number_format($tasas[$index + 1]->valor, 8) }}
                        @else
                            <span class="text-gray-400">--</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm font-bold">Bs. {{ number_format($tasa->valor, 8) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Sistema</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
