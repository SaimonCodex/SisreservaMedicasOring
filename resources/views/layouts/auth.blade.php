<!doctype html>
<html lang="es" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Médico') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>

    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-600 bg-slate-100 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    
    <!-- Decorative Background -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[600px] h-[600px] rounded-full bg-medical-50 blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[500px] h-[500px] rounded-full bg-blue-50 blur-3xl opacity-50"></div>
    </div>

    <!-- Main Container Card -->
    <div class="relative z-10 w-full @yield('box-width', 'max-w-[1100px]') bg-white rounded-3xl shadow-2xl overflow-hidden flex min-h-[650px]">
        
        <!-- Left Side: Form Area -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 sm:p-12 lg:p-16 relative">
            <!-- Brand Logo -->
            <div class="absolute top-8 left-8 sm:left-12 lg:left-16">
                 <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-lg bg-medical-600 text-white flex items-center justify-center transition-transform group-hover:scale-110">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <span class="font-display font-bold text-gray-900 group-hover:text-medical-600 transition-colors">{{ config('app.name') }}</span>
                </a>
            </div>

            <!-- Content -->
            <div class="w-full @yield('form-width', 'max-w-lg') mx-auto pt-10">
                @yield('auth-content')
            </div>

            <!-- Footer Copyright -->
            <div class="absolute bottom-6 left-0 right-0 text-center">
                 <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}
                 </p>
            </div>
        </div>
        
        <!-- Right Side: Visual Area -->
        <div class="hidden lg:block lg:w-1/2 relative bg-medical-900">
            <!-- Background Image -->
            <img class="absolute inset-0 h-full w-full object-cover opacity-60 mix-blend-overlay" src="https://images.unsplash.com/photo-1638202993928-7267aad84c31?q=80&w=1974&auto=format&fit=crop" alt="Medical Team">
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-medical-900/90 via-medical-800/80 to-blue-900/70"></div>
            
            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-center p-16 text-white text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center mx-auto mb-6 shadow-xl">
                        <i class="bi bi-shield-check text-3xl text-medical-200"></i>
                    </div>
                </div>
                
                <h3 class="text-3xl font-display font-bold mb-4 leading-tight">
                    Seguridad y confianza para tu salud
                </h3>
                
                <p class="text-lg text-medical-100 font-light leading-relaxed mb-8">
                    Accede a una plataforma integral diseñada para optimizar la gestión médica y mejorar la experiencia del paciente.
                </p>

                <!-- Review/Trust Badge -->
                <div class="flex items-center justify-center gap-4 pt-8 border-t border-white/10">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-medical-800" src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-medical-800" src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-medical-800" src="https://randomuser.me/api/portraits/women/68.jpg" alt="User">
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-bold text-white">+2k Usuarios</div>
                        <div class="text-xs text-medical-200">Confían en nosotros</div>
                    </div>
                </div>
            </div>

            <!-- Decorative Circles -->
            <div class="absolute top-10 right-10 w-20 h-20 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute bottom-10 left-10 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl"></div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
