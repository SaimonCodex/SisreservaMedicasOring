<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden Médica - {{ $orden->tipo_orden }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            padding: 20mm;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24pt;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 10pt;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14pt;
            color: #1e40af;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
            font-weight: bold;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item strong {
            color: #4b5563;
            display: block;
            margin-bottom: 3px;
        }
        .box {
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #2563eb;
            margin-bottom: 15px;
        }
        .box-warning {
            background: #fef3c7;
            border-left-color: #f59e0b;
        }
        .box-danger {
            background: #fee2e2;
            border-left-color: #ef4444;
        }
        .rx-symbol {
            font-size: 48pt;
            color: #2563eb;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .signature-area {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .signature-line {
            margin-top: 40px;
            padding-top: 2px;
            border-top: 1px solid #333;
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9pt;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ strtoupper($orden->tipo_orden) }}</h1>
        <p>Sistema de Reservas Médicas</p>
        <p>Fecha de Emisión: {{ $orden->fecha_emision ? \Carbon\Carbon::parse($orden->fecha_emision)->format('d/m/Y') : 'N/A' }}</p>
    </div>

    <!-- Información del Médico -->
    <div class="section">
        <h2 class="section-title">Médico</h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>Nombre:</strong>
                Dr. {{ $orden->medico->nombre_completo ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Especialidad:</strong>
                {{ $orden->cita->especialidad->nombre ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Teléfono:</strong>
                {{ $orden->medico->telefono ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Email:</strong>
                {{ $orden->medico->usuario->email ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Información del Paciente -->
    <div class="section">
        <h2 class="section-title">Paciente</h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>Nombre Completo:</strong>
                {{ $orden->cita->paciente->nombre_completo ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Cédula:</strong>
                {{ $orden->cita->paciente->cedula ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Edad:</strong>
                {{ isset($orden->cita->paciente->fecha_nacimiento) ? \Carbon\Carbon::parse($orden->cita->paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Teléfono:</strong>
                {{ $orden->cita->paciente->telefono ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Contenido según tipo de orden -->
    @if($orden->tipo_orden == 'Receta')
        <div class="rx-symbol">℞</div>
        
        <div class="section">
            <h2 class="section-title">Prescripción</h2>
            <div class="box">
                <strong>Descripción:</strong>
                <p style="margin-top: 10px;">{{ $orden->descripcion_detallada }}</p>
            </div>
            
            @if($orden->indicaciones)
            <div class="box box-warning">
                <strong>⚠️ Indicaciones Especiales:</strong>
                <p style="margin-top: 10px;">{{ $orden->indicaciones }}</p>
            </div>
            @endif
        </div>

    @elseif($orden->tipo_orden == 'Laboratorio')
        <div class="section">
            <h2 class="section-title">Exámenes de Laboratorio Solicitados</h2>
            <div class="box">
                <p>{{ $orden->descripcion_detallada }}</p>
            </div>
            
            @if($orden->indicaciones)
            <div class="box box-warning">
                <strong>Indicaciones para el paciente:</strong>
                <p style="margin-top: 10px;">{{ $orden->indicaciones }}</p>
            </div>
            @endif

            @if($orden->resultados)
            <div class="section">
                <h2 class="section-title">Resultados</h2>
                <div class="box">
                    <p>{{ $orden->resultados }}</p>
                </div>
            </div>
            @endif
        </div>

    @elseif($orden->tipo_orden == 'Imagenologia')
        <div class="section">
            <h2 class="section-title">Estudio de Imagenología</h2>
            <div class="box">
                <strong>Tipo de Estudio:</strong>
                <p style="margin-top: 10px;">{{ $orden->descripcion_detallada }}</p>
            </div>
            
            @if($orden->indicaciones)
            <div class="box box-warning">
                <strong>Indicaciones Clínicas:</strong>
                <p style="margin-top: 10px;">{{ $orden->indicaciones }}</p>
            </div>
            @endif
        </div>

    @elseif($orden->tipo_orden == 'Referencia')
        <div class="section">
            <h2 class="section-title">Referencia Médica</h2>
            <div class="box box-warning">
                <strong>Especialidad de Referencia:</strong>
                <p style="margin-top: 10px;">{{ $orden->descripcion_detallada }}</p>
            </div>
            
            @if($orden->indicaciones)
            <div class="box">
                <strong>Motivo de Referencia:</strong>
                <p style="margin-top: 10px;">{{ $orden->indicaciones }}</p>
            </div>
            @endif
        </div>

    @elseif($orden->tipo_orden == 'Interconsulta')
        <div class="section">
            <h2 class="section-title">Solicitud de Interconsulta</h2>
            <div class="box">
                <strong>Especialidad Solicitada:</strong>
                <p style="margin-top: 10px;">{{ $orden->descripcion_detallada }}</p>
            </div>
            
            @if($orden->indicaciones)
            <div class="box box-warning">
                <strong>Motivo de Interconsulta:</strong>
                <p style="margin-top: 10px;">{{ $orden->indicaciones }}</p>
            </div>
            @endif
        </div>

    @else
        <div class="section">
            <h2 class="section-title">Detalles del Procedimiento</h2>
            <div class="box">
                <p>{{ $orden->descripcion_detallada }}</p>
            </div>
            
            @if($orden->indicaciones)
            <div class="box box-warning">
                <strong>Indicaciones:</strong>
                <p style="margin-top: 10px;">{{ $orden->indicaciones }}</p>
            </div>
            @endif
        </div>
    @endif

    <!-- Validez -->
    @if($orden->fecha_vigencia)
    <div class="section">
        <div class="box box-danger">
            <strong>⏰ Vigencia de la Orden:</strong>
            <p style="margin-top: 5px;">Esta orden es válida hasta el {{ \Carbon\Carbon::parse($orden->fecha_vigencia)->format('d/m/Y') }}</p>
        </div>
    </div>
    @endif

    <!-- Firma -->
    <div class="signature-area">
        <div class="info-grid">
            <div>
                <div class="signature-line">
                    <strong>Dr. {{ $orden->medico->nombre_completo ?? 'N/A' }}</strong><br>
                    <smallReg. {{ $orden->medico->numero_registro ?? 'N/A' }}</small>
                </div>
            </div>
            <div style="text-align: right;">
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Este documento es de uso médico oficial.</p>
        <p>Sistema de Reservas Médicas - {{ \Carbon\Carbon::now()->year }}</p>
    </div>
</body>
</html>
