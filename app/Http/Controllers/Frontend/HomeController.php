<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'images', 'variants'])
            ->where('status', 'active')
            ->latest()
            ->limit(8)
            ->get();

        return view('frontend.home', compact('featuredProducts'));
    }
}
