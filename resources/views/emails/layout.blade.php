<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f3f4f6;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .email-logo {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .email-tagline {
            color: #E0E7FF;
            font-size: 14px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-title {
            font-size: 24px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 20px;
        }
        .email-content {
            color: #4B5563;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .info-card {
            background-color: #F3F4F6;
            border-left: 4px solid #3B82F6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6B7280;
            font-size: 14px;
        }
        .info-value {
            color: #1F2937;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }
        .btn-secondary {
            display: inline-block;
            background-color: #ffffff;
            color: #3B82F6 !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 10px 0;
            border: 2px solid #3B82F6;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alert-info {
            background-color: #EFF6FF;
            border-left: 4px solid #3B82F6;
            color: #1E40AF;
        }
        .alert-success {
            background-color: #F0FDF4;
            border-left: 4px solid #10B981;
            color: #065F46;
        }
        .alert-warning {
            background-color: #FFFBEB;
            border-left: 4px solid #F59E0B;
            color: #92400E;
        }
        .alert-danger {
            background-color: #FEF2F2;
            border-left: 4px solid #EF4444;
            color: #991B1B;
        }
        .email-footer {
            background-color: #F9FAFB;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }
        .footer-text {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .footer-links {
            margin: 15px 0;
        }
        .footer-link {
            color: #3B82F6;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        .social-icons {
            margin: 20px 0;
        }
        .social-icon {
            display: inline-block;
            width: 32px;
            height: 32px;
            margin: 0 5px;
            background-color: #3B82F6;
            border-radius: 50%;
            color: #ffffff;
            text-align: center;
            line-height: 32px;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .email-title {
                font-size: 20px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-value {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <div class="email-logo">🏥 MediReserva</div>
            <div class="email-tagline">Sistema de Reservas Médicas</div>
        </div>

        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                © {{ date('Y') }} MediReserva. Todos los derechos reservados.
            </div>
            <div class="footer-links">
                <a href="{{ url('/') }}" class="footer-link">Inicio</a>
                <a href="{{ url('/contacto') }}" class="footer-link">Contacto</a>
                <a href="{{ url('/ayuda') }}" class="footer-link">Ayuda</a>
            </div>
            <div class="footer-text" style="font-size: 12px; margin-top: 15px;">
                Este es un correo automático, por favor no responder a este mensaje.
            </div>
        </div>
    </div>
</body>
</html>
