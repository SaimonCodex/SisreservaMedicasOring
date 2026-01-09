@extends('emails.layout')

@section('title', 'Verifica tu Correo Electrónico')

@section('content')
<h1 class="email-title">✉️ Verifica tu Correo Electrónico</h1>

<div class="email-content">
    <p>Hola <strong>{{ $usuario->primer_nombre }} {{ $usuario->primer_apellido }}</strong>,</p>
    
    <p>Para completar tu registro en <strong>MediReserva</strong> y acceder a todas las funcionalidades, necesitamos verificar tu correo electrónico.</p>
    
    <p>Por favor, haz clic en el botón de abajo para confirmar tu dirección de correo:</p>
</div>

<center>
    <a href="{{ $urlVerificacion }}" class="btn-primary">Verificar Correo Electrónico</a>
</center>

<div class="alert alert-warning">
    <strong>⏰ Este enlace expirará en 60 minutos</strong><br>
    Si no verificas tu correo dentro de este tiempo, deberás solicitar un nuevo enlace de verificación.
</div>

<div class="email-content">
    <p style="color: #6B7280; font-size: 14px;">
        Si no creaste una cuenta en MediReserva, puedes ignorar este correo de forma segura.
    </p>
</div>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px;">
        <strong>¿Problemas con el botón?</strong><br>
        Si el botón no funciona, copia y pega la siguiente URL en tu navegador:
    </p>
    <p style="background-color: #F3F4F6; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px; word-break: break-all; margin-top: 10px;">
        {{ $urlVerificacion }}
    </p>
</div>
@endsection
