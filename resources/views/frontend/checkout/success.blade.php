@extends('frontend.layouts.app')

@section('content')
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Order Confirmation</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-12">
            <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Thank You!</h2>
            <p class="text-gray-600">Your order has been placed successfully.</p>
        </div>

        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">Order Details</h3>
                        <p class="text-sm text-gray-500">Order #{{ $order->order_number }}</p>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Customer Name</h4>
                        <p class="text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Phone</h4>
                        <p class="text-gray-900">{{ $order->phone }}</p>
                    </div>
                    @if($order->email)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                            <p class="text-gray-900">{{ $order->email }}</p>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Payment Method</h4>
                        <p class="text-gray-900">{{ $order->payment_method }}</p>
                    </div>
                    <div class="col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Delivery Address</h4>
                        <p class="text-gray-900">{{ $order->address }}, {{ $order->city }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-4">Order Items</h4>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4">
                                <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                    @if($item->product && $item->product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                             alt="{{ $item->product_name_snapshot }}"
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
                                    <h4 class="font-medium text-gray-900">{{ $item->product_name_snapshot }}</h4>
                                    @if($item->variant_snapshot)
                                        <p class="text-sm text-gray-500">
                                            @if($item->variant_snapshot['color']) Color: {{ $item->variant_snapshot['color'] }} @endif
                                            @if($item->variant_snapshot['color'] && $item->variant_snapshot['size']) / @endif
                                            @if($item->variant_snapshot['size']) Size: {{ $item->variant_snapshot['size'] }} @endif
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t mt-6 pt-6">
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50">
                <div class="flex justify-between items-center">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        ← Back to Home
                    </a>
                    <a href="{{ route('products') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
