@extends('layouts.admin')

@section('title', 'Productos')
@section('page-title', 'Gestión de Productos')

@push('meta')
<meta name="bulk-action-url" content="{{ route('admin.products.bulk-action') }}">
@endpush

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div>
            <p class="text-gray-600">Administra los productos de tu catálogo</p>
        </div>
        <!-- Botón de acciones masivas (oculto inicialmente) -->
        <div x-data="{ open: false, selectedCount: 0 }" 
             @selection-changed.window="selectedCount = $event.detail.count"
             x-show="selectedCount > 0"
             x-cloak
             class="relative">
            <button @click="open = !open" 
                    class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                <span x-text="`Acciones (${selectedCount})`"></span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition
                 class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                <button @click="bulkAction('toggle-availability'); open = false" 
                        class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-3 border-b border-gray-100">
                    <span class="text-xl">👁️</span>
                    <span class="text-sm text-gray-700">Cambiar disponibilidad</span>
                </button>
                <button @click="bulkAction('toggle-featured'); open = false" 
                        class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-3 border-b border-gray-100">
                    <span class="text-xl">⭐</span>
                    <span class="text-sm text-gray-700">Cambiar destacado</span>
                </button>
                <button @click="bulkAction('delete'); open = false" 
                        class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-3 text-red-600">
                    <span class="text-xl">🗑️</span>
                    <span class="text-sm">Eliminar seleccionados</span>
                </button>
            </div>
        </div>
    </div>
    @if(auth()->user()->hasPermission('create-products'))
    <a href="{{ route('admin.products.create') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
        + Nuevo Producto
    </a>
    @endif
</div>

<!-- Tabla de Productos -->
<div class="bg-white rounded-lg shadow overflow-hidden" x-data="bulkSelection()">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left">
                    <input type="checkbox" 
                           @change="toggleAll($event.target.checked)"
                           :checked="allSelected"
                           class="w-4 h-4 text-orange-600 rounded border-gray-300 focus:ring-orange-500">
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($products as $product)
            <tr :class="selected.includes({{ $product->id }}) ? 'bg-orange-50' : ''">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" 
                           data-product-id="{{ $product->id }}"
                           :checked="selected.includes({{ $product->id }})"
                           @change="toggle({{ $product->id }})"
                           class="w-4 h-4 text-orange-600 rounded border-gray-300 focus:ring-orange-500">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover">
                        @else
                        <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                            <span>🍴</span>
                        </div>
                        @endif
                        <div class="ml-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                                @if($product->is_featured)
                                <span class="text-yellow-500" title="Producto Destacado">⭐</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">{{ $product->type_name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">{{ $product->category->name }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        <span class="text-xs text-gray-500">{{ $product->price_suffix }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($product->is_available)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Disponible
                    </span>
                    @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        No disponible
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                        
                        @if(auth()->user()->hasPermission('edit-products'))
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-orange-600 hover:text-orange-900">Editar</a>
                        @endif
                        
                        @if(auth()->user()->hasPermission('delete-products'))
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" data-action="delete-confirm" data-confirm-message="¿Estás seguro de eliminar este producto?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    No hay productos registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6">
    {{ $products->links() }}
</div>

@endsection

@push('styles')
<style>
[x-cloak] { display: none !important; }
</style>
@endpush
