@extends('layouts.shop')

@section('title', 'Servicio No Disponible')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
            <div class="text-9xl mb-6">🔧</div>
            <h1 class="text-6xl font-bold text-gray-800 mb-4">503</h1>
            <h2 class="text-3xl font-semibold text-gray-700 mb-4">Servicio No Disponible</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-lg mx-auto">
                Estamos realizando tareas de mantenimiento. Volveremos pronto.
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 max-w-md mx-auto">
                <p class="text-blue-800 font-medium mb-2">
                    🛠️ Mantenimiento Programado
                </p>
                <p class="text-blue-600 text-sm">
                    Estamos mejorando nuestra plataforma para ofrecerte una mejor experiencia. Por favor, vuelve en unos minutos.
                </p>
            </div>

            <div class="space-x-4">
                <button data-action="reload-page" class="inline-block bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition font-medium">
                    🔄 Reintentar
                </button>
            </div>

            <div class="mt-12 text-gray-500">
                <p class="text-sm">Tiempo estimado de regreso: unos minutos</p>
                <p class="text-sm mt-2">Gracias por tu paciencia.</p>
            </div>
        </div>
    </div>
</div>
@endsection
