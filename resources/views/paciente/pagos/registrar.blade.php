@extends('layouts.paciente')

@section('title', 'Registrar Pago')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('paciente.citas.show', $cita->id) }}" class="btn btn-ghost btn-sm text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition-all rounded-xl">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-3xl font-display font-bold text-gray-900">Registrar Pago</h1>
            <p class="text-gray-500 mt-1">Confirma tu cita registrando el comprobante de pago</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Payment Form -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('paciente.pagos.store') }}" method="POST" id="paymentForm" enctype="multipart/form-data" class="card bg-white border-0 shadow-xl shadow-gray-100/50 overflow-hidden ring-1 ring-gray-100">
                @csrf
                <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                <input type="hidden" name="tasa_aplicada_id" value="{{ $tasaActual->id }}">

                <!-- Form Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i class="bi bi-credit-card-2-front"></i>
                        Detalles de la Transferencia
                    </h3>
                    <p class="text-emerald-100 text-sm mt-1">Complete los datos exactos del comprobante</p>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Tasa Exchange Rate Banner -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="bi bi-currency-exchange"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Tasa del Día (BCV)</p>
                                <p class="text-lg font-bold text-gray-900">Bs. {{ number_format($tasaActual->valor, 2) }}</p>
                            </div>
                        </div>
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-blue-500">Fecha Valor</p>
                            <p class="text-sm font-medium text-blue-700">{{ \Carbon\Carbon::parse($tasaActual->fecha)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Método de Pago -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Método de Pago</span>
                            </label>
                            <select name="id_metodo" id="metodo_pago" class="select select-bordered w-full bg-gray-50 focus:bg-white transition-all h-12" required>
                                <option value="">Seleccione origen...</option>
                                @foreach($metodosPago as $metodo)
                                    <option value="{{ $metodo->id_metodo }}" {{ old('id_metodo') == $metodo->id_metodo ? 'selected' : '' }}>
                                        {{ $metodo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_metodo')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Fecha Pago -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Fecha Realización</span>
                            </label>
                            <input type="date" name="fecha_pago" 
                                   class="input input-bordered w-full bg-gray-50 focus:bg-white transition-all h-12" 
                                   value="{{ old('fecha_pago', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}" required>
                            @error('fecha_pago')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Monto en Bs -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Monto Transferido (Bs.)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Bs.</span>
                                <input type="number" step="0.01" name="monto_pagado_bs" id="monto_pagado_bs" 
                                       class="input input-bordered w-full pl-12 bg-gray-50 focus:bg-white transition-all h-14 text-lg font-bold text-gray-900 placeholder:font-normal" 
                                       placeholder="0.00"
                                       value="{{ old('monto_pagado_bs') }}" required>
                            </div>
                            <div class="flex justify-between items-start mt-2">
                                <p class="text-xs text-blue-600 font-medium italic">
                                    <i class="bi bi-info-circle mr-1"></i> Monto sugerido: <span class="font-bold underline">Bs. {{ number_format($cita->tarifa_total * $tasaActual->valor, 2) }}</span>
                                </p>
                                <!-- Dynamic Conversion Feedback -->
                                <div id="conversion-feedback" class="text-right opacity-0 transition-opacity duration-300">
                                    <span class="text-sm text-gray-500">Equivalente a: </span>
                                    <span class="font-bold text-emerald-600 text-lg" id="monto-usd-display">$0.00</span>
                                </div>
                            </div>
                            @error('monto_pagado_bs')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Referencia -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Número de Referencia</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="bi bi-hash"></i>
                                </span>
                                <input type="text" name="referencia" 
                                       class="input input-bordered w-full pl-10 bg-gray-50 focus:bg-white transition-all h-12 font-mono uppercase" 
                                       placeholder="Últimos 6-8 dígitos"
                                       value="{{ old('referencia') }}" maxlength="255" required>
                            </div>
                             <p class="text-xs text-gray-400 mt-1 ml-1">Ejemplo: 12345678</p>
                            @error('referencia')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Comprobante (Opcional) -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Subir Comprobante (Opcional)</span>
                            </label>
                            
                            <div class="relative group">
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-emerald-400 hover:bg-emerald-50 transition-all cursor-pointer relative" id="drop-zone">
                                    <input type="file" name="comprobante" id="comprobante" accept="image/*,application/pdf"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                           onchange="updateFileName(this)" />
                                    
                                    <div class="space-y-2" id="upload-placeholder">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto group-hover:bg-emerald-100 transition-colors">
                                            <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 group-hover:text-emerald-600 transition-colors"></i>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <span class="font-bold text-emerald-600">Haga clic para subir</span> o arrastre el archivo aquí
                                        </div>
                                        <p class="text-xs text-gray-400">JPG, PNG o PDF (Máx. 2MB)</p>
                                    </div>

                                    <!-- File Preview (Hidden by default) -->
                                    <div id="file-info" class="hidden flex items-center justify-center gap-3">
                                        <i class="bi bi-file-earmark-check text-2xl text-emerald-600"></i>
                                        <div class="text-left">
                                            <p class="text-sm font-bold text-gray-900" id="file-name">nombre_archivo.jpg</p>
                                            <p class="text-xs text-emerald-600">Listo para subir</p>
                                        </div>
                                        <button type="button" onclick="clearFile(event)" class="btn btn-xs btn-ghost text-red-500 hover:bg-red-50 rounded-full z-20">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('comprobante')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Comentarios -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Notas Adicionales (Opcional)</span>
                            </label>
                            <textarea name="comentarios" class="textarea textarea-bordered w-full bg-gray-50 focus:bg-white resize-none h-24" placeholder="Ej: Pago realizado desde cuenta titular...">{{ old('comentarios') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('paciente.citas.show', $cita->id) }}" class="btn btn-ghost text-gray-500 hover:text-gray-700">
                        Cancelar
                    </a>
                    <button type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 text-white border-0 shadow-lg shadow-emerald-200 px-8">
                        Registrar Pago <i class="bi bi-check-lg ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Summary Card -->
        <div class="space-y-6">
            <div class="card bg-white border-0 shadow-xl shadow-gray-100/50 p-6 sticky top-8 ring-1 ring-gray-100">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Resumen a Pagar</h3>
                    <span class="badge badge-warning text-xs font-bold uppercase py-3">Pendiente</span>
                </div>

                <!-- Doctor Info -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                        {{ substr($cita->medico->primer_nombre, 0, 1) }}{{ substr($cita->medico->primer_apellido, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Médico Tratante</p>
                        <p class="font-bold text-gray-900">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</p>
                        <p class="text-xs text-emerald-600 font-medium bg-emerald-50 inline-block px-2 py-0.5 rounded-full mt-0.5">{{ $cita->especialidad->nombre }}</p>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="space-y-3 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500"><i class="bi bi-calendar3 mr-2"></i>Fecha</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d M, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500"><i class="bi bi-clock mr-2"></i>Hora</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500"><i class="bi bi-geo-alt mr-2"></i>Lugar</span>
                        <span class="font-medium text-gray-900 truncate max-w-[150px]">{{ $cita->consultorio->nombre }}</span>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <span class="text-gray-600 font-medium">Total (USD)</span>
                        <span class="text-2xl font-black text-gray-900">${{ number_format($cita->tarifa_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-end pt-2 border-t border-dashed border-gray-200">
                        <span class="text-gray-600 font-medium">Total en Bolívares</span>
                        <div class="text-right">
                            <span class="block text-3xl font-black text-emerald-600">Bs. {{ number_format($cita->tarifa_total * $tasaActual->valor, 2) }}</span>
                            <span class="text-xs text-gray-400">Calculado a tasa del día</span>
                        </div>
                    </div>
                </div>
                
                <!-- Security Note -->
                <div class="mt-6 flex gap-3 p-3 bg-blue-50 rounded-lg">
                    <i class="bi bi-shield-lock text-blue-600 text-xl"></i>
                    <p class="text-xs text-blue-800 leading-relaxed">
                        Su pago será verificado manualmente. Asegúrese de que el número de referencia coincida con el de su comprobante bancario.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const montoInput = document.getElementById('monto_pagado_bs');
        const feedback = document.getElementById('conversion-feedback');
        const display = document.getElementById('monto-usd-display');
        const tasa = {{ $tasaActual->valor }};

        montoInput.addEventListener('input', (e) => {
            const val = parseFloat(e.target.value);
            if (val > 0) {
                const usd = val / tasa;
                display.textContent = '$' + usd.toFixed(2);
                feedback.classList.remove('opacity-0');
            } else {
                feedback.classList.add('opacity-0');
            }
        });
    });
    // File Upload Interaction
    function updateFileName(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const dropZone = document.getElementById('drop-zone');

        if (input.files && input.files[0]) {
            fileName.textContent = input.files[0].name;
            placeholder.classList.add('hidden');
            fileInfo.classList.remove('hidden');
            dropZone.classList.add('border-emerald-500', 'bg-emerald-50');
            dropZone.classList.remove('border-gray-300');
        } else {
            clearFile();
        }
    }

    function clearFile(event) {
        if(event) {
             event.preventDefault(); 
             event.stopPropagation();
        }
        
        const input = document.getElementById('comprobante');
        const placeholder = document.getElementById('upload-placeholder');
        const fileInfo = document.getElementById('file-info');
        const dropZone = document.getElementById('drop-zone');

        input.value = '';
        placeholder.classList.remove('hidden');
        fileInfo.classList.add('hidden');
        dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
        dropZone.classList.add('border-gray-300');
    }
</script>
@endpush
@endsection
