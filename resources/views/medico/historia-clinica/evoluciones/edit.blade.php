@extends('layouts.medico')

@section('title', 'Editar Evolución Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('historia-clinica.evoluciones.show', ['citaId' => $evolucion->cita_id ?? 0]) }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Editar Evolución Clínica</h1>
            <p class="text-gray-600 mt-1">Actualizar registro de consulta médica</p>
        </div>
    </div>

    <form action="{{ route('historia-clinica.evoluciones.update', ['citaId' => $evolucion->cita_id ?? 0]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patient Info (Read-only) -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Datos del Paciente
                    </h3>

                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($evolucion->historiaClinica->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($evolucion->historiaClinica->paciente->primer_apellido ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">
                                    {{ $evolucion->historiaClinica->paciente->primer_nombre ?? 'N/A' }} 
                                    {{ $evolucion->historiaClinica->paciente->primer_apellido ?? '' }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $evolucion->historiaClinica->paciente->cedula ?? 'N/A' }}</p>
                            </div>
                        </div>
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
                            <input type="text" name="presion_arterial" class="input" placeholder="120/80" value="{{ old('presion_arterial', $evolucion->presion_arterial ?? '') }}">
                            <p class="form-help">mmHg</p>
                        </div>
                        <div>
                            <label class="form-label">Temperatura</label>
                            <input type="number" step="0.1" name="temperatura" class="input" placeholder="36.5" value="{{ old('temperatura', $evolucion->temperatura ?? '') }}">
                            <p class="form-help">°C</p>
                        </div>
                        <div>
                            <label class="form-label">Frecuencia Cardíaca</label>
                            <input type="number" name="frecuencia_cardiaca" class="input" placeholder="75" value="{{ old('frecuencia_cardiaca', $evolucion->frecuencia_cardiaca ?? '') }}">
                            <p class="form-help">bpm</p>
                        </div>
                        <div>
                            <label class="form-label">Frecuencia Respiratoria</label>
                            <input type="number" name="frecuencia_respiratoria" class="input" placeholder="18" value="{{ old('frecuencia_respiratoria', $evolucion->frecuencia_respiratoria ?? '') }}">
                            <p class="form-help">rpm</p>
                        </div>
                        <div>
                            <label class="form-label">Saturación O₂</label>
                            <input type="number" name="saturacion_oxigeno" class="input" placeholder="98" value="{{ old('saturacion_oxigeno', $evolucion->saturacion_oxigeno ?? '') }}">
                            <p class="form-help">%</p>
                        </div>
                        <div>
                            <label class="form-label">Peso</label>
                            <input type="number" step="0.1" name="peso" class="input" placeholder="70.5" value="{{ old('peso', $evolucion->peso ?? '') }}">
                            <p class="form-help">kg</p>
                        </div>
                        <div>
                            <label class="form-label">Talla</label>
                            <input type="number" step="0.01" name="talla" class="input" placeholder="1.70" value="{{ old('talla', $evolucion->talla ?? '') }}">
                            <p class="form-help">metros</p>
                        </div>
                        <div>
                            <label class="form-label">IMC</label>
                            <input type="number" step="0.1" name="imc" id="imc" class="input" placeholder="24.2" value="{{ old('imc', $evolucion->imc ?? '') }}" readonly>
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
                            <textarea name="motivo_consulta" rows="2" class="form-textarea" required>{{ old('motivo_consulta', $evolucion->motivo_consulta ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Enfermedad Actual</label>
                            <textarea name="enfermedad_actual" rows="3" class="form-textarea">{{ old('enfermedad_actual', $evolucion->enfermedad_actual ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Examen Físico</label>
                            <textarea name="examen_fisico" rows="3" class="form-textarea">{{ old('examen_fisico', $evolucion->examen_fisico ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Diagnóstico</label>
                            <textarea name="diagnostico" rows="2" class="form-textarea" required>{{ old('diagnostico', $evolucion->diagnostico ?? '') }}</textarea>
                            <p class="form-help">Especifique el diagnóstico clínico</p>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Tratamiento</label>
                            <textarea name="tratamiento" rows="3" class="form-textarea" required>{{ old('tratamiento', $evolucion->tratamiento ?? '') }}</textarea>
                            <p class="form-help">Incluya medicamentos, dosis y recomendaciones</p>
                        </div>

                        <div>
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" rows="2" class="form-textarea">{{ old('observaciones', $evolucion->observaciones ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Metadata -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500">Registro Original</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Última Modificación</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($evolucion->updated_at) ? \Carbon\Carbon::parse($evolucion->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Actualizar Evolución
                        </button>
                        <a href="{{ route('historia-clinica.evoluciones.show', ['citaId' => $evolucion->cita_id ?? 0]) }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Warning -->
                <div class="card p-6">
                    <div class="flex gap-3">
                        <i class="bi bi-exclamation-triangle text-amber-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Advertencia</h4>
                            <p class="text-sm text-gray-600">Los cambios quedarán registrados en el historial del paciente.</p>
                        </div>
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
