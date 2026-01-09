@extends('layouts.admin')

@section('title', 'Logs del Sistema')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Logs del Sistema</h2>
            <p class="text-gray-500 mt-1">Registro de eventos, errores y actividades</p>
        </div>
        <form action="{{ route('configuracion.logs.limpiar') }}" method="POST" onsubmit="return confirm('¿Está seguro de querer borrar todos los logs? Esta acción no se puede deshacer.')">
            @csrf
            <button type="submit" class="btn btn-outline hover:bg-danger-50 hover:text-danger-600 hover:border-danger-200">
                <i class="bi bi-trash mr-2"></i> Limpiar Logs
            </button>
        </form>
    </div>
</div>

<div class="card p-0 overflow-hidden shadow-lg border border-gray-200">
    <div class="bg-gray-900 text-gray-300 p-4 flex justify-between items-center border-b border-gray-700">
        <span class="font-mono text-sm">storage/logs/laravel.log</span>
        <div class="flex gap-2">
            <span class="badge bg-gray-700 text-gray-300">Read-Only</span>
            <span class="badge bg-gray-700 text-gray-300">Tail: 100 lines</span>
        </div>
    </div>
    
    <div class="bg-gray-900 p-4 overflow-x-auto font-mono text-xs leading-relaxed h-[600px] overflow-y-auto custom-scrollbar">
        @forelse($logs as $log)
            <div class="whitespace-nowrap hover:bg-gray-800 px-2 py-0.5 rounded transition-colors group">
                @if(str_contains($log, '.ERROR'))
                    <span class="text-danger-400 font-bold">[ERROR]</span> <span class="text-gray-300">{{ $log }}</span>
                @elseif(str_contains($log, '.WARNING'))
                    <span class="text-warning-400 font-bold">[WARNING]</span> <span class="text-gray-300">{{ $log }}</span>
                @elseif(str_contains($log, '.INFO'))
                    <span class="text-info-400 font-bold">[INFO]</span> <span class="text-gray-400">{{ $log }}</span>
                @else
                    <span class="text-gray-500">{{ $log }}</span>
                @endif
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                <i class="bi bi-file-earmark-check text-4xl mb-3 opacity-50"></i>
                <p>El archivo de log está limpio</p>
            </div>
        @endforelse
    </div>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-danger-100 flex items-center justify-center">
            <i class="bi bi-bug-fill text-danger-600 text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-900 text-lg">0</h4>
            <span class="text-gray-500 text-sm">Errores Críticos</span>
        </div>
    </div>

    <div class="card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-warning-100 flex items-center justify-center">
            <i class="bi bi-exclamation-triangle-fill text-warning-600 text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-900 text-lg">3</h4>
            <span class="text-gray-500 text-sm">Advertencias</span>
        </div>
    </div>

    <div class="card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-info-100 flex items-center justify-center">
            <i class="bi bi-info-circle-fill text-info-600 text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-900 text-lg">125</h4>
            <span class="text-gray-500 text-sm">Eventos Registrados</span>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #111827; 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #374151; 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #4b5563; 
}
</style>
@endsection
