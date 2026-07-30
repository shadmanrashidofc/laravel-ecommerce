<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
