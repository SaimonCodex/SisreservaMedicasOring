@extends('layouts.admin')

@section('title', 'Nueva Factura')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('facturacion.index') }}" class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Factura</h1>
                <p class="text-gray-600">Generar cobro para una cita completada</p>
            </div>
        </div>
    </div>

    <form action="{{ route('facturacion.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- Main Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Selección de Cita -->
            <div class="card p-6 shadow-sm border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
                
                <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-sm shadow-blue-200">
                        <i class="bi bi-calendar-check text-sm"></i>
                    </span>
                    Seleccionar Cita Pendiente
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="form-label font-semibold text-gray-700 mb-1">Cita por Facturar</label>
                        <select name="cita_id" id="cita_id" class="form-select select2" required>
                            <option value="">Seleccionar cita...</option>
                            @foreach($citas as $cita)
                            <option value="{{ $cita->id }}" 
                                    data-paciente="{{ optional($cita->paciente)->nombre_completo ?? 'N/A' }}"
                                    data-cedula="{{ optional($cita->paciente)->cedula ?? 'N/A' }}"
                                    data-medico="Dr. {{ optional($cita->medico)->primer_nombre ?? 'N/A' }} {{ optional($cita->medico)->primer_apellido ?? '' }}"
                                    data-especialidad="{{ optional($cita->especialidad)->nombre ?? 'N/A' }}"
                                    data-tarifa="{{ $cita->tarifa ?? 0 }}"
                                    {{ old('cita_id') == $cita->id ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }} - {{ optional($cita->paciente)->nombre_completo ?? 'Paciente no encontrado' }} ({{ optional($cita->especialidad)->nombre ?? 'Sin especialidad' }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Solo se muestran citas en estado 'Completada' que aún no han sido facturadas.</p>
                    </div>

                    <!-- Info dinámica de la cita -->
                    <div id="cita-info" class="hidden animate-fade-in">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Paciente</p>
                                <p id="info-paciente" class="font-semibold text-gray-900">-</p>
                                <p id="info-cedula" class="text-sm text-gray-600">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Médico / Especialidad</p>
                                <p id="info-medico" class="font-semibold text-gray-900">-</p>
                                <p id="info-especialidad" class="text-sm text-gray-600">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos de Facturación -->
            <div class="card p-6 shadow-sm border-gray-100">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-sm shadow-emerald-200">
                        <i class="bi bi-receipt text-sm"></i>
                    </span>
                    Detalles de la Factura
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label font-semibold text-gray-700">Fecha de Emisión</label>
                        <input type="date" name="fecha_emision" class="input" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label font-semibold text-gray-700">Fecha de Vencimiento (Opcional)</label>
                        <input type="date" name="fecha_vencimiento" class="input" value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label font-semibold text-gray-700">Número de Factura (Opcional)</label>
                        <input type="text" name="numero_factura" class="input" placeholder="Ej: 000001">
                        <p class="text-xs text-gray-500 mt-1">Si se deja vacío, el sistema generará uno secuencial.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Tasa y Totales -->
            <div class="card p-6 bg-gradient-to-br from-indigo-900 to-slate-900 text-white border-none shadow-xl shadow-indigo-100 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white/5 rounded-full"></div>
                
                <h3 class="text-lg font-display font-bold mb-6 flex items-center gap-2">
                    <i class="bi bi-wallet2"></i>
                    Monto y Tasa
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-2">Tasa de Cambio Aplicable</label>
                        <select name="tasa_id" id="tasa_id" class="form-select bg-white/10 border-white/20 text-white focus:ring-indigo-500" required>
                            @foreach($tasas as $tasa)
                            <option value="{{ $tasa->id }}" data-valor="{{ $tasa->valor }}" class="text-gray-900" {{ $loop->first ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($tasa->fecha_tasa)->format('d/m/Y') }} - {{ number_format($tasa->valor, 2) }} Bs.
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-white/10">
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-200">Tarifa Consulta:</span>
                            <span id="label-tarifa-usd" class="text-xl font-bold tracking-tight text-white">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center py-4 bg-white/5 rounded-2xl px-4 border border-white/5">
                            <span class="text-indigo-200">Total en Bs:</span>
                            <span id="label-total-bs" class="text-2xl font-black text-amber-400">0.00 Bs.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6 shadow-sm border-gray-100">
                <button type="submit" class="btn btn-primary w-full py-4 text-lg font-bold shadow-lg shadow-blue-100 mb-3 group">
                    <span>Generar Factura</span>
                    <i class="bi bi-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </button>
                <a href="{{ route('facturacion.index') }}" class="btn btn-outline w-full py-3 justify-center">
                    Cancelar
                </a>
            </div>

            <!-- Aviso -->
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 flex gap-3">
                <i class="bi bi-shield-lock text-amber-600 text-xl"></i>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Al generar la factura, el sistema calculará automáticamente el reparto de honorarios entre médico, consultorio y sistema según la configuración vigente.
                </p>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const citaSelect = document.getElementById('cita_id');
    const tasaSelect = document.getElementById('tasa_id');
    const citaInfo = document.getElementById('cita-info');
    
    // Labels
    const lblPaciente = document.getElementById('info-paciente');
    const lblCedula = document.getElementById('info-cedula');
    const lblMedico = document.getElementById('info-medico');
    const lblEspecialidad = document.getElementById('info-especialidad');
    const lblTarifaUsd = document.getElementById('label-tarifa-usd');
    const lblTotalBs = document.getElementById('label-total-bs');

    function actualizarCalculos() {
        const option = citaSelect.options[citaSelect.selectedIndex];
        
        if (!option.value) {
            citaInfo.classList.add('hidden');
            lblTarifaUsd.textContent = '$0.00';
            lblTotalBs.textContent = '0.00 Bs.';
            return;
        }

        citaInfo.classList.remove('hidden');
        
        // Data from cita
        const tarifa = parseFloat(option.dataset.tarifa) || 0;
        lblPaciente.textContent = option.dataset.paciente;
        lblCedula.textContent = 'C.I: ' + option.dataset.cedula;
        lblMedico.textContent = option.dataset.medico;
        lblEspecialidad.textContent = option.dataset.especialidad;
        lblTarifaUsd.textContent = '$' + tarifa.toFixed(2);

        // Data from tasa
        const tasaOption = tasaSelect.options[tasaSelect.selectedIndex];
        const valorTasa = parseFloat(tasaOption.dataset.valor) || 0;
        
        const totalBs = tarifa * valorTasa;
        lblTotalBs.textContent = new Intl.NumberFormat('es-VE', { minimumFractionDigits: 2 }).format(totalBs) + ' Bs.';
    }

    citaSelect.addEventListener('change', actualizarCalculos);
    tasaSelect.addEventListener('change', actualizarCalculos);
    
    // Initial call
    if (citaSelect.value) actualizarCalculos();
});
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
