@extends('layouts.paciente')

@section('title', 'Agendar Nueva Cita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/paciente/citas') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Agendar Nueva Cita</h1>
            <p class="text-gray-600 mt-1">Solicita tu consulta médica</p>
        </div>
    </div>

    <form action="{{ url('index.php/paciente/citas') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
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

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="input" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                            <p class="form-help">Seleccione la fecha deseada para su cita</p>
                        </div>

                        <div id="horariosDisponibles" class="hidden">
                            <label class="form-label form-label-required">Horario Disponible</label>
                            <div class="grid grid-cols-3 gap-3" id="horariosContainer">
                                <!-- Horarios se cargarán dinámicamente -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultorio -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-building text-purple-600"></i>
                        Consultorio
                    </h3>

                    <div>
                        <label class="form-label form-label-required">Seleccionar Consultorio</label>
                        <select name="consultorio_id" class="form-select" required disabled id="consultorio_id">
                            <option value="">Primero seleccione médico y fecha...</option>
                        </select>
                    </div>
                </div>

                <!-- Motivo -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-chat-left-text text-amber-600"></i>
                        Motivo de Consulta
                    </h3>

                    <div>
                        <label class="form-label form-label-required">Describa el motivo de su consulta</label>
                        <textarea name="motivo" rows="4" class="form-textarea" placeholder="Describa brevemente los síntomas o motivo de su consulta..." required></textarea>
                        <p class="form-help">Proporcione detalles que ayuden al médico a preparar mejor su atención</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Resumen -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Resumen de Cita</h3>
                    <div class="space-y-3 text-sm">
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
                        <div class="p-3 bg-amber-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Consultorio</p>
                            <p class="font-semibold text-gray-900" id="resumen-consultorio">-</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Confirmar Cita
                        </button>
                        <a href="{{ url('index.php/paciente/citas') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
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
    // Actualizar resumen en tiempo real
    document.getElementById('especialidad_id')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('resumen-especialidad').textContent = selectedOption.text || '-';
        
        // Habilitar select de médico
        document.getElementById('medico_id').disabled = false;
        // Aquí iría la lógica AJAX para cargar médicos por especialidad
    });

    document.getElementById('medico_id')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('resumen-medico').textContent = selectedOption.text || '-';
    });

    document.getElementById('fecha')?.addEventListener('change', function() {
        const fecha = this.value;
        if (fecha) {
            const formattedDate = new Date(fecha).toLocaleDateString('es-ES', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('resumen-fecha').textContent = formattedDate;
            
            // Mostrar horarios disponibles
            document.getElementById('horariosDisponibles').classList.remove('hidden');
            document.getElementById('consultorio_id').disabled = false;
        }
    });

    document.querySelector('select[name="consultorio_id"]')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('resumen-consultorio').textContent = selectedOption.text || '-';
    });
</script>
@endpush
@endsection
