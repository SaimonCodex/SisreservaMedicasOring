@extends('layouts.paciente')

@section('title', 'Agendar Nueva Cita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('paciente.citas.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Agendar Nueva Cita</h1>
            <p class="text-gray-600 mt-1">Solicita tu consulta médica</p>
        </div>
    </div>

    <!-- Paso 1: Tipo de Cita -->
    <div id="step-tipo" class="card p-6">
        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-person-check text-blue-600"></i>
            ¿Para quién es esta cita?
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button type="button" id="btn-propia" onclick="selectTipoCita('propia')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="bi bi-person-fill text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita Propia</h4>
                        <p class="text-sm text-gray-600">La cita es para mí mismo</p>
                    </div>
                </div>
            </button>
            
            <button type="button" id="btn-terceros" onclick="selectTipoCita('terceros')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <i class="bi bi-people-fill text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita para Terceros</h4>
                        <p class="text-sm text-gray-600">Agendar para otra persona</p>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Formulario Principal -->
    <form action="{{ route('paciente.citas.store') }}" method="POST" id="citaForm" class="space-y-6 hidden">
        @csrf
        <input type="hidden" name="tipo_cita" id="tipo_cita" value="">
        <input type="hidden" name="paciente_id" id="paciente_id" value="{{ $paciente->id ?? '' }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Datos del Paciente (Solo para Terceros) -->
                <div id="datos-tercero" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-vcard text-emerald-600"></i>
                        Datos del Paciente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Primer Nombre</label>
                            <input type="text" name="tercero_primer_nombre" class="input" placeholder="Nombre">
                        </div>
                        <div>
                            <label class="form-label">Segundo Nombre</label>
                            <input type="text" name="tercero_segundo_nombre" class="input" placeholder="Segundo nombre">
                        </div>
                        <div>
                            <label class="form-label form-label-required">Primer Apellido</label>
                            <input type="text" name="tercero_primer_apellido" class="input" placeholder="Apellido">
                        </div>
                        <div>
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" name="tercero_segundo_apellido" class="input" placeholder="Segundo apellido">
                        </div>
                        <div>
                            <label class="form-label form-label-required">Cédula</label>
                            <div class="flex gap-2">
                                <select name="tercero_tipo_documento" class="form-select w-20">
                                    <option value="V">V</option>
                                    <option value="E">E</option>
                                    <option value="P">P</option>
                                </select>
                                <input type="text" name="tercero_numero_documento" class="input flex-1" placeholder="12345678" maxlength="12">
                            </div>
                        </div>
                        <div>
                            <label class="form-label form-label-required">Teléfono</label>
                            <div class="flex gap-2">
                                <select name="tercero_prefijo_tlf" class="form-select w-24">
                                    <option value="+58">+58</option>
                                    <option value="+57">+57</option>
                                    <option value="+1">+1</option>
                                </select>
                                <input type="tel" name="tercero_numero_tlf" class="input flex-1" placeholder="4121234567">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Datos Propios (Preview, solo lectura) -->
                <div id="datos-propios" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-check text-blue-600"></i>
                        Mis Datos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Nombre Completo</p>
                            <p class="font-semibold text-gray-900">
                                {{ ($paciente->primer_nombre ?? '') . ' ' . ($paciente->segundo_nombre ?? '') . ' ' . ($paciente->primer_apellido ?? '') . ' ' . ($paciente->segundo_apellido ?? '') }}
                            </p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Cédula</p>
                            <p class="font-semibold text-gray-900">{{ ($paciente->tipo_documento ?? 'V') }}-{{ $paciente->numero_documento ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Teléfono</p>
                            <p class="font-semibold text-gray-900">{{ ($paciente->prefijo_tlf ?? '') }} {{ $paciente->numero_tlf ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Fecha Nacimiento</p>
                            <p class="font-semibold text-gray-900">{{ $paciente->fecha_nac ? \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Especialidad y Médico -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-hospital text-blue-600"></i>
                        Especialidad y Médico
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Especialidad</label>
                            <select name="especialidad_id" id="especialidad_id" class="form-select" required>
                                <option value="">Seleccionar especialidad...</option>
                                @foreach($especialidades ?? [] as $especialidad)
                                <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Médico</label>
                            <select name="medico_id" id="medico_id" class="form-select" required disabled>
                                <option value="">Primero seleccione una especialidad...</option>
                            </select>
                            <p class="form-help">Seleccione primero la especialidad para ver los médicos disponibles</p>
                        </div>
                    </div>
                </div>

                <!-- Fecha y Hora -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-emerald-600"></i>
                        Fecha y Hora
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Fecha</label>
                            <input type="date" name="fecha_cita" id="fecha_cita" class="input" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Hora</label>
                            <select name="hora_inicio" id="hora_inicio" class="form-select" required>
                                <option value="">Seleccionar hora...</option>
                                @for($h = 7; $h <= 18; $h++)
                                    <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                    <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Consultorio y Tipo -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-building text-purple-600"></i>
                        Consultorio y Tipo de Consulta
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Consultorio</label>
                            <select name="consultorio_id" id="consultorio_id" class="form-select">
                                <option value="">Seleccionar consultorio...</option>
                                @foreach($consultorios ?? [] as $consultorio)
                                <option value="{{ $consultorio->id }}">{{ $consultorio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="form-label form-label-required">Tipo de Consulta</label>
                            <select name="tipo_consulta" class="form-select" required>
                                <option value="Presencial">Presencial</option>
                                <option value="Telemedicina">Telemedicina</option>
                                <option value="Domicilio">Domicilio</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Motivo -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-chat-left-text text-amber-600"></i>
                        Motivo de Consulta
                    </h3>

                    <div>
                        <label class="form-label">Describa el motivo de su consulta</label>
                        <textarea name="motivo" rows="4" class="form-textarea" placeholder="Describa brevemente los síntomas o motivo de su consulta..."></textarea>
                        <p class="form-help">Proporcione detalles que ayuden al médico a preparar mejor su atención</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Resumen -->
                <div class="card p-6 sticky top-24">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Resumen de Cita</h3>
                    <div class="space-y-3 text-sm">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Tipo de Cita</p>
                            <p class="font-semibold text-gray-900" id="resumen-tipo">-</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Especialidad</p>
                            <p class="font-semibold text-gray-900" id="resumen-especialidad">-</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Médico</p>
                            <p class="font-semibold text-gray-900" id="resumen-medico">-</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Fecha y Hora</p>
                            <p class="font-semibold text-gray-900" id="resumen-fecha">-</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Confirmar Cita
                        </button>
                        <button type="button" onclick="resetForm()" class="btn btn-outline w-full">
                            <i class="bi bi-arrow-left"></i>
                            Volver
                        </button>
                    </div>
                </div>

                <!-- Info -->
                <div class="card p-6 bg-blue-50 border-blue-200">
                    <div class="flex gap-3">
                        <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Importante</h4>
                            <p class="text-sm text-gray-600">Recibirás una confirmación por email una vez que tu cita sea aprobada por el sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function selectTipoCita(tipo) {
        document.getElementById('tipo_cita').value = tipo;
        document.getElementById('step-tipo').classList.add('hidden');
        document.getElementById('citaForm').classList.remove('hidden');
        
        if (tipo === 'propia') {
            document.getElementById('datos-propios').classList.remove('hidden');
            document.getElementById('datos-tercero').classList.add('hidden');
            document.getElementById('resumen-tipo').textContent = 'Cita Propia';
            document.getElementById('btn-propia').classList.add('border-blue-500', 'bg-blue-50');
        } else {
            document.getElementById('datos-propios').classList.add('hidden');
            document.getElementById('datos-tercero').classList.remove('hidden');
            document.getElementById('resumen-tipo').textContent = 'Cita para Terceros';
            document.getElementById('btn-terceros').classList.add('border-emerald-500', 'bg-emerald-50');
        }
    }
    
    function resetForm() {
        document.getElementById('step-tipo').classList.remove('hidden');
        document.getElementById('citaForm').classList.add('hidden');
        document.getElementById('datos-propios').classList.add('hidden');
        document.getElementById('datos-tercero').classList.add('hidden');
        document.getElementById('btn-propia').classList.remove('border-blue-500', 'bg-blue-50');
        document.getElementById('btn-terceros').classList.remove('border-emerald-500', 'bg-emerald-50');
    }

    // Cargar médicos por especialidad
    document.getElementById('especialidad_id')?.addEventListener('change', async function() {
        const especialidadId = this.value;
        const medicoSelect = document.getElementById('medico_id');
        
        document.getElementById('resumen-especialidad').textContent = this.options[this.selectedIndex].text;
        
        if (!especialidadId) {
            medicoSelect.disabled = true;
            medicoSelect.innerHTML = '<option value="">Primero seleccione una especialidad...</option>';
            return;
        }
        
        medicoSelect.disabled = false;
        medicoSelect.innerHTML = '<option value="">Cargando médicos...</option>';
        
        try {
            const response = await fetch('{{ url("api/citas/medicos-por-especialidad") }}/' + especialidadId);
            const medicos = await response.json();
            
            medicoSelect.innerHTML = '<option value="">Seleccionar médico...</option>';
            medicos.forEach(medico => {
                const option = document.createElement('option');
                option.value = medico.id;
                option.textContent = medico.nombre;
                medicoSelect.appendChild(option);
            });
        } catch (e) {
            console.error('Error cargando médicos:', e);
            medicoSelect.innerHTML = '<option value="">Error al cargar médicos</option>';
        }
    });

    document.getElementById('medico_id')?.addEventListener('change', function() {
        document.getElementById('resumen-medico').textContent = this.options[this.selectedIndex].text || '-';
    });

    document.getElementById('fecha_cita')?.addEventListener('change', function() {
        const hora = document.getElementById('hora_inicio').value;
        updateResumenFecha(this.value, hora);
    });
    
    document.getElementById('hora_inicio')?.addEventListener('change', function() {
        const fecha = document.getElementById('fecha_cita').value;
        updateResumenFecha(fecha, this.value);
    });
    
    function updateResumenFecha(fecha, hora) {
        if (fecha) {
            const formattedDate = new Date(fecha + 'T12:00:00').toLocaleDateString('es-ES', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('resumen-fecha').textContent = formattedDate + (hora ? ' - ' + hora : '');
        }
    }
</script>
@endpush
@endsection
