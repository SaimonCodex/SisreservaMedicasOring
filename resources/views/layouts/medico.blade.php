<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Médico') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')
</head>
<body class="min-h-screen bg-smoke-50 font-sans antialiased">
    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-slate-900 shadow-2xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col border-r border-white/5" style="background: linear-gradient(180deg, #0f172a 0%, #020617 100%);">
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center px-6 border-b border-white/5 bg-white/5 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-lg ring-1 ring-white/10">
                    <i class="bi bi-heart-pulse-fill text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-display font-bold text-white text-base leading-tight tracking-wide">{{ config('app.name', 'Sistema Médico') }}</h4>
                    <p class="text-emerald-400/80 text-[10px] uppercase tracking-wider font-semibold mt-0.5">Portal Médico</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
            <!-- Dashboard -->
            <a href="{{ url('index.php/medico/dashboard') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg mb-4 transition-all duration-200 group {{ request()->is('*/medico/dashboard') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-speedometer2 text-lg mr-3 {{ request()->is('*/medico/dashboard') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            
            <!-- Atención Médica Section -->
            <div class="px-3 pb-2 pt-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Atención Médica</p>
            </div>
            
            <a href="{{ url('index.php/citas') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/citas*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-calendar-check-fill text-lg mr-3 {{ request()->is('*/citas*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mis Citas</span>
            </a>
            
            <a href="{{ url('index.php/pacientes') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/pacientes*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-people-fill text-lg mr-3 {{ request()->is('*/pacientes*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mis Pacientes</span>
            </a>
            
            <a href="{{ url('index.php/historia-clinica') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/historia-clinica*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-file-earmark-medical-fill text-lg mr-3 {{ request()->is('*/historia-clinica*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Historias Clínicas</span>
            </a>
            
            <a href="{{ url('index.php/ordenes-medicas') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/ordenes-medicas*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-clipboard2-pulse-fill text-lg mr-3 {{ request()->is('*/ordenes-medicas*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Órdenes Médicas</span>
            </a>
            
            <!-- Gestión Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Gestión</p>
            </div>
            
            <a href="{{ url('index.php/medico/agenda') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/medico/agenda*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-calendar-week-fill text-lg mr-3 {{ request()->is('*/medico/agenda*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mi Agenda</span>
            </a>
            
            <a href="{{ url('index.php/medico/estadisticas') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/medico/estadisticas*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-graph-up-arrow text-lg mr-3 {{ request()->is('*/medico/estadisticas*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Estadísticas</span>
            </a>
            
            <!-- Perfil Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Cuenta</p>
            </div>
            
            <a href="{{ url('index.php/medico/perfil') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/medico/perfil*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-person-fill text-lg mr-3 {{ request()->is('*/medico/perfil*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mi Perfil</span>
            </a>
            
            <div class="pb-20"></div>
        </nav>
        
        <!-- User Footer -->
        <div class="p-4 border-t border-white/5 bg-black/20">
             <div class="flex items-center gap-3">
                <div class="relative flex-shrink-0">
                    <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-xs font-bold text-white shadow-sm ring-2 ring-white/10">
                        {{ strtoupper(substr(auth()->user()->correo, 0, 1)) }}
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-blue-500 border-2 border-slate-900 rounded-full"></div>
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-xs font-semibold text-slate-200 truncate">{{ auth()->user()->correo }}</p>
                    <p class="text-[10px] text-slate-500 flex items-center gap-1">
                        Médico
                    </p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg hover:bg-white/10 text-slate-400 hover:text-rose-400 transition-colors" title="Cerrar Sesión">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
             </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        <!-- Top Bar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
            <div class="px-4 lg:px-6 h-16 flex items-center justify-between">
                <!-- Left: Mobile toggle & Title -->
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-list text-2xl text-gray-700"></i>
                    </button>
                    <h1 class="text-lg lg:text-xl font-display font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                </div>
                
                <!-- Right: Notifications & User -->
                <div class="flex items-center gap-3">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
                            <i class="bi bi-bell text-xl text-gray-700"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                        </button>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-danger-500 to-danger-600 flex items-center justify-center text-white font-semibold shadow-md">
                                {{ strtoupper(substr(auth()->user()->correo, 0, 1)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->correo }}</p>
                                <p class="text-xs text-gray-500">Médico</p>
                            </div>
                            <i class="bi bi-chevron-down text-xs text-gray-600"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-hard border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                            <div class="p-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->correo }}</p>
                                <p class="text-xs text-gray-500">Médico</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ url('index.php/medico/perfil') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-sm text-gray-700">
                                    <i class="bi bi-person"></i>
                                    <span>Mi Perfil</span>
                                </a>
                                <a href="{{ url('index.php/medico/configuracion') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-sm text-gray-700">
                                    <i class="bi bi-gear"></i>
                                    <span>Configuración</span>
                                </a>
                            </div>
                            <div class="p-2 border-t border-gray-100">
                                <form method="POST" action="{{ url('index.php/logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-rose-50 transition-colors text-sm text-rose-600">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Content Area -->
        <div class="p-4 lg:p-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3 animate-slide-in-down">
                    <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-emerald-900">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3 animate-slide-in-down">
                    <i class="bi bi-exclamation-triangle-fill text-rose-500 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-rose-900">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3 animate-slide-in-down">
                    <i class="bi bi-exclamation-circle-fill text-amber-500 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-amber-900">{{ session('warning') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif
            
            <!-- Main Content -->
            @yield('content')
        </div>
    </main>
    
    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            });
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        }
        
        // Auto-close alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.animate-slide-in-down');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
