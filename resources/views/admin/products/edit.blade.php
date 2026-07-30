@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-700">
            ← Back to Products
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Product</h2>

            <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" 
                                id="category_id"
                                class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $product->name) }}"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug
                        </label>
                        <input type="text" 
                               name="slug" 
                               id="slug"
                               value="{{ old('slug', $product->slug) }}"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror"
                               placeholder="Auto-generated if empty">
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="price" 
                               id="price"
                               value="{{ old('price', $product->price) }}"
                               step="0.01"
                               min="0"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                               required>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Price -->
                    <div>
                        <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Discount Price
                        </label>
                        <input type="number" 
                               name="discount_price" 
                               id="discount_price"
                               value="{{ old('discount_price', $product->discount_price) }}"
                               step="0.01"
                               min="0"
                               class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('discount_price') border-red-500 @enderror"
                               placeholder="Optional">
                        @error('discount_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thumbnail -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Thumbnail Image
                    </label>
                    
                    @if($product->thumbnail)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-32 h-32 object-cover rounded-lg">
                            <p class="text-gray-500 text-xs mt-1">Current image</p>
                        </div>
                    @endif
                    
                    <input type="file" 
                           name="thumbnail" 
                           id="thumbnail"
                           accept="image/*"
                           class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('thumbnail') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-1">Max size: 2MB. Formats: jpeg, png, jpg, gif, webp. Leave empty to keep current image.</p>
                    @error('thumbnail')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gallery -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Product Gallery
                    </label>

                    <!-- Upload Form -->
                    <form method="POST" action="{{ route('admin.products.gallery', $product->id) }}" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <input type="file" 
                                       name="images[]" 
                                       multiple
                                       accept="image/jpeg,image/png,image/jpg,image/webp"
                                       class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('images.*') border-red-500 @enderror">
                                <p class="text-gray-500 text-xs mt-1">Max size: 2MB. Formats: jpg, jpeg, png, webp</p>
                                @error('images.*')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Upload
                            </button>
                        </div>
                    </form>

                    <!-- Gallery Grid -->
                    @if($product->images->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="Gallery image"
                                         class="w-full h-32 object-cover rounded-lg">
                                    <form method="POST" action="{{ route('admin.products.image.destroy', $image->id) }}" class="absolute top-2 right-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700"
                                                onclick="return confirm('Delete this image?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mt-2">No gallery images uploaded yet.</p>
                    @endif
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
