<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        // Calcular subtotal sin descuentos primero (para validar montos mínimos de ofertas)
        $subtotalBeforeDiscount = 0;
        foreach ($cart as $productId => $item) {
            $subtotalBeforeDiscount += $item['quantity'] * $item['price'];
        }

        // Calcular totales con descuentos
        $subtotal = 0;
        $cartItems = [];
        $appliedOffers = []; // Para controlar ofertas fijas
        
        foreach ($cart as $productId => $item) {
            $product = Product::with('offers')->find($productId);
            $price = $item['price'];
            $finalPrice = $price;
            
            // Aplicar oferta si existe y cumple monto mínimo
            if ($product) {
                $activeOffer = $product->getActiveOffer();
                if ($activeOffer && $activeOffer->isApplicable($subtotalBeforeDiscount)) {
                    // Solo aplicar descuentos de porcentaje por producto
                    if ($activeOffer->discount_type === 'percentage') {
                        $discount = $activeOffer->calculateDiscount($price);
                        $finalPrice = $price - $discount;
                    } 
                    // Para descuentos fijos, solo marcar que se aplicarán
                    elseif ($activeOffer->discount_type === 'fixed' && !in_array($activeOffer->id, $appliedOffers)) {
                        $appliedOffers[] = $activeOffer->id;
                    }
                }
            }
            
            $itemSubtotal = $item['quantity'] * $finalPrice;
            $subtotal += $itemSubtotal;
            $cartItems[] = array_merge($item, [
                'product_id' => $productId,
                'price' => $finalPrice, // Precio con descuento de porcentaje aplicado
                'subtotal' => $itemSubtotal
            ]);
        }

        // Aplicar descuentos fijos al total
        foreach ($appliedOffers as $offerId) {
            $offer = \App\Models\Offer::find($offerId);
            if ($offer) {
                $fixedDiscount = min($offer->discount_value, $subtotal);
                $subtotal -= $fixedDiscount;
            }
        }

        // Verificar monto mínimo y máximo
        $minOrderAmount = (float) (Setting::where('key', 'minimum_order_amount')->value('value') ?? 0);
        $maxOrderAmount = 12000000; // $12,000,000 ARS
        
        if ($subtotal < $minOrderAmount) {
            return redirect()->route('cart.index')->with('error', 'El monto mínimo de compra es $' . number_format($minOrderAmount, 2));
        }
        
        if ($subtotal > $maxOrderAmount) {
            return redirect()->route('cart.index')->with('error', 'El monto máximo de compra es $' . number_format($maxOrderAmount, 2) . '. Por favor contacta con nosotros para pedidos grandes.');
        }

        // Obtener configuración de fechas
        $minDays = (int) (Setting::where('key', 'min_days_advance')->value('value') ?? 2);
        $maxDays = (int) (Setting::where('key', 'max_days_advance')->value('value') ?? 30);
        $maxOrdersPerDay = (int) (Setting::where('key', 'max_orders_per_day')->value('value') ?? 10);
        
        $minDate = Carbon::today()->addDays($minDays);
        $maxDate = Carbon::today()->addDays($maxDays);

        // Obtener fechas con pedidos y sus conteos (solo órdenes con pago aprobado)
        $disabledDates = Order::selectRaw('delivery_date, COUNT(*) as count')
            ->whereBetween('delivery_date', [$minDate->format('Y-m-d'), $maxDate->format('Y-m-d')])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'confirmed'])
            ->groupBy('delivery_date')
            ->havingRaw('COUNT(*) >= ?', [$maxOrdersPerDay])
            ->pluck('delivery_date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        return view('checkout.index', compact('cartItems', 'subtotal', 'minDate', 'maxDate', 'disabledDates'));
    }

    public function process(Request $request)
    {
        // Obtener configuración de fechas
        $minDays = (int) (Setting::where('key', 'min_days_advance')->value('value') ?? 2);
        $maxDays = (int) (Setting::where('key', 'max_days_advance')->value('value') ?? 30);
        $maxOrdersPerDay = (int) (Setting::where('key', 'max_orders_per_day')->value('value') ?? 10);
        
        $minDate = Carbon::today()->addDays($minDays)->format('Y-m-d');
        $maxDate = Carbon::today()->addDays($maxDays)->format('Y-m-d');

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:500',
            'delivery_date' => 'required|date|after_or_equal:' . $minDate . '|before_or_equal:' . $maxDate,
            'notes' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        // Verificar disponibilidad de la fecha seleccionada (solo órdenes con pago aprobado)
        $ordersOnDate = Order::whereDate('delivery_date', $validated['delivery_date'])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($ordersOnDate >= $maxOrdersPerDay) {
            return back()->withInput()->withErrors([
                'delivery_date' => 'Esta fecha ya no tiene disponibilidad. Por favor selecciona otra fecha.'
            ]);
        }

        // Calcular subtotal sin descuentos primero (para validar montos mínimos de ofertas)
        $subtotalBeforeDiscount = 0;
        foreach ($cart as $productId => $item) {
            $subtotalBeforeDiscount += $item['quantity'] * $item['price'];
        }

        // Calcular totales aplicando descuentos
        $subtotal = 0;
        $orderItems = [];
        $appliedOffers = [];
        
        foreach ($cart as $productId => $item) {
            $product = Product::with('offers')->find($productId);
            $price = $item['price'];
            $finalPrice = $price;
            
            // Aplicar oferta si existe y cumple monto mínimo
            if ($product) {
                $activeOffer = $product->getActiveOffer();
                if ($activeOffer && $activeOffer->isApplicable($subtotalBeforeDiscount)) {
                    // Solo aplicar descuentos de porcentaje por producto
                    if ($activeOffer->discount_type === 'percentage') {
                        $discount = $activeOffer->calculateDiscount($price);
                        $finalPrice = $price - $discount;
                    }
                    // Para descuentos fijos, solo marcar que se aplicarán
                    elseif ($activeOffer->discount_type === 'fixed' && !in_array($activeOffer->id, $appliedOffers)) {
                        $appliedOffers[] = $activeOffer->id;
                    }
                }
            }
            
            $itemSubtotal = $item['quantity'] * $finalPrice;
            $subtotal += $itemSubtotal;
            
            $orderItems[] = [
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $finalPrice, // Guardar precio con descuento de porcentaje
                'subtotal' => $itemSubtotal,
                'product_name' => $item['name']
            ];
        }

        // Aplicar descuentos fijos al total
        foreach ($appliedOffers as $offerId) {
            $offer = \App\Models\Offer::find($offerId);
            if ($offer) {
                $fixedDiscount = min($offer->discount_value, $subtotal);
                $subtotal -= $fixedDiscount;
            }
        }

        // Verificar monto mínimo y máximo
        $minOrderAmount = (float) (Setting::where('key', 'minimum_order_amount')->value('value') ?? 0);
        $maxOrderAmount = 12000000; // $12,000,000 ARS
        
        if ($subtotal < $minOrderAmount) {
            return redirect()->route('cart.index')->with('error', 'El monto mínimo de compra es $' . number_format($minOrderAmount, 2));
        }
        
        if ($subtotal > $maxOrderAmount) {
            return redirect()->route('cart.index')->with('error', 'El monto máximo de compra es $' . number_format($maxOrderAmount, 2) . '. Por favor contacta con nosotros para pedidos grandes.');
        }

        // Guardar datos en sesión para crear la orden después del pago
        session()->put('checkout_data', [
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'delivery_address' => $validated['delivery_address'],
            'delivery_date' => $validated['delivery_date'],
            'notes' => $validated['notes'] ?? null,
            'order_items' => $orderItems,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('checkout.payment');
    }

    public function payment()
    {
        $checkoutData = session()->get('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('cart.index')->with('error', 'No hay un pedido pendiente');
        }

        // Obtener métodos de pago habilitados
        $paymentMethods = [
            'mercadopago' => (bool) (Setting::where('key', 'payment_mercadopago_enabled')->value('value') ?? true),
            'bank_transfer' => (bool) (Setting::where('key', 'payment_bank_transfer_enabled')->value('value') ?? true),
            'cash' => (bool) (Setting::where('key', 'payment_cash_enabled')->value('value') ?? true),
        ];

        return view('checkout.payment', compact('checkoutData', 'paymentMethods'));
    }
}
