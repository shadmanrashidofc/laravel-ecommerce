@extends('frontend.layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="mt-2 text-gray-600">Review your items before checkout</p>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cartItems))
            <div class="text-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('products') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b">
                            <h2 class="text-lg font-semibold">Cart Items ({{ count($cartItems) }})</h2>
                        </div>

                        <div class="divide-y">
                            @foreach($cartItems as $item)
                                <div class="p-6 flex gap-6">
                                    <!-- Product Image -->
                                    <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" 
                                                 alt="{{ $item['product_name'] }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">
                                                    <a href="{{ route('product.show', $item['product']->slug) }}" class="hover:text-blue-600">
                                                        {{ $item['product_name'] }}
                                                    </a>
                                                </h3>
                                                @if($item['color'] || $item['size'])
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        @if($item['color']) Color: {{ $item['color'] }} @endif
                                                        @if($item['color'] && $item['size']) / @endif
                                                        @if($item['size']) Size: {{ $item['size'] }} @endif
                                                    </p>
                                                @endif
                                                <p class="text-sm text-gray-500 mt-1">${{ number_format($item['price'], 2) }} each</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900">${{ number_format($item['subtotal'], 2) }}</p>
                                            </div>
                                        </div>

                                        <!-- Quantity & Actions -->
                                        <div class="flex items-center justify-between mt-4">
                                            <!-- Update Quantity Form -->
                                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="key" value="{{ $item['key'] }}">
                                                <button type="button" onclick="this.previousElementSibling.value = Math.max(1, parseInt(this.nextElementSibling.value) - 1); this.form.submit()" 
                                                        class="w-8 h-8 border rounded flex items-center justify-center hover:bg-gray-100">-</button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                                       onchange="this.form.submit()"
                                                       class="w-16 text-center border rounded py-1">
                                                <button type="button" onclick="this.previousElementSibling.value = parseInt(this.previousElementSibling.value) + 1; this.form.submit()"
                                                        class="w-8 h-8 border rounded flex items-center justify-center hover:bg-gray-100">+</button>
                                            </form>

                                            <!-- Remove Form -->
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="key" value="{{ $item['key'] }}">
                                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Clear Cart -->
                        <div class="p-6 border-t bg-gray-50">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-red-600">
                                    Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h2 class="text-lg font-semibold mb-6">Order Summary</h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ count($cartItems) }} items)</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <div class="border-t pt-4 flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}" 
                           class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('products') }}" 
                           class="block w-full text-center text-gray-600 hover:text-gray-900 mt-4 text-sm">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
