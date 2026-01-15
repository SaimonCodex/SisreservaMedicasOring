@extends('layouts.medico')

@section('title', 'Nueva Historia Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Historia Clínica</h1>
            <p class="text-gray-600 mt-1">Registro inicial del historial médico del paciente</p>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
        <i class="bi bi-exclamation-circle-fill text-red-600"></i>
        <span class="text-red-800">{{ session('error') }}</span>
    </div>
    @endif

    @if ($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="font-semibold text-red-800 mb-2">Por favor corrige los siguientes errores:</p>
        <ul class="list-disc list-inside text-red-700 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('historia-clinica.base.store', $paciente->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Patient Info Card (No select - datos vienen de la cita) -->
                <div class="card p-6 bg-gradient-to-r from-blue-50 to-cyan-50 border-blue-200">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Datos del Paciente
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre Completo</p>
                            <p class="font-semibold text-gray-900 text-lg">
                                {{ $paciente->primer_nombre }} {{ $paciente->segundo_nombre ?? '' }} 
                                {{ $paciente->primer_apellido }} {{ $paciente->segundo_apellido ?? '' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Cédula</p>
                            <p class="font-semibold text-gray-900">
                                {{ $paciente->tipo_documento ?? 'V' }}-{{ $paciente->numero_documento ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fecha de Nacimiento</p>
                            <p class="font-semibold text-gray-900">
                                {{ $paciente->fecha_nac ? \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') : 'No registrada' }}
                                <span class="text-sm font-normal text-gray-500">
                                    ({{ $paciente->fecha_nac ? \Carbon\Carbon::parse($paciente->fecha_nac)->age . ' años' : '' }})
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Género</p>
                            <p class="font-semibold text-gray-900">{{ $paciente->genero ?? 'No registrado' }}</p>
                        </div>
                    </div>
                </div>    
                    @if(isset($cita))
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <p class="text-sm text-gray-500">Cita relacionada</p>
                        <p class="font-semibold text-blue-700">
                            <i class="bi bi-calendar-check"></i>
                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }} - 
                            {{ $cita->especialidad->nombre ?? 'Especialidad' }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Datos Básicos: Tipo de Sangre -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-clipboard-data text-emerald-600"></i>
                        Datos Básicos
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Tipo de Sangre</label>
                            <select name="grupo_sanguineo" id="grupo_sanguineo" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="A" {{ old('grupo_sanguineo') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('grupo_sanguineo') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('grupo_sanguineo') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('grupo_sanguineo') == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Factor RH</label>
                            <select name="factor_rh" id="factor_rh" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="+" {{ old('factor_rh') == '+' ? 'selected' : '' }}>Positivo (+)</option>
                                <option value="-" {{ old('factor_rh') == '-' ? 'selected' : '' }}>Negativo (-)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">&nbsp;</label>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="tipo_sangre_no_especificado" id="tipo_sangre_no_especificado" 
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                       {{ old('tipo_sangre_no_especificado') ? 'checked' : '' }}>
                                <label for="tipo_sangre_no_especificado" class="text-sm text-gray-700">
                                    No Especificado
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Campo oculto para tipo_sangre combinado -->
                    <input type="hidden" name="tipo_sangre" id="tipo_sangre_hidden" value="{{ old('tipo_sangre') }}">
                </div>

                <!-- Antecedentes Médicos -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-journal-medical text-purple-600"></i>
                        Antecedentes Médicos
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Antecedentes Personales</label>
                            <textarea name="antecedentes_personales" rows="3" class="form-textarea" 
                                      placeholder="Enfermedades previas, hospitalizaciones...">{{ old('antecedentes_personales') }}</textarea>
                            <p class="form-help">Historial de enfermedades y hospitalizaciones previas</p>
                        </div>

                        <div>
                            <label class="form-label">Antecedentes Familiares</label>
                            <textarea name="antecedentes_familiares" rows="3" class="form-textarea" 
                                      placeholder="Enfermedades hereditarias, condiciones familiares...">{{ old('antecedentes_familiares') }}</textarea>
                            <p class="form-help">Enfermedades de padres, hermanos y abuelos</p>
                        </div>

                        <div>
                            <label class="form-label">Enfermedades Crónicas</label>
                            <textarea name="enfermedades_cronicas" rows="2" class="form-textarea" 
                                      placeholder="Diabetes, hipertensión, asma, etc...">{{ old('enfermedades_cronicas') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Cirugías Previas</label>
                            <textarea name="cirugias_previas" rows="2" class="form-textarea" 
                                      placeholder="Intervenciones quirúrgicas realizadas y fechas aproximadas...">{{ old('cirugias_previas') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Alergias -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle text-red-600"></i>
                        Alergias
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Alergias Generales</label>
                            <textarea name="alergias" rows="2" class="form-textarea" 
                                      placeholder="Alimentos, sustancias ambientales, látex, etc...">{{ old('alergias') }}</textarea>
                        </div>

                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <label class="form-label flex items-center gap-2">
                                <i class="bi bi-capsule text-gray-600"></i>
                                Alergias a Medicamentos
                            </label>
                            <textarea name="alergias_medicamentos" rows="2" class="form-textarea" 
                                      placeholder="Penicilina, aspirina, sulfas, etc...">{{ old('alergias_medicamentos') }}</textarea>
                            <p class="form-help mt-1">Registrar si el paciente es alérgico a algún fármaco</p>
                        </div>
                    </div>
                </div>

                <!-- Medicamentos y Hábitos -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-prescription2 text-cyan-600"></i>
                        Medicamentos y Hábitos
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Medicamentos Actuales</label>
                            <textarea name="medicamentos_actuales" rows="2" class="form-textarea" 
                                      placeholder="Lista de medicamentos que toma actualmente con dosis...">{{ old('medicamentos_actuales') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label mb-3">Hábitos y Estilo de Vida</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Tabaquismo</label>
                                    <select name="habito_tabaco" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="No fuma" {{ old('habito_tabaco') == 'No fuma' ? 'selected' : '' }}>No fuma</option>
                                        <option value="Ex-fumador" {{ old('habito_tabaco') == 'Ex-fumador' ? 'selected' : '' }}>Ex-fumador</option>
                                        <option value="Fumador ocasional" {{ old('habito_tabaco') == 'Fumador ocasional' ? 'selected' : '' }}>Fumador ocasional</option>
                                        <option value="Fumador diario" {{ old('habito_tabaco') == 'Fumador diario' ? 'selected' : '' }}>Fumador diario</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Consumo Alcohol</label>
                                    <select name="habito_alcohol" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="No consume" {{ old('habito_alcohol') == 'No consume' ? 'selected' : '' }}>No consume</option>
                                        <option value="Ocasional" {{ old('habito_alcohol') == 'Ocasional' ? 'selected' : '' }}>Ocasional</option>
                                        <option value="Moderado" {{ old('habito_alcohol') == 'Moderado' ? 'selected' : '' }}>Moderado</option>
                                        <option value="Frecuente" {{ old('habito_alcohol') == 'Frecuente' ? 'selected' : '' }}>Frecuente</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Actividad Física</label>
                                    <select name="actividad_fisica" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="Sedentario" {{ old('actividad_fisica') == 'Sedentario' ? 'selected' : '' }}>Sedentario</option>
                                        <option value="Ligera" {{ old('actividad_fisica') == 'Ligera' ? 'selected' : '' }}>Ligera (1-2 días/sem)</option>
                                        <option value="Moderada" {{ old('actividad_fisica') == 'Moderada' ? 'selected' : '' }}>Moderada (3-4 días/sem)</option>
                                        <option value="Intensa" {{ old('actividad_fisica') == 'Intensa' ? 'selected' : '' }}>Intensa (5+ días/sem)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Dieta / Alimentación</label>
                                    <select name="dieta" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="Balanceada" {{ old('dieta') == 'Balanceada' ? 'selected' : '' }}>Balanceada</option>
                                        <option value="Baja en sal" {{ old('dieta') == 'Baja en sal' ? 'selected' : '' }}>Baja en sal</option>
                                        <option value="Baja en azúcar" {{ old('dieta') == 'Baja en azúcar' ? 'selected' : '' }}>Baja en azúcar</option>
                                        <option value="Vegetariana" {{ old('dieta') == 'Vegetariana' ? 'selected' : '' }}>Vegetariana</option>
                                        <option value="Sin restricciones" {{ old('dieta') == 'Sin restricciones' ? 'selected' : '' }}>Sin restricciones</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Campo oculto o extra para compatibilidad -->
                            <input type="hidden" name="habitos" value="{{ old('habitos') }}">
                        </div>
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
                            <p class="text-gray-700">Complete toda la información disponible</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Registre TODAS las alergias conocidas</p>
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
                        <a href="{{ url()->previous() }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card p-6 bg-amber-50 border-amber-200">
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
document.addEventListener('DOMContentLoaded', function() {
    const grupoSelect = document.getElementById('grupo_sanguineo');
    const factorSelect = document.getElementById('factor_rh');
    const noEspecificadoCheckbox = document.getElementById('tipo_sangre_no_especificado');
    const tipoSangreHidden = document.getElementById('tipo_sangre_hidden');
    
    function updateTipoSangre() {
        if (noEspecificadoCheckbox.checked) {
            tipoSangreHidden.value = 'No Especificado';
            grupoSelect.disabled = true;
            factorSelect.disabled = true;
            grupoSelect.value = '';
            factorSelect.value = '';
        } else {
            grupoSelect.disabled = false;
            factorSelect.disabled = false;
            const grupo = grupoSelect.value;
            const factor = factorSelect.value;
            if (grupo && factor) {
                tipoSangreHidden.value = grupo + factor;
            } else {
                tipoSangreHidden.value = '';
            }
        }
    }
    
    grupoSelect.addEventListener('change', updateTipoSangre);
    factorSelect.addEventListener('change', updateTipoSangre);
    noEspecificadoCheckbox.addEventListener('change', updateTipoSangre);
    
    // Inicializar
    updateTipoSangre();
});
</script>
@endpush
@endsection
