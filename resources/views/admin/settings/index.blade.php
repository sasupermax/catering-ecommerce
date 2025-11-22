@extends('layouts.admin')

@section('title', 'Configuración del Sitio')
@section('page-title', 'Configuración del Sitio')

@section('content')
<div class="max-w-4xl">
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Información General del Sitio -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Información General</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Sitio *</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']->value ?? '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('site_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email de Contacto *</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email']->value ?? '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('site_email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono de Contacto *</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $settings['site_phone']->value ?? '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('site_phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                    <textarea name="site_address" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ old('site_address', $settings['site_address']->value ?? '') }}</textarea>
                    @error('site_address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Configuración de Pedidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Configuración de Pedidos</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Máximo de Pedidos por Día *</label>
                    <input type="number" name="max_orders_per_day" min="1" max="100" 
                        value="{{ old('max_orders_per_day', $settings['max_orders_per_day']->value ?? 10) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Cantidad máxima de pedidos que se pueden procesar en un día</p>
                    @error('max_orders_per_day')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Días Mínimos de Anticipación *</label>
                    <input type="number" name="min_days_advance" min="0" max="30" 
                        value="{{ old('min_days_advance', $settings['min_days_advance']->value ?? 1) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Días mínimos con los que un cliente debe hacer su pedido</p>
                    @error('min_days_advance')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Días Máximos de Anticipación *</label>
                    <input type="number" name="max_days_advance" min="1" max="90" 
                        value="{{ old('max_days_advance', $settings['max_days_advance']->value ?? 30) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Días máximos con los que un cliente puede programar su pedido</p>
                    @error('max_days_advance')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Configuración de Ventas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Configuración de Ventas</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Monto Mínimo de Compra ($) *</label>
                <input type="number" name="minimum_order_amount" min="0" step="0.01" 
                    value="{{ old('minimum_order_amount', $settings['minimum_order_amount']->value ?? 1000) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Monto mínimo en pesos que debe alcanzar un pedido para ser procesado</p>
                @error('minimum_order_amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duración del Carousel (milisegundos) *</label>
                <input type="number" name="carousel_transition_duration" min="1000" max="30000" step="100" 
                    value="{{ old('carousel_transition_duration', $settings['carousel_transition_duration']->value ?? 5000) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Tiempo en milisegundos que cada banner permanece visible (5000 = 5 segundos)</p>
                @error('carousel_transition_duration')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Modo Mantenimiento -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Estado del Sitio</h2>
            
            <label class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg cursor-pointer hover:bg-yellow-100 transition">
                <input type="checkbox" name="maintenance_mode" value="1" 
                    {{ old('maintenance_mode', $settings['maintenance_mode']->value ?? false) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                <div class="ml-3">
                    <span class="text-sm font-semibold text-gray-900">Activar Modo Mantenimiento</span>
                    <p class="text-xs text-gray-600">Al activarlo, los clientes no podrán realizar pedidos temporalmente</p>
                </div>
            </label>
        </div>

        <!-- Métodos de Pago -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Métodos de Pago Habilitados</h2>
            <p class="text-sm text-gray-600 mb-4">Selecciona qué métodos de pago estarán disponibles para los clientes</p>
            
            <div class="space-y-3">
                <!-- Mercado Pago -->
                <label class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-100 transition">
                    <input type="checkbox" name="payment_mercadopago_enabled" value="1" 
                        {{ old('payment_mercadopago_enabled', $settings['payment_mercadopago_enabled']->value ?? true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <div class="ml-3">
                        <span class="text-sm font-semibold text-gray-900">💳 Mercado Pago</span>
                        <p class="text-xs text-gray-600">Tarjetas de crédito, débito y más opciones online</p>
                    </div>
                </label>

                <!-- Transferencia Bancaria -->
                <label class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg cursor-pointer hover:bg-green-100 transition">
                    <input type="checkbox" name="payment_bank_transfer_enabled" value="1" 
                        {{ old('payment_bank_transfer_enabled', $settings['payment_bank_transfer_enabled']->value ?? true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                    <div class="ml-3">
                        <span class="text-sm font-semibold text-gray-900">🏦 Transferencia Bancaria</span>
                        <p class="text-xs text-gray-600">Pago manual mediante transferencia</p>
                    </div>
                </label>

                <!-- Efectivo -->
                <label class="flex items-center p-4 bg-orange-50 border border-orange-200 rounded-lg cursor-pointer hover:bg-orange-100 transition">
                    <input type="checkbox" name="payment_cash_enabled" value="1" 
                        {{ old('payment_cash_enabled', $settings['payment_cash_enabled']->value ?? true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                    <div class="ml-3">
                        <span class="text-sm font-semibold text-gray-900">💵 Efectivo contra entrega</span>
                        <p class="text-xs text-gray-600">Pago en efectivo al recibir el pedido</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Mercado Pago -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Credenciales de Mercado Pago</h2>
                <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Configuración Avanzada</span>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Public Key</label>
                    <input type="text" name="mercadopago_public_key" 
                        value="{{ old('mercadopago_public_key', $settings['mercadopago_public_key']->value ?? '') }}"
                        placeholder="APP_USR-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('mercadopago_public_key')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Access Token</label>
                    <input type="password" name="mercadopago_access_token" 
                        value="{{ old('mercadopago_access_token', $settings['mercadopago_access_token']->value ?? '') }}"
                        placeholder="APP_USR-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Obtén tus credenciales en el panel de <a href="https://www.mercadopago.com.ar/developers/panel" target="_blank" class="text-orange-600 hover:underline">Mercado Pago</a></p>
                    @error('mercadopago_access_token')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Botón de Guardar -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                Guardar Configuración
            </button>
        </div>
    </form>
</div>
@endsection
