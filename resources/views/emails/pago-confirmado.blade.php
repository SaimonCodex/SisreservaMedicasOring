@extends('emails.layout')

@section('title', 'Pago Confirmado')

@section('content')
<h1 class="email-title">✅ ¡Pago Recibido y Confirmado!</h1>

<div class="email-content">
    <p>Hola <strong>{{ $pago->factura->cita->paciente->primer_nombre }} {{ $pago->factura->cita->paciente->primer_apellido }}</strong>,</p>
    
    <p>Hemos recibido tu pago exitosamente. A continuación los detalles de tu transacción:</p>
</div>

<div class="alert alert-success">
    <strong>✓ Pago verificado y procesado</strong><br>
    Tu pago ha sido confirmado por nuestro equipo.
</div>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Número de Pago</span>
        <span class="info-value">#{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Factura</span>
        <span class="info-value">{{ $pago->factura->numero_factura }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Fecha de Pago</span>
        <span class="info-value">{{ \Carbon\Carbon::parse($pago->fecha_pago)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Método de Pago</span>
        <span class="info-value">{{ $pago->metodo_pago }}</span>
    </div>
    @if($pago->numero_referencia)
    <div class="info-row">
        <span class="info-label">Número de Referencia</span>
        <span class="info-value">{{ $pago->numero_referencia }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">Monto Pagado</span>
        <span class="info-value" style="color: #10B981; font-size: 18px; font-weight: bold;">${{ number_format($pago->monto_pagado, 2) }}</span>
    </div>
</div>

<div class="email-content">
    <p><strong>Detalles de la Consulta:</strong></p>
</div>

<div class="info-card" style="background-color: #F0F9FF; border-left-color: #3B82F6;">
    <div class="info-row">
        <span class="info-label">Médico</span>
        <span class="info-value">Dr. {{ $pago->factura->cita->medico->primer_nombre }} {{ $pago->factura->cita->medico->primer_apellido }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Especialidad</span>
        <span class="info-value">{{ $pago->factura->cita->especialidad->nombre_especialidad }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Fecha de Consulta</span>
        <span class="info-value">{{ \Carbon\Carbon::parse($pago->factura->cita->fecha_cita)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
    </div>
</div>

<center>
    <a href="{{ route('pagos.show', $pago->id) }}" class="btn-primary">Ver Comprobante de Pago</a>
</center>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px;">
        Gracias por confiar en nosotros para el cuidado de tu salud.
    </p>
</div>
@endsection
