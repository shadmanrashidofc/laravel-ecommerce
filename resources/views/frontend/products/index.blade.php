@extends('frontend.layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Our Products</h1>
            <p class="mt-2 text-gray-600">Browse our collection of quality products</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Filters</h2>

                    <!-- Search -->
                    <form method="GET" action="{{ route('products') }}" class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="flex">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search products..."
                                   class="flex-1 rounded-l-lg border-gray-300 border px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('products', array_filter(request()->except('search'))) }}" class="text-sm text-blue-600 hover:underline mt-1 inline-block">
                                Clear search
                            </a>
                        @endif
                    </form>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Categories</h3>
                        <div class="space-y-2">
                            <a href="{{ route('products', array_filter(request()->except('category'))) }}" 
                               class="block {{ !request('category') ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                                All Categories
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('products', array_merge(request()->except('page'), ['category' => $category->slug])) }}" 
                                   class="block {{ request('category') == $category->slug ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Clear All Filters -->
                    @if(request()->hasAny(['search', 'category', 'sort']))
                        <a href="{{ route('products') }}" class="text-sm text-red-600 hover:text-red-700">
                            Clear All Filters
                        </a>
                    @endif
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <!-- Sort & Results Info -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <p class="text-gray-600">
                        Showing <span class="font-medium">{{ $products->firstItem() }}</span> to 
                        <span class="font-medium">{{ $products->lastItem() }}</span> of 
                        <span class="font-medium">{{ $products->total() }}</span> products
                    </p>

                    <form method="GET" action="{{ route('products') }}" class="flex items-center gap-2">
                        @foreach(request()->except('sort') as $key => $value)
                            @if($key !== 'sort' && $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <label for="sort" class="text-sm text-gray-700">Sort by:</label>
                        <select name="sort" 
                                id="sort" 
                                onchange="this.form.submit()"
                                class="border-gray-300 border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                        </select>
                    </form>
                </div>

                <!-- Products Grid -->
                @if($products->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
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

                                    @if($product->discount_price)
                                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            SALE
                                        </span>
                                    @endif

                                    <!-- Quick View -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <a href="{{ route('product.show', $product->slug) }}" 
                                           class="bg-white text-gray-900 px-4 py-2 rounded-lg font-medium transform translate-y-4 group-hover:translate-y-0 transition">
                                            Quick View
                                        </a>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="p-4">
                                    <p class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</p>
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 truncate">
                                            {{ $product->name }}
                                        </h3>
                                    </a>

                                    <!-- Variants Info -->
                                    @if($product->variants->isNotEmpty())
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $product->variants->pluck('color')->filter()->unique()->count() }} colors, 
                                            {{ $product->variants->pluck('size')->filter()->unique()->count() }} sizes
                                        </p>
                                    @endif

                                    <div class="mt-2 flex items-center gap-2">
                                        @if($product->discount_price)
                                            <span class="text-lg font-bold text-red-600">${{ number_format($product->discount_price, 2) }}</span>
                                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

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
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-500 mb-4">Try adjusting your search or filter criteria</p>
                        <a href="{{ route('products') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Clear Filters
                        </a>
                    </div>
                @endif

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
