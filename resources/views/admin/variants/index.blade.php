@extends('admin.layouts.app')

@section('title', 'Product Variants - ' . $product->name)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
                &larr; Back to Products
            </a>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">
                Variants: {{ $product->name }}
            </h2>
        </div>
        <a href="{{ route('admin.products.variants.create', $product->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add Variant
        </a>
    </div>

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

    <!-- Variants Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Color</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price Override</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($variants as $variant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $variant->sku }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $variant->size ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $variant->color ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($variant->price)
                                ${{ number_format($variant->price, 2) }}
                            @else
                                <span class="text-gray-400">Uses product price</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($variant->stock > 0)
                                <span class="text-green-600 font-medium">{{ $variant->stock }}</span>
                            @else
                                <span class="text-red-600 font-medium">Out of stock</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.products.variants.edit', [$product->id, $variant->id]) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No variants found. <a href="{{ route('admin.products.variants.create', $product->id) }}" class="text-blue-600 hover:text-blue-900">Add one now</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $variants->links() }}
    </div>
@endsection
