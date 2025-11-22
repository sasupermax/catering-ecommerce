@extends('layouts.admin')

@section('title', 'Nueva Oferta')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.offers.index') }}" class="text-orange-600 hover:text-orange-800">← Volver a Ofertas</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">Nueva Oferta</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Oferta *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Ej: Descuento de Verano">
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Describe los detalles de la oferta...">{{ old('description') }}</textarea>
            </div>

            <!-- Tipo de Descuento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Descuento *</label>
                <select name="discount_type" id="discount_type" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Porcentaje (%)</option>
                    <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Monto Fijo ($)</option>
                </select>
            </div>

            <!-- Valor del Descuento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span id="discount_label">Descuento (%)</span> *
                </label>
                <input type="number" name="discount_value" value="{{ old('discount_value') }}" required step="0.01" min="0"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="0.00">
            </div>

            <!-- Fecha Inicio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio *</label>
                <input type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            <!-- Fecha Fin -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin *</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            <!-- Monto Mínimo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Monto Mínimo de Compra</label>
                <input type="number" name="min_purchase_amount" value="{{ old('min_purchase_amount', 0) }}" step="0.01" min="0"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="0.00">
                <p class="text-xs text-gray-500 mt-1">Opcional: Monto mínimo para aplicar la oferta</p>
            </div>

            <!-- Activa -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Activar oferta</label>
            </div>

            <!-- Imagen -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen de la Oferta</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Formatos: JPEG, PNG, GIF, WebP. Tamaño recomendado: 800x600px. Máximo 2MB.</p>
            </div>

            <!-- Productos -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Productos Incluidos</label>
                <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto bg-gray-50">
                    @foreach($products as $product)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="products[]" value="{{ $product->id }}" id="product_{{ $product->id }}"
                            {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}
                            class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <label for="product_{{ $product->id }}" class="ml-2 text-sm text-gray-700">
                            {{ $product->name }} - <span class="text-orange-600 font-semibold">${{ number_format($product->price, 2) }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-1">Selecciona los productos a los que aplica esta oferta</p>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('admin.offers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                Crear Oferta
            </button>
        </div>
    </form>
</div>
@endsection
