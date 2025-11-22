@extends('layouts.admin')

@section('title', 'Buscar Pedido')
@section('page-title', 'Buscar Pedido')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Formulario de Búsqueda -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔍 Buscar Pedido por Número</h3>
        <form method="GET" action="{{ route('admin.orders.search') }}" class="flex gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="order_number" 
                    value="{{ request('order_number') }}" 
                    placeholder="Ingrese el número de orden (ej: 110000001)" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-lg"
                    required
                >
            </div>
            <button type="submit" class="px-6 py-3 bg-orange-600 text-white rounded-md hover:bg-orange-700 font-medium">
                🔍 Buscar
            </button>
        </form>
        <p class="text-sm text-gray-500 mt-2">
            Ingrese el número completo del pedido para buscar en el historial
        </p>
    </div>

    @if(request()->filled('order_number'))
        @if($order)
            <!-- Resultado de la Búsqueda -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2 text-green-800">
                    <span class="text-2xl">✅</span>
                    <p class="font-semibold">Pedido encontrado</p>
                </div>
            </div>

            <!-- Información del Pedido -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Información Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Datos del Cliente -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">👤 Información del Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nombre</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="mt-1 text-gray-900">{{ $order->customer_email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Teléfono</label>
                                <p class="mt-1 text-gray-900">{{ $order->customer_phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Dirección de Entrega</label>
                                <p class="mt-1 text-gray-900">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🍴 Productos del Pedido</h3>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-4 pb-4 border-b border-gray-200 last:border-0">
                                @if($item->product && $item->product->image_url)
                                <img src="{{ asset('storage/' . $item->product->image_url) }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-2xl">🍽️</span>
                                </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                    <p class="text-sm font-mono font-bold text-orange-600">PLU: {{ $item->product->plu ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">${{ number_format($item->quantity * $item->price, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="mt-6 pt-4 border-t border-gray-200 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->tax > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">IVA:</span>
                                <span class="text-gray-900">${{ number_format($order->tax, 2) }}</span>
                            </div>
                            @endif
                            @if($order->delivery_fee > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Costo de envío:</span>
                                <span class="text-gray-900">${{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-lg font-bold pt-2 border-t">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-orange-600">${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->notes)
                    <!-- Notas del Cliente -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">💬 Notas del Cliente</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $order->notes }}</p>
                    </div>
                    @endif

                    @if($order->internal_notes)
                    <!-- Notas Internas -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-4">📝 Notas Internas</h3>
                        <p class="text-yellow-800 whitespace-pre-line">{{ $order->internal_notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Panel Lateral -->
                <div class="space-y-6">
                    <!-- Detalles del Pedido -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Detalles</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <label class="block text-gray-500">Número de Orden</label>
                                <p class="text-gray-900 font-bold text-lg">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-500">Estado</label>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'delivered' => 'bg-blue-100 text-blue-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pendiente',
                                        'confirmed' => 'Confirmado',
                                        'delivered' => 'Entregado',
                                        'cancelled' => 'Cancelado',
                                    ];
                                @endphp
                                <span class="inline-flex mt-1 px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-gray-500">Estado de Pago</label>
                                @php
                                    $paymentColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                    ];
                                    $paymentLabels = [
                                        'pending' => 'Pendiente',
                                        'paid' => 'Pagado',
                                        'failed' => 'Fallido',
                                    ];
                                @endphp
                                <span class="inline-flex mt-1 px-3 py-1 text-sm font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-gray-500">Fecha de Entrega</label>
                                <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</p>
                                @if($order->delivery_time)
                                <p class="text-gray-600 text-xs">{{ $order->delivery_time }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-gray-500">Método de Pago</label>
                                <p class="text-gray-900 font-medium">{{ ucfirst($order->payment_method) }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-500">Fecha de Creación</label>
                                <p class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($order->paid_at)
                            <div>
                                <label class="block text-gray-500">Fecha de Pago</label>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <a href="{{ route('admin.orders.show', $order) }}" class="block w-full px-4 py-2 bg-orange-600 text-white text-center rounded-md hover:bg-orange-700 font-medium">
                            Ver Detalle Completo
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- No encontrado -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">❌</span>
                    <div>
                        <p class="font-semibold text-red-800">Pedido no encontrado</p>
                        <p class="text-sm text-red-700 mt-1">No se encontró ningún pedido con el número: <strong>{{ request('order_number') }}</strong></p>
                        <p class="text-sm text-red-600 mt-2">Verifica que el número sea correcto e intenta nuevamente.</p>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Mensaje inicial -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <span class="text-5xl">🔍</span>
            <h3 class="text-xl font-semibold text-blue-900 mt-4">Buscar Pedido Histórico</h3>
            <p class="text-blue-700 mt-2">Ingresa el número de pedido en el campo de arriba para buscar en todo el historial de pedidos.</p>
        </div>
    @endif
</div>
@endsection
