@extends('frontend.layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex text-xs text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-700">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products') }}" class="hover:text-gray-700">Shop</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>
    </div>

    <!-- Product Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10">
            
            <!-- LEFT: Image Gallery (60%) -->
            <div class="lg:col-span-7 order-1">
                <div class="flex gap-3">
                    <!-- Vertical Thumbnails (far left) -->
                    @if($product->images->count() > 1)
                        <div class="hidden lg:flex flex-col gap-2 w-16 shrink-0">
                            @foreach($product->images as $index => $image)
                                <button onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)"
                                        class="thumbnail-btn w-14 h-14 rounded-md overflow-hidden border-2 transition-all duration-200 {{ $index === 0 ? 'border-gray-900' : 'border-transparent hover:border-gray-400' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Main Image -->
                    <div class="flex-1 bg-gray-50 rounded-lg overflow-hidden">
                        @if($product->images->isNotEmpty())
                            <img id="mainImage" 
                                 src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-auto max-h-[450px] lg:max-h-[550px] object-contain object-center">
                        @else
                            <div class="w-full h-64 lg:h-80 flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile Thumbnails -->
                @if($product->images->count() > 1)
                    <div class="lg:hidden flex gap-2 overflow-x-auto pb-2 mt-3">
                        @foreach($product->images as $index => $image)
                            <button onclick="changeImageMobile('{{ asset('storage/' . $image->image_path) }}', this)"
                                    class="thumbnail-btn-mobile shrink-0 w-14 h-14 rounded-md overflow-hidden border-2 {{ $index === 0 ? 'border-gray-900' : 'border-transparent hover:border-gray-400' }} transition-all duration-200">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- RIGHT: Product Details (40%) -->
            <div class="lg:col-span-5 order-2">
                <!-- Category/Subtitle -->
                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">
                    {{ $product->category->name ?? 'Shop' }}
                </p>

                <!-- Product Title -->
                <h1 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide text-gray-900 mb-4">
                    {{ $product->name }}
                </h1>

                <!-- Price Row -->
                <div class="flex items-center gap-3 mb-4">
                    @if($product->discount_price)
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-lg text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Reviews Row -->
                <div class="flex items-center gap-2 mb-6">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">(42 reviews)</span>
                </div>

                <!-- Divider -->
                <hr class="border-gray-200 mb-6">

                <!-- Variant Selection Form -->
                @if($product->variants->isNotEmpty())
                    <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                        @csrf

                        <!-- Color Selection -->
                        @php
                            $availableColors = $product->variants->pluck('color')->filter()->unique()->values();
                            $colorHexMap = [
                                'black' => '#1a1a1a',
                                'white' => '#ffffff',
                                'red' => '#dc2626',
                                'blue' => '#2563eb',
                                'green' => '#16a34a',
                                'yellow' => '#eab308',
                                'pink' => '#ec4899',
                                'purple' => '#9333ea',
                                'orange' => '#ea580c',
                                'gray' => '#6b7280',
                                'grey' => '#6b7280',
                                'navy' => '#1e3a5f',
                                'beige' => '#f5f5dc',
                                'brown' => '#92400e',
                                'cream' => '#fffdd0',
                                'burgundy' => '#800020',
                                'olive' => '#808000',
                                'tan' => '#d2b48c',
                            ];
                        @endphp
                        @if($availableColors->isNotEmpty())
                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-900 mb-3">
                                    Color: <span id="selectedColorName" class="font-normal text-gray-500"></span>
                                </p>
                                <div class="flex flex-wrap gap-2" id="colorOptions">
                                    @foreach($availableColors as $color)
                                        @php
                                            $hex = $colorHexMap[strtolower($color)] ?? '#6b7280';
                                        @endphp
                                        <button type="button"
                                                onclick="selectColor('{{ $color }}', this)"
                                                class="color-btn w-8 h-8 rounded-full border-2 border-gray-200 hover:border-gray-400 transition-all duration-200"
                                                style="background-color: {{ $hex }}; {{ strtolower($color) === 'white' ? 'border: 1px solid #d1d5db;' : '' }}"
                                                title="{{ $color }}">
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="color" id="selectedColor" value="">
                            </div>
                        @endif

                        <!-- Size Selection -->
                        @php
                            $sizeOrder = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];
                            $availableSizes = $product->variants->pluck('size')->filter()->unique()->values()->sortBy(function($size) use ($sizeOrder) {
                                return array_search($size, $sizeOrder) !== false ? array_search($size, $sizeOrder) : 999;
                            });
                        @endphp
                        @if($availableSizes->isNotEmpty())
                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-900 mb-3">
                                    Size: <span id="selectedSizeName" class="font-normal text-gray-500"></span>
                                </p>
                                <div class="flex flex-wrap gap-2" id="sizeOptions">
                                    @foreach($availableSizes as $size)
                                        @php
                                            $hasStock = $product->variants->where('size', $size)->sum('stock') > 0;
                                        @endphp
                                        <button type="button"
                                                onclick="{{ $hasStock ? "selectSize('" . $size . "', this)" : 'void(0)' }}"
                                                class="size-btn min-w-[48px] h-10 px-3 rounded-md border border-gray-300 text-sm font-medium transition-all duration-200 {{ $hasStock ? 'hover:border-gray-800 hover:bg-gray-50 cursor-pointer' : 'opacity-40 cursor-not-allowed bg-gray-100' }}"
                                                data-size="{{ $size }}"
                                                {{ !$hasStock ? 'disabled' : '' }}>
                                            {{ $size }}
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="size" id="selectedSize" value="">
                            </div>
                        @endif

                        <!-- Hidden Fields -->
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="selectedVariantId" value="">

                        <!-- Quantity & Add to Cart -->
                        <div class="mb-6">
                            <div class="flex items-center gap-4 mb-4">
                                <!-- Quantity Selector -->
                                <div class="flex items-center border border-gray-300 rounded-md">
                                    <button type="button" onclick="decreaseQty()" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition">−</button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" 
                                           class="w-12 text-center border-none focus:ring-0 text-gray-900 font-medium">
                                    <button type="button" onclick="increaseQty()" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition">+</button>
                                </div>
                                <span id="stockStatus" class="text-sm text-gray-500"></span>
                            </div>

                            <!-- Add to Cart Button -->
                            <button type="submit" id="addToCartBtn" 
                                    class="w-full h-12 bg-gray-900 text-white font-semibold uppercase tracking-wide rounded-md hover:bg-gray-800 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                                    disabled>
                                Add to Cart
                            </button>
                            <a href="{{ route('checkout') }}" class="mt-3 w-full h-12 block text-center bg-green-600 text-white font-semibold uppercase tracking-wide rounded-md hover:bg-green-700 transition">
                                Order Now
                            </a>
                        </div>
                    </form>
                @else
                    <!-- Simple Add to Cart (no variants) -->
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full h-12 bg-gray-900 text-white font-semibold uppercase tracking-wide rounded-md hover:bg-gray-800 transition">
                            Add to Cart
                        </button>
                        <a href="{{ route('checkout') }}" class="mt-3 w-full h-12 block text-center bg-green-600 text-white font-semibold uppercase tracking-wide rounded-md hover:bg-green-700 transition">
                            Order Now
                        </a>
                    </form>
                @endif

                <!-- Description Accordion -->
                <div class="border-t border-gray-200">
                    <button onclick="toggleAccordion()" class="w-full py-4 flex items-center justify-between text-left">
                        <span class="text-sm font-semibold uppercase tracking-wide text-gray-900">Description</span>
                        <svg id="accordionIcon" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="accordionContent" class="pb-4 overflow-hidden transition-all duration-300" style="max-height: 0;">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const variants = @json($product->variants);
        let currentVariant = null;

        // Image Gallery - Desktop
        function changeImage(src, btn) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail-btn').forEach(b => {
                b.classList.remove('border-gray-900');
                b.classList.add('border-transparent');
            });
            btn.classList.remove('border-transparent');
            btn.classList.add('border-gray-900');
        }

        // Image Gallery - Mobile
        function changeImageMobile(src, btn) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail-btn-mobile').forEach(b => {
                b.classList.remove('border-gray-900');
                b.classList.add('border-transparent');
            });
            btn.classList.remove('border-transparent');
            btn.classList.add('border-gray-900');
        }

        // Color Selection
        function selectColor(color, btn) {
            document.querySelectorAll('.color-btn').forEach(b => {
                b.classList.remove('border-gray-900', 'ring-2', 'ring-gray-900', 'ring-offset-2');
            });
            btn.classList.add('border-gray-900', 'ring-2', 'ring-gray-900', 'ring-offset-2');
            document.getElementById('selectedColor').value = color;
            document.getElementById('selectedColorName').textContent = color;
            findVariant();
            updateAddToCartButton();
        }

        // Size Selection
        function selectSize(size, btn) {
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
                b.classList.add('border-gray-300');
            });
            btn.classList.remove('border-gray-300');
            btn.classList.add('border-gray-900', 'bg-gray-900', 'text-white');
            document.getElementById('selectedSize').value = size;
            document.getElementById('selectedSizeName').textContent = size;
            findVariant();
            updateAddToCartButton();
        }

        // Find matching variant
        function findVariant() {
            const selectedColor = document.getElementById('selectedColor').value;
            const selectedSize = document.getElementById('selectedSize').value;
            const hasColors = @json($availableColors->isNotEmpty());
            const hasSizes = @json($availableSizes->isNotEmpty());

            if (hasColors && hasSizes) {
                currentVariant = variants.find(v => v.color === selectedColor && v.size === selectedSize) || null;
            } else if (hasColors) {
                currentVariant = variants.find(v => v.color === selectedColor) || null;
            } else if (hasSizes) {
                currentVariant = variants.find(v => v.size === selectedSize) || null;
            } else {
                currentVariant = variants[0] || null;
            }

            if (currentVariant) {
                document.getElementById('selectedVariantId').value = currentVariant.id;
                document.getElementById('stockStatus').textContent = currentVariant.stock > 0 
                    ? `${currentVariant.stock} in stock` 
                    : 'Out of stock';
                document.getElementById('stockStatus').className = currentVariant.stock > 0 
                    ? 'text-sm text-green-600' 
                    : 'text-sm text-red-600';
            }
        }

        // Update Add to Cart Button
        function updateAddToCartButton() {
            const btn = document.getElementById('addToCartBtn');
            const hasColors = @json($availableColors->isNotEmpty());
            const hasSizes = @json($availableSizes->isNotEmpty());
            
            const colorSelected = !hasColors || document.getElementById('selectedColor').value;
            const sizeSelected = !hasSizes || document.getElementById('selectedSize').value;
            const inStock = currentVariant && currentVariant.stock > 0;

            if (colorSelected && sizeSelected && inStock) {
                btn.disabled = false;
                btn.textContent = 'Add to Cart';
            } else if (!hasColors && !hasSizes) {
                btn.disabled = false;
                btn.textContent = 'Add to Cart';
            } else if (!sizeSelected && hasSizes) {
                btn.disabled = true;
                btn.textContent = 'Select Size';
            } else {
                btn.disabled = true;
                btn.textContent = 'Add to Cart';
            }
        }

        // Quantity controls
        function decreaseQty() {
            const qty = document.getElementById('quantity');
            if (parseInt(qty.value) > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        }

        function increaseQty() {
            const qty = document.getElementById('quantity');
            const max = currentVariant ? currentVariant.stock : 999;
            if (parseInt(qty.value) < max) {
                qty.value = parseInt(qty.value) + 1;
            }
        }

        // Accordion Toggle
        function toggleAccordion() {
            const content = document.getElementById('accordionContent');
            const icon = document.getElementById('accordionIcon');
            
            if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = '0px';
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection
