@extends('layouts.admin')

@section('title', 'Acceso Denegado')
@section('page-title', 'Acceso Denegado')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center">
        <div class="text-9xl mb-4">🚫</div>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">403 - Acceso Denegado</h1>
        <p class="text-xl text-gray-600 mb-8">No tienes permiso para acceder a esta sección.</p>
        
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8 max-w-md mx-auto">
            <p class="text-red-800">
                {{ $exception->getMessage() ?? 'No tienes los permisos necesarios para realizar esta acción.' }}
            </p>
        </div>

        <div class="space-x-4">
            <a href="{{ route('admin.dashboard') }}" class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                Volver al Dashboard
            </a>
            <a href="{{ route('home') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
                Ir al Sitio Web
            </a>
        </div>

        <div class="mt-8 text-sm text-gray-500">
            <p>Si crees que esto es un error, contacta al administrador del sistema.</p>
        </div>
    </div>
</div>
@endsection
