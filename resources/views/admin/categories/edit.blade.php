@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('page-title', 'Editar Categoría')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
            <textarea name="description" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ old('description', $category->description) }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        @if($category->image)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Actual</label>
            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-32 w-32 object-cover rounded">
        </div>
        @endif

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cambiar Imagen</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">Formatos: JPEG, PNG, GIF, WebP. Deja vacío para mantener la imagen actual. Tamaño recomendado: 400x400px. Máximo 2MB.</p>
            @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                <span class="ml-2 text-sm text-gray-700">Categoría activa</span>
            </label>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                Actualizar Categoría
            </button>
        </div>
    </form>
</div>
@endsection
