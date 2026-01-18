<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Bloqueada</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 40px 30px; text-align: center; }
        .header-icon { width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .header-icon svg { width: 40px; height: 40px; fill: white; }
        .header h1 { color: white; margin: 0; font-size: 28px; font-weight: 600; }
        .content { padding: 40px 30px; }
        .alert-box { background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .alert-box h2 { color: #991b1b; margin: 0 0 10px 0; font-size: 20px; }
        .alert-box p { color: #7f1d1d; margin: 0; line-height: 1.6; }
        .info-grid { background-color: #f9fafb; border-radius: 8px; padding: 20px; margin: 30px 0; }
        .info-item { margin-bottom: 15px; }
        .info-item:last-child { margin-bottom: 0; }
        .info-label { font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 5px; }
        .info-value { color: #6b7280; font-size: 14px; }
        .security-tips { background-color: #eff6ff; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .security-tips h3 { color: #1e40af; margin: 0 0 15px 0; font-size: 16px; }
        .security-tips ul { margin: 0; padding-left: 20px; color: #1e3a8a; }
        .security-tips li { margin-bottom: 8px; line-height: 1.5; }
        .button { display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; transition: transform 0.2s; }
        .button:hover { transform: translateY(-2px); }
        .footer { background-color: #f9fafb; padding: 30px; text-align: center; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb; }
        .footer p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
            </div>
            <h1>⚠️ Cuenta Bloqueada</h1>
        </div>

        <div class="content">
            <div class="alert-box">
                <h2>Tu cuenta ha sido bloqueada temporalmente</h2>
                <p>Por razones de seguridad, hemos bloqueado temporalmente tu cuenta debido a múltiples intentos fallidos de recuperación de contraseña.</p>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">👤 Usuario:</div>
                    <div class="info-value">{{ $usuario->correo }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">🔒 Motivo del Bloqueo:</div>
                    <div class="info-value">{{ $reason }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">⏱️ Bloqueado Hasta:</div>
                    <div class="info-value">{{ $blockedUntil->format('d/m/Y H:i') }} ({{ $blockedUntil->diffForHumans() }})</div>
                </div>

                <div class="info-item">
                    <div class="info-label">🔓 Desbloqueo Automático:</div>
                    <div class="info-value">Tu cuenta se desbloqueará automáticamente después de este periodo</div>
                </div>
            </div>

            <div class="security-tips">
                <h3>🛡️ Medidas de Seguridad:</h3>
                <ul>
                    <li><strong>Si fuiste tú:</strong> Por favor espera hasta que se levante el bloqueo y asegúrate de recordar tus respuestas de seguridad correctamente.</li>
                    <li><strong>Si NO fuiste tú:</strong> Alguien intentó acceder a tu cuenta sin autorización. Te recomendamos cambiar tu contraseña inmediatamente cuando se desbloquee tu cuenta.</li>
                    <li><strong>¿Necesitas ayuda?</strong> Contacta a soporte técnico si necesitas asistencia inmediata.</li>
                </ul>
            </div>

            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-top: 30px;">
                Este es un mensaje automático del sistema de seguridad. Si tienes dudas o necesitas ayuda, por favor contacta a nuestro equipo de soporte.
            </p>
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>&copy; {{ date('Y') }} Todos los derechos reservados</p>
            <p style="font-size: 12px; margin-top: 15px;">
                Este correo fue enviado automáticamente, por favor no respondas a esta dirección.
            </p>
        </div>
    </div>
</body>
</html>
