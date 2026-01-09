@extends('layouts.admin')

@section('title', 'Detalle de Evolución')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/shared/historia-clinica/evoluciones/' . $cita->paciente_id) }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Detalle de Evolución</h1>
                <p class="text-gray-600 mt-1">Cita #{{ $cita->id }} • {{ $evolucion->created_at->format('d/m/Y h:i A') }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ url('index.php/shared/historia-clinica/evoluciones/edit/' . $cita->id) }}" class="btn btn-outline">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>
    </div>

    <!-- Signos Vitales -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
        <div class="card p-3 text-center bg-blue-50 border-blue-200">
            <span class="text-xs text-blue-600 uppercase font-bold">T. Sistólica</span>
            <p class="text-lg font-bold text-gray-900">{{ $evolucion->tension_sistolica ?? '--' }}</p>
            <span class="text-xs text-gray-500">mmHg</span>
        </div>
        <div class="card p-3 text-center bg-blue-50 border-blue-200">
            <span class="text-xs text-blue-600 uppercase font-bold">T. Diastólica</span>
            <p class="text-lg font-bold text-gray-900">{{ $evolucion->tension_diastolica ?? '--' }}</p>
            <span class="text-xs text-gray-500">mmHg</span>
        </div>
        <div class="card p-3 text-center bg-rose-50 border-rose-200">
            <span class="text-xs text-rose-600 uppercase font-bold">F. Cardíaca</span>
            <p class="text-lg font-bold text-gray-900">{{ $evolucion->frecuencia_cardiaca ?? '--' }}</p>
            <span class="text-xs text-gray-500">bpm</span>
        </div>
        <div class="card p-3 text-center bg-amber-50 border-amber-200">
            <span class="text-xs text-amber-600 uppercase font-bold">Temperatura</span>
            <p class="text-lg font-bold text-gray-900">{{ $evolucion->temperatura_c ?? '--' }}</p>
            <span class="text-xs text-gray-500">°C</span>
        </div>
        <div class="card p-3 text-center bg-gray-50 border-gray-200">
            <span class="text-xs text-gray-600 uppercase font-bold">Peso</span>
            <p class="text-lg font-bold text-gray-900">{{ $evolucion->peso_kg ?? '--' }}</p>
            <span class="text-xs text-gray-500">kg</span>
        </div>
        <div class="card p-3 text-center bg-gray-50 border-gray-200">
            <span class="text-xs text-gray-600 uppercase font-bold">Talla</span>
            <p class="text-lg font-bold text-gray-900">{{ $evolucion->talla_cm ?? '--' }}</p>
            <span class="text-xs text-gray-500">cm</span>
        </div>
        <div class="card p-3 text-center bg-emerald-50 border-emerald-200">
            <span class="text-xs text-emerald-600 uppercase font-bold">IMC</span>
            <p class="text-lg font-bold text-gray-900">{{ number_format($evolucion->imc, 1) ?? '--' }}</p>
            <span class="text-xs text-gray-500">kg/m²</span>
        </div>
        <div class="card p-3 text-center bg-cyan-50 border-cyan-200">
            <span class="text-xs text-cyan-600 uppercase font-bold">Sat O2</span>
            <p class="text-lg font-bold text-gray-900">{{ $evolucion->saturacion_oxigeno ?? '--' }}</p>
            <span class="text-xs text-gray-500">%</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Evaluación Clínica</h3>
                
                <div class="space-y-6">
                    <div>
                        <h4 class="font-semibold text-blue-700 mb-1">Motivo de Consulta</h4>
                        <p class="text-gray-800">{{ $evolucion->motivo_consulta }}</p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-blue-700 mb-1">Enfermedad Actual</h4>
                        <p class="text-gray-800 whitespace-pre-line">{{ $evolucion->enfermedad_actual }}</p>
                    </div>

                    @if($evolucion->examen_fisico)
                    <div>
                        <h4 class="font-semibold text-blue-700 mb-1">Examen Físico</h4>
                        <p class="text-gray-800 whitespace-pre-line">{{ $evolucion->examen_fisico }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Resolución</h3>
                
                <div class="space-y-6">
                    <div>
                        <h4 class="font-semibold text-emerald-700 mb-1">Diagnóstico</h4>
                        <div class="p-4 bg-emerald-50 rounded-lg text-emerald-900 whitespace-pre-line">
                            {{ $evolucion->diagnostico }}
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-blue-700 mb-1">Plan / Tratamiento</h4>
                        <div class="p-4 bg-blue-50 rounded-lg text-blue-900 whitespace-pre-line">
                            {{ $evolucion->tratamiento }}
                        </div>
                    </div>

                    @if($evolucion->recomendaciones)
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-1">Recomendaciones</h4>
                        <p class="text-gray-800 whitespace-pre-line">{{ $evolucion->recomendaciones }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Detalles del Profesional</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                        <i class="bi bi-person-fill text-gray-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">Dr. {{ $cita->medico->nombre_completo }}</p>
                        <p class="text-sm text-gray-600">{{ $cita->especialidad->nombre }}</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 text-sm text-gray-500">
                    <p>Licencia Médica: {{ $cita->medico->licencia_medica ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Recursos Asociados</h3>
                <div class="space-y-2">
                    @if($cita->ordenesMedicas->count() > 0)
                    <a href="{{ url('index.php/medico/ordenes-medicas?cita_id=' . $cita->id) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <span class="font-medium text-gray-700">Órdenes Médicas</span>
                        <span class="badge badge-info">{{ $cita->ordenesMedicas->count() }}</span>
                    </a>
                    @endif
                    
                    <a href="{{ url('index.php/medico/ordenes-medicas/create?cita_id=' . $cita->id) }}" class="btn btn-outline w-full justify-start text-sm">
                        <i class="bi bi-plus-lg mr-2"></i> Crear Orden Médica
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
