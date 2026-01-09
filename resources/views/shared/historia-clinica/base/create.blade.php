@extends('layouts.admin')

@section('title', 'Crear Historia Clínica Base')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/shared/historia-clinica') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Crear Historia Clínica Base</h1>
            <p class="text-gray-600 mt-1">Paciente: {{ $paciente->nombre_completo }}</p>
        </div>
    </div>

    <form action="{{ route('historia-clinica.base.store', $paciente->id) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Datos Básicos -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-blue-600"></i> Datos Básicos
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Tipo de Sangre</label>
                        <select name="tipo_sangre" class="form-select">
                            <option value="">Seleccionar...</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_sangre') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Alergias Conocidas</label>
                        <textarea name="alergias" rows="3" class="form-textarea" placeholder="Describa alergias alimentarias, ambientales, etc.">{{ old('alergias') }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Alergias a Medicamentos</label>
                        <textarea name="alergias_medicamentos" rows="3" class="form-textarea" placeholder="Especifique medicamentos a los que es alérgico">{{ old('alergias_medicamentos') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Antecedentes Patológicos -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-clipboard-pulse text-rose-600"></i> Antecedentes Patológicos
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Enfermedades Crónicas</label>
                        <textarea name="enfermedades_cronicas" rows="3" class="form-textarea" placeholder="Diabetes, Hipertensión, Asma, etc.">{{ old('enfermedades_cronicas') }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Antecedentes Personales</label>
                        <textarea name="antecedentes_personales" rows="3" class="form-textarea" placeholder="Otras enfermedades relevantes en el pasado">{{ old('antecedentes_personales') }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Cirugías Previas</label>
                        <textarea name="cirugias_previas" rows="2" class="form-textarea" placeholder="Describa cirugías y fechas aproximadas">{{ old('cirugias_previas') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Antecedentes Familiares y Hábitos -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-people text-purple-600"></i> Antecedentes Familiares
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Antecedentes Hereditarios</label>
                        <textarea name="antecedentes_familiares" rows="4" class="form-textarea" placeholder="Enfermedades relevantes en padres, abuelos, hermanos...">{{ old('antecedentes_familiares') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tratamiento y Estilo de Vida -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-capsule text-emerald-600"></i> Tratamiento y Estilo de Vida
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Medicamentos Actuales</label>
                        <textarea name="medicamentos_actuales" rows="3" class="form-textarea" placeholder="Medicamentos que toma regularmente">{{ old('medicamentos_actuales') }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Hábitos Psicobiológicos</label>
                        <textarea name="habitos" rows="3" class="form-textarea" placeholder="Fumar, alcohol, actividad física, sueño...">{{ old('habitos') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="lg:col-span-2 flex justify-end gap-3">
                <a href="{{ url('index.php/shared/historia-clinica') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Crear Historia Clínica
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
