@extends('emails.layout')

@section('title', 'Cita Cancelada')

@section('content')
<h1 class="email-title">❌ Cita Cancelada</h1>

<div class="email-content">
    <p>Hola <strong>{{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}</strong>,</p>
    
    <p>Te confirmamos que tu cita médica ha sido <strong>cancelada</strong> exitosamente.</p>
</div>

<div class="alert alert-danger">
    <strong>Estado:</strong> Cancelada<br>
    <strong>Fecha de cancelación:</strong> {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY, h:mm A') }}
</div>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Fecha Original</span>
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
    @if(isset($motivo_cancelacion))
    <div class="info-row">
        <span class="info-label">Motivo</span>
        <span class="info-value">{{ $motivo_cancelacion }}</span>
    </div>
    @endif
</div>

<div class="email-content">
    <p>Si deseas programar una nueva cita, puedes hacerlo cuando lo necesites a través de nuestra plataforma.</p>
</div>

<center>
    <a href="{{ route('citas.create') }}" class="btn-primary">Agendar Nueva Cita</a>
</center>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px; text-align: center;">
        Esperamos poder atenderte pronto. Tu salud es nuestra prioridad.
    </p>
</div>
@endsection
