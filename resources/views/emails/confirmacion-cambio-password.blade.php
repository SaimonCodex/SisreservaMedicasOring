@extends('emails.layout')

@section('content')
<tr>
    <td style="padding: 40px 30px;">
        <!-- Icon Header -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; padding: 20px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                <i class="bi bi-shield-check" style="font-size: 40px; color: white; line-height: 40px;"></i>
            </div>
        </div>

        <!-- Title -->
        <h1 style="color: #1e293b; font-size: 28px; font-weight: 700; margin: 0 0 20px 0; text-align: center;">
            Contraseña Actualizada
        </h1>

        <!-- Message -->
        <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0;">
            Hola <strong>{{ $usuario->nombre_completo }}</strong>,
        </p>

        <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0;">
            Te confirmamos que tu contraseña ha sido <strong>restablecida exitosamente</strong> en el Sistema de Reservas Médicas.
        </p>

        <!-- Info Box -->
        <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; margin: 25px 0; border-radius: 8px;">
            <p style="color: #065f46; font-size: 14px; line-height: 1.6; margin: 0;">
                <strong>✓ Actualización Confirmada</strong><br>
                Fecha: {{ now()->format('d/m/Y H:i') }}<br>
                Ya puedes iniciar sesión con tu nueva contraseña.
            </p>
        </div>

        <!-- Security Alert -->
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0; border-radius: 8px;">
            <p style="color: #92400e; font-size: 14px; line-height: 1.6; margin: 0;">
                <strong>⚠ ¿No realizaste este cambio?</strong><br>
                Si no solicitaste este cambio de contraseña, por favor contacta inmediatamente con nuestro equipo de soporte para asegurar tu cuenta.
            </p>
        </div>

        <!-- Action Button -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('login') }}" 
               style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                Iniciar Sesión
            </a>
        </div>

        <!-- Footer Message -->
        <p style="color: #94a3b8; font-size: 14px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
            Gracias por confiar en nuestro sistema de salud.<br>
            <strong>Sistema de Reservas Médicas</strong>
        </p>
    </td>
</tr>
@endsection
