@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Productos -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Productos</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['products'] }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <span class="text-3xl">🍴</span>
            </div>
        </div>
        @if(auth()->user()->hasPermission('view-products'))
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 text-sm mt-4 inline-block hover:underline">
            Ver todos →
        </a>
        @endif
    </div>

    <!-- Categorías -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Categorías</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['categories'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <span class="text-3xl">📁</span>
            </div>
        </div>
        @if(auth()->user()->hasPermission('view-categories'))
        <a href="{{ route('admin.categories.index') }}" class="text-green-600 text-sm mt-4 inline-block hover:underline">
            Ver todas →
        </a>
        @endif
    </div>

    <!-- Pedidos -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Pedidos</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['orders'] }}</p>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <span class="text-3xl">📦</span>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-orange-600 text-sm mt-4 inline-block hover:underline">
            Ver todos →
        </a>
    </div>

    <!-- Usuarios -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Usuarios</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['users'] }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <span class="text-3xl">👥</span>
            </div>
        </div>
        @if(auth()->user()->hasPermission('view-users'))
        <a href="{{ route('admin.users.index') }}" class="text-purple-600 text-sm mt-4 inline-block hover:underline">
            Ver todos →
        </a>
        @endif
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.orders.index') }}" class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
                <span class="mr-3 text-2xl">📦</span>
                <div>
                    <p class="font-semibold text-gray-800">Ver Todos los Pedidos</p>
                    <p class="text-sm text-gray-600">Gestionar y filtrar pedidos</p>
                </div>
            </a>
            
            @if(auth()->user()->hasPermission('create-products'))
            <a href="{{ route('admin.products.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                <span class="mr-3 text-2xl">➕</span>
                <div>
                    <p class="font-semibold text-gray-800">Nuevo Producto</p>
                    <p class="text-sm text-gray-600">Agregar producto al catálogo</p>
                </div>
            </a>
            @endif

            @if(auth()->user()->hasPermission('create-categories'))
            <a href="{{ route('admin.categories.create') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                <span class="mr-3 text-2xl">📁</span>
                <div>
                    <p class="font-semibold text-gray-800">Nueva Categoría</p>
                    <p class="text-sm text-gray-600">Crear categoría de productos</p>
                </div>
            </a>
            @endif

            @if(auth()->user()->hasPermission('create-users'))
            <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                <span class="mr-3 text-2xl">👤</span>
                <div>
                    <p class="font-semibold text-gray-800">Nuevo Usuario</p>
                    <p class="text-sm text-gray-600">Registrar nuevo usuario</p>
                </div>
            </a>
            @endif
        </div>
    </div>

    <!-- Pedidos para Entregar Hoy -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">🚚 Entregas de Hoy</h3>
            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $stats['todayDeliveries'] }}</span>
        </div>
        @if($todayOrders->count() > 0)
        <div class="space-y-3 max-h-80 overflow-y-auto">
            @foreach($todayOrders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="block p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-800">#{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-600">{{ $order->customer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->delivery_time }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                            ];
                            $statusLabels = [
                                'pending' => 'Pendiente',
                                'confirmed' => 'Confirmado',
                            ];
                        @endphp
                        <span class="text-xs px-2 py-1 rounded font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                        </span>
                        <p class="font-semibold text-orange-600 mt-1">${{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">✅ No hay entregas programadas para hoy</p>
        @endif
    </div>
</div>

<!-- Pedidos Recientes -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Pedidos Recientes</h3>
    @if($recentOrders->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nº Orden</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Entrega</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentOrders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $order->customer_name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-orange-600">${{ number_format($order->total, 2) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
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
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-orange-600 hover:text-orange-900">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-gray-500 text-center py-8">No hay pedidos recientes</p>
    @endif
</div>

<!-- Información de Permisos -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h3 class="text-lg font-semibold text-blue-900 mb-2">👤 Tu Rol: {{ auth()->user()->roles->first()->name ?? 'Sin rol' }}</h3>
    <p class="text-blue-700 mb-4">{{ auth()->user()->roles->first()->description ?? '' }}</p>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach(auth()->user()->roles->first()->permissions ?? [] as $permission)
        <div class="bg-white rounded px-3 py-2 text-sm text-gray-700">
            ✓ {{ $permission->name }}
        </div>
        @endforeach
    </div>
</div>
@endsection
