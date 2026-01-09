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
    <div class="relative mx-auto min-h-screen w-full bg-mesh-premium">
        <header class="sticky top-0 z-40 border-b border-white/20 bg-white/90 backdrop-blur-xl shadow-soft">
            <div class="container flex h-20 items-center justify-between">
                <a href="{{ url('index.php/paciente/dashboard') }}" class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-medical-500 to-premium-500 text-white shadow-medium">
                        <i class="bi bi-person-check text-2xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-semibold text-smoke-800">{{ config('app.name', 'Sistema Médico') }}</span>
                        <span class="text-xs font-medium uppercase tracking-widest text-medical-600">Portal Paciente</span>
                    </div>
                </a>

                <nav class="hidden items-center gap-2 md:flex">
                    <a href="{{ url('index.php/paciente/dashboard') }}" class="nav-pill {{ request()->is('index.php/paciente/dashboard') ? 'nav-pill-active' : '' }}">
                        <i class="bi bi-speedometer2 text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ url('index.php/citas') }}" class="nav-pill {{ request()->is('index.php/citas*') ? 'nav-pill-active' : '' }}">
                        <i class="bi bi-calendar-plus text-lg"></i>
                        <span>Solicitar Cita</span>
                    </a>
                    <a href="{{ url('index.php/paciente/historial') }}" class="nav-pill {{ request()->is('index.php/paciente/historial*') ? 'nav-pill-active' : '' }}">
                        <i class="bi bi-file-medical text-lg"></i>
                        <span>Mi Historial</span>
                    </a>
                    <a href="{{ url('index.php/paciente/pagos') }}" class="nav-pill {{ request()->is('index.php/paciente/pagos*') ? 'nav-pill-active' : '' }}">
                        <i class="bi bi-credit-card text-lg"></i>
                        <span>Pagos</span>
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <div class="hidden items-center gap-3 rounded-2xl border border-smoke-200 bg-white/80 px-4 py-2 shadow-soft md:flex">
                        <i class="bi bi-person-circle text-medical-500 text-lg"></i>
                        <div>
                            <p class="text-sm font-semibold text-smoke-700">{{ auth()->user()->correo }}</p>
                            <p class="text-xs text-smoke-400">Paciente</p>
                        </div>
                        <form method="POST" action="{{ url('index.php/logout') }}" class="inline">@csrf
                            <button type="submit" class="text-xs font-semibold uppercase tracking-wide text-rose-500 transition-colors hover:text-rose-600">Salir</button>
                        </form>
                    </div>
                    <button id="mobileMenuToggle" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white/90 text-smoke-700 shadow-soft ring-1 ring-smoke-200 md:hidden">
                        <i class="bi bi-list text-lg"></i>
                    </button>
                </div>
            </div>

            <div id="mobileMenu" class="md:hidden">
                <div class="container pb-4">
                    <nav class="flex flex-col gap-2 rounded-3xl bg-white/80 p-4 shadow-soft ring-1 ring-white/70">
                        <a href="{{ url('index.php/paciente/dashboard') }}" class="nav-link-mobile {{ request()->is('index.php/paciente/dashboard') ? 'nav-link-mobile-active' : '' }}">
                            <i class="bi bi-speedometer2 text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ url('index.php/citas') }}" class="nav-link-mobile {{ request()->is('index.php/citas*') ? 'nav-link-mobile-active' : '' }}">
                            <i class="bi bi-calendar-plus text-lg"></i>
                            <span>Solicitar Cita</span>
                        </a>
                        <a href="{{ url('index.php/paciente/historial') }}" class="nav-link-mobile {{ request()->is('index.php/paciente/historial*') ? 'nav-link-mobile-active' : '' }}">
                            <i class="bi bi-file-medical text-lg"></i>
                            <span>Mi Historial</span>
                        </a>
                        <a href="{{ url('index.php/paciente/pagos') }}" class="nav-link-mobile {{ request()->is('index.php/paciente/pagos*') ? 'nav-link-mobile-active' : '' }}">
                            <i class="bi bi-credit-card text-lg"></i>
                            <span>Pagos</span>
                        </a>
                        <form method="POST" action="{{ url('index.php/logout') }}" class="mt-3">@csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-rose-500/10 py-2 text-sm font-semibold text-rose-600 transition-colors hover:bg-rose-500/20">
                                <i class="bi bi-box-arrow-right"></i>
                                Cerrar Sesión
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </header>

        <div class="container py-6">
            @if(session('success'))
                <div id="alert-success" class="mb-4 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-700 shadow-soft">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button data-dismiss="alert-success" class="text-emerald-500 transition-colors hover:text-emerald-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="alert-error" class="mb-4 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50/90 px-4 py-3 text-sm text-rose-700 shadow-soft">
                    <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    <div class="flex-1">{{ session('error') }}</div>
                    <button data-dismiss="alert-error" class="text-rose-500 transition-colors hover:text-rose-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @yield('content')
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
        });
    </script>
    @endpush

    @stack('scripts')
</body>
</html>
