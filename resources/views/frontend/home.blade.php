@extends('frontend.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Welcome to Our Store
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Discover amazing products at unbeatable prices
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('products') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Shop Now
                    </a>
                    <a href="{{ route('products') }}?category=all" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                        Browse Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Shop by Category</h2>
                <p class="mt-2 text-gray-600">Explore our wide range of products</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                @php
                    $categories = \App\Models\Category::where('status', 'active')->with('products.images')->get();
                @endphp

                @foreach($categories as $category)
                    <a href="{{ route('products', ['category' => $category->slug]) }}" 
                       class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $category->products->count() }} products</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Featured Products</h2>
                    <p class="mt-2 text-gray-600">Check out our latest arrivals</p>
                </div>
                <a href="{{ route('products') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    View All Products →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($featuredProducts as $product)
                    <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden">
                        <!-- Product Image -->
                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Discount Badge -->
                            @if($product->discount_price)
                                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                    SALE
                                </span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <p class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</p>
                            <a href="{{ route('product.show', $product->slug) }}">
                                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 truncate">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            
                            <div class="mt-2 flex items-center gap-2">
                                @if($product->discount_price)
                                    <span class="text-lg font-bold text-red-600">${{ number_format($product->discount_price, 2) }}</span>
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>

                            <!-- Add to Cart -->
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                                    Add to Cart
                                </button>
                            </form>
                            <a href="{{ route('checkout') }}" class="mt-2 w-full block text-center bg-green-600 text-white py-2 rounded-lg font-medium hover:bg-green-700 transition">
                                Order Now
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No products available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Free Shipping</h3>
                    <p class="text-gray-600 text-sm">On orders over $50</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Secure Payment</h3>
                    <p class="text-gray-600 text-sm">100% secure checkout</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Easy Returns</h3>
                    <p class="text-gray-600 text-sm">30-day return policy</p>
                </div>
            </div>
        </div>
    </section>
@endsection
