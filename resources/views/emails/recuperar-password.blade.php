@extends('emails.layout')

@section('title', 'Recuperación de Contraseña')

@section('content')
<h1 class="email-title">🔐 Recuperación de Contraseña</h1>

<div class="email-content">
    <p>Hola <strong>{{ $usuario->primer_nombre }} {{ $usuario->primer_apellido }}</strong>,</p>
    
    <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>MediReserva</strong>.</p>
    
    <p>Haz clic en el botón de abajo para crear una nueva contraseña:</p>
</div>

<center>
    <a href="{{ $urlRecuperacion }}" class="btn-primary">Restablecer Contraseña</a>
</center>

<div class="alert alert-warning">
    <strong>⏰ Este enlace expirará en 60 minutos</strong><br>
    Por razones de seguridad, este enlace solo es válido por una hora.
</div>

<div class="alert alert-danger">
    <strong>⚠️ ¿No solicitaste este cambio?</strong><br>
    Si no fuiste tú quien solicitó restablecer la contraseña, ignora este correo. Tu contraseña permanecerá segura.
</div>

<div class="email-content">
    <p><strong>Consejos de seguridad:</strong></p>
    <ul style="padding-left: 20px; margin-top: 10px;">
        <li>Usa una contraseña única que no uses en otros sitios</li>
        <li>Tu contraseña debe tener al menos 8 caracteres</li>
        <li>Combina letras mayúsculas, minúsculas, números y símbolos</li>
        <li>Nunca compartas tu contraseña con nadie</li>
    </ul>
</div>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p style="color: #6B7280; font-size: 14px;">
        <strong>¿Problemas con el botón?</strong><br>
        Si el botón no funciona, copia y pega la siguiente URL en tu navegador:
    </p>
    <p style="background-color: #F3F4F6; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px; word-break: break-all; margin-top: 10px;">
        {{ $urlRecuperacion }}
    </p>
</div>

<div class="email-content" style="text-align: center; margin-top: 20px;">
    <p style="color: #6B7280; font-size: 12px;">
        Si necesitas ayuda adicional, contáctanos a través de nuestro equipo de soporte.
    </p>
</div>
@endsection
