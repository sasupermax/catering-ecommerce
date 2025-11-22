<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product']);

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por estado de pago
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filtro por fecha de entrega (opcional)
        if ($request->filled('delivery_date')) {
            $query->whereDate('delivery_date', $request->delivery_date);
        }

        // Búsqueda por número de orden, nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Paginación con selector de cantidad
        $perPage = $request->input('per_page', 5);
        $orders = $query->latest()->paginate($perPage)->appends($request->except('page'));

        // Estadísticas generales (todos los pedidos)
        $stats = [
            'total' => Order::where('payment_status', 'paid')->count(),
            'pending' => Order::where('status', 'pending')
                              ->where('payment_status', 'paid')
                              ->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'today_deliveries' => Order::whereDate('delivery_date', today())
                                       ->where('payment_status', 'paid')
                                       ->whereIn('status', ['pending', 'confirmed'])
                                       ->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,delivered,cancelled',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        $order->update(array_filter($validated));

        return redirect()->back()->with('success', 'Pedido actualizado correctamente.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'status' => $order->status
        ]);
    }

    public function search(Request $request)
    {
        $order = null;
        
        if ($request->filled('order_number')) {
            $order = Order::with(['items.product'])
                          ->where('order_number', $request->order_number)
                          ->first();
        }

        return view('admin.orders.search', compact('order'));
    }
}
