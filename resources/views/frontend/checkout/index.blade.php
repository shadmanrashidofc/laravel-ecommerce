@extends('frontend.layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="mt-2 text-gray-600">Complete your order</p>
        </div>
    </div>

    <!-- Checkout Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.place-order') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-6">Customer Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Name -->
                            <div class="md:col-span-2">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="customer_name" 
                                       id="customer_name"
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                                       class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('customer_name') border-red-500 @enderror"
                                       required>
                                @error('customer_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone"
                                       value="{{ old('phone') }}"
                                       class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                       required>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-gray-400">(optional)</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       value="{{ old('email', Auth::user()->email ?? '') }}"
                                       class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="city" 
                                       id="city"
                                       value="{{ old('city') }}"
                                       class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-500 @enderror"
                                       required>
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                    Payment Method <span class="text-red-500">*</span>
                                </label>
                                <select name="payment_method" 
                                        id="payment_method"
                                        class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_method') border-red-500 @enderror"
                                        required>
                                    <option value="COD" selected>Cash on Delivery (COD)</option>
                                </select>
                                @error('payment_method')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Delivery Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" 
                                          id="address" 
                                          rows="3"
                                          class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                          required>{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-4 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Place Order
                        </button>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h2 class="text-lg font-semibold mb-6">Order Summary</h2>

                        <!-- Cart Items -->
                        <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex gap-4">
                                    <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                        @if($item['product']->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}" 
                                                 alt="{{ $item['product']->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->name }}</h3>
                                        <p class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                        <p class="text-sm font-medium text-gray-900">${{ number_format($item['subtotal'], 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Security Note -->
                        <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Secure Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
