@extends('layouts.shop')

@section('title', 'Carrito de Compras')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <h1 class="text-2xl sm:text-3xl swissregular text-gray-800 mb-6 sm:mb-8">Carrito de Compras</h1>

    @if(count($cartItems) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Items del Carrito -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6" data-product-id="{{ $item['product_id'] }}">
                <div class="flex gap-3 sm:gap-4">
                    {{-- Imagen clickeable que redirige al producto --}}
                    <a href="{{ route('product', $item['product']->slug) }}" class="flex-shrink-0">
                        @if($item['product']->image)
                        <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-lg hover:opacity-80 transition">
                        @else
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-gray-100 to-red-400 rounded-lg flex items-center justify-center hover:opacity-80 transition">
                            <span class="text-3xl">🍴</span>
                        </div>
                        @endif
                    </a>

                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 truncate">{{ $item['product']->name }}</h3>
                        <!-- <p class="text-sm text-gray-600">{{ $item['product']->category->name }}</p> -->
                        
                        {{-- Mostrar subtotal del item en móvil, ocultar en desktop --}}
                        <p class="text-lg font-bold text-gray-800 mt-1 sm:hidden" id="subtotal-mobile-{{ $item['product_id'] }}">
                            ${{ number_format($item['subtotal'], 2) }}
                        </p>
                        
                        @if($item['offer'])
                        <div class="flex items-center gap-2 mt-1">
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="m20.749 12l1.104-1.908a1 1 0 0 0-.365-1.366l-1.91-1.104v-2.2a1 1 0 0 0-1-1h-2.199l-1.103-1.909a1 1 0 0 0-.607-.466a1 1 0 0 0-.759.1L12 3.251l-1.91-1.105a1 1 0 0 0-1.366.366L7.62 4.422H5.421a1 1 0 0 0-1 1v2.199l-1.91 1.104a1 1 0 0 0-.365 1.367L3.25 12l-1.104 1.908a1.004 1.004 0 0 0 .364 1.367l1.91 1.104v2.199a1 1 0 0 0 1 1h2.2l1.104 1.91a1.01 1.01 0 0 0 .866.5c.174 0 .347-.046.501-.135l1.908-1.104l1.91 1.104a1 1 0 0 0 1.366-.365l1.103-1.91h2.199a1 1 0 0 0 1-1v-2.199l1.91-1.104a1 1 0 0 0 .365-1.367zM9.499 6.99a1.5 1.5 0 1 1-.001 3.001a1.5 1.5 0 0 1 .001-3.001m.3 9.6l-1.6-1.199l6-8l1.6 1.199zm4.7.4a1.5 1.5 0 1 1 .001-3.001a1.5 1.5 0 0 1-.001 3.001"/></svg>
                                {{ $item['offer']->name }}
                            </span>
                        </div>
                        @endif
                        
                        {{-- Mostrar si hay una oferta disponible pero no se cumple el monto mínimo --}}
                        @php
                            $productOffer = $item['product']->getActiveOffer();
                            if (!$item['offer'] && $productOffer && !$productOffer->isApplicable(array_sum(array_map(fn($i) => $i['original_subtotal'] ?? ($i['quantity'] * $i['product']->price), $cartItems)))) {
                                $missingAmount = $productOffer->min_purchase_amount - array_sum(array_map(fn($i) => $i['original_subtotal'] ?? ($i['quantity'] * $i['product']->price), $cartItems));
                                echo '<p class="flex items-center gap-1 text-xs text-amber-600 mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="m22 10l-.625-1.375L20 8l1.375-.625L22 6l.625 1.375L24 8l-1.375.625L22 10Zm-3-4l-.95-2.05L16 3l2.05-.95L19 0l.95 2.05L22 3l-2.05.95L19 6ZM9 22q-.825 0-1.413-.588T7 20h4q0 .825-.588 1.413T9 22Zm-4-3v-2h8v2H5Zm.25-3q-1.725-1.025-2.738-2.75T1.5 9.5q0-3.125 2.188-5.313T9 2q3.125 0 5.313 2.188T16.5 9.5q0 2.025-1.012 3.75T12.75 16h-7.5Z"/></svg> Agrega $' . number_format($missingAmount, 2) . ' más para activar: ' . $productOffer->name . '</p>';
                            }
                        @endphp

                        <div class="flex items-center gap-2 sm:gap-4 mt-3">
                            <div class="flex items-center gap-1 sm:gap-2">
                                <button data-action="decrease-qty" data-product-id="{{ $item['product_id'] }}" data-step="{{ $item['product']->type === 'P' ? '0.5' : '1' }}" class="bg-gray-200 hover:bg-gray-300 px-2 sm:px-3 py-1 rounded text-sm font-bold">-</button>
                                <input type="number" id="qty-{{ $item['product_id'] }}" value="{{ $item['product']->type === 'P' ? number_format($item['quantity'], 1, '.', '') : $item['quantity'] }}" step="{{ $item['product']->type === 'P' ? '0.5' : '1' }}" min="{{ $item['product']->type === 'P' ? '0.5' : '1' }}" max="10000" 
                                    class="w-12 sm:w-16 text-center border border-gray-300 rounded py-1 text-sm" data-action="update-qty-input" data-product-id="{{ $item['product_id'] }}">
                                <button data-action="increase-qty" data-product-id="{{ $item['product_id'] }}" data-step="{{ $item['product']->type === 'P' ? '0.5' : '1' }}" class="bg-gray-200 hover:bg-gray-300 px-2 sm:px-3 py-1 rounded text-sm font-bold">+</button>
                            </div>
                            <button data-action="remove-item" data-product-id="{{ $item['product_id'] }}" class="text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium flex items-center gap-1">
                                <span class="hidden sm:inline"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class=""><g fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 5a1 1 0 0 0-1 1h4a1 1 0 0 0-1-1h-2zm0-2a3 3 0 0 0-3 3H4a1 1 0 0 0 0 2h1v10a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 1 0 0-2h-4a3 3 0 0 0-3-3h-2zm0 8a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0v-5zm4 0a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0v-5z"/></g></svg></span> Eliminar
                            </button>
                        </div>
                    </div>

                    {{-- Subtotal solo visible en desktop --}}
                    <div class="hidden sm:block text-right flex-shrink-0">
                        <p class="text-lg sm:text-xl font-bold text-gray-800" id="subtotal-{{ $item['product_id'] }}">
                            ${{ number_format($item['subtotal'], 2) }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Resumen -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Resumen del Pedido</h2>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="cart-subtotal">${{ number_format($subtotalOriginal, 2) }}</span>
                    </div>
                    
                    @if($totalDiscount > 0)
                    <div class="flex justify-between text-green-600">
                        <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m20.749 12l1.104-1.908a1 1 0 0 0-.365-1.366l-1.91-1.104v-2.2a1 1 0 0 0-1-1h-2.199l-1.103-1.909a1 1 0 0 0-.607-.466a1 1 0 0 0-.759.1L12 3.251l-1.91-1.105a1 1 0 0 0-1.366.366L7.62 4.422H5.421a1 1 0 0 0-1 1v2.199l-1.91 1.104a1 1 0 0 0-.365 1.367L3.25 12l-1.104 1.908a1.004 1.004 0 0 0 .364 1.367l1.91 1.104v2.199a1 1 0 0 0 1 1h2.2l1.104 1.91a1.01 1.01 0 0 0 .866.5c.174 0 .347-.046.501-.135l1.908-1.104l1.91 1.104a1 1 0 0 0 1.366-.365l1.103-1.91h2.199a1 1 0 0 0 1-1v-2.199l1.91-1.104a1 1 0 0 0 .365-1.367zM9.499 6.99a1.5 1.5 0 1 1-.001 3.001a1.5 1.5 0 0 1 .001-3.001m.3 9.6l-1.6-1.199l6-8l1.6 1.199zm4.7.4a1.5 1.5 0 1 1 .001-3.001a1.5 1.5 0 0 1-.001 3.001"/></svg> Descuentos</span>
                        <span id="cart-discount">-${{ number_format($totalDiscount, 2) }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-green-600">
                        <span>Envío</span>
                        <span>Gratis</span>
                    </div>
                </div>

                <div class="border-t pt-4 mb-6">
                    <div class="flex justify-between text-xl font-bold text-gray-800">
                        <span>Total</span>
                        <span id="cart-total-final">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div id="minimum-warning" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 {{ $total >= $minOrderAmount ? 'hidden' : '' }}">
                    <p class="text-sm text-red-700">
                        <strong>⚠️ Monto mínimo no alcanzado</strong><br>
                        El monto mínimo de compra es <strong>${{ number_format($minOrderAmount, 2) }}</strong>.<br>
                        Te faltan <strong id="amount-needed">${{ number_format(max(0, $minOrderAmount - $total), 2) }}</strong>
                    </p>
                </div>

                <!-- Botón de actualizar carrito (aparece solo cuando hay cambios) -->
                <button id="update-cart-btn" 
                        data-action="reload-cart" 
                        class="hidden w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition mb-4 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 20q-3.35 0-5.675-2.325T4 12t2.325-5.675T12 4q1.725 0 3.3.712T18 6.75V4h2v7h-7V9h4.2q-.8-1.4-2.188-2.2T12 6Q9.5 6 7.75 7.75T6 12t1.75 4.25T12 18q1.925 0 3.475-1.1T17.65 14h2.1q-.7 2.65-2.85 4.325T12 20"/></svg>
                    Actualizar Carrito
                </button>
                
                <a id="checkout-btn" 
                   @if($total >= $minOrderAmount) href="{{ route('checkout.index') }}" @endif
                   class="block w-full text-center py-3 rounded-lg font-semibold transition {{ $total >= $minOrderAmount ? 'bg-[#ffd90f] hover:bg-[#fad300]' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                   data-action="checkout" @if($total < $minOrderAmount) data-disabled="true" @endif>
                    Continuar con el Pedido
                </a>

                <a href="{{ route('home') }}" class="block w-full text-center text-red-600 mt-3 hover:underline">
                    Seguir Comprando
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gray-50 rounded-lg p-12 text-center">
        <div class="flex justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 576 512" class="text-gray-400"><path fill="currentColor" d="M0 24C0 10.7 10.7 0 24 0h45.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5l-51.6-271c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24m128 440a48 48 0 1 1 96 0a48 48 0 1 1-96 0m336-48a48 48 0 1 1 0 96a48 48 0 1 1 0-96"/></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Tu carrito está vacío</h2>
        <p class="text-gray-600 mb-6">Agrega productos para comenzar tu pedido</p>
        <a href="{{ route('home') }}" class="inline-block bg-[#ffd90f] font-semibold px-6 py-3 rounded-lg hover:bg-[#fad300] transition">
            Ver Productos
        </a>
    </div>
    @endif
</div>

@endsection
