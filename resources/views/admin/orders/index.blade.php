@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Orders</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-4">
            <input type="text" 
                   name="search" 
                   value="{{ $search }}" 
                   placeholder="Search by order #, name, email, phone..."
                   class="flex-1 min-w-[200px] border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
            
            <select name="status" 
                    class="border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                Filter
            </button>
            @if($search || $status)
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->email }}</div>
                            <div class="text-xs text-gray-500">{{ $order->phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->items_count }} item(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ucfirst($order->payment_method) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($order->status)
                                @case('pending')
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                    @break
                                @case('processing')
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Processing</span>
                                    @break
                                @case('shipped')
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Shipped</span>
                                    @break
                                @case('delivered')
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Delivered</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Cancelled</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            No orders found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection
