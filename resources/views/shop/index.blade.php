@extends('layouts.shop')

@section('title', 'Inicio - Supermax Catering')

@section('content')
<!-- Hero Carousel -->
<x-hero-carousel />

<!-- Categories Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
    <h2 class="text-2xl sm:text-3xl text-gray-800 text-center mb-6 sm:mb-8 swissregular">Nuestras Categorías</h2>
    
    @if($categories->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
        @foreach($categories as $category)
        <a href="{{ route('category', $category->slug) }}" class="group">
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-32 sm:h-48 object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="w-full h-32 sm:h-48 bg-gradient-to-br from-gray-100 to-red-500 flex items-center justify-center">
                    <span class="text-4xl sm:text-6xl">🍽️</span>
                </div>
                @endif
                <div class="p-3 sm:p-4 bg-[#d81d25]">
                    <h3 class="text-base text-center sm:text-xl text-white swissbold uppercase">{{ $category->name }}</h3>
                    <!-- <p class="text-gray-600 text-xs sm:text-sm mt-1">{{ $category->products_count }} productos</p> -->
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 sm:p-8 text-center">
        <p class="text-yellow-800 text-base sm:text-lg">No hay categorías disponibles en este momento.</p>
    </div>
    @endif
</div>

<!-- Featured Products Section -->
<div class="bg-gray-100 py-8 sm:py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl swissregular text-gray-800 mb-6 sm:mb-8">Productos Destacados</h2>
        
        @if($featuredProducts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-32 sm:h-48 p-4 object-contain bg-white" style="view-transition-name: product-{{ $product->slug }};">
                @else
                <div class="w-full h-32 sm:h-48 bg-gradient-to-br from-gray-100 to-red-400 flex items-center justify-center">
                    <span class="text-4xl sm:text-6xl">🍴</span>
                </div>
                @endif
                <div class="p-3 sm:p-4">
                    <span class="text-xs font-semibold text-[#d81d25] uppercase">{{ $product->category->name }}</span>
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
        @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 sm:p-8 text-center">
            <p class="text-yellow-800 text-base sm:text-lg">No hay productos disponibles en este momento.</p>
        </div>
        @endif
    </div>
</div>

<!-- Features Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <div class="text-center">
            <div class="bg-orange-100 w-14 h-14 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <span class="text-2xl sm:text-3xl">🚚</span>
            </div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">Entrega a Domicilio</h3>
            <p class="text-sm sm:text-base text-gray-600">Llevamos tus pedidos directamente a tu evento</p>
        </div>
        <div class="text-center">
            <div class="bg-orange-100 w-14 h-14 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <span class="text-2xl sm:text-3xl">⭐</span>
            </div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">Calidad Premium</h3>
            <p class="text-sm sm:text-base text-gray-600">Ingredientes frescos y de la mejor calidad</p>
        </div>
        <div class="text-center">
            <div class="bg-orange-100 w-14 h-14 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <span class="text-2xl sm:text-3xl">👨‍🍳</span>
            </div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">Chefs Profesionales</h3>
            <p class="text-sm sm:text-base text-gray-600">Preparado por expertos en gastronomía</p>
        </div>
    </div>
</div>

<!--- How to Buy Section --->
<div class="bg-gray-100 py-8 sm:py-12 lg:py-16">
<section id="como-comprar" class="max-w-4xl mx-auto p-4 md:p-8">
  <h2 class="text-3xl md:text-4xl swissbold text-[#d81d25] mb-8 text-center">¿Cómo comprar?</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-6">
      <div class="flex items-start gap-4 p-4 rounded-lg transition-colors">
        <span class="inline-flex items-center justify-center bg-[#d81d25] text-white rounded-full w-8 h-8 font-bold flex-shrink-0">1</span>
        <div>
          <strong class="text-gray-800">Explora el catálogo:</strong>
          <p class="text-gray-600 mt-1">Navega por las categorías y productos destacados.</p>
        </div>
      </div>
      
      <div class="flex items-start gap-4 p-4 rounded-lg transition-colors">
        <span class="inline-flex items-center justify-center bg-[#d81d25] text-white rounded-full w-8 h-8 font-bold flex-shrink-0">2</span>
        <div>
          <strong class="text-gray-800">Agrega al carrito:</strong>
          <p class="text-gray-600 mt-1">Haz clic en <span class="font-semibold whitespace-nowrap py-0.5 px-1 rounded-md bg-[#ffd90f] text-gray-800">Agregar al Carrito</span> en los productos que te interesen.</p>
        </div>
      </div>
      
      <div class="flex items-start gap-4 p-4 rounded-lg transition-colors">
        <span class="inline-flex items-center justify-center bg-[#d81d25] text-white rounded-full w-8 h-8 font-bold flex-shrink-0">3</span>
        <div>
          <strong class="text-gray-800">Revisa tu pedido:</strong>
          <p class="text-gray-600 mt-1">Accede al carrito para verificar cantidades y productos seleccionados.</p>
        </div>
      </div>
    </div>
    <div class="space-y-6">
        <div class="flex items-start gap-4 p-4 rounded-lg transition-colors">
        <span class="inline-flex items-center justify-center bg-[#d81d25] text-white rounded-full w-8 h-8 font-bold flex-shrink-0">4</span>
        <div>
          <strong class="text-gray-800">Completa tus datos:</strong>
          <p class="text-gray-600 mt-1">Ingresa tu información de contacto y dirección de entrega.</p>
        </div>
      </div>
      
      <div class="flex items-start gap-4 p-4 rounded-lg transition-colors">
        <span class="inline-flex items-center justify-center bg-[#d81d25] text-white rounded-full w-8 h-8 font-bold flex-shrink-0">5</span>
        <div>
          <strong class="text-gray-800">Confirma tu compra:</strong>
          <p class="text-gray-600 mt-1">Revisa el resumen y haz clic en <span class="font-semibold whitespace-nowrap py-0.5 px-1 rounded-md bg-[#ffd90f] text-gray-800">Continuar con el Pago</span>.</p>
        </div>
      </div>
      
      <div class="flex items-start gap-4 p-4 rounded-lg transition-colors">
        <span class="inline-flex items-center justify-center bg-[#d81d25] text-white rounded-full w-8 h-8 font-bold flex-shrink-0">6</span>
        <div>
          <strong class="text-gray-800">¡Listo!</strong>
          <p class="text-gray-600 mt-1">Recibirás la confirmación y te avisaremos cuando tu pedido esté preparado para su envío.</p>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
@endsection
