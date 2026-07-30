<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|min:10',
            'email' => 'nullable|email|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'payment_method' => 'required|in:COD',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter your full name.',
            'phone.required' => 'Please enter your phone number.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'city.required' => 'Please enter your city.',
            'address.required' => 'Please enter your delivery address.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Invalid payment method selected.',
        ];
    }
}
