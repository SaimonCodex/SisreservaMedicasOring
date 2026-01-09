@extends('layouts.medico')

@section('title', 'Nueva Orden Médica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/ordenes-medicas') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Orden Médica</h1>
            <p class="text-gray-600 mt-1">Crear receta, orden de laboratorio o referencia</p>
        </div>
    </div>

    <form action="{{ url('index.php/ordenes-medicas') }}" method="POST" class="space-y-6" id="ordenForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tipo de Orden -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-ui-checks text-purple-600"></i>
                        Tipo de Orden
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="card card-hover p-4 cursor-pointer has-[:checked]:ring-2 has-[:checked]:ring-purple-500 has-[:checked]:bg-purple-50">
                            <input type="radio" name="tipo" value="receta" class="sr-only" required checked>
                            <div class="text-center">
                                <i class="bi bi-prescription text-3xl text-purple-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Receta</p>
                                <p class="text-xs text-gray-500 mt-1">Medicamentos</p>
                            </div>
                        </label>

                        <label class="card card-hover p-4 cursor-pointer has-[:checked]:ring-2 has-[:checked]:ring-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="tipo" value="laboratorio" class="sr-only" required>
                            <div class="text-center">
                                <i class="bi bi-activity text-3xl text-blue-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Laboratorio</p>
                                <p class="text-xs text-gray-500 mt-1">Exámenes</p>
                            </div>
                        </label>

                        <label class="card card-hover p-4 cursor-pointer has-[:checked]:ring-2 has-[:checked]:ring-emerald-500 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="tipo" value="imagenologia" class="sr-only" required>
                            <div class="text-center">
                                <i class="bi bi-x-ray text-3xl text-emerald-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Imagenología</p>
                                <p class="text-xs text-gray-500 mt-1">Estudios</p>
                            </div>
                        </label>

                        <label class="card card-hover p-4 cursor-pointer has-[:checked]:ring-2 has-[:checked]:ring-amber-500 has-[:checked]:bg-amber-50">
                            <input type="radio" name="tipo" value="referencia" class="sr-only" required>
                            <div class="text-center">
                                <i class="bi bi-arrow-right-circle text-3xl text-amber-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Referencia</p>
                                <p class="text-xs text-gray-500 mt-1">Especialista</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Patient Selection -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Información del Paciente
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label form-label-required">Paciente</label>
                            <select name="paciente_id" class="form-select" required>
                                <option value="">Seleccionar paciente...</option>
                                @foreach($pacientes ?? [] as $paciente)
                                <option value="{{ $paciente->id }}" {{ request('paciente') == $paciente->id ? 'selected' : '' }}>
                                    {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }} - {{ $paciente->cedula }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        @if(request('cita'))
                        <input type="hidden" name="cita_id" value="{{ request('cita') }}">
                        <div class="md:col-span-2">
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <p class="text-sm font-semibold text-blue-900">
                                    <i class="bi bi-info-circle"></i> Orden asociada a una cita médica
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Receta Content (Visible when receta is selected) -->
                <div id="recetaContent" class="orden-content">
                    <div class="card p-6">
                        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bi bi-prescription text-purple-600"></i>
                            Detalles de la Receta
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="form-label form-label-required">Medicamento</label>
                                <input type="text" name="medicamento" class="input" placeholder="Nombre del medicamento">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label form-label-required">Dosis</label>
                                    <input type="text" name="dosis" class="input" placeholder="500mg, 10ml, etc.">
                                </div>
                                <div>
                                    <label class="form-label form-label-required">Frecuencia</label>
                                    <input type="text" name="frecuencia" class="input" placeholder="Cada 8 horas">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label form-label-required">Duración</label>
                                    <input type="text" name="duracion" class="input" placeholder="7 días">
                                </div>
                                <div>
                                    <label class="form-label">Vía de Administración</label>
                                    <select name="via_administracion" class="form-select">
                                        <option value="oral">Oral</option>
                                        <option value="intravenosa">Intravenosa</option>
                                        <option value="intramuscular">Intramuscular</option>
                                        <option value="topica">Tópica</option>
                                        <option value="subcutanea">Subcutánea</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Instrucciones Especiales</label>
                                <textarea name="instrucciones" rows="2" class="form-textarea" placeholder="Tomar con alimentos, antes de dormir, etc."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laboratorio Content -->
                <div id="laboratorioContent" class="orden-content hidden">
                    <div class="card p-6">
                        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bi bi-activity text-blue-600"></i>
                            Órdenes de Laboratorio
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Exámenes Solicitados</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                        <input type="checkbox" name="examenes[]" value="hemograma" class="form-checkbox">
                                        <span class="text-sm">Hemograma Completo</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                        <input type="checkbox" name="examenes[]" value="glicemia" class="form-checkbox">
                                        <span class="text-sm">Glicemia</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                        <input type="checkbox" name="examenes[]" value="creatinina" class="form-checkbox">
                                        <span class="text-sm">Creatinina</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                        <input type="checkbox" name="examenes[]" value="perfil_lipidico" class="form-checkbox">
                                        <span class="text-sm">Perfil Lipídico</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                        <input type="checkbox" name="examenes[]" value="orina" class="form-checkbox">
                                        <span class="text-sm">Examen de Orina</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                        <input type="checkbox" name="examenes[]" value="heces" class="form-checkbox">
                                        <span class="text-sm">Examen de Heces</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Otros Exámenes</label>
                                <textarea name="otros_examenes" rows="2" class="form-textarea" placeholder="Especificar otros exámenes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Imagenología Content -->
                <div id="imagenologiaContent" class="orden-content hidden">
                    <div class="card p-6">
                        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bi bi-x-ray text-emerald-600"></i>
                            Estudios de Imagenología
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="form-label form-label-required">Tipo de Estudio</label>
                                <select name="tipo_estudio" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="radiografia">Radiografía</option>
                                    <option value="ecografia">Ecografía</option>
                                    <option value="tomografia">Tomografía</option>
                                    <option value="resonancia">Resonancia Magnética</option>
                                    <option value="mamografia">Mamografía</option>
                                    <option value="densitometria">Densitometría Ósea</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label form-label-required">Área/Región</label>
                                <input type="text" name="region" class="input" placeholder="Ej: Tórax, Abdomen, Rodilla derecha">
                            </div>

                            <div>
                                <label class="form-label">Indicaciones Clínicas</label>
                                <textarea name="indicaciones_clinicas" rows="2" class="form-textarea" placeholder="Motivo del estudio..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Referencia Content -->
                <div id="referenciaContent" class="orden-content hidden">
                    <div class="card p-6">
                        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bi bi-arrow-right-circle text-amber-600"></i>
                            Referencia Médica
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="form-label form-label-required">Especialidad</label>
                                <select name="especialidad_referencia" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="cardiologia">Cardiología</option>
                                    <option value="neurologia">Neurología</option>
                                    <option value="traumatologia">Traumatología</option>
                                    <option value="gastroenterologia">Gastroenterología</option>
                                    <option value="dermatologia">Dermatología</option>
                                    <option value="psiquiatria">Psiquiatría</option>
                                    <option value="oftalmologia">Oftalmología</option>
                                    <option value="otros">Otra</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label form-label-required">Motivo de Referencia</label>
                                <textarea name="motivo_referencia" rows="3" class="form-textarea" placeholder="Describir el motivo de la referencia..." required></textarea>
                            </div>

                            <div>
                                <label class="form-label">Prioridad</label>
                                <select name="prioridad" class="form-select">
                                    <option value="normal">Normal</option>
                                    <option value="urgente">Urgente</option>
                                    <option value="muy_urgente">Muy Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observaciones Generales -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-sticky text-gray-600"></i>
                        Observaciones
                    </h3>

                    <div>
                        <label class="form-label">Notas Adicionales</label>
                        <textarea name="observaciones" rows="3" class="form-textarea" placeholder="Información adicional relevante..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Crear Orden
                        </button>
                        <a href="{{ url('index.php/ordenes-medicas') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Help -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">
                        <i class="bi bi-info-circle text-blue-600"></i> Guía Rápida
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Selecciona el tipo de orden a crear</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Elige el paciente</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Completa la información requerida</p>
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
        const tipoRadios = document.querySelectorAll('input[name="tipo"]');
        const contents = document.querySelectorAll('.orden-content');
        
        function showContent(tipo) {
            contents.forEach(content => {
                content.classList.add('hidden');
            });
            
            const targetContent = document.getElementById(tipo + 'Content');
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        }
        
        tipoRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                showContent(this.value);
            });
        });
        
        // Show initial content
        const checkedRadio = document.querySelector('input[name="tipo"]:checked');
        if (checkedRadio) {
            showContent(checkedRadio.value);
        }
    });
</script>
@endpush
@endsection
