@extends('layouts.admin')

@section('title', 'Crear Producto')
@section('page-title', 'Crear Nuevo Producto')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf

        <!-- Nombre -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="Ej: Brochetas de Pollo">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Categoría -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
            <select name="category_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">Seleccionar categoría</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
            <input type="text" name="plu" value="{{ old('plu') }}" required
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
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="Descripción del producto">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tipo de Producto -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Venta *</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="type" value="N" {{ old('type', 'N') === 'N' ? 'checked' : '' }} required
                        class="text-orange-600 focus:ring-orange-500">
                    <div class="ml-3">
                        <span class="font-medium text-gray-900">Por Unidad</span>
                        <p class="text-xs text-gray-500">Venta por cantidad de unidades</p>
                    </div>
                </label>
                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="type" value="P" {{ old('type') === 'P' ? 'checked' : '' }} required
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
            <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="0.00">
            <p class="text-xs text-gray-500 mt-1">El precio se mostrará por unidad o por kilo según el tipo seleccionado</p>
            @error('price')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pedido Mínimo -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pedido Mínimo (porciones) *</label>
            <input type="number" name="min_order" value="{{ old('min_order', 1) }}" min="1" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="1">
            @error('min_order')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Imagen -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Producto</label>
            <input type="file" name="image" accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            <p class="text-sm text-gray-500 mt-1">Formatos: JPG, PNG, GIF. Máximo 2MB</p>
            @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Disponible -->
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                <span class="ml-2 text-sm text-gray-700">Producto disponible para la venta</span>
            </label>
        </div>

        <!-- Producto Destacado -->
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', false) ? 'checked' : '' }}
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
                Crear Producto
            </button>
        </div>
    </form>
</div>
@endsection
