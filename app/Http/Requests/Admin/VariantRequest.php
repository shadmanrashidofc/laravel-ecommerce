<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variantId = $this->route('variant')?->id;
        $productId = $this->route('product')?->id;

        return [
            'sku' => 'required|string|max:255|unique:product_variants,sku,'.$variantId,
            'size' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU already exists.',
            'stock.required' => 'Stock quantity is required.',
            'stock.integer' => 'Stock must be a whole number.',
            'stock.min' => 'Stock cannot be negative.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price cannot be negative.',
        ];
    }
}
