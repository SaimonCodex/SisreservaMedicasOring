@extends('layouts.medico')

@section('title', 'Nueva Evolución Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('historia-clinica/evoluciones') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Evolución Clínica</h1>
            <p class="text-gray-600 mt-1">Registrar consulta y evaluación médica</p>
        </div>
    </div>

    <form action="{{ route('historia-clinica.evoluciones.store', ['citaId' => request('cita') ?? 0]) }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patient Selection -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Datos del Paciente
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label form-label-required">Paciente</label>
                            <select name="historia_clinica_id" id="historia_clinica_id" class="form-select" required>
                                <option value="">Seleccionar paciente...</option>
                                @foreach($historiasClinicas ?? [] as $historia)
                                <option value="{{ $historia->id }}" {{ request('historia_clinica_id') == $historia->id ? 'selected' : '' }}>
                                    {{ $historia->paciente->primer_nombre }} {{ $historia->paciente->primer_apellido }} - 
                                    {{ $historia->paciente->cedula }}
                                </option>
                                @endforeach
                            </select>
                            <p class="form-help">Selecciona el paciente para registrar la evolución</p>
                        </div>

                        @if(request('cita'))
                        <input type="hidden" name="cita_id" value="{{ request('cita') }}">
                        <div class="md:col-span-2">
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <p class="text-sm font-semibold text-blue-900">
                                    <i class="bi bi-info-circle"></i> Esta evolución está asociada a una cita médica
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-heart-pulse text-rose-600"></i>
                        Signos Vitales
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="form-label">Presión Arterial</label>
                            <input type="text" name="presion_arterial" class="input" placeholder="120/80" value="{{ old('presion_arterial') }}">
                            <p class="form-help">mmHg</p>
                        </div>
                        <div>
                            <label class="form-label">Temperatura</label>
                            <input type="number" step="0.1" name="temperatura" class="input" placeholder="36.5" value="{{ old('temperatura') }}">
                            <p class="form-help">°C</p>
                        </div>
                        <div>
                            <label class="form-label">Frecuencia Cardíaca</label>
                            <input type="number" name="frecuencia_cardiaca" class="input" placeholder="75" value="{{ old('frecuencia_cardiaca') }}">
                            <p class="form-help">bpm</p>
                        </div>
                        <div>
                            <label class="form-label">Frecuencia Respiratoria</label>
                            <input type="number" name="frecuencia_respiratoria" class="input" placeholder="18" value="{{ old('frecuencia_respiratoria') }}">
                            <p class="form-help">rpm</p>
                        </div>
                        <div>
                            <label class="form-label">Saturación O₂</label>
                            <input type="number" name="saturacion_oxigeno" class="input" placeholder="98" value="{{ old('saturacion_oxigeno') }}">
                            <p class="form-help">%</p>
                        </div>
                        <div>
                            <label class="form-label">Peso</label>
                            <input type="number" step="0.1" name="peso" class="input" placeholder="70.5" value="{{ old('peso') }}">
                            <p class="form-help">kg</p>
                        </div>
                        <div>
                            <label class="form-label">Talla</label>
                            <input type="number" step="0.01" name="talla" class="input" placeholder="1.70" value="{{ old('talla') }}">
                            <p class="form-help">metros</p>
                        </div>
                        <div>
                            <label class="form-label">IMC</label>
                            <input type="number" step="0.1" name="imc" id="imc" class="input" placeholder="24.2" value="{{ old('imc') }}" readonly>
                            <p class="form-help">kg/m²</p>
                        </div>
                    </div>
                </div>

                <!-- Clinical Evaluation -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-clipboard2-pulse text-emerald-600"></i>
                        Evaluación Clínica
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Motivo de Consulta</label>
                            <textarea name="motivo_consulta" rows="2" class="form-textarea" required>{{ old('motivo_consulta') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Enfermedad Actual</label>
                            <textarea name="enfermedad_actual" rows="3" class="form-textarea">{{ old('enfermedad_actual') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Examen Físico</label>
                            <textarea name="examen_fisico" rows="3" class="form-textarea">{{ old('examen_fisico') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Diagnóstico</label>
                            <textarea name="diagnostico" rows="2" class="form-textarea" required>{{ old('diagnostico') }}</textarea>
                            <p class="form-help">Especifique el diagnóstico clínico</p>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Tratamiento</label>
                            <textarea name="tratamiento" rows="3" class="form-textarea" required>{{ old('tratamiento') }}</textarea>
                            <p class="form-help">Incluya medicamentos, dosis y recomendaciones</p>
                        </div>

                        <div>
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" rows="2" class="form-textarea">{{ old('observaciones') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Instructions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">
                        <i class="bi bi-info-circle text-blue-600"></i> Guía Rápida
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Registre todos los signos vitales del paciente</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Describa detalladamente el motivo de consulta</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Especifique el diagnóstico con claridad</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Incluya el tratamiento completo</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Guardar Evolución
                        </button>
                        <a href="{{ url('historia-clinica/evoluciones') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Enlaces Rápidos</h3>
                    <div class="space-y-2">
                        <a href="{{ route('ordenes-medicas.create') }}" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-2">
                            <i class="bi bi-clipboard-plus"></i>
                            Crear Orden Médica
                        </a>
                        <a href="{{ url('historia-clinica/base/create') }}" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-2">
                            <i class="bi bi-file-earmark-medical"></i>
                            Nueva Historia Clínica
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Calculate IMC automatically
    const pesoInput = document.querySelector('input[name="peso"]');
    const tallaInput = document.querySelector('input[name="talla"]');
    const imcInput = document.getElementById('imc');

    function calculateIMC() {
        const peso = parseFloat(pesoInput.value);
        const talla = parseFloat(tallaInput.value);
        
        if (peso && talla && talla > 0) {
            const imc = peso / (talla * talla);
            imcInput.value = imc.toFixed(1);
        }
    }

    pesoInput?.addEventListener('input', calculateIMC);
    tallaInput?.addEventListener('input', calculateIMC);
</script>
@endpush
@endsection
