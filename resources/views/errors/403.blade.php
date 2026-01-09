@extends('layouts.error')

@section('code', '403')

@section('title', 'Acceso Prohibido')

@section('message', 'No tienes permisos para acceder a esta página. Por favor contacta al administrador si crees que es un error.')

@section('icon')
<div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-warning-500 to-amber-500 shadow-hard mb-4 animate-pulse-soft">
    <i class="bi bi-shield-exclamation text-6xl text-white"></i>
</div>
@endsection
