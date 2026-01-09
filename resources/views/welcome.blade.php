<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Médico') }} - Salud Integral</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="font-sans antialiased text-slate-600 bg-slate-50 selection:bg-medical-100 selection:text-medical-700">
    
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 h-20 flex justify-between items-center">
            <!-- Brand -->
            <a href="#" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-medical-600 flex items-center justify-center text-white shadow-lg shadow-medical-200">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <span class="font-display font-bold text-xl text-slate-800 tracking-tight">{{ config('app.name', 'SisMed') }}</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-medical-600 transition-colors">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn bg-medical-600 text-white hover:bg-medical-700 shadow-md shadow-medical-200 rounded-lg px-6 py-2.5 font-medium transition-all hover:-translate-y-0.5">
                    Registrarme
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden bg-white">
        <!-- Subtle Pattern Background -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%230ea5e9\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        
        <!-- Gradient Blobs -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-gradient-to-b from-medical-50 to-transparent rounded-full blur-3xl opacity-60 -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Text Content -->
                <div class="lg:w-1/2 space-y-8 text-center lg:text-left animate-slide-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-sm font-semibold mb-2">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        Plataforma de Salud Digital
                    </div>

                    <h1 class="text-5xl lg:text-6xl font-display font-bold text-slate-900 leading-tight">
                        Tu salud, gestionada con <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-medical-600 to-blue-500">excelencia y calidez</span>
                    </h1>
                    
                    <p class="text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Simplificamos la conexión entre médicos y pacientes. Agenda citas, consulta resultados y gestiona tu historial clínico en un entorno seguro y moderno.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                        <a href="{{ route('register') }}" class="btn bg-slate-900 text-white hover:bg-slate-800 h-12 px-8 rounded-lg text-base shadow-xl flex items-center justify-center gap-2 transition-all hover:-translate-y-1">
                            Crear Cuenta Gratis
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="#demo" class="btn bg-white border border-slate-200 text-slate-700 hover:border-medical-500 hover:text-medical-600 h-12 px-8 rounded-lg text-base flex items-center justify-center gap-2 transition-all">
                            <i class="bi bi-play-circle-fill"></i>
                            Ver Cómo Funciona
                        </a>
                    </div>
                    
                    <div class="pt-8 border-t border-slate-100 flex items-center justify-center lg:justify-start gap-8">
                        <div>
                            <p class="text-2xl font-bold text-slate-900">24/7</p>
                            <p class="text-sm text-slate-500">Soporte Activo</p>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">100%</p>
                            <p class="text-sm text-slate-500">Datos Seguros</p>
                        </div>
                    </div>
                </div>

                <!-- Visual Content -->
                <div class="lg:w-1/2 relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white/50 animate-float">
                        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=2070&auto=format&fit=crop" alt="Dashboard Preview" class="w-full">
                        
                        <!-- Floating Card 1 -->
                        <div class="absolute top-8 left-8 bg-white/90 backdrop-blur p-4 rounded-xl shadow-lg border border-white/50 animate-slide-in-left">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="bi bi-calendar-check-fill"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-medium">Cita Confirmada</p>
                                    <p class="text-sm font-bold text-slate-800">Hoy, 10:00 AM</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Card 2 -->
                        <div class="absolute bottom-8 right-8 bg-white/90 backdrop-blur p-4 rounded-xl shadow-lg border border-white/50 animate-slide-in-right" style="animation-delay: 0.5s">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="bi bi-person-video"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-medium">Dr. Rodríguez</p>
                                    <p class="text-sm font-bold text-slate-800">Consulta Virtual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-24 bg-slate-50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-sm font-bold text-medical-600 tracking-wider uppercase mb-2">Nuestros Portales</h2>
                <h3 class="text-3xl font-display font-bold text-slate-900">Acceso personalizado para cada rol</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Patient -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Portal Paciente</h4>
                    <p class="text-slate-500 mb-6 leading-relaxed">
                        Gestiona tus citas médicas, accede a tu historial clínico y recibe recordatorios automáticos.
                    </p>
                    <a href="{{ url('index.php/login?rol=paciente') }}" class="inline-flex items-center font-semibold text-green-600 group-hover:gap-2 transition-all">
                        Ingresar <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Doctor -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Portal Médico</h4>
                    <p class="text-slate-500 mb-6 leading-relaxed">
                        Control total de tu agenda, historias clínicas digitales y seguimiento detallado de pacientes.
                    </p>
                    <a href="{{ url('index.php/login?rol=medico') }}" class="inline-flex items-center font-semibold text-blue-600 group-hover:gap-2 transition-all">
                        Ingresar <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Admin -->
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Administración</h4>
                    <p class="text-slate-500 mb-6 leading-relaxed">
                        Panel de control integral para la gestión de usuarios, configuraciones y reportes del sistema.
                    </p>
                    <a href="{{ url('index.php/login?rol=admin') }}" class="inline-flex items-center font-semibold text-purple-600 group-hover:gap-2 transition-all">
                        Ingresar <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- Brand -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-medical-600 flex items-center justify-center text-white text-sm">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-lg">{{ config('app.name') }}</span>
                </div>
                
                <div class="text-slate-400 text-sm font-medium">
                    &copy; {{ date('Y') }} Sistema Médico Integral. Todos los derechos reservados.
                </div>

                <div class="flex gap-6 text-slate-400">
                    <a href="#" class="hover:text-medical-600 transition-colors"><i class="bi bi-facebook text-xl"></i></a>
                    <a href="#" class="hover:text-medical-600 transition-colors"><i class="bi bi-twitter-x text-xl"></i></a>
                    <a href="#" class="hover:text-medical-600 transition-colors"><i class="bi bi-linkedin text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>