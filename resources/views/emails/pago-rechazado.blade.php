@extends('emails.layout')

@section('title', 'Pago Rechazado')

@section('content')
<h1 class="email-title">⚠️ Problema con tu Pago</h1>

<div class="email-content">
    <p>Hola <strong>{{ $pago->factura->cita->paciente->primer_nombre }} {{ $pago->factura->cita->paciente->primer_apellido }}</strong>,</p>
    
    <p>Lamentamos informarte que no hemos podido verificar tu pago. Por favor revisa los detalles a continuación:</p>
</div>

<div class="alert alert-danger">
    <strong>Estado:</strong> Rechazado<br>
    @if(isset($motivo_rechazo))
    <strong>Motivo:</strong> {{ $motivo_rechazo }}
    @else
    <strong>Motivo:</strong> No se pudo verificar el comprobante de pago
    @endif
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
        <span class="info-label">Monto Pendiente</span>
        <span class="info-value" style="color: #EF4444; font-size: 18px; font-weight: bold;">${{ number_format($pago->factura->monto_total, 2) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Método Usado</span>
        <span class="info-value">{{ $pago->metodo_pago }}</span>
    </div>
</div>

<div class="email-content">
    <p><strong>¿Qué puedes hacer?</strong></p>
    <ul style="padding-left: 20px; margin-top: 10px;">
        <li>Verifica que los datos del comprobante sean correctos</li>
        <li>Asegúrate de que el monto coincida con el total de la factura</li>
        <li>Intenta realizar el pago nuevamente</li>
        <li>Si el problema persiste, contacta a nuestro equipo de soporte</li>
    </ul>
</div>

<center>
    <a href="{{ route('pagos.create') }}" class="btn-primary">Realizar Nuevo Pago</a>
    <br>
    <a href="{{ route('contacto') }}" class="btn-secondary">Contactar Soporte</a>
</center>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px;">
        Estamos aquí para ayudarte. No dudes en contactarnos si tienes alguna pregunta.
    </p>
</div>
@endsection
