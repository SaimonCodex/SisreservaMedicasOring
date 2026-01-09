@extends('emails.layout')

@section('title', $titulo ?? 'Notificación del Sistema')

@section('content')
<h1 class="email-title">{{ $icono ?? '🔔' }} {{ $titulo ?? 'Notificación' }}</h1>

<div class="email-content">
    @if(isset($destinatario))
    <p>Hola <strong>{{ $destinatario }}</strong>,</p>
    @endif
    
    {!! $mensaje !!}
</div>

@if(isset($tipo))
<div class="alert alert-{{ $tipo }}">
    {!! $alertaMensaje ?? 'Información importante' !!}
</div>
@endif

@if(isset($datos) && count($datos) > 0)
<div class="info-card">
    @foreach($datos as $label => $value)
    <div class="info-row">
        <span class="info-label">{{ $label }}</span>
        <span class="info-value">{{ $value }}</span>
    </div>
    @endforeach
</div>
@endif

@if(isset($urlAccion) && isset($textoBoton))
<center>
    <a href="{{ $urlAccion }}" class="btn-primary">{{ $textoBoton }}</a>
</center>
@endif

@if(isset($notaAdicional))
<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px;">
        {!! $notaAdicional !!}
    </p>
</div>
@endif
@endsection
