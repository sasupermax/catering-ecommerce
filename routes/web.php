<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Api\DeliveryDateController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

// API Routes
Route::get('/api/delivery/available-dates', [DeliveryDateController::class, 'availableDates'])->name('api.delivery.dates');

// Rutas públicas de la tienda
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/categoria/{slug}', [ShopController::class, 'category'])->name('category');
Route::get('/producto/{slug}', [ShopController::class, 'product'])->name('product');

// Páginas legales
Route::view('/terminos-y-condiciones', 'legal.terms')->name('terms');

// Rutas del carrito (sin autenticación - para invitados)
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/carrito/actualizar', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/remover/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');

// Rutas de checkout (sin autenticación - para invitados)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');

// Rutas de Mercado Pago
Route::post('/mercadopago/create-preference', [MercadoPagoController::class, 'createPreference'])->name('mercadopago.create.preference');
Route::post('/mercadopago/webhook', [MercadoPagoController::class, 'webhook'])->name('mercadopago.webhook');
Route::get('/payment/success', [MercadoPagoController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [MercadoPagoController::class, 'failure'])->name('payment.failure');
Route::get('/payment/pending', [MercadoPagoController::class, 'pending'])->name('payment.pending');

// Rutas de autenticación (solo para administradores)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        // Redirigir a admin si tiene rol administrativo
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('viewer')) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas del panel de administración
Route::middleware(['auth', 'role:admin,editor,viewer', 'throttle:60,1'])->prefix(env('ADMIN_PATH', 'admin'))->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Productos
    Route::middleware('permission:view-products')->group(function () {
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    });
    
    Route::middleware('permission:create-products')->group(function () {
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    });
    
    Route::middleware('permission:view-products')->group(function () {
        Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    });
    
    Route::middleware('permission:edit-products')->group(function () {
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    });
    
    Route::middleware('permission:delete-products')->group(function () {
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    });

    // Acciones masivas de productos
    Route::middleware('permission:edit-products')->group(function () {
        Route::post('/products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    });
    
    // Categorías
    Route::middleware('permission:view-categories')->group(function () {
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    });
    
    Route::middleware('permission:create-categories')->group(function () {
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    });
    
    Route::middleware('permission:view-categories')->group(function () {
        Route::get('/categories/{category}', [AdminCategoryController::class, 'show'])->name('categories.show');
    });
    
    Route::middleware('permission:edit-categories')->group(function () {
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    });
    
    Route::middleware('permission:delete-categories')->group(function () {
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    });
    
    // Pedidos
    Route::middleware('permission:view-orders')->group(function () {
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/search', [AdminOrderController::class, 'search'])->name('orders.search');
        Route::get('/orders/export', [AdminOrderController::class, 'exportDaily'])->name('orders.export');
        Route::get('/orders/export-pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.export.pdf');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    });
    
    Route::middleware('permission:edit-orders')->group(function () {
        Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
    
    // Ofertas
    Route::middleware('permission:view-offers')->group(function () {
        Route::get('/offers', [\App\Http\Controllers\Admin\OfferController::class, 'index'])->name('offers.index');
    });
    
    Route::middleware('permission:create-offers')->group(function () {
        Route::get('/offers/create', [\App\Http\Controllers\Admin\OfferController::class, 'create'])->name('offers.create');
        Route::post('/offers', [\App\Http\Controllers\Admin\OfferController::class, 'store'])->name('offers.store');
    });
    
    Route::middleware('permission:edit-offers')->group(function () {
        Route::get('/offers/{offer}/edit', [\App\Http\Controllers\Admin\OfferController::class, 'edit'])->name('offers.edit');
        Route::put('/offers/{offer}', [\App\Http\Controllers\Admin\OfferController::class, 'update'])->name('offers.update');
    });
    
    Route::middleware('permission:delete-offers')->group(function () {
        Route::delete('/offers/{offer}', [\App\Http\Controllers\Admin\OfferController::class, 'destroy'])->name('offers.destroy');
    });
    
    // Banners del Carousel
    Route::middleware('permission:view-banners')->group(function () {
        Route::get('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    });
    
    Route::middleware('permission:create-banners')->group(function () {
        Route::get('/banners/create', [\App\Http\Controllers\Admin\BannerController::class, 'create'])->name('banners.create');
        Route::post('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    });
    
    Route::middleware('permission:edit-banners')->group(function () {
        Route::get('/banners/{banner}/edit', [\App\Http\Controllers\Admin\BannerController::class, 'edit'])->name('banners.edit');
        Route::put('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    });
    
    Route::middleware('permission:delete-banners')->group(function () {
        Route::delete('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('banners.destroy');
        Route::post('/banners/update-order', [\App\Http\Controllers\Admin\BannerController::class, 'updateOrder'])->name('banners.updateOrder');
    });
    
    // Usuarios
    Route::middleware('permission:view-users')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    });
    
    Route::middleware('permission:create-users')->group(function () {
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    });
    
    Route::middleware('permission:view-users')->group(function () {
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    });
    
    Route::middleware('permission:edit-users')->group(function () {
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    });
    
    Route::middleware('permission:delete-users')->group(function () {
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
    
    // Roles
    Route::middleware('permission:view-roles')->group(function () {
        Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
    });
    
    Route::middleware('permission:create-roles')->group(function () {
        Route::get('/roles/create', [AdminRoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [AdminRoleController::class, 'store'])->name('roles.store');
    });
    
    Route::middleware('permission:view-roles')->group(function () {
        Route::get('/roles/{role}', [AdminRoleController::class, 'show'])->name('roles.show');
    });
    
    Route::middleware('permission:edit-roles')->group(function () {
        Route::get('/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [AdminRoleController::class, 'update'])->name('roles.update');
    });
    
    Route::middleware('permission:delete-roles')->group(function () {
        Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');
    });
    
    // Configuración
    Route::middleware('permission:view-settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    });
    
    Route::middleware('permission:edit-settings')->group(function () {
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // Importación de productos
    Route::middleware('permission:import-products')->group(function () {
        // Importar nuevos
        Route::get('/imports/new', [ImportController::class, 'index'])->name('imports.new');
        Route::post('/imports/new', [ImportController::class, 'store'])->name('imports.store');
        
        // Actualizar existentes
        Route::get('/imports/update', [ImportController::class, 'indexUpdate'])->name('imports.update');
        Route::post('/imports/update', [ImportController::class, 'processUpdate'])->name('imports.process-update');
        
        // Comunes
        Route::get('/imports/{import}/download', [ImportController::class, 'download'])->name('imports.download');
        Route::delete('/imports/clean', [ImportController::class, 'cleanHistory'])->name('imports.clean');
    });
});

require __DIR__.'/auth.php';
