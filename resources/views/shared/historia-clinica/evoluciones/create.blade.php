@extends('layouts.admin')

@section('title', 'Nueva Evolución Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/medico/consultas') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Evolución Clínica</h1>
            <p class="text-gray-600 mt-1">Cita #{{ $cita->id }} - {{ $cita->paciente->nombre_completo }}</p>
        </div>
    </div>

    <form action="{{ route('historia-clinica.evoluciones.store', $cita->id) }}" method="POST">
        @csrf

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
                            <input type="number" name="tension_sistolica" class="input" placeholder="120" value="{{ old('tension_sistolica') }}">
                            <span class="input-group-text">mmHg</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Tensión Diastólica</label>
                        <div class="input-group">
                            <input type="number" name="tension_diastolica" class="input" placeholder="80" value="{{ old('tension_diastolica') }}">
                            <span class="input-group-text">mmHg</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Frecuencia Cardíaca</label>
                        <div class="input-group">
                            <input type="number" name="frecuencia_cardiaca" class="input" placeholder="75" value="{{ old('frecuencia_cardiaca') }}">
                            <span class="input-group-text">bpm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Temperatura</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="temperatura_c" class="input" placeholder="36.5" value="{{ old('temperatura_c') }}">
                            <span class="input-group-text">°C</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Peso</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="peso_kg" class="input" placeholder="70.5" value="{{ old('peso_kg') }}">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Talla</label>
                        <div class="input-group">
                            <input type="number" name="talla_cm" class="input" placeholder="170" value="{{ old('talla_cm') }}">
                            <span class="input-group-text">cm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Frec. Respiratoria</label>
                        <div class="input-group">
                            <input type="number" name="frecuencia_respiratoria" class="input" placeholder="18" value="{{ old('frecuencia_respiratoria') }}">
                            <span class="input-group-text">rpm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Saturación O2</label>
                        <div class="input-group">
                            <input type="number" name="saturacion_oxigeno" class="input" placeholder="98" value="{{ old('saturacion_oxigeno') }}">
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
                            <input type="text" name="motivo_consulta" class="input" value="{{ old('motivo_consulta') }}" required>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Enfermedad Actual</label>
                            <textarea name="enfermedad_actual" rows="4" class="form-textarea" required>{{ old('enfermedad_actual') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Examen Físico</label>
                            <textarea name="examen_fisico" rows="4" class="form-textarea">{{ old('examen_fisico') }}</textarea>
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
                            <textarea name="diagnostico" rows="3" class="form-textarea" required>{{ old('diagnostico') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Plan / Tratamiento</label>
                            <textarea name="tratamiento" rows="3" class="form-textarea" required>{{ old('tratamiento') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Recomendaciones</label>
                            <textarea name="recomendaciones" rows="3" class="form-textarea">{{ old('recomendaciones') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Información del Paciente</h3>
                    <div class="space-y-3 text-sm">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="block text-xs text-gray-500">Nombre Completo</span>
                            <span class="block font-semibold text-gray-900">{{ $cita->paciente->nombre_completo }}</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="block text-xs text-gray-500">Cédula</span>
                            <span class="block font-semibold text-gray-900">{{ $cita->paciente->cedula }}</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="block text-xs text-gray-500">Edad</span>
                            <span class="block font-semibold text-gray-900">{{ $cita->paciente->edad }} años</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ url('index.php/shared/historia-clinica/base/' . $cita->paciente_id) }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-semibold" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i> Ver Historia Base
                        </a>
                    </div>
                </div>

                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Finalizar Consulta
                        </button>
                        <a href="{{ url('index.php/medico/consultas') }}" class="btn btn-outline w-full text-rose-600 hover:bg-rose-50 border-rose-200">
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
