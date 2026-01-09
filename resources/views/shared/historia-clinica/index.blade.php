@extends('layouts.admin')

@section('title', 'Historia Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Historia Clínica</h1>
            <p class="text-gray-600 mt-1">Historial médico de pacientes</p>
        </div>
    </div>

    <!-- Search -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="form-label">Buscar Paciente</label>
                <input type="text" name="search" class="input" placeholder="Nombre, cédula o código..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Desde</label>
                <input type="date" name="fecha_desde" class="input" value="{{ request('fecha_desde') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ url('index.php/shared/historia-clinica') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Patients List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($historias ?? [] as $historia)
        <a href="{{ url('index.php/shared/historia-clinica/' . $historia->paciente->id) }}" class="card p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($historia->paciente->nombre ?? 'P', 0, 1) }}
                </div>
                <div class="flex-1">
                    <h3 class="font-display font-bold text-gray-900">{{ $historia->paciente->nombre_completo ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $historia->paciente->cedula ?? 'N/A' }}</p>
                    <div class="flex items-center gap-4 mt-3 text-sm">
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="bi bi-droplet"></i>
                            {{ $historia->tipo_sangre ?? 'N/A' }}
                        </span>
                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="bi bi-calendar"></i>
                            {{ isset($historia->updated_at) ? \Carbon\Carbon::parse($historia->updated_at)->format('d/m/Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full card p-12 text-center">
            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No se encontraron historias clínicas</p>
        </div>
        @endforelse
    </div>

    @if(isset($historias) && $historias->hasPages())
    <div class="flex justify-center">
        {{ $historias->links() }}
    </div>
    @endif
</div>
@endsection
