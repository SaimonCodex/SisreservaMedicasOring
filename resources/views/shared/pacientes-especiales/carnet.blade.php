<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carnet de Paciente Especial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .carnet {
            width: 350px;
            height: 220px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .content {
            padding: 15px;
            font-size: 12px;
            color: #333;
        }
        .field {
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        .value {
            font-weight: bold;
            color: #000;
        }
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px;
            background: #f8fafc;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .photo-placeholder {
            position: absolute;
            top: 60px;
            right: 15px;
            width: 80px;
            height: 100px;
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #94a3b8;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        .badge-warning { background-color: #f59e0b; }
        .badge-danger { background-color: #ef4444; }
        .badge-info { background-color: #3b82f6; }
        .badge-purple { background-color: #8b5cf6; }
    </style>
</head>
<body>
    <div class="carnet">
        <div class="header">
            <div class="title">Sistema Médico<br>Paciente Especial</div>
        </div>
        
        <div class="photo-placeholder">
            FOTO
        </div>

        <div class="content">
            <div class="field">
                <div class="label">Paciente</div>
                <div class="value">{{ $pacienteEspecial->paciente->primer_nombre }} {{ $pacienteEspecial->paciente->primer_apellido }}</div>
            </div>
            
            <div class="field">
                <div class="label">Documento</div>
                <div class="value">{{ $pacienteEspecial->paciente->tipo_documento }}-{{ $pacienteEspecial->paciente->numero_documento }}</div>
            </div>

            <div class="field">
                <div class="label">Condición</div>
                <div class="value">
                    @php
                        $color = match($pacienteEspecial->tipo) {
                            'Menor de Edad' => 'info',
                            'Discapacitado' => 'purple',
                            'Anciano' => 'warning',
                            'Incapacitado' => 'danger',
                            default => 'info'
                        };
                    @endphp
                    <span class="badge badge-{{ $color }}">{{ $pacienteEspecial->tipo }}</span>
                </div>
            </div>
            
            <div class="field">
                <div class="label">N° Historia</div>
                <div class="value">{{ str_pad($pacienteEspecial->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <div class="footer">
            Este carnet es personal e intransferible. Válido solo para consumo interno.
        </div>
    </div>
</body>
</html>
