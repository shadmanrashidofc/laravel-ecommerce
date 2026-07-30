<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name' => 'required|string|max:255|unique:categories,name,'.$categoryId,
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$categoryId,
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique' => 'This category name already exists.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}
