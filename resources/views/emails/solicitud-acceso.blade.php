@extends('emails.layout')

@section('content')
<div style="text-align: center; margin-bottom: 25px;">
    <h1 style="color: #1a202c; font-size: 24px; font-weight: bold; margin-bottom: 15px;">Solicitud de Acceso</h1>
    <p style="color: #4a5568; font-size: 16px; line-height: 1.6;">
        El <strong>Dr. {{ $solicitud->medicoSolicitante->usuario->primer_nombre }} {{ $solicitud->medicoSolicitante->usuario->primer_apellido }}</strong> ha solicitado acceso para ver una evolución clínica
        @if($esParaRepresentante)
            de su representado <strong>{{ $nombrePaciente }}</strong>.
        @else
            de su historial médico.
        @endif
    </p>
</div>

<div style="background-color: #f7fafc; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #4fd1c5;">
    <p style="margin: 0; margin-bottom: 10px; color: #718096; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: bold;">Motivo</p>
    <p style="margin: 0; color: #2d3748; font-size: 16px;">{{ $solicitud->motivo_solicitud }}</p>
    
    @if($solicitud->observaciones)
    <p style="margin: 15px 0 10px 0; color: #718096; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: bold;">Observaciones</p>
    <p style="margin: 0; color: #2d3748; font-size: 16px; font-style: italic;">"{{ $solicitud->observaciones }}"</p>
    @endif
</div>

<div style="text-align: center; margin-bottom: 30px;">
    <p style="color: #4a5568; margin-bottom: 20px;">Por favor, ingrese al sistema para aprobar o rechazar esta solicitud.</p>
    <a href="{{ route('paciente.solicitudes') }}" style="background-color: #2b6cb0; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Revisar Solicitud</a>
</div>

<div style="text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px;">
    <p style="color: #718096; font-size: 14px;">Este enlace es seguro. Si usted no reconoce esta actividad, por favor contacte a soporte inmediatamente.</p>
</div>
@endsection
