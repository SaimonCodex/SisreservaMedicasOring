<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Clínico - {{ $paciente->nombre_completo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            padding: 20mm;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20pt;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 10pt;
        }
        .patient-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .patient-info h2 {
            font-size: 14pt;
            color: #1e40af;
            margin-bottom: 10px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }
        .info-item {
            font-size: 10pt;
        }
        .info-item strong {
            color: #4b5563;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 13pt;
            color: #1e40af;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .section-content {
            padding-left: 10px;
            font-size: 10pt;
        }
        .evolution-item {
            background: #f9fafb;
            padding: 12px;
            margin-bottom: 15px;
            border-left: 3px solid #2563eb;
            page-break-inside: avoid;
        }
        .evolution-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: bold;
            color: #1e40af;
        }
        .evolution-meta {
            font-size: 9pt;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .field-label {
            font-weight: bold;
            color: #4b5563;
            display: block;
            margin-top: 8px;
            margin-bottom: 3px;
        }
        .field-value {
            color: #374151;
            margin-bottom: 5px;
        }
        .alert-box {
            background: #fee2e2;
            border: 2px solid #ef4444;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-box strong {
            color: #991b1b;
            display: block;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9pt;
            color: #6b7280;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Historial Clínico</h1>
        <p>Sistema de Reservas Médicas</p>
        <p>Generado: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Patient Information -->
    <div class="patient-info">
        <h2>Información del Paciente</h2>
        <div class="info-grid">
            <div class="info-item"><strong>Nombre:</strong> {{ $paciente->nombre_completo ?? 'N/A' }}</div>
            <div class="info-item"><strong>Cédula:</strong> {{ $paciente->cedula ?? 'N/A' }}</div>
            <div class="info-item"><strong>Edad:</strong> {{ $paciente->edad ?? 'N/A' }} años</div>
            <div class="info-item"><strong>Género:</strong> {{ ucfirst($paciente->genero ?? 'N/A') }}</div>
            <div class="info-item"><strong>Teléfono:</strong> {{ $paciente->telefono ?? 'N/A' }}</div>
            <div class="info-item"><strong>Email:</strong> {{ $paciente->usuario->email ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Allergies Alert -->
    @if($paciente->historiaClinicaBase && ($paciente->historiaClinicaBase->alergias || $paciente->historiaClinicaBase->alergias_medicamentos))
    <div class="alert-box">
        <strong>⚠️ ALERTA DE ALERGIAS</strong>
        @if($paciente->historiaClinicaBase->alergias)
        <p><strong>Alergias Generales:</strong> {{ $paciente->historiaClinicaBase->alergias }}</p>
        @endif
        @if($paciente->historiaClinicaBase->alergias_medicamentos)
        <p><strong>Alergias a Medicamentos:</strong> {{ $paciente->historiaClinicaBase->alergias_medicamentos }}</p>
        @endif
    </div>
    @endif

    <!-- Base Clinical History -->
    @if($paciente->historiaClinicaBase)
    <div class="section">
        <h2 class="section-title">Historia Clínica Base</h2>
        <div class="section-content">
            @if($paciente->historiaClinicaBase->tipo_sangre)
            <span class="field-label">Tipo de Sangre:</span>
            <span class="field-value">{{ $paciente->historiaClinicaBase->tipo_sangre }}</span>
            @endif

            @if($paciente->historiaClinicaBase->enfermedades_cronicas)
            <span class="field-label">Enfermedades Crónicas:</span>
            <p class="field-value">{{ $paciente->historiaClinicaBase->enfermedades_cronicas }}</p>
            @endif

            @if($paciente->historiaClinicaBase->antecedentes_personales)
            <span class="field-label">Antecedentes Personales:</span>
            <p class="field-value">{{ $paciente->historiaClinicaBase->antecedentes_personales }}</p>
            @endif

            @if($paciente->historiaClinicaBase->antecedentes_familiares)
            <span class="field-label">Antecedentes Familiares:</span>
            <p class="field-value">{{ $paciente->historiaClinicaBase->antecedentes_familiares }}</p>
            @endif

            @if($paciente->historiaClinicaBase->cirugias_previas)
            <span class="field-label">Cirugías Previas:</span>
            <p class="field-value">{{ $paciente->historiaClinicaBase->cirugias_previas }}</p>
            @endif

            @if($paciente->historiaClinicaBase->medicamentos_actuales)
            <span class="field-label">Medicamentos Actuales:</span>
            <p class="field-value">{{ $paciente->historiaClinicaBase->medicamentos_actuales }}</p>
            @endif

            @if($paciente->historiaClinicaBase->habitos)
            <span class="field-label">Hábitos Psicobiológicos:</span>
            <p class="field-value">{{ $paciente->historiaClinicaBase->habitos }}</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Clinical Evolutions -->
    @if($evoluciones && count($evoluciones) > 0)
    <div class="section">
        <h2 class="section-title">Evoluciones Clínicas ({{ count($evoluciones) }})</h2>
        @foreach($evoluciones as $evolucion)
        <div class="evolution-item">
            <div class="evolution-header">
                <span>{{ $evolucion->motivo_consulta ?? 'Consulta General' }}</span>
                <span>{{ $evolucion->created_at ? \Carbon\Carbon::parse($evolucion->created_at)->format('d/m/Y') : 'N/A' }}</span>
            </div>
            <div class="evolution-meta">
                Médico: Dr. {{ $evolucion->medico->nombre_completo ?? 'N/A' }} • 
                Especialidad: {{ $evolucion->cita->especialidad->nombre ?? 'N/A' }}
            </div>

            @if($evolucion->sintomas_actuales)
            <span class="field-label">Síntomas Actuales:</span>
            <p class="field-value">{{ $evolucion->sintomas_actuales }}</p>
            @endif

            @if($evolucion->examen_fisico)
            <span class="field-label">Examen Físico:</span>
            <p class="field-value">{{ $evolucion->examen_fisico }}</p>
            @endif

            @if($evolucion->diagnostico)
            <span class="field-label">Diagnóstico:</span>
            <p class="field-value">{{ $evolucion->diagnostico }}</p>
            @endif

            @if($evolucion->tratamiento)
            <span class="field-label">Tratamiento:</span>
            <p class="field-value">{{ $evolucion->tratamiento }}</p>
            @endif

            @if($evolucion->observaciones)
            <span class="field-label">Observaciones:</span>
            <p class="field-value">{{ $evolucion->observaciones }}</p>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="section">
        <h2 class="section-title">Evoluciones Clínicas</h2>
        <p class="section-content" style="color: #6b7280; font-style: italic;">No hay evoluciones clínicas registradas.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Este documento es confidencial y de uso exclusivo médico.</p>
        <p>Sistema de Reservas Médicas - {{ \Carbon\Carbon::now()->year }}</p>
    </div>
</body>
</html>
