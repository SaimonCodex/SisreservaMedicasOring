<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error') - {{ config('app.name', 'Sistema Médico') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-smoke-100 via-white to-medical-50 flex items-center justify-center p-4 font-sans antialiased">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-20 w-96 h-96 bg-medical-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse-soft"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-premium-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse-soft" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative z-10 max-w-2xl w-full">
        <!-- Error Content -->
        <div class="text-center animate-slide-in-up">
            <!-- Error Icon -->
            <div class="mb-8">
                @yield('icon', '<div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-medical-500 to-premium-500 shadow-hard mb-4">
                    <i class="bi bi-exclamation-triangle text-6xl text-white"></i>
                </div>')
            </div>
            
            <!-- Error Code -->
            <h1 class="text-8xl md:text-9xl font-display font-bold text-gradient mb-4">
                @yield('code', '500')
            </h1>
            
            <!-- Error Title -->
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">
                @yield('title', 'Error del Servidor')
            </h2>
            
            <!-- Error Message -->
            <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto">
                @yield('message', 'Ha ocurrido un error inesperado. Por favor, intenta nuevamente.')
            </p>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="javascript:history.back()" class="btn btn-outline">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Volver Atrás
                </a>
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="bi bi-house mr-2"></i>
                    Ir al Inicio
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-12 text-center animate-fade-in" style="animation-delay: 0.3s;">
            <p class="text-sm text-gray-500">
                Si el problema persiste, por favor contacta al administrador del sistema.
            </p>
            <p class="text-xs text-gray-400 mt-2">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
