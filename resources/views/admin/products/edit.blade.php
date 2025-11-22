@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <!-- Nombre -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Categoría -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
            <select name="category_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- PLU -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">PLU (Identificador) *</label>
            <input type="text" name="plu" value="{{ old('plu', $product->plu) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="Ej: 1234">
            <p class="text-xs text-gray-500 mt-1">Código único de identificación del producto en el supermercado</p>
            @error('plu')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
            <textarea name="description" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tipo de Producto -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Venta *</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="type" value="N" {{ old('type', $product->type) === 'N' ? 'checked' : '' }} required
                        class="text-orange-600 focus:ring-orange-500">
                    <div class="ml-3">
                        <span class="font-medium text-gray-900">Por Unidad</span>
                        <p class="text-xs text-gray-500">Venta por cantidad de unidades</p>
                    </div>
                </label>
                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="type" value="P" {{ old('type', $product->type) === 'P' ? 'checked' : '' }} required
                        class="text-orange-600 focus:ring-orange-500">
                    <div class="ml-3">
                        <span class="font-medium text-gray-900">Por Kilo</span>
                        <p class="text-xs text-gray-500">Venta por peso (pesable)</p>
                    </div>
                </label>
            </div>
            @error('type')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Precio -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">El precio se mostrará por unidad o por kilo según el tipo seleccionado</p>
            @error('price')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pedido Mínimo -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pedido Mínimo (porciones) *</label>
            <input type="number" name="min_order" value="{{ old('min_order', $product->min_order) }}" min="1" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            @error('min_order')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Imagen Actual -->
        @if($product->image)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Actual</label>
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded">
        </div>
        @endif

        <!-- Nueva Imagen -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cambiar Imagen</label>
            <input type="file" name="image" accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            <p class="text-sm text-gray-500 mt-1">Dejar vacío para mantener la imagen actual</p>
            @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Disponible -->
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                <span class="ml-2 text-sm text-gray-700">Producto disponible para la venta</span>
            </label>
        </div>

        <!-- Producto Destacado -->
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500">
                <span class="ml-2 text-sm text-gray-700">
                    <span class="font-semibold">⭐ Producto Destacado</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Aparecerá en la sección de destacados (máximo 8 productos)</span>
                </span>
            </label>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                Actualizar Producto
            </button>
        </div>
    </form>
</div>
@endsection
