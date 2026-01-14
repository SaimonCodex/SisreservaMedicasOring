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
<body class="min-h-screen bg-smoke-50">
    <div class="relative mx-auto min-h-screen w-full bg-[#f8fafc] selection:bg-medical-500/30">
        <!-- Premium Mesh Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-medical-200/20 blur-[120px] rounded-full"></div>
            <div class="absolute top-[20%] -right-[5%] w-[30%] h-[50%] bg-premium-200/20 blur-[100px] rounded-full"></div>
        </div>

        <header class="sticky top-4 z-40 mx-auto max-w-7xl px-4">
            <div class="rounded-3xl border border-white/40 bg-white/70 backdrop-blur-2xl shadow-[0_8px_32px_rgba(0,0,0,0.04)] ring-1 ring-black/[0.03]">
                <div class="container flex h-20 items-center justify-between px-6">
                    <a href="{{ route('paciente.dashboard') }}" class="flex items-center gap-3 active:scale-95 transition-transform">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-medical-500 to-teal-500 text-white shadow-lg shadow-medical-200/50">
                            <i class="bi bi-shield-check text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold text-slate-800 tracking-tight leading-tight">{{ config('app.name', 'SaimonDoc') }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-emerald-500 animate-pulse"></span>
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
                                ['route' => 'paciente.historial', 'icon' => 'bi-folder2-open', 'label' => 'Historial'],
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
                        
                        <!-- User Card -->
                        <div class="hidden sm:flex items-center gap-3 pl-2">
                            <div class="flex flex-col items-end">
                                <p class="text-xs font-bold text-slate-800 leading-none mb-0.5">{{ auth()->user()->correo }}</p>
                                <span class="text-[10px] font-bold text-medical-500 uppercase tracking-wider">Paciente Verificado</span>
                            </div>
                            <div class="relative group">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 group-hover:bg-medical-50 group-hover:text-medical-600 transition-colors">
                                    <i class="bi bi-person-fill text-xl"></i>
                                </div>
                                <!-- Logout Tooltip/Menu -->
                                <div class="absolute right-0 top-full mt-2 w-48 scale-95 opacity-0 pointer-events-none group-hover:scale-100 group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-2">
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
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-base font-bold transition-all {{ request()->routeIs($item['route']) ? 'bg-medical-500 text-white shadow-xl shadow-medical-200' : 'text-slate-600 hover:bg-slate-50' }}">
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
                <div id="alert-success" class="mb-6 flex items-center gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/80 backdrop-blur-sm px-6 py-4 text-sm font-bold text-emerald-800 shadow-sm transition-all animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-white shadow-md shadow-emerald-200">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button data-dismiss="alert-success" class="h-8 w-8 rounded-lg hover:bg-emerald-100/50 text-emerald-400 transition-colors">
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
    </script>
    @endpush

    @stack('scripts')
</body>
</html>
