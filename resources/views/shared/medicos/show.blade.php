@extends('layouts.admin')

@section('title', 'Perfil del Médico')

@section('content')
<div class="mb-6">
    <a href="{{ route('medicos.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Médicos
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Perfil del Médico</h2>
            <p class="text-gray-500 mt-1">Información completa del profesional</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('medicos.horarios', $medico->id) }}" class="btn btn-outline">
                <i class="bi bi-clock mr-2"></i>
                Horarios
            </a>
            <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Información Personal Header -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-medical-600 to-medical-500 p-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold border-4 border-white/30">
                        {{ strtoupper(substr($medico->primer_nombre, 0, 1) . substr($medico->primer_apellido, 0, 1)) }}
                    </div>
                    <div class="text-white">
                        <h3 class="text-2xl font-bold mb-1">Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }}</h3>
                        <p class="text-white/90 mb-2">
                            @forelse($medico->especialidades as $especialidad)
                                {{ $especialidad->nombre }}{{ !$loop->last ? ' • ' : '' }}
                            @empty
                                Sin especialidad registrada
                            @endforelse
                        </p>
                        <div class="flex gap-2">
                            <span class="badge bg-white/20 text-white border border-white/30">{{ $medico->status ? 'Activo' : 'Inactivo' }}</span>
                            @if($medico->nro_colegiatura)
                                <span class="badge bg-white/20 text-white border border-white/30">MPPS: {{ $medico->nro_colegiatura }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">{{ $medico->citas ? $medico->citas->count() : 0 }}</p>
                        <p class="text-sm text-gray-500">Consultas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">N/A</p>
                        <p class="text-sm text-gray-500">Calificación</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">
                             {{ \Carbon\Carbon::parse($medico->created_at)->diffInYears(now()) }}
                        </p>
                        <p class="text-sm text-gray-500">Años Registrado</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">{{ $medico->status ? '100%' : '0%' }}</p>
                        <p class="text-sm text-gray-500">Estatus</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Personales -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-circle text-medical-600"></i>
                Datos Personales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Documento de Identidad</p>
                    <p class="font-semibold text-gray-900">{{ $medico->tipo_documento }}-{{ $medico->numero_documento }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                    <p class="font-semibold text-gray-900">
                        @if($medico->fecha_nac)
                            {{ \Carbon\Carbon::parse($medico->fecha_nac)->format('d/m/Y') }} 
                            ({{ \Carbon\Carbon::parse($medico->fecha_nac)->age }} años)
                        @else
                            No registrada
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">{{ $medico->genero ?? 'No registrado' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Usuario del Sistema</p>
                    <p class="font-semibold text-gray-900">{{ optional($medico->usuario)->correo ?? 'Sin usuario asignado' }}</p>
                </div>
            </div>
        </div>

        <!-- Datos Profesionales -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-award text-success-600"></i>
                Información Profesional
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Registro MPPS</p>
                    <p class="font-semibold text-gray-900">{{ $medico->nro_colegiatura ?? 'No registrado' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Colegio de Médicos (CMG)</p>
                    <p class="font-semibold text-gray-900">{{ $medico->cmg ?? 'No registrado' }}</p>
                </div>
                <!-- Especialidades (Iterar si hay más de una, o mostrar principal) -->
                <!-- Especialidades Detalladas -->
                 <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-2">Especialidades y Tarifas</p>
                    <div class="overflow-hidden border border-gray-200 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tarifa</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Exp.</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Domicilio</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($medico->especialidades as $especialidad)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $especialidad->nombre }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-right">
                                        ${{ number_format($especialidad->pivot->tarifa, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ $especialidad->pivot->anos_experiencia ?? 0 }} años
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                        @if($especialidad->pivot->atiende_domicilio)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="bi bi-check-circle-fill"></i> Sí (+${{ number_format($especialidad->pivot->tarifa_extra_domicilio, 2) }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-sm text-gray-500 text-center italic">
                                        Sin especialidades registradas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Consultorios Asignados</p>
                    <div class="flex flex-wrap gap-2">
                         @forelse($medico->consultorios as $consultorio)
                             <span class="badge badge-outline">{{ $consultorio->nombre ?? 'Consultorio #'.$consultorio->id }}</span>
                        @empty
                             <span class="text-gray-500 italic">No tiene consultorios asignados (Configurar en Horarios)</span>
                        @endforelse
                    </div>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-2">Formación Académica</p>
                    <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                        {{ $medico->formacion_academica ?? 'No registrada.' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-2">Biografía / Experiencia</p>
                    <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                        {{ $medico->experiencia_profesional ?? 'Sin biografía registrada.' }}
                    </p>
                </div>
            </div>
        </div>

            </div>
        </div>

        <!-- Horarios de Atención -->
        <div class="card p-6 border-l-4 border-l-purple-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-clock-history text-purple-600"></i>
                Horarios de Atención
            </h3>
            
            @if($medico->horarios->count() > 0)
                <div class="space-y-4">
                    @php
                        // Ordenar días de la semana
                        $ordenDias = ['lunes' => 1, 'martes' => 2, 'miercoles' => 3, 'jueves' => 4, 'viernes' => 5, 'sabado' => 6, 'domingo' => 7];
                        
                        $horariosAgrupados = $medico->horarios->groupBy(function($item) {
                            return strtolower(\Illuminate\Support\Str::ascii($item->dia_semana));
                        })->sortBy(function($items, $key) use ($ordenDias) {
                            return $ordenDias[$key] ?? 8;
                        });
                    @endphp

                    @foreach($horariosAgrupados as $dia => $turnos)
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <!-- Día Header -->
                            <div class="sm:w-32 flex-shrink-0">
                                <span class="badge bg-purple-100 text-purple-700 font-bold uppercase tracking-wider w-full justify-center">
                                    {{ ucfirst($dia) }}
                                </span>
                            </div>

                            <!-- Turnos -->
                            <div class="flex-grow space-y-2">
                                @foreach($turnos as $turno)
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="font-semibold {{ stripos($turno->turno, 'm') === 0 ? 'text-blue-600' : 'text-orange-600' }}">
                                            @if(stripos($turno->turno, 'm') === 0)
                                                <i class="bi bi-sun-fill mr-1"></i> Mañana
                                            @else
                                                <i class="bi bi-sunset-fill mr-1"></i> Tarde
                                            @endif
                                        </span>
                                        <span class="text-gray-700">
                                            {{ \Carbon\Carbon::parse($turno->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($turno->horario_fin)->format('H:i') }}
                                        </span>
                                        <div class="hidden sm:block text-gray-400">|</div>
                                        <div class="text-gray-500 flex flex-col sm:flex-row sm:gap-3">
                                            <span title="Consultorio"><i class="bi bi-geo-alt"></i> {{ $turno->consultorio->nombre ?? 'N/A' }}</span>
                                            @if($turno->especialidad)
                                                <span title="Especialidad"><i class="bi bi-heart-pulse"></i> {{ $turno->especialidad->nombre }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 bg-gray-50 rounded-lg">
                    <i class="bi bi-calendar-x text-3xl text-gray-300 mb-2 block"></i>
                    <p class="text-gray-500">Este médico aún no tiene horarios configurados.</p>
                </div>
            @endif
        </div>

        <!-- Información de Contacto -->
        <div class="card p-6 border-l-4 border-l-info-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-telephone text-info-600"></i>
                Contacto
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Principal</p>
                    <p class="font-semibold text-gray-900">
                        {{ $medico->prefijo_tlf }} {{ $medico->numero_tlf }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Secundario</p>
                    <p class="font-semibold text-gray-900">{{ $medico->telefono_secundario ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Correo Electrónico (Contacto)</p>
                    <p class="font-semibold text-gray-900">{{ optional($medico->usuario)->correo }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Dirección</p>
                    <p class="font-semibold text-gray-900">
                        {{ $medico->direccion_detallada ?? '' }}
                        @if($medico->parroquia || $medico->municipio || $medico->ciudad || $medico->estado)
                            <br>
                            <span class="text-sm font-normal text-gray-600">
                                {{ optional($medico->parroquia)->parroquia }}, 
                                {{ optional($medico->municipio)->municipio }}, 
                                {{ optional($medico->ciudad)->ciudad }} - 
                                {{ optional($medico->estado)->estado }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones Rápidas</h4>
            <div class="space-y-2">
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-plus mr-2"></i>
                    Agendar Cita
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-clock-history mr-2"></i>
                    Ver Historial de Citas
                </button>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-100">
                 <h4 class="font-bold text-gray-900 mb-4">Estado del Sistema</h4>
                 <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Cuenta de Usuario</span>
                        <span class="badge {{ optional($medico->usuario)->status ? 'badge-success' : 'badge-danger' }}">
                            {{ optional($medico->usuario)->status ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="text-sm text-gray-600">Registrado el</span>
                        <span class="text-xs text-gray-500">{{ $medico->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
