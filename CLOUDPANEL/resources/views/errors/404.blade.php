@extends('layouts.shop')

@section('title', 'Página No Encontrada')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
            <div class="text-9xl mb-6">🔍</div>
            <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
            <h2 class="text-3xl font-semibold text-gray-700 mb-4">Página No Encontrada</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-lg mx-auto">
                Lo sentimos, la página que estás buscando no existe o ha sido movida.
            </p>
            
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-8 max-w-md mx-auto">
                <p class="text-orange-800 font-medium">
                    ¿Perdido? No te preocupes, te ayudamos a encontrar lo que buscas.
                </p>
            </div>

            <div class="space-x-4">
                <a href="{{ route('home') }}" class="inline-block bg-orange-600 text-white px-8 py-3 rounded-lg hover:bg-orange-700 transition font-medium">
                    🏠 Ir al Inicio
                </a>
                <a href="{{ route('home') }}" class="inline-block bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
                    🛒 Ver Productos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
