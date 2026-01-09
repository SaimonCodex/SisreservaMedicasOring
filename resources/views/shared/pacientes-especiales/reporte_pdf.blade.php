<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pacientes Especiales</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte de Pacientes Especiales</h2>
        <p>Fecha de Emisión: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Documento</th>
                <th>Condición</th>
                <th>Representante</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pacientesEspeciales as $paciente)
            <tr>
                <td>{{ $paciente->id }}</td>
                <td>{{ $paciente->paciente->primer_nombre }} {{ $paciente->paciente->primer_apellido }}</td>
                <td>{{ $paciente->paciente->numero_documento }}</td>
                <td>{{ $paciente->tipo }}</td>
                <td>
                    @if($paciente->representantes->isNotEmpty())
                        {{ $paciente->representantes->first()->nombre_completo }}
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                <td>{{ $paciente->status ? 'Activo' : 'Inactivo' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Reservas Médicas - Reporte Generado Automáticamente</p>
    </div>
</body>
</html>
