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
                <!-- Data Content (Patient, Vitals, Evaluation) -->
                <!-- Patient Info Card -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Datos del Paciente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Nombre Completo</p>
                            <p class="font-semibold text-gray-900">{{ $historia->paciente->primer_nombre }} {{ $historia->paciente->primer_apellido }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Documento de Identidad</p>
                            <p class="font-semibold text-gray-900">{{ $historia->paciente->tipo_documento }}-{{ $historia->paciente->numero_documento }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fecha de Nacimiento</p>
                            <p class="font-semibold text-gray-900">
                                {{ $historia->paciente->fecha_nac ? \Carbon\Carbon::parse($historia->paciente->fecha_nac)->format('d/m/Y') : 'No registrada' }}
                                <span class="text-sm font-normal text-gray-500">
                                    ({{ $historia->paciente->fecha_nac ? \Carbon\Carbon::parse($historia->paciente->fecha_nac)->age . ' años' : '' }})
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Género</p>
                            <p class="font-semibold text-gray-900">{{ $historia->paciente->genero ?? 'No registrado' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Datos Básicos -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-clipboard-data text-emerald-600"></i>
                        Datos Básicos
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tipo de Sangre Separado visualmente pero guardado junto si se desea, o separado -->
                        <!-- ADVERTENCIA: En BD es un solo campo 'tipo_sangre'. En create.blade.php se usó lógica hidden con JS. 
                             Aquí simplificaremos usando el campo directo si es compatible, o replicamos la lógica. 
                             Replicaré la lógica de create: inputs separados y hidden field. -->
                        
                        <div>
                            <label class="form-label">Grupo Sanguíneo</label>
                            <select id="grupo_sanguineo" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="A" {{ (strpos($historia->tipo_sangre ?? '', 'A') !== false && strpos($historia->tipo_sangre ?? '', 'B') === false) ? 'selected' : '' }}>A</option>
                                <option value="B" {{ (strpos($historia->tipo_sangre ?? '', 'B') !== false && strpos($historia->tipo_sangre ?? '', 'A') === false) ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ strpos($historia->tipo_sangre ?? '', 'AB') !== false ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ strpos($historia->tipo_sangre ?? '', 'O') !== false ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Factor RH</label>
                            <select id="factor_rh" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="+" {{ strpos($historia->tipo_sangre ?? '', '+') !== false ? 'selected' : '' }}>Positivo (+)</option>
                                <option value="-" {{ strpos($historia->tipo_sangre ?? '', '-') !== false ? 'selected' : '' }}>Negativo (-)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="flex items-center gap-2">
                             @php
                                $esNoEspecificado = ($historia->tipo_sangre == 'No Especificado');
                             @endphp
                            <input type="checkbox" name="tipo_sangre_no_especificado" id="tipo_sangre_no_especificado" 
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                   {{ $esNoEspecificado ? 'checked' : '' }}>
                            <label for="tipo_sangre_no_especificado" class="text-sm text-gray-700">
                                No Especificado
                            </label>
                        </div>
                    </div>
                    <!-- Campo oculto para tipo_sangre -->
                    <input type="hidden" name="tipo_sangre" id="tipo_sangre_hidden" value="{{ old('tipo_sangre', $historia->tipo_sangre) }}">
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
                                      placeholder="Enfermedades previas, hospitalizaciones...">{{ old('antecedentes_personales', $historia->antecedentes_personales) }}</textarea>
                            <p class="form-help">Historial de enfermedades y hospitalizaciones previas</p>
                        </div>

                        <div>
                            <label class="form-label">Antecedentes Familiares</label>
                            <textarea name="antecedentes_familiares" rows="3" class="form-textarea" 
                                      placeholder="Enfermedades hereditarias, condiciones familiares...">{{ old('antecedentes_familiares', $historia->antecedentes_familiares) }}</textarea>
                            <p class="form-help">Enfermedades de padres, hermanos y abuelos</p>
                        </div>

                        <div>
                            <label class="form-label">Enfermedades Crónicas</label>
                            <textarea name="enfermedades_cronicas" rows="2" class="form-textarea" 
                                      placeholder="Diabetes, hipertensión, asma, etc...">{{ old('enfermedades_cronicas', $historia->enfermedades_cronicas ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Cirugías Previas</label>
                            <textarea name="cirugias_previas" rows="2" class="form-textarea" 
                                      placeholder="Intervenciones quirúrgicas realizadas y fechas aproximadas...">{{ old('cirugias_previas', $historia->cirugias_previas ?? '') }}</textarea>
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
                                      placeholder="Alimentos, sustancias ambientales, látex, etc...">{{ old('alergias', $historia->alergias) }}</textarea>
                        </div>

                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <label class="form-label flex items-center gap-2">
                                <i class="bi bi-capsule text-gray-600"></i>
                                Alergias a Medicamentos
                            </label>
                            <textarea name="alergias_medicamentos" rows="2" class="form-textarea" 
                                      placeholder="Penicilina, aspirina, sulfas, etc...">{{ old('alergias_medicamentos', $historia->alergias_medicamentos ?? '') }}</textarea>
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
                                      placeholder="Lista de medicamentos que toma actualmente con dosis...">{{ old('medicamentos_actuales', $historia->medicamentos_actuales) }}</textarea>
                        </div>

                        <div>
                            <label class="form-label mb-3">Hábitos y Estilo de Vida</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Tabaquismo</label>
                                    <select name="habito_tabaco" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="No fuma" {{ old('habito_tabaco', $historia->habito_tabaco) == 'No fuma' ? 'selected' : '' }}>No fuma</option>
                                        <option value="Ex-fumador" {{ old('habito_tabaco', $historia->habito_tabaco) == 'Ex-fumador' ? 'selected' : '' }}>Ex-fumador</option>
                                        <option value="Fumador ocasional" {{ old('habito_tabaco', $historia->habito_tabaco) == 'Fumador ocasional' ? 'selected' : '' }}>Fumador ocasional</option>
                                        <option value="Fumador diario" {{ old('habito_tabaco', $historia->habito_tabaco) == 'Fumador diario' ? 'selected' : '' }}>Fumador diario</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Consumo Alcohol</label>
                                    <select name="habito_alcohol" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="No consume" {{ old('habito_alcohol', $historia->habito_alcohol) == 'No consume' ? 'selected' : '' }}>No consume</option>
                                        <option value="Ocasional" {{ old('habito_alcohol', $historia->habito_alcohol) == 'Ocasional' ? 'selected' : '' }}>Ocasional</option>
                                        <option value="Moderado" {{ old('habito_alcohol', $historia->habito_alcohol) == 'Moderado' ? 'selected' : '' }}>Moderado</option>
                                        <option value="Frecuente" {{ old('habito_alcohol', $historia->habito_alcohol) == 'Frecuente' ? 'selected' : '' }}>Frecuente</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Actividad Física</label>
                                    <select name="actividad_fisica" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="Sedentario" {{ old('actividad_fisica', $historia->actividad_fisica) == 'Sedentario' ? 'selected' : '' }}>Sedentario</option>
                                        <option value="Ligera" {{ old('actividad_fisica', $historia->actividad_fisica) == 'Ligera' ? 'selected' : '' }}>Ligera (1-2 días/sem)</option>
                                        <option value="Moderada" {{ old('actividad_fisica', $historia->actividad_fisica) == 'Moderada' ? 'selected' : '' }}>Moderada (3-4 días/sem)</option>
                                        <option value="Intensa" {{ old('actividad_fisica', $historia->actividad_fisica) == 'Intensa' ? 'selected' : '' }}>Intensa (5+ días/sem)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700 mb-1 block">Dieta / Alimentación</label>
                                    <select name="dieta" class="form-select w-full">
                                        <option value="">Seleccione...</option>
                                        <option value="Balanceada" {{ old('dieta', $historia->dieta) == 'Balanceada' ? 'selected' : '' }}>Balanceada</option>
                                        <option value="Baja en sal" {{ old('dieta', $historia->dieta) == 'Baja en sal' ? 'selected' : '' }}>Baja en sal</option>
                                        <option value="Baja en azúcar" {{ old('dieta', $historia->dieta) == 'Baja en azúcar' ? 'selected' : '' }}>Baja en azúcar</option>
                                        <option value="Vegetariana" {{ old('dieta', $historia->dieta) == 'Vegetariana' ? 'selected' : '' }}>Vegetariana</option>
                                        <option value="Sin restricciones" {{ old('dieta', $historia->dieta) == 'Sin restricciones' ? 'selected' : '' }}>Sin restricciones</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Campo oculto o extra para compatibilidad -->
                            <input type="hidden" name="habitos" value="{{ old('habitos', $historia->habitos) }}">
                        </div>
                    </div>
                </div>
                
                <!-- Notas Adicionales -->
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const grupoSelect = document.getElementById('grupo_sanguineo');
        const factorSelect = document.getElementById('factor_rh');
        const noEspecificadoCheckbox = document.getElementById('tipo_sangre_no_especificado');
        const tipoSangreHidden = document.getElementById('tipo_sangre_hidden');
        
        if (grupoSelect && factorSelect && noEspecificadoCheckbox && tipoSangreHidden) {
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
                    } 
                    // Si falta uno, no actualizamos el hidden a vacío necesariamente, 
                    // o sí para forzar validación requerida.
                    // El hidden se envió vacío si no completan ambos.
                }
            }
            
            grupoSelect.addEventListener('change', updateTipoSangre);
            factorSelect.addEventListener('change', updateTipoSangre);
            noEspecificadoCheckbox.addEventListener('change', updateTipoSangre);
            
            // Inicializar estado visual
            updateTipoSangre();
        }
    });
</script>
@endpush
@endsection
