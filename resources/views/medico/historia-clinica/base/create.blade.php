@extends('layouts.medico')

@section('title', 'Nueva Historia Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('historia-clinica.base.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Historia Clínica</h1>
            <p class="text-gray-600 mt-1">Registro inicial del historial médico del paciente</p>
        </div>
    </div>

    <form id="createHistoriaForm" action="{{ route('historia-clinica.base.store', '0') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patient Selection -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Selección de Paciente
                    </h3>

                    <div>
                        <label class="form-label form-label-required">Paciente</label>
                        <select name="paciente_id" id="paciente_id" class="form-select" required>
                            <option value="">Seleccionar paciente...</option>
                            @foreach($pacientes ?? [] as $paciente)
                            <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }} - {{ $paciente->cedula }}
                            </option>
                            @endforeach
                        </select>
                        <p class="form-help">Selecciona el paciente para crear su historia clínica</p>
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
                                <option value="A+" {{ old('tipo_sangre') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('tipo_sangre') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('tipo_sangre') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('tipo_sangre') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('tipo_sangre') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('tipo_sangre') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('tipo_sangre') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('tipo_sangre') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Factor RH</label>
                            <select name="factor_rh" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="positivo" {{ old('factor_rh') == 'positivo' ? 'selected' : '' }}>Positivo (+)</option>
                                <option value="negativo" {{ old('factor_rh') == 'negativo' ? 'selected' : '' }}>Negativo (-)</option>
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
                            <textarea name="antecedentes_personales" rows="3" class="form-textarea" placeholder="Enfermedades previas, cirugías, hospitalizaciones...">{{ old('antecedentes_personales') }}</textarea>
                            <p class="form-help">Incluya enfermedades crónicas, cirugías y hospitalizaciones previas</p>
                        </div>

                        <div>
                            <label class="form-label">Antecedentes Familiares</label>
                            <textarea name="antecedentes_familiares" rows="3" class="form-textarea" placeholder="Enfermedades hereditarias, condiciones familiares...">{{ old('antecedentes_familiares') }}</textarea>
                            <p class="form-help">Enfermedades de padres, hermanos y abuelos</p>
                        </div>

                        <div>
                            <label class="form-label">Alergias</label>
                            <textarea name="alergias" rows="2" class="form-textarea" placeholder="Medicamentos, alimentos, sustancias...">{{ old('alergias') }}</textarea>
                            <p class="form-help">Especifique medicamentos, alimentos o sustancias a las que el paciente es alérgico</p>
                        </div>

                        <div>
                            <label class="form-label">Medicamentos Actuales</label>
                            <textarea name="medicamentos_actuales" rows="2" class="form-textarea" placeholder="Lista de medicamentos que toma actualmente...">{{ old('medicamentos_actuales') }}</textarea>
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
                                <option value="no_fuma" {{ old('habito_tabaco') == 'no_fuma' ? 'selected' : '' }}>No fuma</option>
                                <option value="fumador" {{ old('habito_tabaco') == 'fumador' ? 'selected' : '' }}>Fumador</option>
                                <option value="ex_fumador" {{ old('habito_tabaco') == 'ex_fumador' ? 'selected' : '' }}>Ex-fumador</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Consumo de Alcohol</label>
                            <select name="consumo_alcohol" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="no_consume" {{ old('consumo_alcohol') == 'no_consume' ? 'selected' : '' }}>No consume</option>
                                <option value="ocasional" {{ old('consumo_alcohol') == 'ocasional' ? 'selected' : '' }}>Ocasional</option>
                                <option value="frecuente" {{ old('consumo_alcohol') == 'frecuente' ? 'selected' : '' }}>Frecuente</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Actividad Física</label>
                            <select name="actividad_fisica" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="sedentario" {{ old('actividad_fisica') == 'sedentario' ? 'selected' : '' }}>Sedentario</option>
                                <option value="ligera" {{ old('actividad_fisica') == 'ligera' ? 'selected' : '' }}>Ligera</option>
                                <option value="moderada" {{ old('actividad_fisica') == 'moderada' ? 'selected' : '' }}>Moderada</option>
                                <option value="intensa" {{ old('actividad_fisica') == 'intensa' ? 'selected' : '' }}>Intensa</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Dieta</label>
                            <select name="dieta" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="balanceada" {{ old('dieta') == 'balanceada' ? 'selected' : '' }}>Balanceada</option>
                                <option value="vegetariana" {{ old('dieta') == 'vegetariana' ? 'selected' : '' }}>Vegetariana</option>
                                <option value="vegana" {{ old('dieta') == 'vegana' ? 'selected' : '' }}>Vegana</option>
                                <option value="especial" {{ old('dieta') == 'especial' ? 'selected' : '' }}>Dieta Especial</option>
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
                        <textarea name="observaciones" rows="4" class="form-textarea" placeholder="Información adicional relevante...">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Instructions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">
                        <i class="bi bi-info-circle text-blue-600"></i> Instrucciones
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Complete toda la información disponible del paciente</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Registre todas las alergias conocidas</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Documente antecedentes familiares relevantes</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Actualice los medicamentos actuales</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Crear Historia Clínica
                        </button>
                        <a href="{{ route('historia-clinica.base.index') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card p-6">
                    <div class="flex gap-3">
                        <i class="bi bi-lightbulb text-amber-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Consejo</h4>
                            <p class="text-sm text-gray-600">Una historia clínica completa facilita diagnósticos futuros más precisos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const form = document.getElementById('createHistoriaForm');
    const pacienteSelect = document.getElementById('paciente_id');
    // Store original action with placeholder 0
    const originalAction = "{{ route('historia-clinica.base.store', '0') }}";

    if (pacienteSelect && form) {
        pacienteSelect.addEventListener('change', function() {
            const pacienteId = this.value;
            if (pacienteId) {
                // Replace last segment (0) with actual ID
                const newAction = originalAction.replace('/0', '/' + pacienteId);
                form.action = newAction;
            } else {
                form.action = originalAction;
            }
        });
    }
</script>
@endpush
@endsection
