@extends('layouts.admin')

@section('title', 'Crear Banner')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.banners.index') }}" class="text-orange-600 hover:underline">← Volver a Banners</a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2">Crear Nuevo Banner</h1>
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

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- Imagen -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Banner *</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required
                    class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                    data-action="preview-image">
                <p class="text-xs text-gray-500 mt-1">Formatos: JPEG, PNG, WebP. Tamaño recomendado: 1920x600px. Máximo 2MB.</p>
                <div id="image-preview" class="mt-3 hidden">
                    <img src="" alt="Preview" class="max-h-48 rounded-lg border">
                </div>
            </div>

            <!-- URL -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL de Destino (opcional)</label>
                <input type="url" name="url" value="{{ old('url') }}"
                    class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                    placeholder="https://ejemplo.com">
                <p class="text-xs text-gray-500 mt-1">Si se configura, el banner será clickeable</p>
            </div>

            <!-- Estado Activo -->
            <div class="flex items-center h-full md:col-span-2">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Banner Activo</span>
                </label>
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio (opcional)</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}"
                    class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                <p class="text-xs text-gray-500 mt-1">El banner se mostrará desde esta fecha</p>
            </div>

            <!-- Fecha de Fin -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin (opcional)</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                    class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                <p class="text-xs text-gray-500 mt-1">El banner dejará de mostrarse después de esta fecha</p>
            </div>
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                Crear Banner
            </button>
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
