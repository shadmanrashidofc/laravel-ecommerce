@extends('admin.layouts.app')

@section('title', 'Edit Variant - ' . $product->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products.variants.index', $product->id) }}" class="text-blue-600 hover:text-blue-700">
            &larr; Back to Variants
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Variant</h2>

            <form method="POST" action="{{ route('admin.products.variants.update', [$product->id, $variant->id]) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                            SKU <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="sku" 
                               id="sku"
                               value="{{ old('sku', $variant->sku) }}"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror"
                               required>
                        @error('sku')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Size -->
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 mb-2">
                            Size
                        </label>
                        <input type="text" 
                               name="size" 
                               id="size"
                               value="{{ old('size', $variant->size) }}"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('size') border-red-500 @enderror"
                               placeholder="e.g., S, M, L, XL">
                        @error('size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Color
                        </label>
                        <input type="text" 
                               name="color" 
                               id="color"
                               value="{{ old('color', $variant->color) }}"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror"
                               placeholder="e.g., Red, Blue, Black">
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            Stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="stock" 
                               id="stock"
                               value="{{ old('stock', $variant->stock) }}"
                               min="0"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror"
                               required>
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price Override -->
                    <div class="md:col-span-2">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Price Override
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" 
                                   name="price" 
                                   id="price"
                                   value="{{ old('price', $variant->price) }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full border-gray-300 border rounded-lg pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                   placeholder="Leave empty to use product price">
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Leave empty to use the product's base price</p>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Update Variant
                    </button>
                    <a href="{{ route('admin.products.variants.index', $product->id) }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
