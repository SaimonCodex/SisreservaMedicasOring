@extends('layouts.medico')

@section('title', 'Editar Orden Médica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('ordenes-medicas.show', $orden->id ?? 1) }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Editar Orden Médica</h1>
            <p class="text-gray-600 mt-1">Actualizar información de la orden</p>
        </div>
    </div>

    <form action="{{ route('ordenes-medicas.update', $orden->id ?? 1) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tipo de Orden (Read-only) -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-ui-checks text-purple-600"></i>
                        Tipo de Orden
                    </h3>

                    <div class="p-4 bg-gray-50 rounded-xl">
                        @if($orden->tipo == 'receta')
                        <p class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-prescription text-purple-600"></i> Receta Médica
                        </p>
                        @elseif($orden->tipo == 'laboratorio')
                        <p class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-activity text-blue-600"></i> Orden de Laboratorio
                        </p>
                        @elseif($orden->tipo == 'imagenologia')
                        <p class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-x-ray text-emerald-600"></i> Orden de Imagenología
                        </p>
                        @else
                        <p class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-arrow-right-circle text-amber-600"></i> Referencia Médica
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Patient Info (Read-only) -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Paciente
                    </h3>

                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($orden->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($orden->paciente->primer_apellido ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">
                                    {{ $orden->paciente->primer_nombre ?? 'N/A' }} 
                                    {{ $orden->paciente->primer_apellido ?? '' }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $orden->paciente->cedula ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receta Content -->
                @if($orden->tipo == 'receta')
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-prescription text-purple-600"></i>
                        Detalles de la Receta
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Medicamento</label>
                            <input type="text" name="medicamento" class="input" value="{{ old('medicamento', $orden->receta->medicamento ?? '') }}" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Dosis</label>
                                <input type="text" name="dosis" class="input" value="{{ old('dosis', $orden->receta->dosis ?? '') }}" required>
                            </div>
                            <div>
                                <label class="form-label form-label-required">Frecuencia</label>
                                <input type="text" name="frecuencia" class="input" value="{{ old('frecuencia', $orden->receta->frecuencia ?? '') }}" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Duración</label>
                                <input type="text" name="duracion" class="input" value="{{ old('duracion', $orden->receta->duracion ?? '') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Vía de Administración</label>
                                <select name="via_administracion" class="form-select">
                                    <option value="oral" {{ old('via_administracion', $orden->receta->via_administracion ?? '') == 'oral' ? 'selected' : '' }}>Oral</option>
                                    <option value="intravenosa" {{ old('via_administracion', $orden->receta->via_administracion ?? '') == 'intravenosa' ? 'selected' : '' }}>Intravenosa</option>
                                    <option value="intramuscular" {{ old('via_administracion', $orden->receta->via_administracion ?? '') == 'intramuscular' ? 'selected' : '' }}>Intramuscular</option>
                                    <option value="topica" {{ old('via_administracion', $orden->receta->via_administracion ?? '') == 'topica' ? 'selected' : '' }}>Tópica</option>
                                    <option value="subcutanea" {{ old('via_administracion', $orden->receta->via_administracion ?? '') == 'subcutanea' ? 'selected' : '' }}>Subcutánea</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Instrucciones Especiales</label>
                            <textarea name="instrucciones" rows="2" class="form-textarea">{{ old('instrucciones', $orden->receta->instrucciones ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Laboratorio Content -->
                @if($orden->tipo == 'laboratorio')
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-activity text-blue-600"></i>
                        Órdenes de Laboratorio
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Exámenes Solicitados</label>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                    $examenesSeleccionados = json_decode($orden->laboratorio->examenes ?? '[]') ?? [];
                                @endphp
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="checkbox" name="examenes[]" value="hemograma" class="form-checkbox" {{ in_array('hemograma', $examenesSeleccionados) ? 'checked' : '' }}>
                                    <span class="text-sm">Hemograma Completo</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="checkbox" name="examenes[]" value="glicemia" class="form-checkbox" {{ in_array('glicemia', $examenesSeleccionados) ? 'checked' : '' }}>
                                    <span class="text-sm">Glicemia</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="checkbox" name="examenes[]" value="creatinina" class="form-checkbox" {{ in_array('creatinina', $examenesSeleccionados) ? 'checked' : '' }}>
                                    <span class="text-sm">Creatinina</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="checkbox" name="examenes[]" value="perfil_lipidico" class="form-checkbox" {{ in_array('perfil_lipidico', $examenesSeleccionados) ? 'checked' : '' }}>
                                    <span class="text-sm">Perfil Lipídico</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="checkbox" name="examenes[]" value="orina" class="form-checkbox" {{ in_array('orina', $examenesSeleccionados) ? 'checked' : '' }}>
                                    <span class="text-sm">Examen de Orina</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="checkbox" name="examenes[]" value="heces" class="form-checkbox" {{ in_array('heces', $examenesSeleccionados) ? 'checked' : '' }}>
                                    <span class="text-sm">Examen de Heces</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Otros Exámenes</label>
                            <textarea name="otros_examenes" rows="2" class="form-textarea">{{ old('otros_examenes', $orden->laboratorio->otros_examenes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Imagenología Content -->
                @if($orden->tipo == 'imagenologia')
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-x-ray text-emerald-600"></i>
                        Estudios de Imagenología
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Tipo de Estudio</label>
                            <select name="tipo_estudio" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="radiografia" {{ old('tipo_estudio', $orden->imagenologia->tipo_estudio ?? '') == 'radiografia' ? 'selected' : '' }}>Radiografía</option>
                                <option value="ecografia" {{ old('tipo_estudio', $orden->imagenologia->tipo_estudio ?? '') == 'ecografia' ? 'selected' : '' }}>Ecografía</option>
                                <option value="tomografia" {{ old('tipo_estudio', $orden->imagenologia->tipo_estudio ?? '') == 'tomografia' ? 'selected' : '' }}>Tomografía</option>
                                <option value="resonancia" {{ old('tipo_estudio', $orden->imagenologia->tipo_estudio ?? '') == 'resonancia' ? 'selected' : '' }}>Resonancia Magnética</option>
                                <option value="mamografia" {{ old('tipo_estudio', $orden->imagenologia->tipo_estudio ?? '') == 'mamografia' ? 'selected' : '' }}>Mamografía</option>
                                <option value="densitometria" {{ old('tipo_estudio', $orden->imagenologia->tipo_estudio ?? '') == 'densitometria' ? 'selected' : '' }}>Densitometría Ósea</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Área/Región</label>
                            <input type="text" name="region" class="input" value="{{ old('region', $orden->imagenologia->region ?? '') }}" required>
                        </div>

                        <div>
                            <label class="form-label">Indicaciones Clínicas</label>
                            <textarea name="indicaciones_clinicas" rows="2" class="form-textarea">{{ old('indicaciones_clinicas', $orden->imagenologia->indicaciones_clinicas ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Referencia Content -->
                @if($orden->tipo == 'referencia')
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-arrow-right-circle text-amber-600"></i>
                        Referencia Médica
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Especialidad</label>
                            <select name="especialidad_referencia" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="cardiologia" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'cardiologia' ? 'selected' : '' }}>Cardiología</option>
                                <option value="neurologia" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'neurologia' ? 'selected' : '' }}>Neurología</option>
                                <option value="traumatologia" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'traumatologia' ? 'selected' : '' }}>Traumatología</option>
                                <option value="gastroenterologia" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'gastroenterologia' ? 'selected' : '' }}>Gastroenterología</option>
                                <option value="dermatologia" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'dermatologia' ? 'selected' : '' }}>Dermatología</option>
                                <option value="psiquiatria" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'psiquiatria' ? 'selected' : '' }}>Psiquiatría</option>
                                <option value="oftalmologia" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'oftalmologia' ? 'selected' : '' }}>Oftalmología</option>
                                <option value="otros" {{ old('especialidad_referencia', $orden->referencia->especialidad_referencia ?? '') == 'otros' ? 'selected' : '' }}>Otra</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Motivo de Referencia</label>
                            <textarea name="motivo_referencia" rows="3" class="form-textarea" required>{{ old('motivo_referencia', $orden->referencia->motivo_referencia ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Prioridad</label>
                            <select name="prioridad" class="form-select">
                                <option value="normal" {{ old('prioridad', $orden->referencia->prioridad ?? '') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="urgente" {{ old('prioridad', $orden->referencia->prioridad ?? '') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                <option value="muy_urgente" {{ old('prioridad', $orden->referencia->prioridad ?? '') == 'muy_urgente' ? 'selected' : '' }}>Muy Urgente</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Observaciones -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-sticky text-gray-600"></i>
                        Observaciones
                    </h3>

                    <div>
                        <label class="form-label">Notas Adicionales</label>
                        <textarea name="observaciones" rows="3" class="form-textarea">{{ old('observaciones', $orden->observaciones ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Estado -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-toggles text-blue-600"></i>
                        Estado de la Orden
                    </h3>

                    <div>
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="pendiente" {{ old('status', $orden->status ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_proceso" {{ old('status', $orden->status ?? '') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="completada" {{ old('status', $orden->status ?? '') == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada" {{ old('status', $orden->status ?? '') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
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
                                {{ isset($orden->created_at) ? \Carbon\Carbon::parse($orden->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Última Modificación</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($orden->updated_at) ? \Carbon\Carbon::parse($orden->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
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
                            Actualizar Orden
                        </button>
                        <a href="{{ route('ordenes-medicas.show', $orden->id ?? 1) }}" class="btn btn-outline w-full">
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
                            <p class="text-sm text-gray-600">Los cambios quedarán registrados en el historial médico del paciente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
