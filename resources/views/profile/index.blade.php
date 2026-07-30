<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Personal Information') }}</h3>
                    <p class="text-gray-600 mb-2"><strong>{{ __('Name:') }}</strong> {{ $user->name }}</p>
                    <p class="text-gray-600 mb-2"><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-4">
                        {{ __('Edit Profile') }}
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('My Orders') }}</h3>
                    @if($orders->isEmpty())
                        <p class="text-gray-500">{{ __('You have no orders yet.') }}</p>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ __('Order #') }}{{ $order->order_number }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600"><strong>{{ __('Total:') }}</strong> ${{ number_format($order->total_amount, 2) }}</p>
                                    @if($order->items->count() > 0)
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $order->items->count() }} {{ __('item(s)') }}
                                            @foreach($order->items->take(2) as $item)
                                                , {{ $item->product->name }}
                                            @endforeach
                                            @if($order->items->count() > 2)
                                                {{ __(' and more') }}
                                            @endif
                                        </p>
                                    @endif
                                    <div class="flex gap-2 mt-4">
                                        <form action="{{ route('profile.reorder', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gray-200 border border-transparent rounded-md text-xs font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Add to Cart') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('profile.reorder-checkout', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md text-xs font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Buy Now') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
