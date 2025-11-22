<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'plu' => 'required|string|max:255|unique:products,plu',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:N,P',
            'min_order' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');

        // Manejar la imagen
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'plu' => 'required|string|max:255|unique:products,plu,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:N,P',
            'min_order' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');

        // Manejar la imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        // Eliminar imagen si existe
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,toggle-availability,toggle-featured',
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $action = $validated['action'];
        $ids = $validated['ids'];
        $count = count($ids);

        switch ($action) {
            case 'delete':
                $products = Product::whereIn('id', $ids)->get();
                foreach ($products as $product) {
                    // Eliminar imagen si existe
                    if ($product->image && \Storage::disk('public')->exists($product->image)) {
                        \Storage::disk('public')->delete($product->image);
                    }
                    $product->delete();
                }
                $message = "{$count} producto(s) eliminado(s) exitosamente.";
                break;

            case 'toggle-availability':
                foreach ($ids as $id) {
                    $product = Product::find($id);
                    if ($product) {
                        $product->update(['is_available' => !$product->is_available]);
                    }
                }
                $message = "Disponibilidad actualizada para {$count} producto(s).";
                break;

            case 'toggle-featured':
                foreach ($ids as $id) {
                    $product = Product::find($id);
                    if ($product) {
                        $product->update(['is_featured' => !$product->is_featured]);
                    }
                }
                $message = "Estado destacado actualizado para {$count} producto(s).";
                break;
        }

        return redirect()->route('admin.products.index')
            ->with('success', $message);
    }
}
