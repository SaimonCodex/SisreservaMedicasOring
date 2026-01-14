@extends('layouts.admin')

@section('title', 'Detalle de Pago')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('pagos.index') }}" class="btn btn-outline hover:bg-gray-100">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Pago {{ $pago->referencia ?? 'N/A' }}</h1>
                <p class="text-gray-600 mt-1">Detalle completo de la transacción</p>
            </div>
        </div>
        <div class="flex gap-2">
            @if($pago->estado == 'Pendiente')
                <div class="flex gap-3">
                    <form action="{{ route('pagos.rechazar', $pago->id_pago) }}" method="POST" id="rechazarPagoForm">
                        @csrf
                        <input type="hidden" name="motivo" id="motivoRechazo">
                        <button type="button" onclick="openModal('modalRechazo')" class="btn btn-danger text-white shadow-lg shadow-rose-200">
                            <i class="bi bi-x-lg mr-2"></i> Rechazar Pago
                        </button>
                    </form>

                    <form action="{{ route('pagos.confirmar', $pago->id_pago) }}" method="POST">
                        @csrf
                        <input type="hidden" name="confirmado_por" value="{{ auth()->user()->administrador->id ?? '' }}">
                        <button type="submit" class="btn btn-success text-white shadow-lg shadow-emerald-200">
                            <i class="bi bi-check-lg mr-2"></i> Confirmar Pago
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Receipt Card -->
            <div class="card p-0 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-100 p-8 text-center">
                    @if($pago->estado == 'Confirmado')
                        <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                            <i class="bi bi-check-circle text-emerald-600 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-display font-bold text-gray-900 mb-1">Pago Confirmado</h2>
                    @elseif($pago->estado == 'Pendiente')
                         <div class="w-20 h-20 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-4">
                            <i class="bi bi-clock-history text-amber-600 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-display font-bold text-gray-900 mb-1">Pago Pendiente</h2>
                    @else
                        <div class="w-20 h-20 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-4">
                            <i class="bi bi-x-circle text-red-600 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-display font-bold text-gray-900 mb-1">{{ $pago->estado }}</h2>
                    @endif
                    
                    <p class="text-gray-500">Referencia: <span class="font-mono font-bold text-gray-700">{{ $pago->referencia ?? 'N/A' }}</span></p>
                    
                    <div class="mt-6">
                        <span class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Monto Pagado</span>
                        <div class="flex items-center justify-center gap-2 mt-1">
                            <span class="text-4xl font-bold text-gray-900">Bs. {{ number_format($pago->monto_pagado_bs, 2) }}</span>
                            <span class="text-xl text-gray-500 font-medium">/ ${{ number_format($pago->monto_equivalente_usd, 2) }} USD</span>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Payment Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-1">
                            <span class="text-sm font-medium text-gray-500">Paciente</span>
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    @php $paciente = $pago->facturaPaciente->cita->paciente; @endphp
                                    <p class="font-bold text-gray-900">{{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</p>
                                    <p class="text-xs text-gray-600">{{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="text-sm font-medium text-gray-500">Factura Relacionada</span>
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $pago->facturaPaciente->numero_factura }}</p>
                                    <p class="text-xs text-gray-600">Total: ${{ number_format($pago->facturaPaciente->monto_usd, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 col-span-1 md:col-span-2 my-2"></div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Método de Pago</span>
                            <p class="font-semibold text-gray-900 mt-1">{{ $pago->metodoPago->nombre ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Fecha de Pago</span>
                            <p class="font-semibold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tasa de Cambio Aplicada</span>
                            <p class="font-semibold text-gray-900 mt-1">
                                {{ number_format($pago->tasaAplicada->valor ?? 0, 2) }} Bs/USD
                                <span class="text-xs text-gray-500 font-normal block">{{ \Carbon\Carbon::parse($pago->tasaAplicada->fecha_tasa ?? now())->format('d/m/Y H:i A') }}</span>
                            </p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Registrado el</span>
                            <p class="font-semibold text-gray-900 mt-1">{{ $pago->created_at->format('d/m/Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($pago->comprobante)
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <p class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bi bi-file-earmark-image text-emerald-600"></i>
                            Comprobante Adjunto:
                        </p>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-2 group shadow-sm">
                            @php
                                $extension = pathinfo($pago->comprobante, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp

                            @if($isImage)
                                <div class="relative overflow-hidden rounded-lg mx-auto max-w-md border border-gray-100 shadow-sm">
                                    <a href="{{ asset('storage/' . $pago->comprobante) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $pago->comprobante) }}" alt="Comprobante de Pago" class="w-full max-h-[400px] object-contain rounded-lg transition-transform duration-300 group-hover:scale-[1.01]">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/30 text-white">
                                            <span class="bg-black/60 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2">
                                                <i class="bi bi-zoom-in"></i> Ver tamaño completo
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @else
                                <div class="p-6 text-center">
                                    <i class="bi bi-file-earmark-pdf text-5xl text-red-500 mb-3 block"></i>
                                    <p class="text-gray-600 font-medium mb-4">Archivo Comprobante (.{{ strtoupper($extension) }})</p>
                                    <a href="{{ asset('storage/' . $pago->comprobante) }}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download mr-1"></i> Descargar Comprobante
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($pago->comentarios)
                    <div class="mt-8 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                        <p class="text-sm font-bold text-yellow-800 mb-1">Comentarios / Notas:</p>
                        <p class="text-sm text-yellow-700">{{ $pago->comentarios }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Línea de Tiempo</h3>
                <div class="relative pl-4 border-l-2 border-gray-200 space-y-6">
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-gray-900">Pago Registrado</p>
                        <p class="text-xs text-gray-500">{{ $pago->created_at->format('d M Y, h:i A') }}</p>
                    </div>

                    @if($pago->estado == 'Confirmado')
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-blue-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-gray-900">Confirmado</p>
                        <p class="text-xs text-gray-500">{{ $pago->updated_at->format('d M Y, h:i A') }}</p>
                        @if($pago->confirmadoPor)
                            <p class="text-xs text-gray-400 mt-1">Por: {{ $pago->confirmadoPor->nombre ?? 'Admin' }}</p>
                        @endif
                    </div>
                    @endif
                    
                    @if($pago->estado == 'Rechazado')
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-red-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-gray-900">Rechazado</p>
                        <p class="text-xs text-gray-500">{{ $pago->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    <button onclick="window.print()" class="btn btn-outline w-full justify-start hover:bg-gray-50">
                        <i class="bi bi-printer mr-2"></i> Imprimir Detalle
                    </button>
                    <!-- Agrega aquí más acciones si fuera necesario, como enviar email, descargar PDF, etc. -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function confirmarRechazo() {
        const motivo = document.getElementById('motivo_input').value.trim();
        if (!motivo) {
            document.getElementById('motivo_error').classList.remove('hidden');
            return;
        }
        
        document.getElementById('motivoRechazo').value = motivo;
        
        // Show loading state in button
        const btn = document.getElementById('confirmRechazoBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Procesando...';
        
        document.getElementById('rechazarPagoForm').submit();
    }
</script>

<!-- Custom Modal: Rechazar Pago -->
<div id="modalRechazo" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <!-- Backdrop with glassmorphism -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalRechazo')"></div>
    
    <!-- Modal Container -->
    <div class="modal-content relative bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <!-- Decoration header -->
        <div class="h-2 bg-gradient-to-r from-rose-500 to-rose-600"></div>
        
        <div class="p-8">
            <!-- Icon -->
            <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mb-6 ring-4 ring-rose-50/50">
                <i class="bi bi-exclamation-triangle-fill text-rose-500 text-3xl"></i>
            </div>
            
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-2">¿Rechazar este pago?</h3>
            <p class="text-gray-500 mb-6">Esta acción es irreversible y el paciente será notificado de inmediato sobre la decisión.</p>
            
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label for="motivo_input" class="text-sm font-semibold text-gray-700 ml-1">Motivo del rechazo</label>
                    <textarea id="motivo_input" rows="4" 
                        class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all resize-none placeholder:text-gray-400"
                        placeholder="Explique brevemente por qué se rechaza este pago..."
                        oninput="document.getElementById('motivo_error').classList.add('hidden')"></textarea>
                    <p id="motivo_error" class="hidden text-xs font-medium text-rose-500 mt-1 flex items-center gap-1">
                        <i class="bi bi-exclamation-circle"></i> Debe proporcionar un motivo para el rechazo
                    </p>
                </div>
            </div>
            
            <div class="flex gap-3 mt-8">
                <button onclick="closeModal('modalRechazo')" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all active:scale-95">
                    Cancelar
                </button>
                <button id="confirmRechazoBtn" onclick="confirmarRechazo()" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all active:scale-95 flex items-center justify-center">
                    Rechazar Pago
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
