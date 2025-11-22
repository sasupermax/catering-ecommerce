@php
    $banners = \App\Models\Banner::active()->valid()->ordered()->get();
    $hasCarousel = $banners->count() > 0;
    // Configuración global del carousel desde settings
    $transitionDuration = (int) (\App\Models\Setting::where('key', 'carousel_transition_duration')->value('value') ?? 5000);
@endphp

@if($hasCarousel)
<div class="hero-carousel-container relative w-full overflow-hidden bg-gray-100" data-transition-duration="{{ $transitionDuration }}">
    <div class="carousel-wrapper relative">
        <!-- Slides -->
        <div class="carousel-slides flex transition-transform duration-700 ease-in-out" data-carousel-slides>
            @foreach($banners as $banner)
            <div class="carousel-slide min-w-full relative">
                @if($banner->url)
                <a href="{{ $banner->url }}" target="_blank" rel="noopener noreferrer">
                    <img 
                        src="{{ asset('storage/' . $banner->image) }}" 
                        alt="{{ $banner->title }}"
                        class="w-full h-[250px] sm:h-[350px] md:h-[450px] object-cover"
                        loading="lazy"
                    />
                </a>
                @else
                <img 
                    src="{{ asset('storage/' . $banner->image) }}" 
                    alt="{{ $banner->title }}"
                    class="w-full h-[250px] sm:h-[350px] md:h-[450px] object-cover"
                    loading="lazy"
                />
                @endif
            </div>
            @endforeach
        </div>

        <!-- Indicadores (puntos) -->
        @if($banners->count() > 1)
        <div class="carousel-dots absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            @foreach($banners as $index => $banner)
            <button 
                class="carousel-dot rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8 h-3' : 'bg-white/50 hover:bg-white/75 w-3 h-3' }}"
                data-index="{{ $index }}"
                aria-label="Ir al banner {{ $index + 1 }}"
            ></button>
            @endforeach
        </div>
        @endif
    </div>
</div>

<style>
.hero-carousel-container {
    max-width: 100vw;
}

.carousel-slide img {
    user-select: none;
    -webkit-user-drag: none;
}

.carousel-slides {
    will-change: transform;
}

.carousel-dot:focus {
    outline: 2px solid white;
    outline-offset: 2px;
}
</style>
@endif
