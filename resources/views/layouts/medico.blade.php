@php
    $medico = auth()->user()->medico;
    $temaDinamico = $medico?->tema_dinamico ?? false;
    $baseColor = '#10b981'; // Emerald Default
    
    if ($temaDinamico && $medico->banner_color) {
        if (str_starts_with($medico->banner_color, '#')) {
            $baseColor = $medico->banner_color;
        } elseif (str_contains($medico->banner_color, 'from-')) {
            // Extraer color del gradiente de Tailwind
            if (preg_match('/from-([a-z]+)-(\d+)/', $medico->banner_color, $matches)) {
                $colorName = $matches[1];
                $shade = $matches[2];
                // Mapa aproximado de colores Tailwind 500/600 a Hex
                $colors = [
                    'slate' => '#64748b', 'gray' => '#6b7280', 'zinc' => '#71717a', 'neutral' => '#737373', 'stone' => '#78716c',
                    'red' => '#ef4444', 'orange' => '#f97316', 'amber' => '#f59e0b', 'yellow' => '#eab308', 'lime' => '#84cc16',
                    'green' => '#22c55e', 'emerald' => '#10b981', 'teal' => '#14b8a6', 'cyan' => '#06b6d4', 'sky' => '#0ea5e9',
                    'blue' => '#3b82f6', 'indigo' => '#6366f1', 'violet' => '#8b5cf6', 'purple' => '#a855f7', 'fuchsia' => '#d946ef',
                    'pink' => '#ec4899', 'rose' => '#f43f5e'
                ];
                $baseColor = $colors[$colorName] ?? '#10b981';
            }
        }
    }

    // Calcular contraste y color oscuro para sidebar
    $hex = str_replace('#', '', $baseColor);
    if(strlen($hex) == 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114) / 255;
    $textColorOnPrimary = $luminance > 0.6 ? '#0f172a' : '#ffffff';
    
    // Sidebar Dark Bg
    $darkSidebar = sprintf("#%02x%02x%02x", max(0, $r * 0.15), max(0, $g * 0.15), max(0, $b * 0.15));
    $sidebarBg = isset($darkSidebar) ? "linear-gradient(180deg, $darkSidebar 0%, #020617 100%)" : "linear-gradient(180deg, #0f172a 0%, #020617 100%)";
