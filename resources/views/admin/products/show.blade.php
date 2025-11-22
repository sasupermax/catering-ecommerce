@extends('layouts.admin')

@section('title', 'Ver Producto')
@section('page-title', 'Detalle del Producto')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Imagen -->
            <div>
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow">
                @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-6xl">🍴</span>
                </div>
                @endif
            </div>

            <!-- Información -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>
                <p class="text-sm text-gray-600 mb-4">Categoría: {{ $product->category->name }}</p>

                <div class="mb-6">
                    @if($product->is_available)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                        Disponible
                    </span>
                    @else
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                        No disponible
                    </span>
                    @endif
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tipo de Producto</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $product->type_name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Precio</p>
                        <p class="text-2xl font-bold text-orange-600">${{ number_format($product->price, 2) }} <span class="text-sm text-gray-500">{{ $product->price_suffix }}</span></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Pedido Mínimo</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $product->min_order }}</p>
                    </div>

                    @if($product->description)
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Descripción</p>
                        <p class="text-gray-800">{{ $product->description }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Slug</p>
                        <p class="text-gray-800 font-mono text-sm">{{ $product->slug }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Fecha de Creación</p>
                        <p class="text-gray-800">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($product->updated_at != $product->created_at)
                    <div>
                        <p class="text-sm text-gray-600">Última Actualización</p>
                        <p class="text-gray-800">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Acciones -->
                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Volver
                    </a>
                    @if(auth()->user()->hasPermission('edit-products'))
                    <a href="{{ route('admin.products.edit', $product) }}" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                        Editar
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
