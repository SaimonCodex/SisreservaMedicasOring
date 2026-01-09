@extends('layouts.error')

@section('code', '500')

@section('title', 'Error del Servidor')

@section('message', 'Algo salió mal en nuestro servidor. Estamos trabajando para solucionarlo lo antes posible.')

@section('icon')
<div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-danger-500 to-warning-500 shadow-hard mb-4 animate-pulse-soft">
    <i class="bi bi-exclamation-triangle text-6xl text-white"></i>
</div>
@endsection
