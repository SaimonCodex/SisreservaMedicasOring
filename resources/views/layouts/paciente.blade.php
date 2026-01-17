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


    @php
        $paciente = auth()->user()->paciente;
        $temaDinamico = $paciente->tema_dinamico ?? false;
        $bannerColor = $paciente->banner_color ?? null;
        
        // Determinar color base para el tema
        $baseColor = '#10b981'; // Emerald 500 predeterminado
        if ($temaDinamico && $bannerColor) {
            if (str_contains($bannerColor, '#')) {
                $baseColor = $bannerColor;
            } elseif (str_contains($bannerColor, 'from-')) {
                // Extraer el primer color del gradiente de Tailwind para aproximar
                if (preg_match('/from-([a-z]+)-(\d+)/', $bannerColor, $matches)) {
                    $colors = [
                        'emerald' => '#10b981', 'blue' => '#3b82f6', 'teal' => '#14b8a6',
                        'purple' => '#a855f7', 'rose' => '#f43f5e', 'slate' => '#64748b',
                        'orange' => '#f97316', 'indigo' => '#6366f1'
                    ];
                    $baseColor = $colors[$matches[1]] ?? $baseColor;
                }
            }
        }

        // Función para calcular si un color es claro (Luminancia)
        $isLight = false;
        if ($temaDinamico) {
            $hex = str_replace('#', '', $baseColor);
            if (strlen($hex) == 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
            $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114) / 255;
            $isLight = $luminance > 0.6; // Umbral de claridad
        }
        $textColorOnPrimary = $isLight ? '#0f172a' : '#ffffff'; // Negro pizarra o blanco
    @endphp

    @if($temaDinamico)
    <style>
        :root {
            --medical-500: {{ $baseColor }};
            --medical-600: {{ $baseColor }}cc; /* 80% opacidad */
            --medical-200: {{ $baseColor }}33; /* 20% opacidad */
            --medical-50: {{ $baseColor }}1a;  /* 10% opacidad */
            --text-on-medical: {{ $textColorOnPrimary }};
        }
        /* Ajustes específicos para Tailwind config no dinámico */
        .bg-medical-500 { background-color: var(--medical-500) !important; }
        .text-medical-500 { color: var(--medical-500) !important; }
        .text-medical-600 { color: var(--medical-600) !important; }
        .bg-medical-200\/20 { background-color: var(--medical-200) !important; }
        .bg-medical-50 { background-color: var(--medical-50) !important; }
        .shadow-medical-200\/50 { --tw-shadow-color: var(--medical-200) !important; }
        .border-medical-500 { border-color: var(--medical-500) !important; }
        .hover\:bg-medical-50:hover { background-color: var(--medical-50) !important; }
        .hover\:text-medical-600:hover { color: var(--medical-600) !important; }

        /* Animaciones Premium de Fondo */
        @keyframes float-orb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-float-orb {
            animation: float-orb 15s ease-in-out infinite;
        }
        .animate-float-orb-slow {
            animation: float-orb 25s ease-in-out infinite reverse;
        }
        .animate-float-orb-delayed {
            animation: float-orb 20s ease-in-out infinite;
            animation-delay: -5s;
        }
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toast-card.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    </style>
    @endif

    @stack('styles')
