@extends('layouts.shop')

@section('title', $product->name . ' - Supermax Catering')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs sm:text-sm overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="text-red-600 hover:underline">Inicio</a>
            <span class="mx-1 sm:mx-2 text-gray-500">/</span>
            <a href="{{ route('category', $product->category->slug) }}" class="text-red-600 hover:underline">{{ $product->category->name }}</a>
            <span class="mx-1 sm:mx-2 text-gray-500">/</span>
            <span class="text-gray-700">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<!-- Product Details -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12">
        <!-- Product Image -->
        <div class="order-1">
            @if($product->image)
            <div class="w-full h-64 sm:h-80 lg:h-96 bg-gray-50 rounded-lg shadow-lg flex items-center justify-center overflow-hidden">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full p-5 bg-white h-full object-contain" style="view-transition-name: product-{{ $product->slug }};">
            </div>
            @else
            <div class="w-full h-64 sm:h-80 lg:h-96 bg-gradient-to-br from-gray-100 to-red-400 rounded-lg flex items-center justify-center shadow-lg">
                <span class="text-6xl sm:text-8xl lg:text-9xl">🍴</span>
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="order-2">
            <!-- <span class="text-xs sm:text-sm font-semibold text-orange-600 uppercase">{{ $product->category->name }}</span> -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl swissregular text-gray-800 mt-2">{{ $product->name }}</h1>
            
            @if($product->description)
            <p class="text-gray-600 mt-4 text-sm sm:text-base leading-relaxed">{{ $product->description }}</p>
            @endif
            
            <div class="flex items-baseline mt-4 sm:mt-6">
                <span class="text-3xl sm:text-4xl swissbold text-[#d81d25]">${{ number_format($product->price, 2) }}</span>
                <span class="text-sm sm:text-base text-gray-600 ml-2">{{ $product->price_suffix }}</span>
            </div>
            
            <p class="text-xs text-gray-500 mt-1">
                Precio sin impuestos nacionales: ${{ number_format($product->price / 1.21, 2) }}
            </p>

            <!-- Order Form -->
            <div class="mt-6 sm:mt-8 bg-gray-50 rounded-lg py-4 pr-4 sm:py-6 sm:pr-6">
                <form action="{{ route('cart.add', $product) }}" method="POST" id="add-to-cart-form">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                            Cantidad {{ $product->type === 'P' ? '(en kg)' : '(unidades)' }}
                        </label>
                        <div class="flex items-center justify-between sm:justify-start sm:space-x-4">
                            <button type="button" data-action="decrease-product-qty" data-step="{{ $product->type === 'P' ? '0.5' : '1' }}" data-min="{{ $product->type === 'P' ? '0.5' : '1' }}" data-product-type="{{ $product->type }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-5 sm:py-2 sm:px-4 rounded-lg text-lg sm:text-base">
                                -
                            </button>
                            <input type="number" id="quantity" name="quantity" value="1" min="{{ $product->type === 'P' ? '0.5' : '1' }}" max="10000" step="{{ $product->type === 'P' ? '0.5' : '1' }}" data-product-type="{{ $product->type }}" 
                                class="w-20 sm:w-24 text-center text-lg sm:text-base border-2 border-gray-300 rounded-lg py-3 sm:py-2 font-semibold">
                            <button type="button" data-action="increase-product-qty" data-step="{{ $product->type === 'P' ? '0.5' : '1' }}" data-max="10000" data-product-type="{{ $product->type }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-5 sm:py-2 sm:px-4 rounded-lg text-lg sm:text-base">
                                +
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="add-to-cart-btn" class="w-full bg-[#ffd90f] text-white py-4 sm:py-3 rounded-lg text-lg font-semibold active:bg-[#e6c800] transition-all duration-500 ease-in-out shadow-lg flex items-center justify-center gap-2 transform active:scale-95">
                        <span id="btn-text" class="text-gray-700">🛒 Agregar al Carrito</span>
                        <svg id="btn-spinner" class="hidden animate-spin h-5 w-5 text-white ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg id="btn-check" class="hidden h-6 w-6 text-white ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </form>
            </div>


        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12 sm:mt-16">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 sm:mb-8">Productos Relacionados</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                @if($relatedProduct->image)
                <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-32 sm:h-48 p-4 object-contain bg-white" style="view-transition-name: product-{{ $relatedProduct->slug }};">
                @else
                <div class="w-full h-32 sm:h-48 bg-gradient-to-br from-gray-100 to-red-400 flex items-center justify-center">
                    <span class="text-4xl sm:text-6xl">🍴</span>
                </div>
                @endif
                <div class="p-3 sm:p-4">
                    <!-- <span class="text-xs font-semibold text-[#d81d25] uppercase">{{ $relatedProduct->category->name }}</span> -->
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-700 mt-1 line-clamp-2">{{ $relatedProduct->name }}</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-3 sm:mt-4 gap-2">
                        <div>
                            <span class="text-lg sm:text-2xl swissbold text-gray-800">${{ number_format($relatedProduct->price, 2) }}</span>
                            <span class="text-xs text-gray-500 block sm:inline">{{ $relatedProduct->price_suffix }}</span>
                        </div>
                    </div>
                    <a href="{{ route('product', $relatedProduct->slug) }}" class="mt-3 block w-full bg-[#ffd90f] swissregular text-gray-800 px-3 sm:px-4 py-2 rounded-lg hover:bg-[#e2bf00] active:[#c9a700] transition text-xs sm:text-sm text-center">
                        Ver más
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
