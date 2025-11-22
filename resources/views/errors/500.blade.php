@extends('layouts.shop')

@section('title', 'Error del Servidor')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
            <div class="text-9xl mb-6">⚠️</div>
            <h1 class="text-6xl font-bold text-gray-800 mb-4">500</h1>
            <h2 class="text-3xl font-semibold text-gray-700 mb-4">Error del Servidor</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-lg mx-auto">
                Oops! Algo salió mal en nuestro servidor. Estamos trabajando para solucionarlo.
            </p>
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8 max-w-md mx-auto">
                <p class="text-red-800 font-medium mb-2">
                    Error interno del servidor
                </p>
                <p class="text-red-600 text-sm">
                    Por favor, intenta nuevamente en unos momentos. Si el problema persiste, contáctanos.
                </p>
            </div>

            <div class="space-x-4">
                <button data-action="reload-page" class="inline-block bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition font-medium">
                    🔄 Reintentar
                </button>
                <a href="{{ route('home') }}" class="inline-block bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
                    🏠 Ir al Inicio
                </a>
            </div>

            <div class="mt-12 text-gray-500">
                <p class="mb-2">Código de error: 500</p>
                <p class="text-sm">Si necesitas ayuda, contacta a nuestro equipo de soporte.</p>
            </div>
        </div>
    </div>
</div>
@endsection