</head>
<body class="min-h-screen bg-smoke-50">
    <div class="relative mx-auto min-h-screen w-full bg-[#f8fafc] selection:bg-medical-500/30">
        <!-- Premium Mesh Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <!-- Orbe 1: Principal -->
            <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full animate-float-orb blur-[120px]"
                 style="background-color: var(--medical-500); opacity: 0.15;"></div>
            <!-- Orbe 2: Secundario -->
            <div class="absolute top-[20%] -right-[5%] w-[40%] h-[60%] rounded-full animate-float-orb-slow blur-[100px]"
                 style="background-color: var(--medical-600); opacity: 0.1;"></div>
            <!-- Orbe 3: Inferior Acento -->
            <div class="absolute -bottom-[10%] left-[20%] w-[35%] h-[45%] rounded-full animate-float-orb-delayed blur-[130px]"
                 style="background-color: var(--medical-500); opacity: 0.08;"></div>
            
            @if(!$temaDinamico)
                <div class="absolute top-[10%] left-[30%] w-[20%] h-[30%] bg-premium-200/10 blur-[80px] rounded-full"></div>
            @endif
        </div>

        <header class="sticky top-4 z-40 mx-auto max-w-7xl px-4">
            <div class="rounded-3xl border border-white/40 bg-white/70 backdrop-blur-2xl shadow-[0_8px_32px_rgba(0,0,0,0.04)] ring-1 ring-black/[0.03]">
                <div class="container flex h-20 items-center justify-between px-6">
                    <a href="{{ route('paciente.dashboard') }}" class="flex items-center gap-3 active:scale-95 transition-transform">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-medical-500 to-medical-600 text-white shadow-lg shadow-medical-200/50">
                            <i class="bi bi-shield-check text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold text-slate-800 tracking-tight leading-tight">{{ config('app.name', 'SaimonDoc') }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-medical-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-medical-600">Portal Salud</span>
                            </div>
                        </div>
                    </a>

                    <nav class="hidden items-center gap-1.5 lg:flex">
                        @php
                            $navItems = [
                                ['route' => 'paciente.dashboard', 'icon' => 'bi-grid-1x2', 'label' => 'Inicio'],
                                ['route' => 'paciente.citas.index', 'icon' => 'bi-calendar3', 'label' => 'Mis Citas'],
                                ['route' => 'paciente.citas.create', 'icon' => 'bi-calendar-plus', 'label' => 'Nueva Cita'],
                                ['route' => 'paciente.ordenes.index', 'icon' => 'bi-file-medical', 'label' => 'Recetas'],
                                ['route' => 'paciente.historial', 'icon' => 'bi-folder2-open', 'label' => 'Historial'],
                                ['route' => 'paciente.solicitudes', 'icon' => 'bi-shield-lock', 'label' => 'Permisos'],
                                ['route' => 'paciente.pagos', 'icon' => 'bi-wallet2', 'label' => 'Pagos'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            <a href="{{ route($item['route']) }}" 
                               class="group flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all duration-300 {{ request()->routeIs($item['route']) ? 'bg-medical-500 text-white shadow-md shadow-medical-200 scale-105' : 'text-slate-500 hover:bg-medical-50 hover:text-medical-600' }}">
                                <i class="bi {{ $item['icon'] }} text-base transition-transform group-hover:scale-110"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>

                    <div class="flex items-center gap-4">
                        <div class="hidden h-10 w-[1px] bg-slate-200 lg:block"></div>
                        
                        <!-- Notification Bell -->
                        <div class="relative group">
                            <a href="{{ route('paciente.notificaciones.index') }}" 
                               class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition-all hover:bg-medical-50 hover:text-medical-600 ring-1 ring-slate-200 group-hover:ring-medical-500">
                                <i class="bi bi-bell-fill text-xl"></i>
                                @php
                                    $unreadCount = auth()->user()->paciente->unreadNotifications()->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                            
                            <!-- Notification Dropdown -->
                            <div class="absolute right-0 top-full mt-2 w-80 scale-95 opacity-0 pointer-events-none group-hover:scale-100 group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                                    <div class="flex items-center justify-between px-4 py-3 bg-medical-50 border-b border-medical-100">
                                        <h3 class="text-sm font-bold text-medical-800">Notificaciones</h3>
                                        @if($unreadCount > 0)
                                            <form method="POST" action="{{ route('paciente.notificaciones.leer-todas') }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs font-bold text-medical-600 hover:text-medical-700">
                                                    Marcar todas como leídas
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @forelse(auth()->user()->paciente->unreadNotifications()->take(5)->get() as $notification)
                                            <a href="{{ $notification->data['link'] ?? '#' }}" 
                                               class="block px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0"
                                               onclick="event.preventDefault(); marcarComoLeida('{{ $notification->id }}', '{{ $notification->data['link'] ?? '#' }}')">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-{{ $notification->data['tipo'] === 'success' ? 'medical' : ($notification->data['tipo'] === 'danger' ? 'rose' : 'blue') }}-100 flex items-center justify-center">
                                                        <i class="bi bi-{{ $notification->data['tipo'] === 'success' ? 'check-circle' : ($notification->data['tipo'] === 'danger' ? 'exclamation-circle' : 'info-circle') }}-fill text-{{ $notification->data['tipo'] === 'success' ? 'medical' : ($notification->data['tipo'] === 'danger' ? 'rose' : 'blue') }}-600"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $notification->data['mensaje'] ?? 'Nueva notificación' }}</p>
                                                        <p class="text-xs text-slate-500 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-8 text-center">
                                                <i class="bi bi-bell-slash text-4xl text-slate-300 mb-2"></i>
                                                <p class="text-sm text-slate-500">No tienes notificaciones nuevas</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    @if($unreadCount > 0)
                                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-100">
                                            <a href="{{ route('paciente.notificaciones.index') }}" class="text-xs font-bold text-medical-600 hover:text-medical-700 flex items-center justify-center gap-1">
                                                Ver todas las notificaciones
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Card -->
                        <div class="hidden sm:flex items-center gap-3 pl-2">
                            <div class="flex flex-col items-end">
                                <p class="text-xs font-bold text-slate-800 leading-none mb-0.5">{{ auth()->user()->correo }}</p>
                                <span class="text-[10px] font-bold text-medical-500 uppercase tracking-wider">Paciente Verificado</span>
                            </div>
                            <div class="relative group">
                                @if(auth()->user()->paciente && auth()->user()->paciente->foto_perfil)
                                    <img src="{{ asset('storage/' . auth()->user()->paciente->foto_perfil) }}" 
                                         alt="Foto de perfil" 
                                         class="h-10 w-10 rounded-xl object-cover border border-slate-200 group-hover:border-medical-500 transition-colors">
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 group-hover:bg-medical-50 group-hover:text-medical-600 transition-colors">
                                        <i class="bi bi-person-fill text-xl"></i>
                                    </div>
                                @endif
                                <!-- Logout Tooltip/Menu -->
                                <div class="absolute right-0 top-full mt-2 w-48 scale-95 opacity-0 pointer-events-none group-hover:scale-100 group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-2">
                                        <a href="{{ route('paciente.perfil.edit') }}" class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-medical-600 hover:bg-medical-50 transition-colors mb-1">
                                            <i class="bi bi-person-gear text-lg"></i>
                                            Editar Perfil
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">@csrf
                                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                                <i class="bi bi-box-arrow-right text-lg"></i>
                                                Cerrar Sesión
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button id="mobileMenuToggle" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition-all active:scale-90 hover:bg-medical-50 hover:text-medical-600 lg:hidden ring-1 ring-slate-200">
                            <i class="bi bi-grid-fill text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden">
                <div class="mt-3 px-2">
                    <nav class="flex flex-col gap-1.5 rounded-[2rem] bg-white/90 backdrop-blur-xl p-4 shadow-2xl ring-1 ring-black/[0.05]">
                        @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center gap-4 px-5 py-4 rounded-2xl text-base font-bold transition-all {{ request()->routeIs($item['route']) ? 'bg-medical-500 text-white shadow-xl shadow-medical-200' : 'text-slate-600 hover:bg-slate-50' }}"
                           style="{{ (request()->routeIs($item['route']) && $temaDinamico) ? 'color: var(--text-on-medical) !important' : '' }}">
                            <i class="bi {{ $item['icon'] }} text-xl"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                        @endforeach
                        
                        <div class="h-[1px] bg-slate-100 my-2"></div>
                        
                        <div class="flex items-center gap-3 px-4 py-2 mb-2">
                            <div class="h-10 w-10 rounded-xl bg-medical-50 flex items-center justify-center text-medical-600 font-bold">
                                {{ strtoupper(substr(auth()->user()->correo, 0, 1)) }}
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->correo }}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Estado: Activo</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-rose-50 py-4 text-sm font-bold text-rose-600 transition-colors hover:bg-rose-100">
                                <i class="bi bi-box-arrow-right text-lg"></i>
                                Finalizar Sesión
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </header>

        <main class="container mx-auto max-w-7xl px-4 py-8 relative z-10">
            @if(session('success'))
                <div id="alert-success" class="mb-6 flex items-center gap-4 rounded-2xl border border-medical-200 bg-medical-50/80 backdrop-blur-sm px-6 py-4 text-sm font-bold text-medical-800 shadow-sm transition-all animate-in fade-in slide-in-from-top-4 duration-300"
                     style="{{ $temaDinamico ? 'color: var(--medical-800) !important; border-color: var(--medical-200) !important; background-color: var(--medical-50) !important;' : '' }}">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-medical-500 text-white shadow-md shadow-medical-200"
                         style="{{ $temaDinamico ? 'background-color: var(--medical-500) !important; color: var(--text-on-medical) !important;' : '' }}">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button data-dismiss="alert-success" class="h-8 w-8 rounded-lg hover:bg-medical-100/50 text-medical-400 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="alert-error" class="mb-6 flex items-center gap-4 rounded-2xl border border-rose-100 bg-rose-50/80 backdrop-blur-sm px-6 py-4 text-sm font-bold text-rose-800 shadow-sm transition-all animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-500 text-white shadow-md shadow-rose-200">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="flex-1">{{ session('error') }}</div>
                    <button data-dismiss="alert-error" class="h-8 w-8 rounded-lg hover:bg-rose-100/50 text-rose-400 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-24 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none"></div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('mobileMenuToggle');
            const menu = document.getElementById('mobileMenu');
            if (toggle && menu) {
                toggle.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('#alert-success, #alert-error');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });

            // Dismiss buttons
            document.querySelectorAll('[data-dismiss]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-dismiss');
                    const target = document.getElementById(targetId);
                    if(target) {
                        target.style.transition = 'opacity 0.3s';
                        target.style.opacity = '0';
                        setTimeout(() => target.remove(), 300);
                    }
                });
            });
        });
        
        // Function to mark notification as read
        window.marcarComoLeida = function(notificationId, redirectUrl) {
            fetch(`/paciente/notificaciones/${notificationId}/marcar-leida`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && redirectUrl && redirectUrl !== '#') {
                    window.location.href = redirectUrl;
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (redirectUrl && redirectUrl !== '#') {
                    window.location.href = redirectUrl;
                }
            });
        };

        // Laravel Echo Listener
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                const pacienteId = {{ auth()->user()->paciente->id }};
                
                window.Echo.private(`App.Models.Paciente.${pacienteId}`)
                    .notification((notification) => {
                        console.log('Notificación recibida:', notification);
                        
                        const data = notification.data || notification;
                        const title = data.titulo || 'Nueva Notificación';
                        const message = data.mensaje || '';
                        const type = data.tipo || 'info';
                        
                        // Crear toast
                        createToast(title, message, type, notification.id);
                        
                        // Actualizar contador en la campana
                        const badge = document.querySelector('.relative .bg-rose-500');
                        if (badge) {
                            let count = parseInt(badge.innerText.replace('+', '')) || 0;
                            count++;
                            badge.innerText = count > 9 ? '9+' : count;
                        } else {
                            // Si no hay badge, crearlo (esto es más complejo, por ahora al menos intentamos buscarlo)
                            const bellLink = document.querySelector('a[href="{{ route('paciente.notificaciones.index') }}"]');
                            if (bellLink) {
                                const newBadge = document.createElement('span');
                                newBadge.className = "absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white";
                                newBadge.innerText = "1";
                                bellLink.appendChild(newBadge);
                            }
                        }
                    });
            }
        });

        // Toast Notification System
        function createToast(title, message, type = 'info', id = null) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const config = {
                success: { bg: 'bg-emerald-500/90', icon: 'check-circle-fill' },
                danger: { bg: 'bg-rose-500/90', icon: 'exclamation-circle-fill' },
                warning: { bg: 'bg-amber-500/90', icon: 'exclamation-triangle-fill' },
                info: { bg: 'bg-blue-600/90', icon: 'info-circle-fill' }
            }[type] || { bg: 'bg-slate-700/90', icon: 'bell-fill' };

            const toast = document.createElement('div');
            toast.className = `toast-card pointer-events-auto w-full backdrop-blur-xl rounded-2xl shadow-2xl p-4 flex gap-4 items-start group border-t border-white/20 shadow-lg ${config.bg} text-white`;
            
            toast.innerHTML = `
                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center text-white shadow-inner">
                    <i class="bi bi-${config.icon} text-xl"></i>
                </div>
                <div class="flex-1 min-w-0 text-white">
                    <h4 class="text-sm font-bold text-shadow-sm">${title}</h4>
                    <p class="text-xs opacity-90 mt-1 line-clamp-2">${message}</p>
                </div>
                <button class="text-white/60 hover:text-white transition-colors p-1" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            `;

            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 100);

            // Auto-remove after 8 seconds
            setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.classList.remove('show');
                    setTimeout(() => { if (toast && toast.parentNode) toast.remove(); }, 500);
                }
            }, 8000);
        }

        // Show unread notifications as toasts ONLY if the login flag is present
        @if(session('mostrar_bienvenida_toasts'))
            @php
                $toasts = auth()->user()->paciente->unreadNotifications->take(3)->map(function($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->data['titulo'] ?? 'Notificación',
                        'message' => $n->data['mensaje'] ?? '',
                        'tipo' => $n->data['tipo'] ?? 'info'
                    ];
                });
                // Consumir el flag para que no aparezca en la siguiente página
                session()->forget('mostrar_bienvenida_toasts');
            @endphp

            const unreadToasts = @json($toasts);
            unreadToasts.forEach((toast, index) => {
                setTimeout(() => {
                    createToast(toast.title, toast.message, toast.tipo, toast.id);
                }, 500 * (index + 1));
            });
        @endif
    </script>
    @endpush

    @stack('scripts')
</body>
</html>
```
