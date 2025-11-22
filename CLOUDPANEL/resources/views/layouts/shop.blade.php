<!-- Desarrollado por Pablo Duran -->
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Catering E-Commerce')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/shop.js'])
</head>
<body class="bg-gray-50 flex flex-col min-h-full">
    <!-- Navbar -->
    <nav class="bg-[#d81d25] shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-lg sm:text-2xl font-bold text-white">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo Supermax" class="h-20 sm:h-24 object-contain">
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-2 lg:space-x-4">
                    <a href="{{ route('home') }}" class="text-white hover:text-gray-200 px-2 lg:px-3 py-2 rounded-md text-md font-bold">
                        Inicio
                    </a>

                    <a href="{{ route('home') }}#como-comprar" class="scroll-link text-white hover:text-gray-200 px-2 lg:px-3 py-2 rounded-md text-md font-bold">
                        Como comprar
                    </a>
                    
                    @auth
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('viewer'))
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-gray-200 px-2 lg:px-3 py-2 rounded-md text-md font-medium">
                            Admin
                        </a>
                        @endif
                    @endauth
                        
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-12 h-12 rounded-full bg-[#d81d25] hover:bg-[#c11a21] transition-all duration-300">
                        <!-- Icono de carrito -->
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512"><path fill="#ffffff" d="M0 24C0 10.7 10.7 0 24 0h45.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5l-51.6-271c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24m128 440a48 48 0 1 1 96 0a48 48 0 1 1-96 0m336-48a48 48 0 1 1 0 96a48 48 0 1 1 0-96"/></svg>
                        </div>
                        <!-- Contador -->
                        <span class="cart-count absolute -top-0 -right-0 w-5 h-5 bg-[#ffd90f] text-gray-800 text-opacity-100 text-xs font-bold rounded-full flex items-center justify-center border-0 border-gray-700">
                            0
                        </span>
                    </a>
                </div>

                <!-- Mobile Menu Button & Cart -->
                <div class="flex md:hidden items-center space-x-2">
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-12 h-12 rounded-full bg-[#d81d25] hover:bg-[#c11a21] transition-all duration-300">
                        <!-- Icono de carrito -->
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512"><path fill="#ffffff" d="M0 24C0 10.7 10.7 0 24 0h45.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5l-51.6-271c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24m128 440a48 48 0 1 1 96 0a48 48 0 1 1-96 0m336-48a48 48 0 1 1 0 96a48 48 0 1 1 0-96"/></svg>
                        </div>
                        <!-- Contador -->
                        <span class="cart-count absolute top-1 -right-0 w-5 h-5 bg-[#ffd90f] text-gray-800 text-opacity-100 text-xs font-bold rounded-full flex items-center justify-center border-0 border-gray-700">
                            0
                        </span>
                    </a>
                    <button id="mobile-menu-btn" class="text-white p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Overlay -->
            <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden transition-opacity duration-300 opacity-0 pointer-events-none"></div>

            <!-- Mobile Menu Sidebar -->
            <div id="mobile-menu" class="fixed top-0 right-0 h-full w-64 bg-[#d81d25] shadow-2xl z-50 md:hidden transition-transform duration-300 ease-out transform translate-x-full">
                <div class="flex flex-col h-full">
                    <!-- Header del menú -->
                    <div class="flex justify-between items-center p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-white">Menú</h3>
                        <button id="close-mobile-menu" class="text-white hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Enlaces del menú -->
                    <div class="flex flex-col p-4 space-y-2">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 text-white uppercase hover:text-[#d81d25] hover:bg-gray-50 px-4 py-3 rounded-md text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8z"/></svg>
                            <span>Inicio</span>
                        </a>

                        <a href="{{ route('home') }}#como-comprar" class="scroll-link flex items-center space-x-2 text-white uppercase hover:text-[#d81d25] hover:bg-gray-50 px-4 py-3 rounded-md text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-7v2h2v-2zm2-1.645A3.502 3.502 0 0 0 12 6.5a3.5 3.5 0 0 0-3.433 2.813l1.962.393A1.5 1.5 0 1 1 12 11.5a1 1 0 0 0-1 1V14h2z"/></svg>
                            <span>Como Comprar</span>
                        </a>
                        
                        @auth
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('viewer'))
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 text-white uppercase hover:text-[#d81d25] hover:bg-gray-50 px-4 py-3 rounded-md text-sm font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 8 8"><path fill="currentColor" d="M4 4v3q2 0 3-3M4 4V1L1 2v2m3-4l4 2c0 8-8 8-8 0"/></svg>
                                <span>Panel Admin</span>
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script @cspNonce>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeMobileMenuBtn = document.getElementById('close-mobile-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        let isMenuOpen = false;
        
        function openMenu() {
            isMenuOpen = true;
            document.body.style.overflow = 'hidden';
            mobileMenuOverlay.classList.remove('pointer-events-none', 'opacity-0');
            mobileMenuOverlay.classList.add('pointer-events-auto', 'opacity-100');
            mobileMenu.classList.remove('translate-x-full');
            mobileMenu.classList.add('translate-x-0');
        }
        
        function closeMenu() {
            isMenuOpen = false;
            mobileMenuOverlay.classList.remove('opacity-100', 'pointer-events-auto');
            mobileMenuOverlay.classList.add('opacity-0');
            mobileMenu.classList.remove('translate-x-0');
            mobileMenu.classList.add('translate-x-full');
            setTimeout(() => {
                mobileMenuOverlay.classList.add('pointer-events-none');
                document.body.style.overflow = '';
            }, 300);
        }
        
        mobileMenuBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            if (isMenuOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        
        closeMobileMenuBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            closeMenu();
        });
        
        mobileMenuOverlay?.addEventListener('click', closeMenu);

        // Actualizar contador del carrito al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/carrito/count')
                .then(res => res.json())
                .then(data => {
                    document.querySelectorAll('.cart-count').forEach(el => {
                        el.textContent = data.count;
                    });
                });

            // Manejar scroll suave para enlaces internos
            const scrollToTarget = (target) => {
                const navbarHeight = 64; // Altura del navbar (h-14 sm:h-16)
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight - 20;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            };

            const handleScrollLinks = () => {
                const hash = window.location.hash;
                if (hash) {
                    const target = document.querySelector(hash);
                    if (target) {
                        setTimeout(() => {
                            scrollToTarget(target);
                        }, 100);
                    }
                }
            };

            // Ejecutar al cargar
            handleScrollLinks();

            // Manejar clicks en enlaces con clase scroll-link
            document.querySelectorAll('.scroll-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    const hashIndex = href.indexOf('#');
                    
                    // Cerrar menú móvil si está abierto
                    if (isMenuOpen) {
                        closeMenu();
                    }
                    
                    if (hashIndex !== -1) {
                        const hash = href.substring(hashIndex);
                        const target = document.querySelector(hash);
                        
                        // Si estamos en la misma página
                        if (window.location.pathname === new URL(href, window.location.origin).pathname) {
                            e.preventDefault();
                            if (target) {
                                scrollToTarget(target);
                                history.pushState(null, '', href);
                            }
                        }
                    }
                });
            });
        });
    </script>

    <!-- Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#d81d25] text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="space-y-4">
                    <!-- <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Catering Supermax</h3> -->
                    <img src="{{ asset('images/logo_2.svg') }}" alt="Supermax Catering Logo" srcset="" class="w-[50%]">
                </div>
                <div>
                    <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-sm sm:text-base">
                        <li><a href="#" class="hover:text-white">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-white">Servicios</a></li>
                        <li><a href="/terminos-y-condiciones" class="hover:text-white">Términos y Condiciones</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Contacto</h4>
                    <ul class="space-y-2 text-sm sm:text-bas0">
                        <li>📞 +52 123 456 7890</li>
                        <li>📧 info@catering.com</li>
                        <li>📍 Corrientes, Argentina</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white mt-6 sm:mt-8 pt-6 sm:pt-8 text-center">
                <p class="text-xs sm:text-sm">&copy; 2025 Supermax S.A. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
