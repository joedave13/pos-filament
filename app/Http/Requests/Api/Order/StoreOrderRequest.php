<?php

namespace App\Http\Requests\Api\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'total_price' => ['required', 'integer'],
            'note' => ['nullable'],
            'payment_method_id' => ['required', Rule::exists('payment_methods', 'id')],
            'paid_amount' => ['nullable', 'integer', 'min:1'],
            'order_items' => ['required', 'array'],
            'order_items.*.product_id' => ['required', Rule::exists('products', 'id')],
            'order_items.*.quantity' => ['required', 'integer', 'min:1'],
            'order_items.*.price' => ['required', 'integer', 'min:0'],
        ];
    }
}
