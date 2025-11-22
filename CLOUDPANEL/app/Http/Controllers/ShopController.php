<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();
        
        $featuredProducts = Product::where('is_available', true)
            ->where('is_featured', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('shop.index', compact('categories', 'featuredProducts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $query = Product::where('category_id', $category->id)
            ->where('is_available', true);

        // Buscar por nombre
        if (request()->has('search') && request('search') !== null && request('search') !== '') {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        // Filtrar por precio
        if (request()->has('min_price') && request('min_price') !== null) {
            $query->where('price', '>=', request('min_price'));
        }
        
        if (request()->has('max_price') && request('max_price') !== null) {
            $query->where('price', '<=', request('max_price'));
        }

        // Ordenar
        $sort = request('sort', 'newest');
        
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 16 productos por carga (buen balance entre rendimiento y UX)
        $products = $query->paginate(16)->withQueryString();

        // Si es petición AJAX, solo devolver JSON con los productos
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'products' => $products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'price_suffix' => $product->price_suffix,
                        'image' => $product->image ? asset('storage/' . $product->image) : null,
                        'category_name' => $product->category->name,
                        'url' => route('product', $product->slug),
                    ];
                }),
                'has_more' => $products->hasMorePages(),
                'next_page' => $products->currentPage() + 1,
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
            ]);
        }

        return view('shop.category', compact('category', 'products'));
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)
            ->with('category')
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }
}
