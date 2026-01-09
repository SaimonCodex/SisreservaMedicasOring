@extends('layouts.paciente')

@section('title', 'Mi Historial')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-display font-bold text-gray-900">Mi Historial Médico</h1>
    <p class="text-gray-600 mt-1">Todas tus consultas y procedimientos</p>
</div>

<div class="card">
    @if($historial && $historial->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($historial as $cita)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex gap-4 items-start">
                    <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-file-medical text-blue-600 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">
                                    {{ $cita->diagnostico ?? 'Consulta Médica' }}
                                </h4>
                                <p class="text-gray-600 flex items-center gap-2 mt-1">
                                    <i class="bi bi-person-badge text-blue-600"></i>
                                    <span>Dr. {{ $cita->medico->primer_nombre ?? '' }} {{ $cita->medico->primer_apellido ?? '' }}</span>
                                </p>
                            </div>
                            <span class="badge {{ $cita->estatus == 'completada' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($cita->estatus ?? 'Pendiente') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm mt-3">
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-calendar3 text-blue-600"></i>
                                <span>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d M, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-building text-blue-600"></i>
                                <span>{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                            </div>
                        </div>

                        @if($cita->motivo)
                        <div class="p-3 bg-gray-100 rounded-lg mt-3">
                            <p class="text-sm text-gray-700"><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $historial->links() }}
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                <i class="bi bi-folder-x text-5xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 mb-2 font-medium text-lg">No tienes historial médico aún</p>
            <p class="text-gray-400 text-sm">Tu historial aparecerá aquí después de tu primera consulta</p>
        </div>
    @endif
</div>
@endsection
