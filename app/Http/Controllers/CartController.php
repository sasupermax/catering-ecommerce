<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotalBeforeDiscount = 0;
        
        // Primero calcular el subtotal sin descuentos para validar montos mínimos
        foreach ($cart as $productId => $item) {
            $subtotalBeforeDiscount += $item['quantity'] * $item['price'];
        }

        $total = 0;
        $totalDiscount = 0;
        $appliedOffers = []; // Para controlar ofertas fijas que solo se aplican una vez

        foreach ($cart as $productId => $item) {
            $product = Product::with('offers')->find($productId);
            if ($product) {
                $originalPrice = $item['price'];
                $finalPrice = $originalPrice;
                $discount = 0;
                $offer = null;

                // Obtener oferta activa para este producto
                $activeOffer = $product->getActiveOffer();
                
                // Validar que la oferta sea aplicable con el subtotal actual
                if ($activeOffer && $activeOffer->isApplicable($subtotalBeforeDiscount)) {
                    // Para descuentos de porcentaje, aplicar por producto
                    if ($activeOffer->discount_type === 'percentage') {
                        $discount = $activeOffer->calculateDiscount($originalPrice);
                        $finalPrice = $originalPrice - $discount;
                        $offer = $activeOffer;
                    } 
                    // Para descuentos fijos, aplicar solo una vez por oferta
                    elseif ($activeOffer->discount_type === 'fixed' && !in_array($activeOffer->id, $appliedOffers)) {
                        // Marcar oferta como aplicada
                        $appliedOffers[] = $activeOffer->id;
                        // El descuento fijo se aplicará al final, no por producto
                        $offer = $activeOffer;
                    }
                }

                $subtotal = $item['quantity'] * $finalPrice;
                $originalSubtotal = $item['quantity'] * $originalPrice;
                
                $cartItems[] = [
                    'product_id' => $productId,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'original_price' => $originalPrice,
                    'price' => $finalPrice,
                    'discount' => $discount,
                    'subtotal' => $subtotal,
                    'original_subtotal' => $originalSubtotal,
                    'offer' => $offer
                ];
                
                $total += $subtotal;
                $totalDiscount += ($originalSubtotal - $subtotal);
            }
        }

        // Aplicar descuentos fijos al total (una sola vez por oferta)
        foreach ($appliedOffers as $offerId) {
            $offer = \App\Models\Offer::find($offerId);
            if ($offer) {
                $fixedDiscount = min($offer->discount_value, $total); // No descontar más del total
                $total -= $fixedDiscount;
                $totalDiscount += $fixedDiscount;
            }
        }

        // Obtener monto mínimo de compra
        $minOrderAmount = (float) (Setting::where('key', 'minimum_order_amount')->value('value') ?? 0);

        // Calcular subtotal original (antes de descuentos)
        $subtotalOriginal = $total + $totalDiscount;

        return view('cart.index', compact('cartItems', 'total', 'minOrderAmount', 'totalDiscount', 'subtotalOriginal'));
    }

    public function add(Request $request, Product $product)
    {
        // Validar según el tipo de producto
        if ($product->type === 'N') {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:10000'
            ]);
        } else {
            $validated = $request->validate([
                'quantity' => 'required|numeric|min:0.5|max:10000'
            ]);
        }
        
        // Validación adicional de seguridad: prevenir valores negativos o cero
        if ($validated['quantity'] <= 0) {
            return back()->withErrors(['quantity' => 'La cantidad debe ser mayor a 0']);
        }

        $cart = session()->get('cart', []);

        // Si el producto ya existe, SUMAR la cantidad
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $validated['quantity'];
        } else {
            // Agregar nuevo producto al carrito
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
                'image' => $product->image,
                'type' => $product->type
            ];
        }

        session()->put('cart', $cart);

        // Si es una petición AJAX, devolver JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado al carrito',
                'cart_count' => count($cart)
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        // Validar según el tipo de producto
        if ($product->type === 'N') {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:10000'
            ]);
        } else {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:0.5|max:10000'
            ]);
        }
        
        // Validación adicional de seguridad: prevenir valores negativos o cero
        if ($validated['quantity'] <= 0) {
            return response()->json(['success' => false, 'error' => 'La cantidad debe ser mayor a 0'], 400);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$validated['product_id']])) {
            $cart[$validated['product_id']]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);

            // Recalcular todo (la lógica es compleja, mejor recargar)
            // Pero devolvemos valores temporales para la UI
            
            // Calcular subtotal sin descuentos primero
            $subtotalBeforeDiscount = 0;
            foreach ($cart as $productId => $item) {
                $subtotalBeforeDiscount += $item['quantity'] * $item['price'];
            }

            // Calcular el subtotal del producto actualizado
            $product = Product::with('offers')->find($validated['product_id']);
            $price = $cart[$validated['product_id']]['price'];
            $finalPrice = $price;
            
            // Aplicar oferta activa si existe y cumple monto mínimo (solo porcentajes)
            $activeOffer = $product->getActiveOffer();
            if ($activeOffer && $activeOffer->isApplicable($subtotalBeforeDiscount)) {
                if ($activeOffer->discount_type === 'percentage') {
                    $discount = $activeOffer->calculateDiscount($price);
                    $finalPrice = $price - $discount;
                }
            }

            $subtotal = $cart[$validated['product_id']]['quantity'] * $finalPrice;
            
            // Calcular total con ofertas validando montos mínimos
            $total = 0;
            $appliedOffers = [];
            
            foreach ($cart as $productId => $item) {
                $prod = Product::with('offers')->find($productId);
                $itemPrice = $item['price'];
                
                $offer = $prod->getActiveOffer();
                if ($offer && $offer->isApplicable($subtotalBeforeDiscount)) {
                    if ($offer->discount_type === 'percentage') {
                        $disc = $offer->calculateDiscount($itemPrice);
                        $itemPrice = $itemPrice - $disc;
                    } elseif ($offer->discount_type === 'fixed' && !in_array($offer->id, $appliedOffers)) {
                        $appliedOffers[] = $offer->id;
                    }
                }
                
                $total += $item['quantity'] * $itemPrice;
            }
            
            // Aplicar descuentos fijos
            foreach ($appliedOffers as $offerId) {
                $offer = \App\Models\Offer::find($offerId);
                if ($offer) {
                    $fixedDiscount = min($offer->discount_value, $total);
                    $total -= $fixedDiscount;
                }
            }

            // Obtener monto mínimo de pedido
            $minOrderAmount = (float) (Setting::where('key', 'minimum_order_amount')->value('value') ?? 0);

            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'subtotalOriginal' => $subtotalBeforeDiscount,
                'total' => $total,
                'minOrderAmount' => $minOrderAmount,
                'reload' => count($appliedOffers) > 0 // Indicar si hay ofertas fijas para recargar
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count' => $count]);
    }
}
