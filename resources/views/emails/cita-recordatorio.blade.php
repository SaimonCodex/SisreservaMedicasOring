@extends('emails.layout')

@section('title', 'Recordatorio de Cita')

@section('content')
<h1 class="email-title">⏰ Recordatorio: Tu Cita es Mañana</h1>

<div class="email-content">
    <p>Hola <strong>{{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}</strong>,</p>
    
    <p>Te recordamos que tienes una cita médica programada para <strong>mañana</strong>.</p>
</div>

<div class="alert alert-warning">
    <strong>⏰ Tu cita es en menos de 24 horas</strong><br>
    Por favor confirma tu asistencia o cancela si no podrás asistir.
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
</div>

<div class="email-content">
    <p><strong>Recordatorios importantes:</strong></p>
    <ul style="padding-left: 20px; margin-top: 10px;">
        <li>✓ Llega 15 minutos antes de tu cita</li>
        <li>✓ Trae tu documento de identidad</li>
        <li>✓ Trae resultados de exámenes previos (si aplica)</li>
        <li>✓ Usa mascarilla durante tu visita</li>
    </ul>
</div>

<center>
    <a href="{{ route('citas.show', $cita->id) }}" class="btn-primary">Confirmar Asistencia</a>
    <br>
    <a href="{{ route('citas.cancelar', $cita->id) }}" class="btn-secondary">Cancelar Cita</a>
</center>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px; text-align: center;">
        Si tienes alguna pregunta, no dudes en contactarnos.
    </p>
</div>
@endsection
