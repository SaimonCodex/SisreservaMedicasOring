@extends('layouts.error')

@section('code', '404')

@section('title', 'Página No Encontrada')

@section('message', 'Lo sentimos, la página que buscas no existe o fue movida a otra ubicación.')

@section('icon')
<div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-info-500 to-premium-500 shadow-hard mb-4 animate-pulse-soft">
    <i class="bi bi-search text-6xl text-white"></i>
</div>
@endsection
