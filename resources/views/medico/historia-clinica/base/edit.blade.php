@extends('layouts.medico')

@section('title', 'Editar Historia Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('historia-clinica.base.show', $historia->paciente_id ?? 1) }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Editar Historia Clínica</h1>
            <p class="text-gray-600 mt-1">Actualizar información médica del paciente</p>
        </div>
    </div>

    <form action="{{ route('historia-clinica.base.update', $historia->paciente_id ?? 1) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patient Info (Read-only) -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Paciente
                    </h3>

                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($historia->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($historia->paciente->primer_apellido ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">
                                    {{ $historia->paciente->primer_nombre ?? 'N/A' }} 
                                    {{ $historia->paciente->primer_apellido ?? '' }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $historia->paciente->cedula ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Data -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-clipboard-data text-emerald-600"></i>
                        Datos Básicos
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Tipo de Sangre</label>
                            <select name="tipo_sangre" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="A+" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('tipo_sangre', $historia->tipo_sangre ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Factor RH</label>
                            <select name="factor_rh" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="positivo" {{ old('factor_rh', $historia->factor_rh ?? '') == 'positivo' ? 'selected' : '' }}>Positivo (+)</option>
                                <option value="negativo" {{ old('factor_rh', $historia->factor_rh ?? '') == 'negativo' ? 'selected' : '' }}>Negativo (-)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Medical History -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-journal-medical text-purple-600"></i>
                        Antecedentes Médicos
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Antecedentes Personales</label>
                            <textarea name="antecedentes_personales" rows="3" class="form-textarea" placeholder="Enfermedades previas, cirugías, hospitalizaciones...">{{ old('antecedentes_personales', $historia->antecedentes_personales ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Antecedentes Familiares</label>
                            <textarea name="antecedentes_familiares" rows="3" class="form-textarea" placeholder="Enfermedades hereditarias, condiciones familiares...">{{ old('antecedentes_familiares', $historia->antecedentes_familiares ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Alergias</label>
                            <textarea name="alergias" rows="2" class="form-textarea" placeholder="Medicamentos, alimentos, sustancias...">{{ old('alergias', $historia->alergias ?? '') }}</textarea>
                            <p class="form-help">Importante para evitar reacciones adversas</p>
                        </div>

                        <div>
                            <label class="form-label">Medicamentos Actuales</label>
                            <textarea name="medicamentos_actuales" rows="2" class="form-textarea" placeholder="Lista de medicamentos que toma actualmente...">{{ old('medicamentos_actuales', $historia->medicamentos_actuales ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Lifestyle -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-heart text-rose-600"></i>
                        Estilo de Vida
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Hábitos de Tabaco</label>
                            <select name="habito_tabaco" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="no_fuma" {{ old('habito_tabaco', $historia->habito_tabaco ?? '') == 'no_fuma' ? 'selected' : '' }}>No fuma</option>
                                <option value="fumador" {{ old('habito_tabaco', $historia->habito_tabaco ?? '') == 'fumador' ? 'selected' : '' }}>Fumador</option>
                                <option value="ex_fumador" {{ old('habito_tabaco', $historia->habito_tabaco ?? '') == 'ex_fumador' ? 'selected' : '' }}>Ex-fumador</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Consumo de Alcohol</label>
                            <select name="consumo_alcohol" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="no_consume" {{ old('consumo_alcohol', $historia->consumo_alcohol ?? '') == 'no_consume' ? 'selected' : '' }}>No consume</option>
                                <option value="ocasional" {{ old('consumo_alcohol', $historia->consumo_alcohol ?? '') == 'ocasional' ? 'selected' : '' }}>Ocasional</option>
                                <option value="frecuente" {{ old('consumo_alcohol', $historia->consumo_alcohol ?? '') == 'frecuente' ? 'selected' : '' }}>Frecuente</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Actividad Física</label>
                            <select name="actividad_fisica" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="sedentario" {{ old('actividad_fisica', $historia->actividad_fisica ?? '') == 'sedentario' ? 'selected' : '' }}>Sedentario</option>
                                <option value="ligera" {{ old('actividad_fisica', $historia->actividad_fisica ?? '') == 'ligera' ? 'selected' : '' }}>Ligera</option>
                                <option value="moderada" {{ old('actividad_fisica', $historia->actividad_fisica ?? '') == 'moderada' ? 'selected' : '' }}>Moderada</option>
                                <option value="intensa" {{ old('actividad_fisica', $historia->actividad_fisica ?? '') == 'intensa' ? 'selected' : '' }}>Intensa</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Dieta</label>
                            <select name="dieta" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="balanceada" {{ old('dieta', $historia->dieta ?? '') == 'balanceada' ? 'selected' : '' }}>Balanceada</option>
                                <option value="vegetariana" {{ old('dieta', $historia->dieta ?? '') == 'vegetariana' ? 'selected' : '' }}>Vegetariana</option>
                                <option value="vegana" {{ old('dieta', $historia->dieta ?? '') == 'vegana' ? 'selected' : '' }}>Vegana</option>
                                <option value="especial" {{ old('dieta', $historia->dieta ?? '') == 'especial' ? 'selected' : '' }}>Dieta Especial</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-sticky text-amber-600"></i>
                        Notas Adicionales
                    </h3>

                    <div>
                        <label class="form-label">Observaciones Generales</label>
                        <textarea name="observaciones" rows="4" class="form-textarea" placeholder="Información adicional relevante...">{{ old('observaciones', $historia->observaciones ?? '') }}</textarea>
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
                                {{ isset($historia->created_at) ? \Carbon\Carbon::parse($historia->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Última Modificación</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($historia->updated_at) ? \Carbon\Carbon::parse($historia->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Evoluciones</p>
                            <p class="font-semibold text-gray-900">{{ $historia->evoluciones->count() ?? 0 }} registros</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Actualizar Historia
                        </button>
                        <a href="{{ route('historia-clinica.base.show', $historia->paciente_id ?? 1) }}" class="btn btn-outline w-full">
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
                            <h4 class="font-semibold text-gray-900 mb-1">Importante</h4>
                            <p class="text-sm text-gray-600">Los cambios en la historia clínica quedarán registrados permanentemente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
