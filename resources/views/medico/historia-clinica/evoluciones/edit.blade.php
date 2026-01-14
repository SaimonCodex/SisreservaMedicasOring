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
                            <label class="form-label">Peso</label>
                            <input type="number" step="0.1" name="peso_kg" class="input" placeholder="70.5" min="0" oninput="validarInput(this)"
                                   value="{{ old('peso_kg', $evolucion->peso_kg ?? '') }}">
                            <p class="form-help">kg</p>
                        </div>
                        <div>
                            <label class="form-label">Talla</label>
                            <input type="number" step="0.1" name="talla_cm" class="input" placeholder="170" min="0" oninput="validarInput(this)"
                                   value="{{ old('talla_cm', $evolucion->talla_cm ?? '') }}">
                            <p class="form-help">cm</p>
                        </div>
                        <div>
                            <label class="form-label">IMC</label>
                            <input type="number" step="0.1" name="imc" id="imc" class="input bg-gray-100" placeholder="24.2" 
                                   value="{{ old('imc', $evolucion->imc ?? '') }}" readonly>
                            <p class="form-help">kg/m² (calculado)</p>
                        </div>
                        <div>
                            <label class="form-label">Temperatura</label>
                            <input type="number" step="0.1" name="temperatura_c" class="input" placeholder="36.5" min="0" oninput="validarInput(this)"
                                   value="{{ old('temperatura_c', $evolucion->temperatura_c ?? '') }}">
                            <p class="form-help">°C</p>
                        </div>
                        <div>
                            <label class="form-label">T. Sistólica</label>
                            <input type="number" name="tension_sistolica" class="input" placeholder="120" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('tension_sistolica', $evolucion->tension_sistolica ?? '') }}">
                            <p class="form-help">mmHg</p>
                        </div>
                        <div>
                            <label class="form-label">T. Diastólica</label>
                            <input type="number" name="tension_diastolica" class="input" placeholder="80" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('tension_diastolica', $evolucion->tension_diastolica ?? '') }}">
                            <p class="form-help">mmHg</p>
                        </div>
                        <div>
                            <label class="form-label">Frec. Cardíaca</label>
                            <input type="number" name="frecuencia_cardiaca" class="input" placeholder="75" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('frecuencia_cardiaca', $evolucion->frecuencia_cardiaca ?? '') }}">
                            <p class="form-help">bpm</p>
                        </div>
                        <div>
                            <label class="form-label">Frec. Respiratoria</label>
                            <input type="number" name="frecuencia_respiratoria" class="input" placeholder="18" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('frecuencia_respiratoria', $evolucion->frecuencia_respiratoria ?? '') }}">
                            <p class="form-help">rpm</p>
                        </div>
                        <div>
                            <label class="form-label">Saturación O₂</label>
                            <input type="number" step="0.1" name="saturacion_oxigeno" class="input" placeholder="98" min="0" oninput="validarInput(this)"
                                   value="{{ old('saturacion_oxigeno', $evolucion->saturacion_oxigeno ?? '') }}">
                            <p class="form-help">%</p>
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
                            <label class="form-label form-label-required">Enfermedad Actual</label>
                            <textarea name="enfermedad_actual" rows="3" class="form-textarea" required>{{ old('enfermedad_actual', $evolucion->enfermedad_actual ?? '') }}</textarea>
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
                            <label class="form-label">Recomendaciones</label>
                            <textarea name="recomendaciones" rows="2" class="form-textarea">{{ old('recomendaciones', $evolucion->recomendaciones ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Notas Adicionales</label>
                            <textarea name="notas_adicionales" rows="2" class="form-textarea">{{ old('notas_adicionales', $evolucion->notas_adicionales ?? '') }}</textarea>
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
    // Validar input para permitir solo números positivos
    function validarInput(input, soloEnteros = false) {
        if (soloEnteros) {
            // Eliminar todo lo que no sea número
            input.value = input.value.replace(/[^0-9]/g, '');
        } else {
            // Eliminar todo lo que no sea número o punto
            input.value = input.value.replace(/[^0-9.]/g, '');
            
            // Asegurar que solo haya un punto decimal
            if ((input.value.match(/\./g) || []).length > 1) {
                // Si hay más de un punto, eliminar el último ingresado
                const parts = input.value.split('.');
                input.value = parts.shift() + '.' + parts.join('');
            }
        }
    }

    // Calculate IMC automatically
    const pesoInput = document.querySelector('input[name="peso_kg"]');
    const tallaInput = document.querySelector('input[name="talla_cm"]');
    const imcInput = document.getElementById('imc');

    function calculateIMC() {
        const peso = parseFloat(pesoInput.value);
        const talla = parseFloat(tallaInput.value);
        
        if (peso && talla && talla > 0) {
            const tallaMetros = talla / 100;
            const imc = peso / (tallaMetros * tallaMetros);
            imcInput.value = imc.toFixed(1);
        }
    }

    pesoInput?.addEventListener('input', calculateIMC);
    tallaInput?.addEventListener('input', calculateIMC);
    
    // Calculate on load if values exist
    if (pesoInput?.value && tallaInput?.value) {
        calculateIMC();
    }
</script>
@endpush
@endsection
