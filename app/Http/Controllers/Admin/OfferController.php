<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('products')->latest()->paginate(10);
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::where('is_available', true)->orderBy('name')->get();
        return view('admin.offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        // Validar descuento de porcentaje
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'El descuento porcentual no puede ser mayor a 100%'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['min_purchase_amount'] = $validated['min_purchase_amount'] ?? 0;

        // Subir imagen si existe
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('offers', 'public');
        }

        $offer = Offer::create($validated);

        // Asociar productos
        if ($request->has('products')) {
            $offer->products()->attach($request->products);
        }

        return redirect()->route('admin.offers.index')
            ->with('success', 'Oferta creada exitosamente');
    }

    public function edit(Offer $offer)
    {
        $products = Product::where('is_available', true)->orderBy('name')->get();
        $offer->load('products');
        return view('admin.offers.edit', compact('offer', 'products'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        // Validar descuento de porcentaje
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'El descuento porcentual no puede ser mayor a 100%'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['min_purchase_amount'] = $validated['min_purchase_amount'] ?? 0;

        // Actualizar imagen si se subió una nueva
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $validated['image'] = $request->file('image')->store('offers', 'public');
        }

        $offer->update($validated);

        // Sincronizar productos
        if ($request->has('products')) {
            $offer->products()->sync($request->products);
        } else {
            $offer->products()->detach();
        }

        return redirect()->route('admin.offers.index')
            ->with('success', 'Oferta actualizada exitosamente');
    }

    public function destroy(Offer $offer)
    {
        // Eliminar imagen si existe
        if ($offer->image) {
            Storage::disk('public')->delete($offer->image);
        }

        // Eliminar relaciones
        $offer->products()->detach();

        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'Oferta eliminada exitosamente');
    }
}
