@extends('layouts.medico')

@section('title', 'Registrar Evolución Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Registrar Evolución Clínica</h1>
            <p class="text-gray-600 mt-1">
                Cita #{{ $cita->id }} - {{ $cita->paciente->primer_nombre ?? '' }} {{ $cita->paciente->primer_apellido ?? '' }}
            </p>
        </div>
    </div>

    <!-- Alerta informativa si hay datos pre-cargados -->
    @if(isset($ultimaEvolucion) && $ultimaEvolucion)
    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
        <div class="flex items-center gap-3">
            <i class="bi bi-info-circle text-blue-600 text-xl"></i>
            <div>
                <p class="font-semibold text-blue-900">Datos pre-cargados de la última consulta</p>
                <p class="text-sm text-blue-700">Se han cargado los datos de la evolución anterior ({{ \Carbon\Carbon::parse($ultimaEvolucion->created_at)->format('d/m/Y') }}). Puede editarlos según sea necesario.</p>
            </div>
        </div>
    </div>
    @endif

    <form id="form-evolucion" action="{{ route('historia-clinica.evoluciones.store', $cita->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Content (Patient, Vitals, Evaluation) -->
                <!-- Patient Info Card -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Información de la Cita
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Paciente</p>
                            <p class="font-semibold text-gray-900">{{ $cita->paciente->primer_nombre ?? '' }} {{ $cita->paciente->primer_apellido ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Cédula</p>
                            <p class="font-semibold text-gray-900">{{ $cita->paciente->tipo_documento ?? '' }}-{{ $cita->paciente->numero_documento ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Especialidad</p>
                            <p class="font-semibold text-gray-900">{{ $cita->especialidad->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fecha de Cita</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }}</p>
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
                                   value="{{ old('peso_kg', $ultimaEvolucion->peso_kg ?? '') }}">
                            <p class="form-help">kg</p>
                        </div>
                        <div>
                            <label class="form-label">Talla</label>
                            <input type="number" step="0.1" name="talla_cm" class="input" placeholder="170" min="0" oninput="validarInput(this)"
                                   value="{{ old('talla_cm', $ultimaEvolucion->talla_cm ?? '') }}">
                            <p class="form-help">cm</p>
                        </div>
                        <div>
                            <label class="form-label">IMC</label>
                            <input type="number" step="0.1" name="imc" id="imc" class="input bg-gray-100" placeholder="24.2" 
                                   value="{{ old('imc', $ultimaEvolucion->imc ?? '') }}" readonly>
                            <p class="form-help">kg/m²</p>
                        </div>
                        <div>
                            <label class="form-label">Temperatura</label>
                            <input type="number" step="0.1" name="temperatura_c" class="input" placeholder="36.5" min="0" oninput="validarInput(this)"
                                   value="{{ old('temperatura_c', $ultimaEvolucion->temperatura_c ?? '') }}">
                            <p class="form-help">°C</p>
                        </div>
                        <div>
                            <label class="form-label">T. Sistólica</label>
                            <input type="number" name="tension_sistolica" class="input" placeholder="120" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('tension_sistolica', $ultimaEvolucion->tension_sistolica ?? '') }}">
                            <p class="form-help">mmHg</p>
                        </div>
                        <div>
                            <label class="form-label">T. Diastólica</label>
                            <input type="number" name="tension_diastolica" class="input" placeholder="80" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('tension_diastolica', $ultimaEvolucion->tension_diastolica ?? '') }}">
                            <p class="form-help">mmHg</p>
                        </div>
                        <div>
                            <label class="form-label">Frec. Cardíaca</label>
                            <input type="number" name="frecuencia_cardiaca" class="input" placeholder="75" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('frecuencia_cardiaca', $ultimaEvolucion->frecuencia_cardiaca ?? '') }}">
                            <p class="form-help">bpm</p>
                        </div>
                        <div>
                            <label class="form-label">Frec. Respiratoria</label>
                            <input type="number" name="frecuencia_respiratoria" class="input" placeholder="18" min="0" oninput="validarInput(this, true)"
                                   value="{{ old('frecuencia_respiratoria', $ultimaEvolucion->frecuencia_respiratoria ?? '') }}">
                            <p class="form-help">rpm</p>
                        </div>
                        <div>
                            <label class="form-label">Saturación O₂</label>
                            <input type="number" step="0.1" name="saturacion_oxigeno" class="input" placeholder="98" min="0" oninput="validarInput(this)"
                                   value="{{ old('saturacion_oxigeno', $ultimaEvolucion->saturacion_oxigeno ?? '') }}">
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
                            <textarea name="motivo_consulta" rows="2" class="form-textarea" required>{{ old('motivo_consulta', $cita->motivo ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Enfermedad Actual</label>
                            <textarea name="enfermedad_actual" rows="3" class="form-textarea" required>{{ old('enfermedad_actual') }}</textarea>
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
                            <label class="form-label">Recomendaciones</label>
                            <textarea name="recomendaciones" rows="2" class="form-textarea">{{ old('recomendaciones') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Notas Adicionales</label>
                            <textarea name="notas_adicionales" rows="2" class="form-textarea">{{ old('notas_adicionales') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
    </form> <!-- Cierre del form principal ANTES del sidebar -->

            <!-- Sidebar (Fuera del form principal) -->
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
                        <!-- Botón vinculado al form principal por ID -->
                        <button type="submit" form="form-evolucion" class="btn btn-success w-full" onclick="return confirm('¿Está seguro de guardar esta evolución clínica?')">
                            <i class="bi bi-check-lg"></i>
                            Guardar Evolución
                        </button>
                        
                        <hr class="border-gray-200">
                        
                        @if($cita->estado_cita == 'Confirmada')
                        <form action="{{ route('citas.cambiar-estado', $cita->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="estado_cita" value="Completada">
                            <button type="submit" class="btn btn-primary w-full" onclick="return confirm('¿Está seguro de marcar esta cita como COMPLETADA? Esta acción indica que la consulta ha finalizado.')">
                                <i class="bi bi-check-all"></i>
                                Marcar Cita Completada
                            </button>
                        </form>
                        @endif
                        
                        <hr class="border-gray-200">
                        
                        <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-outline w-full" onclick="return confirm('¿Está seguro de cancelar? Se perderán los datos no guardados.')">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Quick Stats if previous evolutions exist -->
                @if(isset($ultimaEvolucion) && $ultimaEvolucion)
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Última Consulta</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500">Fecha</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($ultimaEvolucion->created_at)->format('d/m/Y') }}</p>
                        </div>
                        @if($ultimaEvolucion->diagnostico)
                        <div>
                            <p class="text-gray-500">Diagnóstico Anterior</p>
                            <p class="font-semibold text-gray-900">{{ Str::limit($ultimaEvolucion->diagnostico, 80) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
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
