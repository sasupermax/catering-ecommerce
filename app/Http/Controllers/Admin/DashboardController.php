<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => Order::where('payment_status', 'paid')->count(),
            'users' => User::count(),
            'pendingOrders' => Order::where('status', 'pending')->where('payment_status', 'paid')->count(),
            'todayDeliveries' => Order::whereDate('delivery_date', today())
                                      ->where('payment_status', 'paid')
                                      ->whereIn('status', ['pending', 'confirmed'])
                                      ->count(),
        ];

        $todayOrders = Order::with(['items.product'])
                            ->whereDate('delivery_date', today())
                            ->where('payment_status', 'paid')
                            ->whereIn('status', ['pending', 'confirmed'])
                            ->get();

        $recentOrders = Order::with(['items.product'])
                             ->where('payment_status', 'paid')
                             ->latest()
                             ->take(5)
                             ->get();

        return view('admin.dashboard', compact('stats', 'todayOrders', 'recentOrders'));
    }
}
