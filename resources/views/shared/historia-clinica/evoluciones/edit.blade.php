@extends('layouts.admin')

@section('title', 'Editar Evolución Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/shared/historia-clinica/evoluciones/show/' . $cita->id) }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Editar Evolución Clínica</h1>
            <p class="text-gray-600 mt-1">Cita #{{ $cita->id }} - {{ $cita->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <form action="{{ route('historia-clinica.evoluciones.update', $cita->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Signos Vitales -->
            <div class="lg:col-span-3 card p-6 bg-blue-50 border-blue-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-heart-pulse text-rose-600"></i> Signos Vitales
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="form-label">Tensión Sistólica</label>
                        <div class="input-group">
                            <input type="number" name="tension_sistolica" class="input" value="{{ old('tension_sistolica', $evolucion->tension_sistolica) }}">
                            <span class="input-group-text">mmHg</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Tensión Diastólica</label>
                        <div class="input-group">
                            <input type="number" name="tension_diastolica" class="input" value="{{ old('tension_diastolica', $evolucion->tension_diastolica) }}">
                            <span class="input-group-text">mmHg</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Frecuencia Cardíaca</label>
                        <div class="input-group">
                            <input type="number" name="frecuencia_cardiaca" class="input" value="{{ old('frecuencia_cardiaca', $evolucion->frecuencia_cardiaca) }}">
                            <span class="input-group-text">bpm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Temperatura</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="temperatura_c" class="input" value="{{ old('temperatura_c', $evolucion->temperatura_c) }}">
                            <span class="input-group-text">°C</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Peso</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="peso_kg" class="input" value="{{ old('peso_kg', $evolucion->peso_kg) }}">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Talla</label>
                        <div class="input-group">
                            <input type="number" name="talla_cm" class="input" value="{{ old('talla_cm', $evolucion->talla_cm) }}">
                            <span class="input-group-text">cm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Frec. Respiratoria</label>
                        <div class="input-group">
                            <input type="number" name="frecuencia_respiratoria" class="input" value="{{ old('frecuencia_respiratoria', $evolucion->frecuencia_respiratoria) }}">
                            <span class="input-group-text">rpm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Saturación O2</label>
                        <div class="input-group">
                            <input type="number" name="saturacion_oxigeno" class="input" value="{{ old('saturacion_oxigeno', $evolucion->saturacion_oxigeno) }}">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos Clínicos -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-file-medical text-blue-600"></i> Evaluación Clínica
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Motivo de Consulta</label>
                            <input type="text" name="motivo_consulta" class="input" value="{{ old('motivo_consulta', $evolucion->motivo_consulta) }}" required>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Enfermedad Actual</label>
                            <textarea name="enfermedad_actual" rows="4" class="form-textarea" required>{{ old('enfermedad_actual', $evolucion->enfermedad_actual) }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Examen Físico</label>
                            <textarea name="examen_fisico" rows="4" class="form-textarea">{{ old('examen_fisico', $evolucion->examen_fisico) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-journal-check text-emerald-600"></i> Resolución
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Diagnóstico</label>
                            <textarea name="diagnostico" rows="3" class="form-textarea" required>{{ old('diagnostico', $evolucion->diagnostico) }}</textarea>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Plan / Tratamiento</label>
                            <textarea name="tratamiento" rows="3" class="form-textarea" required>{{ old('tratamiento', $evolucion->tratamiento) }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Recomendaciones</label>
                            <textarea name="recomendaciones" rows="3" class="form-textarea">{{ old('recomendaciones', $evolucion->recomendaciones) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="bi bi-save"></i>
                            Guardar Cambios
                        </button>
                        <a href="{{ url('index.php/shared/historia-clinica/evoluciones/show/' . $cita->id) }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
