@extends('layouts.shop')

@section('title', 'Método de Pago')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <h1 class="text-2xl sm:text-3xl swissbold text-gray-800 mb-6 sm:mb-8">Seleccionar Método de Pago</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Métodos de Pago -->
        <div class="lg:col-span-2 space-y-4">
            @if($paymentMethods['mercadopago'])
            <!-- Mercado Pago -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-blue-50 rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/mercado-pago.svg') }}" alt="Mercado Pago Logo">
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Mercado Pago</h3>
                        <p class="text-sm text-gray-600">Tarjetas de crédito, débito y más</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Paga de forma segura con Mercado Pago. Acepta tarjetas de crédito, débito, efectivo y más opciones.
                </p>
                <form action="{{ route('mercadopago.create.preference') }}" method="POST" id="mercadopago-form">
                    @csrf
                    <button type="submit" class="w-full bg-[#009EE3] text-white py-3 rounded-lg font-semibold hover:bg-[#0085C6] transition shadow-sm flex items-center justify-center gap-2">
                        <span id="mp-btn-text">Pagar con Mercado Pago</span>
                        <svg id="mp-loader" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>
            @endif

            @if($paymentMethods['bank_transfer'])
            <!-- Transferencia Bancaria -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Transferencia Bancaria</h3>
                        <p class="text-sm text-gray-600">Pago manual por transferencia</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Realiza una transferencia bancaria y envíanos el comprobante.
                </p>
                <button data-action="select-payment" data-payment-method="bank_transfer" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Seleccionar Transferencia
                </button>
            </div>
            @endif

            @if($paymentMethods['cash'])
            <!-- Efectivo -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Efectivo contra entrega</h3>
                        <p class="text-sm text-gray-600">Paga en efectivo al recibir</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    Paga en efectivo cuando recibas tu pedido.
                </p>
                <button data-action="select-payment" data-payment-method="cash" class="w-full bg-orange-600 text-white py-3 rounded-lg font-semibold hover:bg-orange-700 transition">
                    Seleccionar Efectivo
                </button>
            </div>
            @endif

            @if(!$paymentMethods['mercadopago'] && !$paymentMethods['bank_transfer'] && !$paymentMethods['cash'])
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <p class="text-yellow-800 font-semibold">No hay métodos de pago disponibles</p>
                <p class="text-sm text-yellow-600 mt-2">Por favor contacta al administrador</p>
            </div>
            @endif
        </div>

        <!-- Resumen del Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Resumen del Pedido</h2>
                
                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Entrega</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($checkoutData['delivery_date'])->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($checkoutData['subtotal'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Envío</span>
                        <span>Gratis</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t">
                        <span>Total</span>
                        <span>${{ number_format($checkoutData['subtotal'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
