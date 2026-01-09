<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Médico') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen bg-smoke-50 font-sans antialiased">
    <div class="relative">
        <div class="absolute inset-0 h-72 bg-hero-medical"></div>
        <div class="relative z-10">
            <header class="border-b border-white/20 backdrop-blur-xl bg-white/80 shadow-soft">
                <div class="container flex h-20 items-center justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-medical-500 to-premium-500 text-white shadow-medium">
                            <i class="bi bi-shield-check text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-semibold tracking-tight text-smoke-800">{{ config('app.name', 'Sistema Médico') }}</span>
                            <span class="text-xs font-medium uppercase tracking-widest text-medical-600">Salud & Reservas</span>
                        </div>
                    </a>

                    <nav class="hidden gap-6 text-sm font-medium text-smoke-600 md:flex">
                        <a href="{{ url('/') }}" class="hover:text-medical-600 transition-colors">Inicio</a>
                        <a href="{{ route('login') }}" class="hover:text-medical-600 transition-colors">Acceder</a>
                        <a href="{{ route('register') }}" class="hover:text-medical-600 transition-colors">Registrarse</a>
                    </nav>

                    <div class="flex items-center gap-4">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline hidden md:inline-flex">Iniciar sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-primary hidden md:inline-flex">Crear cuenta</a>
                        @else
                            <div class="hidden items-center gap-3 rounded-2xl border border-smoke-200 bg-white/70 px-4 py-2 shadow-soft md:flex">
                                <span class="text-sm font-semibold text-smoke-700">{{ auth()->user()->correo }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
                                    <button type="submit" class="text-sm font-medium text-rose-500 transition-colors hover:text-rose-600">Cerrar sesión</button>
                                </form>
                            </div>
                            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white/80 shadow-soft ring-1 ring-medical-200 text-medical-600 md:hidden">
                                <i class="bi bi-person"></i>
                            </button>
                        @endguest
                        <button class="md:hidden inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white/90 shadow-soft ring-1 ring-smoke-200 text-smoke-700">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="container relative z-10 -mt-8 rounded-3xl bg-white/95 p-6 shadow-hard ring-1 ring-white/60">
                @if(session('success'))
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-700 shadow-soft">
                        <i class="bi bi-check-circle-fill text-xl"></i>
                        <div class="flex-1">{{ session('success') }}</div>
                        <button type="button" onclick="this.closest('div').remove()" class="text-emerald-500 hover:text-emerald-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50/80 px-4 py-3 text-sm text-rose-700 shadow-soft">
                        <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                        <div class="flex-1">{{ session('error') }}</div>
                        <button type="button" onclick="this.closest('div').remove()" class="text-rose-500 hover:text-rose-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="mt-12 bg-transparent">
                <div class="container py-10">
                    <div class="rounded-3xl bg-white/70 p-8 shadow-soft ring-1 ring-white/50 backdrop-blur-xl">
                        <div class="flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-widest text-medical-500">{{ config('app.name', 'Sistema Médico') }}</p>
                                <p class="mt-2 text-sm text-smoke-500">Innovación en reservas médicas y experiencia del paciente.</p>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('login') }}" class="btn btn-outline">Ingresar</a>
                                <a href="{{ route('register') }}" class="btn btn-secondary">Regístrate</a>
                            </div>
                        </div>
                    </div>
                    <p class="mt-6 text-center text-xs text-smoke-500">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
