@extends('emails.layout')

@section('title', 'Nueva Factura Generada')

@section('content')
<h1 class="email-title">📄 Nueva Factura Generada</h1>

<div class="email-content">
    <p>Hola <strong>{{ $factura->cita->paciente->primer_nombre }} {{ $factura->cita->paciente->primer_apellido }}</strong>,</p>
    
    <p>Se ha generado una nueva factura por tu consulta médica. A continuación los detalles:</p>
</div>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Número de Factura</span>
        <span class="info-value">{{ $factura->numero_factura }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Fecha de Emisión</span>
        <span class="info-value">{{ \Carbon\Carbon::parse($factura->fecha_factura)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Médico</span>
        <span class="info-value">Dr. {{ $factura->cita->medico->primer_nombre }} {{ $factura->cita->medico->primer_apellido }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Especialidad</span>
        <span class="info-value">{{ $factura->cita->especialidad->nombre_especialidad }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Total a Pagar</span>
        <span class="info-value" style="color: #3B82F6; font-size: 20px; font-weight: bold;">${{ number_format($factura->monto_total, 2) }}</span>
    </div>
</div>

<div class="email-content">
    <p><strong>Desglose de la Factura:</strong></p>
</div>

<div class="info-card" style="background-color: #F9FAFB;">
    <div class="info-row">
        <span class="info-label">Honorarios Profesionales</span>
        <span class="info-value">${{ number_format($factura->honorarios ?? $factura->monto_total * 0.8, 2) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Servicios Médicos</span>
        <span class="info-value">${{ number_format($factura->servicios ?? $factura->monto_total * 0.2, 2) }}</span>
    </div>
    @if(isset($factura->descuento) && $factura->descuento > 0)
    <div class="info-row">
        <span class="info-label">Descuento</span>
        <span class="info-value" style="color: #10B981;">-${{ number_format($factura->descuento, 2) }}</span>
    </div>
    @endif
</div>

<div class="alert alert-info">
    <strong>💳 Métodos de pago disponibles:</strong><br>
    Transferencia bancaria, Pago móvil, Tarjeta de débito/crédito
</div>

<center>
    <a href="{{ route('facturacion.show', $factura->id) }}" class="btn-primary">Ver Factura Completa</a>
    <br>
    <a href="{{ route('pagos.create', ['factura' => $factura->id]) }}" class="btn-secondary">Realizar Pago</a>
</center>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px;">
        Puedes descargar tu factura en formato PDF desde tu panel de usuario.
    </p>
</div>
@endsection
