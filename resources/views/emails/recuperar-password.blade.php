@extends('emails.layout')

@section('content')
<tr>
    <td style="padding: 40px 30px;">
        <!-- Icon Header -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); border-radius: 50%; padding: 20px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                <i class="bi bi-key-fill" style="font-size: 40px; color: white; line-height: 40px;"></i>
            </div>
        </div>

        <!-- Title -->
        <h1 style="color: #1e293b; font-size: 28px; font-weight: 700; margin: 0 0 20px 0; text-align: center;">
            Recuperar Contraseña
        </h1>

        <!-- Message -->
        <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0;">
            Hola <strong>{{ $usuario->nombre_completo }}</strong>,
        </p>

        <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0;">
            Recibimos una solicitud para restablecer la contraseña de tu cuenta en el <strong>Sistema de Reservas Médicas</strong>. Si no solicitaste esto, puedes ignorar este correo.
        </p>

        <!-- Action Button -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}" 
               style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                Restablecer Contraseña
            </a>
        </div>

        <!-- Info Box -->
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0; border-radius: 8px;">
            <p style="color: #92400e; font-size: 14px; line-height: 1.6; margin: 0;">
                <strong>⏰ Este enlace expirará en 60 minutos</strong><br>
                Por tu seguridad, este enlace es temporal. Si expira, deberás solicitar uno nuevo.
            </p>
        </div>

        <!-- Security Tips -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-top: 30px;">
            <h4 style="color: #1e293b; margin: 0 0 10px 0; font-size: 14px;">Consejos de Seguridad:</h4>
            <ul style="color: #64748b; font-size: 13px; margin: 0; padding-left: 20px;">
                <li>Usa una contraseña que no repitas en otros sitios.</li>
                <li>Incluye letras (mayúsculas/minúsculas), números y símbolos.</li>
                <li>Nunca compartas tu información de acceso.</li>
            </ul>
        </div>

        <!-- Trouble Link -->
        <p style="color: #94a3b8; font-size: 12px; margin-top: 30px; text-align: center;">
            ¿Problemas con el botón? Copia y pega esta URL en tu navegador:<br>
            <span style="display: block; margin-top: 5px; color: #3b82f6;">{{ $resetUrl }}</span>
        </p>
    </td>
</tr>
@endsection
