@extends('layouts.shop')

@section('title', 'Pago Fallido')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Pago No Completado</h1>
        <p class="text-lg text-gray-600 mb-6">El pago no pudo ser procesado</p>
        
        @if($order)
        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
            <p class="text-gray-600 mb-2">Tu pedido aún está registrado:</p>
            <div class="flex justify-between">
                <span class="text-gray-600">Número de Orden:</span>
                <span class="font-semibold">{{ $order->order_number }}</span>
            </div>
        </div>
        
        <p class="text-sm text-gray-600 mb-6">
            Puedes intentar pagar nuevamente o elegir otro método de pago.
        </p>
        @endif
        
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('checkout.payment') }}" class="flex-1 bg-orange-600 text-white py-3 rounded-lg font-semibold hover:bg-orange-700 transition text-center">
                Intentar Nuevamente
            </a>
            <a href="{{ route('home') }}" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                Volver al Inicio
            </a>
        </div>
    </div>
</div>
@endsection
