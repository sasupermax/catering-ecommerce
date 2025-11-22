@extends('layouts.shop')

@section('title', 'Sesión Expirada')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
            <div class="text-9xl mb-6">⏱️</div>
            <h1 class="text-6xl font-bold text-gray-800 mb-4">419</h1>
            <h2 class="text-3xl font-semibold text-gray-700 mb-4">Sesión Expirada</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-lg mx-auto">
                Tu sesión ha expirado por seguridad. Por favor, intenta nuevamente.
            </p>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8 max-w-md mx-auto">
                <p class="text-yellow-800 font-medium mb-2">
                    🔒 Seguridad de Sesión
                </p>
                <p class="text-yellow-700 text-sm">
                    Por tu seguridad, las sesiones expiran después de cierto tiempo de inactividad.
                </p>
            </div>

            <div class="space-x-4">
                <button data-action="go-back" class="inline-block bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition font-medium">
                    ← Volver e Intentar de Nuevo
                </button>
                <a href="{{ route('home') }}" class="inline-block bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
                    🏠 Ir al Inicio
                </a>
            </div>

            <div class="mt-12 text-gray-500">
                <p class="text-sm">Tip: Guarda tu trabajo frecuentemente para evitar perder información.</p>
            </div>
        </div>
    </div>
</div>
@endsection
