@extends('layouts.admin')

@section('title', 'Ver Categoría')
@section('page-title', 'Detalle de la Categoría')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $category->name }}</h2>
                <p class="text-gray-600 mt-2">{{ $category->description }}</p>
            </div>
            @if($category->is_active)
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Activa</span>
            @else
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">Inactiva</span>
            @endif
        </div>

        @if($category->image)
        <div class="mb-6">
            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full max-w-md rounded-lg shadow">
        </div>
        @endif

        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600">Productos en esta categoría</p>
                <p class="text-2xl font-bold text-orange-600">{{ $category->products_count }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Slug</p>
                <p class="font-mono text-sm">{{ $category->slug }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Creada el</p>
                <p>{{ $category->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-8 flex space-x-4">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Volver
            </a>
            @if(auth()->user()->hasPermission('edit-categories'))
            <a href="{{ route('admin.categories.edit', $category) }}" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                Editar
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
