<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Representantes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        .info-section {
            background: #F3F4F6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .info-card {
            background: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            border-left: 3px solid #3B82F6;
        }
        .info-card .label {
            font-size: 9px;
            color: #6B7280;
            margin-bottom: 5px;
        }
        .info-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #1F2937;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #3B82F6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background: #3B82F6;
            color: white;
        }
        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #E5E7EB;
        }
        tbody tr:nth-child(even) {
            background: #F9FAFB;
        }
        tbody tr:hover {
            background: #EFF6FF;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-info {
            background: #DBEAFE;
            color: #1E40AF;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Representantes</h1>
        <p>Sistema de Reservas Médicas</p>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-card">
                <div class="label">Total Representantes</div>
                <div class="value">{{ $representantes->count() }}</div>
            </div>
            <div class="info-card">
                <div class="label">Tipos de Parentesco</div>
                <div class="value">{{ $representantes->pluck('parentesco')->unique()->count() }}</div>
            </div>
            <div class="info-card">
                <div class="label">Estados Diferentes</div>
                <div class="value">{{ $representantes->pluck('estado_id')->unique()->count() }}</div>
            </div>
            <div class="info-card">
                <div class="label">Promedio Pacientes</div>
                <div class="value">{{ number_format($representantes->avg(fn($r) => $r->pacientesEspeciales->count()), 1) }}</div>
            </div>
        </div>
    </div>

    <div class="section-title">Lista de Representantes</div>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Representante</th>
                <th>Documento</th>
                <th>Parentesco</th>
                <th>Contacto</th>
                <th>Pacientes</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($representantes as $index => $representante)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $representante->primer_nombre }} {{ $representante->primer_apellido }}</strong><br>
                    <small style="color: #6B7280;">{{ $representante->genero ?? 'N/A' }}</small>
                </td>
                <td>{{ $representante->tipo_documento }}-{{ $representante->numero_documento }}</td>
                <td>
                    <span class="badge badge-info">{{ $representante->parentesco ?? 'N/A' }}</span>
                </td>
                <td>
                    {{ $representante->prefijo_tlf }} {{ $representante->numero_tlf ?? 'N/A' }}
                </td>
                <td style="text-align: center;">
                    <strong>{{ $representante->pacientesEspeciales->count() }}</strong>
                </td>
                <td>{{ $representante->estado->estado ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Distribución por Parentesco</div>
    
    <table>
        <thead>
            <tr>
                <th>Parentesco</th>
                <th style="text-align: center;">Cantidad</th>
                <th style="text-align: right;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @php
                $porParentesco = $representantes->groupBy('parentesco')->map(fn($group) => $group->count());
                $total = $representantes->count();
            @endphp
            @foreach($porParentesco as $parentesco => $count)
            <tr>
                <td>{{ $parentesco ?: 'Sin especificar' }}</td>
                <td style="text-align: center;"><strong>{{ $count }}</strong></td>
                <td style="text-align: right;">{{ number_format(($count / $total) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Reservas Médicas - Reporte generado automáticamente</p>
        <p>Este documento es confidencial y solo debe ser usado para fines administrativos</p>
    </div>
</body>
</html>
