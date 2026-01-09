@extends('layouts.admin')

@section('title', 'Reparto de Ganancias')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3 transition-colors">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Reparto de Ganancias</h2>
    <p class="text-gray-500 mt-1">Define cómo se distribuyen los ingresos entre médicos y la clínica</p>
</div>

<form method="POST" action="{{ route('configuracion.reparto.guardar') }}" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuración Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Porcentajes Base -->
            <div class="card p-8 border-l-4 border-l-success-500">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                        <i class="bi bi-pie-chart-fill text-success-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Distribución Estándar</h3>
                        <p class="text-sm text-gray-500">Aplica a todos los médicos sin acuerdo especial</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-success-50 to-medical-50 rounded-2xl p-8 mb-8">
                    <div class="grid grid-cols-2 gap-8 mb-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-3 block">Porcentaje Médico</label>
                            <div class="relative">
                                <input type="number" name="porcentaje_medico" id="porcentaje_medico" 
                                       class="w-full text-5xl font-bold text-success-600 bg-transparent border-b-4 border-success-300 focus:border-success-500 transition-colors text-center outline-none" 
                                       value="70" min="0" max="100" step="0.01">
                                <span class="absolute right-4 bottom-2 text-3xl font-bold text-success-400">%</span>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-2">Para el profesional</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-3 block">Porcentaje Clínica</label>
                            <div class="relative">
                                <input type="number" name="porcentaje_clinica" id="porcentaje_clinica" 
                                       class="w-full text-5xl font-bold text-medical-600 bg-transparent border-b-4 border-medical-300 transition-colors text-center outline-none" 
                                       value="30" readonly>
                                <span class="absolute right-4 bottom-2 text-3xl font-bold text-medical-400">%</span>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-2">Para la institución</p>
                        </div>
                    </div>

                    <!-- Barra Visual -->
                    <div class="space-y-2">
                        <div class="flex h-8 rounded-full overflow-hidden shadow-inner bg-white">
                            <div class="bg-gradient-to-r from-success-500 to-success-600 flex items-center justify-center text-white font-bold text-sm transition-all duration-500" style="width: 70%" id="bar_medico">
                                70% Médico
                            </div>
                            <div class="bg-gradient-to-r from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold text-sm transition-all duration-500" style="width: 30%" id="bar_clinica">
                                30% Clínica
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-info-50 border border-info-200 rounded-xl p-4 flex gap-3">
                    <i class="bi bi-info-circle-fill text-info-600 text-xl flex-shrink-0"></i>
                    <div class="text-sm text-info-800">
                        <p class="font-medium mb-1">Importante</p>
                        <p>Esta distribución se aplica automáticamente a todos los médicos. Puedes crear excepciones individuales desde el perfil de cada profesional.</p>
                    </div>
                </div>
            </div>

            <!-- Reglas Adicionales -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-warning-50 flex items-center justify-center">
                        <i class="bi bi-sliders text-warning-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Reglas y Descuentos</h3>
                </div>

                <div class="space-y-4">
                    <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="descontar_impuestos" class="form-checkbox mt-0.5 h-5 w-5 text-medical-600" checked>
                        <div class="flex-1">
                            <span class="font-semibold text-gray-900 block mb-1">Descontar impuestos antes del reparto</span>
                            <p class="text-sm text-gray-500">El IVA y otros impuestos se restan del total antes de calcular los porcentajes.</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="descontar_insumos" class="form-checkbox mt-0.5 h-5 w-5 text-medical-600">
                        <div class="flex-1">
                            <span class="font-semibold text-gray-900 block mb-1">Descontar insumos médicos al profesional</span>
                            <p class="text-sm text-gray-500">Los materiales utilizados se descuentan de la parte del médico.</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="aplicar_urgencias" class="form-checkbox mt-0.5 h-5 w-5 text-medical-600">
                        <div class="flex-1">
                            <span class="font-semibold text-gray-900 block mb-1">Ajuste especial para urgencias</span>
                            <p class="text-sm text-gray-500">Las consultas de emergencia tienen una distribución diferente (65/35).</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Acciones -->
            <div class="card p-6 sticky top-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-4">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Configuración
                </button>
                <button type="button" class="btn btn-outline w-full">
                    <i class="bi bi-arrow-clockwise mr-2"></i>
                    Restaurar Valores
                </button>
                
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-400">Última actualización</p>
                    <p class="text-sm font-medium text-gray-600 mt-1">Hace 1 mes</p>
                </div>
            </div>

            <!-- Excepciones Activas -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-warning-500"></i>
                    Acuerdos Especiales
                </h4>
                <div class="space-y-3">
                    <div class="bg-warning-50 border border-warning-200 rounded-lg p-3">
                        <div class="flex justify-between items-start mb-1">
                            <p class="font-semibold text-warning-900 text-sm">Dr. Juan Pérez</p>
                            <span class="text-xs font-bold text-warning-700">80/20</span>
                        </div>
                        <p class="text-xs text-warning-700">Cardiología - Contrato personalizado</p>
                    </div>

                    <div class="bg-info-50 border border-info-200 rounded-lg p-3">
                        <div class="flex justify-between items-start mb-1">
                            <p class="font-semibold text-info-900 text-sm">Dra. María González</p>
                            <span class="text-xs font-bold text-info-700">75/25</span>
                        </div>
                        <p class="text-xs text-info-700">Pediatría - Senior</p>
                    </div>

                    <a href="{{ route('medicos.index') }}" class="btn btn-sm btn-ghost w-full mt-3">
                        Ver todos los médicos
                        <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Resumen del Mes</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Generado</span>
                        <span class="font-bold text-gray-900">$12,450</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">A Médicos</span>
                        <span class="font-bold text-success-600">$8,715</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">A Clínica</span>
                        <span class="font-bold text-medical-600">$3,735</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    const inputMedico = document.getElementById('porcentaje_medico');
    const inputClinica = document.getElementById('porcentaje_clinica');
    const barMedico = document.getElementById('bar_medico');
    const barClinica = document.getElementById('bar_clinica');

    inputMedico.addEventListener('input', function() {
        let val = parseFloat(this.value);
        if(val > 100) val = 100;
        if(val < 0) val = 0;
        
        let clinicaVal = 100 - val;
        inputClinica.value = clinicaVal.toFixed(2);
        
        barMedico.style.width = val + '%';
        barClinica.style.width = clinicaVal + '%';
        
        barMedico.textContent = val.toFixed(0) + '% Médico';
        barClinica.textContent = clinicaVal.toFixed(0) + '% Clínica';
    });
</script>
@endpush
@endsection
