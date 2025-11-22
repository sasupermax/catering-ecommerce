@extends('layouts.shop')

@section('title', 'Finalizar Pedido')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Breadcrumb -->
    <div class="mb-6 sm:mb-8">
        <nav class="text-xs sm:text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-red-600">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-red-600">Carrito</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-semibold">Checkout</span>
        </nav>
    </div>

    <h1 class="text-2xl sm:text-3xl swissbold text-gray-800 mb-6 sm:mb-8">Finalizar Pedido</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Formulario de Datos -->
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Datos de Contacto -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="flex items-center text-xl font-bold text-gray-800 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-red-400 mr-2"><path fill="currentColor" d="M12 14q.825 0 1.413-.587T14 12t-.587-1.412T12 10t-1.412.588T10 12t.588 1.413T12 14m-4 4h8v-.575q0-.6-.325-1.1t-.9-.75q-.65-.275-1.338-.425T12 15t-1.437.15t-1.338.425q-.575.25-.9.75T8 17.425zm10 4H6q-.825 0-1.412-.587T4 20V4q0-.825.588-1.412T6 2h7.175q.4 0 .763.15t.637.425l4.85 4.85q.275.275.425.638t.15.762V20q0 .825-.587 1.413T18 22"/></svg> 
                        Datos de Contacto
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                @error('customer_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                                <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                @error('customer_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos de Entrega -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="flex items-center text-xl font-bold text-gray-800 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-red-400 mr-2"><path fill="currentColor" d="M1.75 13.325q-.425 0-.712-.287t-.288-.713t.288-.712t.712-.288h3.5q.425 0 .713.288t.287.712t-.288.713t-.712.287zM7 20q-1.25 0-2.125-.875T4 17H2.75q-.5 0-.8-.375t-.175-.85l.225-.95h3.125q1.05 0 1.775-.725t.725-1.775q0-.325-.075-.6t-.2-.55h.95q1.05 0 1.775-.725t.725-1.775t-.725-1.775T8.3 6.175H4.5l.15-.6q.15-.7.688-1.137T6.6 4h10.15q.5 0 .8.375t.175.85L17.075 8H19q.475 0 .9.213t.7.587l1.875 2.475q.275.35.35.763t0 .837L22.15 16.2q-.075.35-.35.575t-.625.225H20q0 1.25-.875 2.125T17 20t-2.125-.875T14 17h-4q0 1.25-.875 2.125T7 20M3.75 9.675q-.425 0-.712-.288t-.288-.712t.288-.712t.712-.288h4.5q.425 0 .713.288t.287.712t-.288.713t-.712.287zM7 18q.425 0 .713-.288T8 17t-.288-.712T7 16t-.712.288T6 17t.288.713T7 18m10 0q.425 0 .713-.288T18 17t-.288-.712T17 16t-.712.288T16 17t.288.713T17 18m-1.075-5h4.825l.1-.525L19 10h-2.375z"/></svg>
                         Datos de Entrega
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección de Entrega *</label>
                            <input type="text" name="delivery_address" value="{{ old('delivery_address') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                placeholder="Ej: Calle 123, Ciudad">
                            @error('delivery_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Entrega *</label>
                            <input type="date" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}" required
                                min="{{ $minDate->format('Y-m-d') }}"
                                max="{{ $maxDate->format('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <p id="fecha-info" class="text-xs text-gray-500 mt-1">Solo puedes elegir fechas disponibles ({{ $minDate->format('d/m/Y') }} - {{ $maxDate->format('d/m/Y') }})</p>
                            <p id="fecha-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('delivery_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notas Adicionales</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                placeholder="Instrucciones especiales, referencias, etc.">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('cart.index') }}" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m12 16l1.4-1.4l-1.6-1.6H16v-2h-4.2l1.6-1.6L12 8l-4 4zm0 6q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"/></svg>
                        Volver al Carrito
                    </a>
                    <button type="submit" class="flex-1 bg-[#ffd90f] py-3 rounded-lg font-semibold hover:bg-[#fad300] transition flex items-center justify-center gap-2">
                        Continuar al Pago
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m12 16l4-4l-4-4l-1.4 1.4l1.6 1.6H8v2h4.2l-1.6 1.6zm0 6q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Resumen del Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 mt-6 sticky top-20">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Resumen del Pedido</h2>
                
                <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                    @foreach($cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $item['name'] }}</p>
                            <p class="text-gray-600">{{ $item['quantity'] }} {{ $item['type'] === 'P' ? 'kg' : 'ud' }} × ${{ number_format($item['price'], 2) }}</p>
                        </div>
                        <p class="font-semibold text-gray-800">${{ number_format($item['subtotal'], 2) }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Envío</span>
                        <span>Gratis</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t">
                        <span>Total</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
