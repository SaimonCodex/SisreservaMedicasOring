<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Órdenes Médicas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
            padding: 15mm;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20pt;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 9pt;
        }
        .filters {
            background: #f3f4f6;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        .filters strong {
            color: #4b5563;
        }
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f9fafb;
            padding: 12px;
            border-left: 3px solid #2563eb;
            text-align: center;
        }
        .stat-box .value {
            font-size: 18pt;
            font-weight: bold;
            color: #1e40af;
        }
        .stat-box .label {
            font-size: 8pt;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background: #1e40af;
            color: white;
        }
        table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9pt;
        }
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-receta { background: #e9d5ff; color: #6b21a8; }
        .badge-laboratorio { background: #d1fae5; color: #065f46; }
        .badge-imagenologia { background: #fef3c7; color: #92400e; }
        .badge-referencia { background: #fee2e2; color: #991b1b; }
        .badge-interconsulta { background: #dbeafe; color: #1e40af; }
        .badge-procedimiento { background: #f3e8ff; color: #6b21a8; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8pt;
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
        <h1>Reporte de Órdenes Médicas</h1>
        <p>Sistema de Reservas Médicas</p>
        <p>Generado: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Filters Applied -->
    <div class="filters">
        <strong>Período:</strong> {{ \Carbon\Carbon::parse($request->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($request->fecha_fin)->format('d/m/Y') }}
        @if($request->tipo_orden)
        <br><strong>Tipo de Orden:</strong> {{ $request->tipo_orden }}
        @endif
    </div>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-box">
            <div class="value">{{ $ordenes->count() }}</div>
            <div class="label">Total Órdenes</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $ordenes->where('tipo_orden', 'Receta')->count() }}</div>
            <div class="label">Recetas</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $ordenes->where('tipo_orden', 'Laboratorio')->count() }}</div>
            <div class="label">Laboratorios</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $ordenes->where('tipo_orden', 'Imagenologia')->count() }}</div>
            <div class="label">Imagenología</div>
        </div>
    </div>

    <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ordenes as $orden)
            <tr>
                <td>{{ $orden->fecha_emision ? \Carbon\Carbon::parse($orden->fecha_emision)->format('d/m/Y') : 'N/A' }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($orden->tipo_orden) }}">
                        {{ $orden->tipo_orden }}
                    </span>
                </td>
                <td>
                    {{ $orden->cita->paciente->nombre_completo ?? 'N/A' }}<br>
                    <small style="color: #6b7280;">{{ $orden->cita->paciente->cedula ?? 'N/A' }}</small>
                </td>
                <td>
                    Dr. {{ $orden->medico->nombre_completo ?? 'N/A' }}<br>
                    <small style="color: #6b7280;">{{ $orden->cita->especialidad->nombre ?? 'N/A' }}</small>
                </td>
                <td>
                    {{ Str::limit($orden->descripcion_detallada, 60) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; color: #6b7280;">
                    No se encontraron órdenes médicas en el período seleccionado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Detailed Breakdown by Type -->
    @if($ordenes->isNotEmpty())
    <div class="page-break"></div>
    
    <h2 style="color: #1e40af; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
        Desglose Detallado por Tipo
    </h2>

    @foreach(['Receta', 'Laboratorio', 'Imagenologia', 'Referencia', 'Interconsulta', 'Procedimiento'] as $tipo)
        @php
            $ordenesTipo = $ordenes->where('tipo_orden', $tipo);
        @endphp
        
        @if($ordenesTipo->isNotEmpty())
        <h3 style="color: #374151; margin-top: 20px; margin-bottom: 10px;">
            {{ $tipo }} ({{ $ordenesTipo->count() }} {{ Str::plural('orden', $ordenesTipo->count()) }})
        </h3>
        
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordenesTipo as $orden)
                <tr>
                    <td>{{ $orden->fecha_emision ? \Carbon\Carbon::parse($orden->fecha_emision)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $orden->cita->paciente->nombre_completo ?? 'N/A' }}</td>
                    <td>Dr. {{ $orden->medico->nombre_completo ?? 'N/A' }}</td>
                    <td>{{ $orden->descripcion_detallada }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    @endforeach
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Este reporte es confidencial y de uso exclusivo médico-administrativo.</p>
        <p>Sistema de Reservas Médicas - {{ \Carbon\Carbon::now()->year }}</p>
    </div>
</body>
</html>