@endphp
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema Médico') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <style>
        /* Alpine.js x-cloak */
        [x-cloak] { display: none !important; }
        
        :root {
            --medical-500: {{ $baseColor }};
            --medical-600: {{ $baseColor }}cc; /* A bit lighter/transparent for gradients */
            --medical-400: {{ $baseColor }}eb;
            --medical-200: {{ $baseColor }}33;
            --medical-50: {{ $baseColor }}1a;
            --text-on-medical: {{ $textColorOnPrimary }};
            --sidebar-bg: {{ $sidebarBg }};
        }
        /* Override Emerald Classes if Dynamic Theme Active */
        @if($temaDinamico)
        .bg-emerald-500, .bg-medical-500 { background-color: var(--medical-500) !important; }
        .text-emerald-500, .text-medical-500 { color: var(--medical-500) !important; }
        .text-emerald-600, .text-medical-600 { color: var(--medical-600) !important; }
        .text-emerald-400, .text-medical-400 { color: var(--medical-400) !important; }
        .bg-emerald-50, .bg-medical-50 { background-color: var(--medical-50) !important; }
        .border-emerald-500, .border-medical-500 { border-color: var(--medical-500) !important; }
        .ring-emerald-500 { --tw-ring-color: var(--medical-500) !important; }
        .bg-emerald-600\/20 { background-color: var(--medical-200) !important; }
        
        /* Gradients overrides */
        .from-emerald-500 { --tw-gradient-from: var(--medical-500) !important; }
        .to-emerald-700 { --tw-gradient-to: var(--medical-600) !important; }
        @endif
        
        .animate-float-orb { animation: float-orb 15s ease-in-out infinite; }
        .animate-float-orb-slow { animation: float-orb 25s ease-in-out infinite reverse; }
        @keyframes float-orb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.1); }
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-smoke-50 font-sans antialiased">
    <!-- Background Ambiance -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full animate-float-orb blur-[120px]"
             style="background-color: var(--medical-500, #10b981); opacity: 0.1;"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full animate-float-orb-slow blur-[120px]"
             style="background-color: var(--medical-500, #10b981); opacity: 0.08;"></div>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 shadow-2xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col border-r border-white/5" 
           style="background: var(--sidebar-bg, linear-gradient(180deg, #0f172a 0%, #020617 100%));">
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center px-6 border-b border-white/5 bg-white/5 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center shadow-lg ring-1 ring-white/10">
                    <i class="bi bi-heart-pulse-fill text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-display font-bold text-white text-base leading-tight tracking-wide">{{ config('app.name', 'Sistema Médico') }}</h4>
                    <p class="text-medical-500 text-[10px] uppercase tracking-wider font-bold mt-0.5">Portal Médico</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
            <!-- Dashboard -->
            <a href="{{ route('medico.dashboard') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg mb-4 transition-all duration-200 group {{ request()->routeIs('medico.dashboard') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-speedometer2 text-lg mr-3 {{ request()->is('*/medico/dashboard') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            
            <!-- Atención Médica Section -->
            <div class="px-3 pb-2 pt-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Atención Médica</p>
            </div>
            
            <a href="{{ route('citas.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('citas.*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-calendar-check-fill text-lg mr-3 {{ request()->is('*/citas*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mis Citas</span>
            </a>
            
            <a href="{{ route('pacientes.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('pacientes.*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-people-fill text-lg mr-3 {{ request()->is('*/pacientes*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mis Pacientes</span>
            </a>
            
            <a href="{{ route('historia-clinica.base.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('historia-clinica.base.*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-file-earmark-medical-fill text-lg mr-3 {{ request()->routeIs('historia-clinica.base.*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Historias Clínicas</span>
            </a>

            <a href="{{ route('historia-clinica.evoluciones.general') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('historia-clinica.evoluciones.general') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-journal-medical text-lg mr-3 {{ request()->routeIs('historia-clinica.evoluciones.general') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Evoluciones Clínicas</span>
            </a>
            
            <a href="{{ route('ordenes-medicas.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('ordenes-medicas.*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-clipboard2-pulse-fill text-lg mr-3 {{ request()->is('*/ordenes-medicas*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Órdenes Médicas</span>
            </a>
            
            <!-- Gestión Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Gestión</p>
            </div>
            
            <a href="{{ route('medicos.horarios', $medico->id ?? 0) }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('medicos.horarios') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-calendar-week-fill text-lg mr-3 {{ request()->routeIs('medicos.horarios') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mi Agenda</span>
            </a>
            
            <a href="{{ route('ordenes-medicas.estadisticas') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('ordenes-medicas.estadisticas') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-graph-up-arrow text-lg mr-3 {{ request()->is('*/medico/estadisticas*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Estadísticas</span>
            </a>
            
            <!-- Perfil Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Cuenta</p>
            </div>
            
            <a href="{{ route('medicos.show', $medico->id ?? 0) }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('medicos.show') || request()->is('*/medico/perfil*') ? 'bg-emerald-600/20 text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i class="bi bi-person-fill text-lg mr-3 {{ request()->routeIs('medicos.show') || request()->is('*/medico/perfil*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Mi Perfil</span>
            </a>
            
            <div class="pb-20"></div>
        </nav>
        
        <!-- User Footer -->
        <div class="p-4 border-t border-white/5 bg-black/20">
             <div class="flex items-center gap-3">
                <div class="relative flex-shrink-0">
                    @if($medico && $medico->foto_perfil)
                        <img src="{{ asset('storage/' . $medico->foto_perfil) }}" class="w-9 h-9 rounded-lg object-cover ring-2 ring-white/10 shadow-sm">
                    @else
                        <div class="w-9 h-9 rounded-lg bg-medical-500 flex items-center justify-center text-xs font-bold text-white shadow-sm ring-2 ring-white/10">
                            {{ strtoupper(substr(auth()->user()->primer_nombre ?? 'M', 0, 1)) }}
                        </div>
                    @endif
                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-slate-900 rounded-full"></div>
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
                <div class="relative" id="notificaciones-container">
                    <button id="notificaciones-btn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
                        <i class="bi bi-bell text-xl text-gray-700"></i>
                        <span id="notificaciones-badge" class="absolute top-1 right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-white hidden">0</span>
                    </button>

                    <!-- Dropdown -->
                    <div id="notificaciones-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 opacity-0 invisible transform scale-95 transition-all duration-200 origin-top-right z-50">
                        <!-- Encabezado -->
                        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Notificaciones</h3>
                            <button id="marcar-todas-leidas" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                Marcar todas leídas
                            </button>
                        </div>

                        <!-- Lista de Notificaciones -->
                        <div id="notificaciones-lista" class="max-h-96 overflow-y-auto">
                            <div class="flex items-center justify-center py-8 text-gray-400">
                                <i class="bi bi-bell text-3xl"></i>
                            </div>
                            <p class="text-center text-sm text-gray-500 pb-4">No tienes notificaciones</p>
                        </div>

                        <!-- Footer -->
                        <div class="p-3 border-t border-gray-100">
                            <a href="{{ route('medico.notificaciones.index') }}" class="block text-center text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                Ver todas las notificaciones
                            </a>
                        </div>
                    </div>
                </div>
                    
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            @if($medico && $medico->foto_perfil)
                                <img src="{{ asset('storage/' . $medico->foto_perfil) }}" class="w-9 h-9 rounded-full object-cover shadow-md ring-2 ring-white">
                            @else
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ strtoupper(substr(auth()->user()->primer_nombre ?? 'M', 0, 1)) }}
                                </div>
                            @endif
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
                            <div class="p-2">
                                <a href="{{ route('medico.perfil.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-sm text-gray-700">
                                    <i class="bi bi-person"></i>
                                    <span>Mi Perfil</span>
                                </a>
                                <a href="{{ url('medico/configuracion') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-sm text-gray-700">
                                    <i class="bi bi-gear"></i>
                                    <span>Configuración</span>
                                </a>
                            </div>
                            <div class="p-2 border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
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
            
            @if($errors->any())
                <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-xl animate-slide-in-down">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-exclamation-octagon-fill text-rose-500 text-xl mt-0.5"></i>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-rose-900 mb-1">Por favor corrige los siguientes errores:</h4>
                            <ul class="list-disc list-inside text-sm text-rose-800 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
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

            // Cargar notificaciones no leídas al iniciar
            cargarNotificaciones();
        });

        // =================== NOTIFICACIONES ===================
        const notificacionesBtn = document.getElementById('notificaciones-btn');
        const notificacionesDropdown = document.getElementById('notificaciones-dropdown');
        const notificacionesBadge = document.getElementById('notificaciones-badge');
        const notificacionesLista = document.getElementById('notificaciones-lista');
        const marcarTodasLeidasBtn = document.getElementById('marcar-todas-leidas');

        // Toggle dropdown
        if (notificacionesBtn) {
            notificacionesBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = !notificacionesDropdown.classList.contains('opacity-0');
                
                if (isVisible) {
                    cerrarDropdown();
                } else {
                    abrirDropdown();
                }
            });
        }

        // Cerrar al hacer click fuera
        document.addEventListener('click', function(e) {
            const container = document.getElementById('notificaciones-container');
            if (container && !container.contains(e.target)) {
                cerrarDropdown();
            }
        });

        function abrirDropdown() {
            notificacionesDropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
            notificacionesDropdown.classList.add('opacity-100', 'visible', 'scale-100');
        }

        function cerrarDropdown() {
            notificacionesDropdown.classList.remove('opacity-100', 'visible', 'scale-100');
            notificacionesDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
        }

        // Cargar notificaciones no leídas
        function cargarNotificaciones() {
            fetch('{{ route("medico.notificaciones.unread") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        actualizarBadge(data.count);
                        renderizarNotificaciones(data.notificaciones);
                    }
                })
                .catch(error => console.error('Error cargando notificaciones:', error));
        }

        function actualizarBadge(count) {
            if (count > 0) {
                notificacionesBadge.textContent = count > 99 ? '99+' : count;
                notificacionesBadge.classList.remove('hidden');
            } else {
                notificacionesBadge.classList.add('hidden');
            }
        }

        function renderizarNotificaciones(notificaciones) {
            if (!notificaciones || notificaciones.length === 0) {
                notificacionesLista.innerHTML = `
                    <div class="flex items-center justify-center py-8 text-gray-400">
                        <i class="bi bi-bell text-3xl"></i>
                    </div>
                    <p class="text-center text-sm text-gray-500 pb-4">No tienes notificaciones</p>
                `;
                return;
            }

            let html = '';
            notificaciones.forEach(notif => {
                const data = notif.data;
                const iconClass = getTipoIcon(data.tipo);
                const bgClass = getTipoBg(data.tipo);
                
                html += `
                    <a href="${data.link || '#'}" 
                       onclick="marcarComoLeida('${notif.id}')" 
                       class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-10 h-10 ${bgClass} rounded-lg flex items-center justify-center">
                                <i class="bi ${iconClass} text-lg text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">${data.titulo}</p>
                                <p class="text-xs text-gray-600 mt-0.5">${data.mensaje}</p>
                                <p class="text-[10px] text-gray-400 mt-1">${formatearFecha(notif.created_at)}</p>
                            </div>
                        </div>
                    </a>
                `;
            });

            notificacionesLista.innerHTML = html;
        }

        function getTipoIcon(tipo) {
            const icons = {
                'success': 'bi-check-circle-fill',
                'info': 'bi-info-circle-fill',
                'warning': 'bi-exclamation-triangle-fill',
                'danger': 'bi-x-circle-fill'
            };
            return icons[tipo] || 'bi-bell-fill';
        }

        function getTipoBg(tipo) {
            const bgs = {
                'success': 'bg-emerald-500',
                'info': 'bg-blue-500',
                'warning': 'bg-amber-500',
                'danger': 'bg-rose-500'
            };
            return bgs[tipo] || 'bg-gray-500';
        }

        function formatearFecha(fecha) {
            const ahora = new Date();
            const fechaNotif = new Date(fecha);
            const diffMs = ahora - fechaNotif;
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMins < 1) return 'Ahora';
            if (diffMins < 60) return `Hace ${diffMins} min`;
            
            const diffHours = Math.floor(diffMins / 60);
            if (diffHours < 24) return `Hace ${diffHours}h`;
            
            const diffDays = Math.floor(diffHours / 24);
            if (diffDays < 7) return `Hace ${diffDays}d`;
            
            return fechaNotif.toLocaleDateString();
        }

        function marcarComoLeida(notifId) {
            fetch(`/medico/notificaciones/${notifId}/marcar-leida`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cargarNotificaciones();
                }
            });
        }

        if (marcarTodasLeidasBtn) {
            marcarTodasLeidasBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                fetch('{{ route("medico.notificaciones.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cargarNotificaciones();
                        cerrarDropdown();
                    }
                });
            });
        }

        // Recargar notificaciones cada 30 segundos
        setInterval(cargarNotificaciones, 30000);
    </script>
    
    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 flex flex-col gap-2 pointer-events-none">
        @if(session('success'))
        <div class="toast pointer-events-auto flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg border border-gray-100 dark:text-gray-400 dark:bg-gray-800 transition-all duration-300 transform translate-x-0 animate-slide-in-right" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <i class="bi bi-check-lg"></i>
            </div>
            <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="this.closest('.toast').remove()">
                <span class="sr-only">Cerrar</span>
                <i class="bi bi-x"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="toast pointer-events-auto flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg border border-gray-100 dark:text-gray-400 dark:bg-gray-800 transition-all duration-300 transform translate-x-0 animate-slide-in-right" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="ml-3 text-sm font-medium">{{ session('error') }}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="this.closest('.toast').remove()">
                <span class="sr-only">Cerrar</span>
                <i class="bi bi-x"></i>
            </button>
        </div>
        @endif

        @if(session('info'))
        <div class="toast pointer-events-auto flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg border border-gray-100 dark:text-gray-400 dark:bg-gray-800 transition-all duration-300 transform translate-x-0 animate-slide-in-right" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                <i class="bi bi-info-circle-fill"></i>
            </div>
            <div class="ml-3 text-sm font-medium">{{ session('info') }}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="this.closest('.toast').remove()">
                <span class="sr-only">Cerrar</span>
                <i class="bi bi-x"></i>
            </button>
        </div>
        @endif
    </div>

    <script>
        // Auto-close toasts after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const toasts = document.querySelectorAll('.toast');
                toasts.forEach(toast => {
                    toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => toast.remove(), 500);
                });
            }, 3000); // 3 seconds
        });

        // Toast Creation Function
        function createToast(title, message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const iconMap = {
                success: '<i class="bi bi-check-lg"></i>',
                error: '<i class="bi bi-exclamation-triangle-fill"></i>',
                danger: '<i class="bi bi-exclamation-triangle-fill"></i>',
                info: '<i class="bi bi-info-circle-fill"></i>',
                warning: '<i class="bi bi-exclamation-circle-fill"></i>'
            };
            
            const colorMap = {
                success: 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200',
                error: 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200',
                danger: 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200',
                info: 'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200',
                warning: 'text-amber-500 bg-amber-100 dark:bg-amber-800 dark:text-amber-200'
            };

            const toast = document.createElement('div');
            toast.className = 'toast pointer-events-auto flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg border border-gray-100 dark:text-gray-400 dark:bg-gray-800 transition-all duration-300 transform translate-x-0 animate-slide-in-right mb-2';
            toast.role = 'alert';
            
            toast.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg ${colorMap[type] || colorMap.info}">
                    ${iconMap[type] || iconMap.info}
                </div>
                <div class="ml-3 text-sm font-medium">
                    <div class="font-bold text-gray-900 mb-0.5">${title}</div>
                    ${message}
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="this.closest('.toast').remove()">
                    <span class="sr-only">Cerrar</span>
                    <i class="bi bi-x"></i>
                </button>
            `;

            container.appendChild(toast);

            // Auto remove
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    </script>

    @stack('scripts')
</body>
</html>
