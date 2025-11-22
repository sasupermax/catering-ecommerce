<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Mostrar lista de banners
     */
    public function index()
    {
        $banners = Banner::ordered()->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Guardar nuevo banner
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Subir imagen
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        // Asignar orden automático
        $maxOrder = Banner::max('display_order') ?? 0;
        $validated['display_order'] = $maxOrder + 1;

        $validated['is_active'] = $request->has('is_active');

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner creado exitosamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Actualizar banner
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Subir nueva imagen si se proporciona
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $banner->update($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner actualizado exitosamente');
    }

    /**
     * Eliminar banner
     */
    public function destroy(Banner $banner)
    {
        // Eliminar imagen
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner eliminado exitosamente');
    }

    /**
     * Actualizar orden de banners (AJAX)
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            Banner::where('id', $id)->update(['display_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
