@extends('layouts.admin')

@section('title', 'Categorías')
@section('page-title', 'Gestión de Categorías')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-gray-600">Administra las categorías de productos</p>
    </div>
    @if(auth()->user()->hasPermission('create-categories'))
    <a href="{{ route('admin.categories.create') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
        + Nueva Categoría
    </a>
    @endif
</div>

<!-- Grid de Categorías -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($categories as $category)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($category->image)
        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover">
        @else
        <div class="w-full h-48 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
            <span class="text-6xl">📁</span>
        </div>
        @endif
        
        <div class="p-4">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h3>
                @if($category->is_active)
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                    Activa
                </span>
                @else
                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                    Inactiva
                </span>
                @endif
            </div>
            
            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $category->description }}</p>
            
            <p class="text-gray-500 text-sm mb-4">{{ $category->products_count }} productos</p>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.categories.show', $category) }}" 
                    class="flex-1 text-center px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-sm">
                    Ver
                </a>
                
                @if(auth()->user()->hasPermission('edit-categories'))
                <a href="{{ route('admin.categories.edit', $category) }}" 
                    class="flex-1 text-center px-3 py-2 bg-orange-100 text-orange-700 rounded hover:bg-orange-200 transition text-sm">
                    Editar
                </a>
                @endif
                
                @if(auth()->user()->hasPermission('delete-categories'))
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-action="delete-confirm" data-confirm-message="¿Eliminar esta categoría?" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-sm">
                        Eliminar
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500">No hay categorías registradas</p>
    </div>
    @endforelse
</div>

<!-- Paginación -->
<div class="mt-6">
    {{ $categories->links() }}
</div>
@endsection
