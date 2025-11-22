@extends('layouts.shop')

@section('title', 'Demasiadas Solicitudes')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
            <div class="text-9xl mb-6">🚦</div>
            <h1 class="text-6xl font-bold text-gray-800 mb-4">429</h1>
            <h2 class="text-3xl font-semibold text-gray-700 mb-4">Demasiadas Solicitudes</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-lg mx-auto">
                Has realizado demasiadas solicitudes en poco tiempo. Por favor, espera un momento.
            </p>
            
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-8 max-w-md mx-auto">
                <p class="text-orange-800 font-medium mb-2">
                    ⏳ Límite de Solicitudes Alcanzado
                </p>
                <p class="text-orange-700 text-sm">
                    Para proteger nuestro servicio, limitamos el número de solicitudes por minuto. Intenta de nuevo en unos segundos.
                </p>
            </div>

            <div class="space-x-4">
                <button data-action="reload-countdown" class="inline-block bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition font-medium">
                    🔄 Esperar y Reintentar
                </button>
                <a href="{{ route('home') }}" class="inline-block bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
                    🏠 Ir al Inicio
                </a>
            </div>

            <div class="mt-12 text-gray-500">
                <p class="text-sm">Por favor, espera unos segundos antes de intentar nuevamente.</p>
            </div>
        </div>
    </div>
</div>
@endsection
