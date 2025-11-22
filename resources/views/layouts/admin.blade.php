<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración') - Catering</title>
    @stack('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100" x-data>
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-6">
                <h2 class="text-2xl font-bold">🍽️ Catering Admin</h2>
                <p class="text-sm text-gray-400 mt-1">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
            </div>

            <nav class="mt-6">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <span class="mr-3">📊</span>
                    <span>Dashboard</span>
                </a>

                <!-- Pedidos (con dropdown) -->
                @if(auth()->user()->hasPermission('view-orders'))
                <div x-data="{ open: {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                        <div class="flex items-center">
                            <span class="mr-3">📦</span>
                            <span>Pedidos</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="bg-gray-800">
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.orders.index') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">📋</span>
                            <span>Ver Todos</span>
                        </a>
                        <a href="{{ route('admin.orders.search') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.orders.search') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">🔍</span>
                            <span>Buscar Pedido</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Productos -->
                @if(auth()->user()->hasPermission('view-products'))
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <span class="mr-3">🍴</span>
                    <span>Productos</span>
                </a>
                @endif

                <!-- Categorías -->
                @if(auth()->user()->hasPermission('view-categories'))
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <span class="mr-3">📁</span>
                    <span>Categorías</span>
                </a>
                @endif

                <!-- Importación -->
                @if(auth()->user()->hasPermission('import-products'))
                <div x-data="{ open: {{ request()->routeIs('admin.imports.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.imports.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                        <div class="flex items-center">
                            <span class="mr-3">📊</span>
                            <span>Importación</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="bg-gray-800">
                        <a href="{{ route('admin.imports.new') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.imports.new') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">➕</span>
                            <span>Nuevos</span>
                        </a>
                        <a href="{{ route('admin.imports.update') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.imports.update') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">🔄</span>
                            <span>Actualizar</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Usuarios -->
                @if(auth()->user()->hasPermission('view-users') || auth()->user()->hasPermission('view-roles'))
                <div x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                        <div class="flex items-center">
                            <span class="mr-3">👥</span>
                            <span>Usuarios</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="bg-gray-800">
                        @if(auth()->user()->hasPermission('view-users'))
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.users.*') && !request()->routeIs('admin.users.roles.*') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">👤</span>
                            <span>Usuarios</span>
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('view-roles'))
                        <a href="{{ route('admin.roles.index') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.roles.*') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">🛡️</span>
                            <span>Roles</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Ofertas (con dropdown) -->
                @if(auth()->user()->hasPermission('view-offers'))
                <div x-data="{ open: {{ request()->routeIs('admin.offers.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.offers.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                        <div class="flex items-center">
                            <span class="mr-3">🎁</span>
                            <span>Ofertas</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="bg-gray-800">
                        <a href="{{ route('admin.offers.index') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.offers.index') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">📋</span>
                            <span>Ver Ofertas</span>
                        </a>
                        @if(auth()->user()->hasPermission('create-offers'))
                        <a href="{{ route('admin.offers.create') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.offers.create') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">➕</span>
                            <span>Nueva Oferta</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Banners del Carousel -->
                @if(auth()->user()->hasPermission('view-banners'))
                <div x-data="{ open: {{ request()->routeIs('admin.banners.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.banners.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                        <div class="flex items-center">
                            <span class="mr-3">🖼️</span>
                            <span>Banners</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="bg-gray-800">
                        <a href="{{ route('admin.banners.index') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.banners.index') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">📋</span>
                            <span>Ver Banners</span>
                        </a>
                        @if(auth()->user()->hasPermission('create-banners'))
                        <a href="{{ route('admin.banners.create') }}" class="flex items-center px-6 py-2.5 pl-14 hover:bg-gray-700 text-sm {{ request()->routeIs('admin.banners.create') ? 'bg-gray-700' : '' }}">
                            <span class="mr-2">➕</span>
                            <span>Nuevo Banner</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Configuración -->
                @if(auth()->user()->hasPermission('view-settings'))
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <span class="mr-3">⚙️</span>
                    <span>Configuración</span>
                </a>
                @endif

                <div class="border-t border-gray-700 my-4"></div>

                <!-- Ver Sitio -->
                <a href="{{ route('home') }}" target="_blank" class="flex items-center px-6 py-3 hover:bg-gray-800">
                    <span class="mr-3">🌐</span>
                    <span>Ver Sitio</span>
                </a>

                <!-- Cerrar Sesión -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-6 py-3 hover:bg-gray-800 text-left">
                        <span class="mr-3">🚪</span>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Bar -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-8 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                <!-- Mensajes de éxito/error -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
