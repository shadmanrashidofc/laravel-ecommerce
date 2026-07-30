<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,'.$productId,
            'slug' => 'nullable|string|max:255|unique:products,slug,'.$productId,
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'status' => 'required|in:active,inactive,draft',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'name.required' => 'Product name is required.',
            'name.unique' => 'This product name already exists.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'discount_price.lt' => 'Discount price must be less than price.',
            'thumbnail.image' => 'Thumbnail must be an image file.',
            'thumbnail.mimes' => 'Thumbnail must be jpeg, png, jpg, gif, or webp.',
            'thumbnail.max' => 'Thumbnail must be less than 2MB.',
            'status.required' => 'Please select a status.',
        ];
    }
}
