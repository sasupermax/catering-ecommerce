@extends('layouts.shop')

@section('title', 'Pago Pendiente')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-6">
            <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Pago Pendiente</h1>
        <p class="text-lg text-gray-600 mb-6">Tu pago está siendo procesado</p>
        
        @if($order)
        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
            <h2 class="font-bold text-gray-800 mb-4">Detalles del Pedido</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Número de Orden:</span>
                    <span class="font-semibold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Fecha de Entrega:</span>
                    <span class="font-semibold">{{ $order->delivery_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between text-lg border-t pt-2 mt-2">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-bold text-yellow-600">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
        
        <p class="text-sm text-gray-600 mb-6">
            Te notificaremos por email a <strong>{{ $order->customer_email }}</strong> cuando se confirme el pago.
        </p>
        @endif
        
        <div class="flex gap-4">
            <a href="{{ route('home') }}" class="flex-1 bg-orange-600 text-white py-3 rounded-lg font-semibold hover:bg-orange-700 transition text-center">
                Volver al Inicio
            </a>
        </div>
    </div>
</div>
@endsection
