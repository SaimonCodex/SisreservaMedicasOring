@extends('emails.layout')

@section('title', 'Cita Confirmada')

@section('content')
<h1 class="email-title">✅ ¡Tu Cita ha sido Confirmada!</h1>

<div class="email-content">
    <p>Hola <strong>{{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}</strong>,</p>
    
    <p>Tu cita médica ha sido confirmada exitosamente. A continuación encontrarás todos los detalles:</p>
</div>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Fecha</span>
        <span class="info-value">{{ \Carbon\Carbon::parse($cita->fecha_cita)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Hora</span>
        <span class="info-value">{{ $cita->hora_inicio }} - {{ $cita->hora_fin }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Médico</span>
        <span class="info-value">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Especialidad</span>
        <span class="info-value">{{ $cita->especialidad->nombre_especialidad }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Consultorio</span>
        <span class="info-value">{{ $cita->consultorio->nombre ?? 'Por asignar' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Número de Cita</span>
        <span class="info-value">#{{ str_pad($cita->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
</div>

@if($cita->motivo_consulta)
<div class="alert alert-info">
    <strong>Motivo de consulta:</strong><br>
    {{ $cita->motivo_consulta }}
</div>
@endif

<div class="email-content">
    <p><strong>Recomendaciones importantes:</strong></p>
    <ul style="padding-left: 20px; margin-top: 10px;">
        <li>Llega con 15 minutos de anticipación</li>
        <li>Trae tu documento de identidad</li>
        <li>Si has tenido citas previas, trae los resultados de exámenes anteriores</li>
        <li>En caso de no poder asistir, por favor cancela tu cita con al menos 24 horas de anticipación</li>
    </ul>
</div>

<center>
    <a href="{{ route('citas.show', $cita->id) }}" class="btn-primary">Ver Detalles de la Cita</a>
</center>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px;">
        ¿Necesitas modificar o cancelar tu cita? 
        <a href="{{ route('citas.edit', $cita->id) }}" style="color: #3B82F6;">Haz clic aquí</a>
    </p>
</div>
@endsection
