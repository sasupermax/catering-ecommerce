@extends('layouts.shop')

@section('title', $category->name . ' - Supermax Catering')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs sm:text-sm overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="text-red-600 hover:underline">Inicio</a>
            <span class="mx-1 sm:mx-2 text-gray-500">/</span>
            <span class="text-gray-700">{{ $category->name }}</span>
        </nav>
    </div>
</div>

<!-- Category Header -->
<div class="bg-white py-6 sm:py-8 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl swissbold text-gray-800">{{ $category->name }}</h1>
            @if($category->description)
            <p class="text-sm sm:text-base text-gray-600 mt-2">{{ $category->description }}</p>
            @endif
        </div>

        <div class="flex gap-2 sm:gap-3 justify-end">
            <button data-action="open-search" class="flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-4 py-1.5 sm:py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-[#d81d25] transition text-xs sm:text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="hidden sm:inline">Buscar</span>
            </button>
            <button data-action="open-filter" class="flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-4 py-1.5 sm:py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-[#d81d25] transition text-xs sm:text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="hidden sm:inline">Filtrar</span>
            </button>
            <button data-action="open-sort" class="flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-4 py-1.5 sm:py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-[#d81d25] transition text-xs sm:text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                </svg>
                <span class="hidden sm:inline">Ordenar</span>
            </button>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    @if($products->count() > 0)
    <!-- Contador de productos -->
    <div class="mb-4 text-sm text-gray-600">
        Mostrando <span data-products-count>{{ $products->count() }}</span> de <span data-products-total>{{ $products->total() }}</span> productos
    </div>

    <!-- Grid de productos -->
    <div data-products-grid class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition product-card" data-product-id="{{ $product->id }}">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-32 sm:h-48 p-4 object-contain bg-white" loading="lazy" style="view-transition-name: product-{{ $product->slug }};">
            @else
            <div class="w-full h-32 sm:h-48 bg-gradient-to-br from-gray-100 to-red-400 flex items-center justify-center">
                <span class="text-4xl sm:text-6xl">🍴</span>
            </div>
            @endif
            <div class="p-3 sm:p-4">
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-700 mt-1 line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-3 sm:mt-4 gap-2">
                        <div>
                            <span class="text-lg sm:text-2xl swissbold text-gray-800">${{ number_format($product->price, 2) }}</span>
                            <span class="text-xs text-gray-500 block sm:inline">{{ $product->price_suffix }}</span>
                        </div>
                    </div>
                    <a href="{{ route('product', $product->slug) }}" class="mt-3 block w-full bg-[#ffd90f] swissregular text-gray-800 px-3 sm:px-4 py-2 rounded-lg hover:bg-[#e2bf00] active:[#c9a700] transition text-xs sm:text-sm text-center">
                        Ver más
                    </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Botón Cargar Más -->
    @if($products->hasMorePages())
    <div class="mt-8 text-center">
        <button 
            data-action="load-more" 
            data-category-slug="{{ $category->slug }}"
            data-current-page="{{ $products->currentPage() }}"
            data-next-page="{{ $products->currentPage() + 1 }}"
            class="inline-flex items-center gap-2 px-6 py-3 bg-[#d81d25] text-white rounded-lg font-semibold hover:bg-[#c11a21] transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span data-load-more-text>Cargar más productos</span>
            <svg data-load-more-spinner class="hidden animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </button>
    </div>
    @endif
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 sm:p-8 text-center">
        <p class="text-yellow-800 text-base sm:text-lg">No hay productos disponibles en esta categoría.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition text-sm sm:text-base">
            Volver al inicio
        </a>
    </div>
    @endif
</div>

<!-- Search Modal -->
<div data-modal="search" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:w-96 sm:rounded-lg rounded-t-2xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800">Buscar productos</h3>
            <button data-action="close-search" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form method="GET" action="{{ route('category', $category->slug) }}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Buscar en {{ $category->name }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-[#d81d25] focus:outline-none" 
                           placeholder="Nombre del producto..." autofocus>
                </div>
            </div>
            
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
            
            <div class="flex gap-3 mt-6">
                <button type="button" data-action="clear-search" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Limpiar
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-[#d81d25] text-white rounded-lg font-semibold hover:bg-[#c11a21] transition">
                    Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Filter Modal -->
<div data-modal="filter" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:w-96 sm:rounded-lg rounded-t-2xl p-6 sm:p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800">Filtrar por precio</h3>
            <button data-action="close-filter" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form method="GET" action="{{ route('category', $category->slug) }}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Precio mínimo</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-[#d81d25] focus:outline-none" 
                           placeholder="$0" min="0" step="0.01">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Precio máximo</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-[#d81d25] focus:outline-none" 
                           placeholder="$9999" min="0" step="0.01">
                </div>
            </div>
            
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            
            <div class="flex gap-3 mt-6">
                <button type="button" data-action="clear-filter" data-category-slug="{{ $category->slug }}" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Limpiar
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-[#d81d25] text-white rounded-lg font-semibold hover:bg-[#c11a21] transition">
                    Aplicar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Sort Modal -->
<div data-modal="sort" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:w-96 sm:rounded-lg rounded-t-2xl p-6 sm:p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800">Ordenar por</h3>
            <button data-action="close-sort" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form method="GET" action="{{ route('category', $category->slug) }}">
            <div class="space-y-2">
                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-[#d81d25] cursor-pointer transition">
                    <input type="radio" name="sort" value="price_asc" {{ request('sort') == 'price_asc' ? 'checked' : '' }} class="w-4 h-4 text-[#d81d25]">
                    <span class="ml-3 text-gray-700 font-medium">Precio: Menor a Mayor</span>
                </label>
                
                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-[#d81d25] cursor-pointer transition">
                    <input type="radio" name="sort" value="price_desc" {{ request('sort') == 'price_desc' ? 'checked' : '' }} class="w-4 h-4 text-[#d81d25]">
                    <span class="ml-3 text-gray-700 font-medium">Precio: Mayor a Menor</span>
                </label>
                
                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-[#d81d25] cursor-pointer transition">
                    <input type="radio" name="sort" value="featured" {{ request('sort') == 'featured' ? 'checked' : '' }} class="w-4 h-4 text-[#d81d25]">
                    <span class="ml-3 text-gray-700 font-medium">Destacados</span>
                </label>
                
                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-[#d81d25] cursor-pointer transition">
                    <input type="radio" name="sort" value="newest" {{ request('sort') == 'newest' ? 'checked' : '' }} class="w-4 h-4 text-[#d81d25]">
                    <span class="ml-3 text-gray-700 font-medium">Más Nuevos</span>
                </label>
                
                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-[#d81d25] cursor-pointer transition">
                    <input type="radio" name="sort" value="oldest" {{ request('sort') == 'oldest' ? 'checked' : '' }} class="w-4 h-4 text-[#d81d25]">
                    <span class="ml-3 text-gray-700 font-medium">Más Antiguos</span>
                </label>
            </div>
            
            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            
            <button type="submit" class="w-full mt-6 px-4 py-3 bg-[#d81d25] text-white rounded-lg font-semibold hover:bg-[#c11a21] transition">
                Aplicar
            </button>
        </form>
    </div>
</div>

@endsection
