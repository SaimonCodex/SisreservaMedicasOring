<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exportación de Pacientes</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; font-size: 10px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h3>Listado de Pacientes Especiales</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Documento</th>
                <th>Tipo Condición</th>
                <th>Observaciones</th>
                <th>Representante</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pacientesEspeciales as $paciente)
            <tr>
                <td>{{ $paciente->id }}</td>
                <td>{{ $paciente->paciente->primer_nombre }} {{ $paciente->paciente->primer_apellido }}</td>
                <td>{{ $paciente->paciente->tipo_documento }}-{{ $paciente->paciente->numero_documento }}</td>
                <td>{{ $paciente->tipo }}</td>
                <td>{{ Str::limit($paciente->observaciones, 50) }}</td>
                <td>
                    @foreach($paciente->representantes as $rep)
                        {{ $rep->nombre_completo }}<br>
                    @endforeach
                </td>
                <td>{{ $paciente->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
