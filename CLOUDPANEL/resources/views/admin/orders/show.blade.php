@extends('layouts.admin')

@section('title', 'Detalle del Pedido #' . $order->order_number)
@section('page-title', 'Detalle del Pedido #' . $order->order_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-orange-600 hover:text-orange-800">
        ← Volver a Pedidos
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del Pedido -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Información del Cliente -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">👤 Información del Cliente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nombre</label>
                    <p class="mt-1 text-gray-900">{{ $order->customer_name }}</p>
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

        <!-- Productos del Pedido -->
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
                        <h4 class="font-semibold text-gray-900">{{ $item->product_name }}</h4>
                        <p class="text-sm font-mono font-bold text-orange-600">PLU: {{ $item->product->plu ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                        <p class="text-sm text-gray-500">Precio unitario: ${{ number_format($item->price, 2) }}</p>
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

        <!-- Notas del Cliente -->
        @if($order->notes)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💬 Notas del Cliente</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $order->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        <!-- Estado del Pedido -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Estado del Pedido</h3>
            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado del Pedido</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pendiente</option>
                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Confirmado</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>📦 Entregado</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Pago</label>
                    <div class="px-3 py-2 border border-gray-200 rounded-md bg-gray-50">
                        @php
                            $paymentColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                            ];
                            $paymentLabels = [
                                'pending' => '⏳ Pendiente',
                                'paid' => '✅ Pagado',
                                'failed' => '❌ Fallido',
                            ];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>
                    </div>
                    @if($order->payment_id)
                    <p class="text-xs text-gray-500 mt-1">ID: {{ $order->payment_id }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Entrega</label>
                    <input type="date" name="delivery_date" value="{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : \Carbon\Carbon::today()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Horario de Entrega</label>
                    <input type="text" name="delivery_time" value="{{ $order->delivery_time }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Ej: 12:00 - 14:00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notas Internas</label>
                    <textarea name="internal_notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Notas visibles solo para el equipo...">{{ $order->internal_notes ?? '' }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Estas notas no son visibles para el cliente</p>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 font-medium">
                    💾 Guardar Cambios
                </button>
            </form>
        </div>

        <!-- Información Adicional -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ Información Adicional</h3>
            <div class="space-y-3 text-sm">
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
                <div>
                    <label class="block text-gray-500">Última Actualización</label>
                    <p class="text-gray-900">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
