@extends('frontend.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
        <p class="mt-2 text-gray-600">Manage your profile and view your orders</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Info Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl font-bold text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <p class="text-sm text-gray-400 mt-2">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>
                
                <div class="mt-6 space-y-3">
                    <a href="{{ route('profile.edit') }}" class="block w-full text-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">My Orders</h3>
                </div>
                
                <div class="p-6">
                    @if($orders->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500">You haven't placed any orders yet.</p>
                            <a href="{{ route('products') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Start Shopping
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="font-semibold text-gray-900">Order #{{ $order->order_number }}</span>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                                                <span>{{ $order->items->count() }} item(s)</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-lg font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                            @if($order->items->count() > 0)
                                                <div class="flex gap-2">
                                                    <form action="{{ route('profile.reorder', $order->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                                                            Reorder
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
