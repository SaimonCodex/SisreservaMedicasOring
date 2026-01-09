@extends('emails.layout')

@section('title', 'Bienvenido a MediReserva')

@section('content')
<h1 class="email-title">🎉 ¡Bienvenido a MediReserva!</h1>

<div class="email-content">
    <p>Hola <strong>{{ $usuario->primer_nombre }} {{ $usuario->primer_apellido }}</strong>,</p>
    
    <p>¡Nos complace darte la bienvenida a <strong>MediReserva</strong>, tu plataforma de gestión de citas médicas!</p>
    
    <p>Tu cuenta ha sido creada exitosamente y ya puedes comenzar a disfrutar de todos nuestros servicios.</p>
</div>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Usuario</span>
        <span class="info-value">{{ $usuario->email }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Rol</span>
        <span class="info-value">{{ $usuario->rol->nombre_rol }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Fecha de Registro</span>
        <span class="info-value">{{ $usuario->created_at->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
    </div>
</div>

<div class="email-content">
    <p><strong>¿Qué puedes hacer con tu cuenta?</strong></p>
    <ul style="padding-left: 20px; margin-top: 10px;">
        <li>📅 Agendar citas médicas de forma rápida y sencilla</li>
        <li>👨‍⚕️ Consultar información de médicos y especialidades</li>
        <li>📊 Ver tu historial médico y resultados</li>
        <li>💳 Gestionar tus pagos y facturas</li>
        <li>🔔 Recibir recordatorios automáticos de tus citas</li>
    </ul>
</div>

<div class="alert alert-info">
    <strong>🔐 Seguridad de tu cuenta</strong><br>
    Te recomendamos verificar tu correo electrónico para activar todas las funcionalidades de tu cuenta.
</div>

<center>
    @if(!$usuario->email_verified_at)
    <a href="{{ route('verification.notice') }}" class="btn-primary">Verificar Correo Electrónico</a>
    <br>
    @endif
    <a href="{{ route('login') }}" class="btn-secondary">Iniciar Sesión</a>
</center>

<div class="email-content" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
    <p><strong>¿Necesitas ayuda?</strong></p>
    <p style="color: #6B7280; font-size: 14px;">
        Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar a nuestro equipo de soporte.
        Estamos aquí para ayudarte.
    </p>
</div>

<div class="alert alert-success" style="margin-top: 20px;">
    <strong>¡Gracias por confiar en nosotros!</strong><br>
    Estamos comprometidos con tu bienestar y salud.
</div>
@endsection
