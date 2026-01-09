@extends('layouts.medico')

@section('title', 'Registrar Resultados')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/ordenes-medicas/laboratorios') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Registrar Resultados de Laboratorio</h1>
            <p class="text-gray-600 mt-1">Ingreso de resultados de exámenes clínicos</p>
        </div>
    </div>

    <form action="{{ url('index.php/ordenes-medicas/registrar-resultados') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Info -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-600"></i>
                        Información de la Orden
                    </h3>

                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Paciente</p>
                                <p class="font-semibold text-gray-900">{{ $laboratorio->orden->paciente->primer_nombre ?? 'N/A' }} {{ $laboratorio->orden->paciente->primer_apellido ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Fecha de Orden</p>
                                <p class="font-semibold text-gray-900">
                                    {{ isset($laboratorio->created_at) ? \Carbon\Carbon::parse($laboratorio->created_at)->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 mb-2">Exámenes Solicitados</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(json_decode($laboratorio->examenes ?? '[]') ?? [] as $examen)
                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $examen)) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="laboratorio_id" value="{{ $laboratorio->id ?? request('laboratorio') }}">
                </div>

                <!-- Results Input -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-clipboard-data text-emerald-600"></i>
                        Resultados
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Resultados Textuales</label>
                            <textarea name="resultados" rows="6" class="form-textarea" placeholder="Ingrese los resultados de los exámenes..." required></textarea>
                            <p class="form-help">Detalle los valores y hallazgos de cada examen realizado</p>
                        </div>

                        <div>
                            <label class="form-label">Valores Normales de Referencia</label>
                            <textarea name="valores_referencia" rows="3" class="form-textarea" placeholder="Rango normal de cada valor..."></textarea>
                        </div>

                        <div>
                            <label class="form-label">Interpretación</label>
                            <textarea name="interpretacion" rows="3" class="form-textarea" placeholder="Interpretación clínica de los resultados..."></textarea>
                        </div>

                        <div>
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones_resultados" rows="2" class="form-textarea" placeholder="Notas adicionales..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-file-earmark-arrow-up text-purple-600"></i>
                        Adjuntar Documentos
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Archivo de Resultados (PDF, Imagen)</label>
                            <input type="file" name="archivo_resultados" class="input" accept=".pdf,.jpg,.jpeg,.png">
                            <p class="form-help">Formatos aceptados: PDF, JPG, PNG (Máx. 5MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle text-amber-600"></i>
                        Alertas
                    </h3>

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 bg-rose-50 rounded-lg cursor-pointer border border-rose-200">
                            <input type="checkbox" name="valores_criticos" value="1" class="form-checkbox">
                            <div>
                                <p class="font-semibold text-rose-900">Valores Críticos Detectados</p>
                                <p class="text-sm text-rose-700">Marcar si hay valores fuera de rango crítico</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 bg-amber-50 rounded-lg cursor-pointer border border-amber-200">
                            <input type="checkbox" name="requiere_seguimiento" value="1" class="form-checkbox">
                            <div>
                                <p class="font-semibold text-amber-900">Requiere Seguimiento</p>
                                <p class="text-sm text-amber-700">Los resultados requieren atención médica adicional</p>
                            </div>
                        </label>
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
                            Guardar Resultados
                        </button>
                        <a href="{{ url('index.php/ordenes-medicas/laboratorios') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Guidelines -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">
                        <i class="bi bi-info-circle text-blue-600"></i> Guía
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Incluya todos los valores medidos</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Especifique las unidades de medida</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Indique valores de referencia</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-check-circle text-emerald-600 mt-0.5"></i>
                            <p class="text-gray-700">Marque alertas si es necesario</p>
                        </div>
                    </div>
                </div>

                <!-- Warning -->
                <div class="card p-6">
                    <div class="flex gap-3">
                        <i class="bi bi-shield-check text-emerald-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Confidencialidad</h4>
                            <p class="text-sm text-gray-600">Los resultados son confidenciales y quedarán registrados en el expediente del paciente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
