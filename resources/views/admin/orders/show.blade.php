@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700">
            &larr; Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Order Items</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($order->items as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name_snapshot }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        @if($item->variant_snapshot)
                                            {{ $item->variant_snapshot['size'] ?? '' }} / {{ $item->variant_snapshot['color'] ?? '' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm text-gray-500">
                                        ${{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                        No items found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Total</td>
                                <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Shipping Address</h3>
                <div class="text-sm text-gray-600">
                    <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->city }}</p>
                    <p>{{ $order->phone }}</p>
                    <p>{{ $order->email }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Order Number</span>
                        <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span class="text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Payment Method</span>
                        <span class="text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Amount</span>
                        <span class="font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    @if($order->user)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Customer</span>
                            <span class="text-gray-900">{{ $order->user->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Update Status</h3>
                
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <select name="status" 
                                class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
