@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-700">
            ← Back to Categories
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Category</h2>

            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $category->name) }}"
                           class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug
                    </label>
                    <input type="text" 
                           name="slug" 
                           id="slug"
                           value="{{ old('slug', $category->slug) }}"
                           class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror"
                           placeholder="Auto-generated if empty">
                    <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full border-gray-300 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                            required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
