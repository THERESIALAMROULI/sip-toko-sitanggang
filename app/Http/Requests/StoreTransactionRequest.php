<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => [
                'nullable',
                'exists:customers,id',
                'required_if:payment_type,credit',
            ],
            'payment_type' => ['required', Rule::in(['cash', 'transfer', 'qris', 'credit'])],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id', 'distinct'],
            'products.*.quantity' => ['required', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $items = $this->input('products', []);
            $hasPositiveQty = collect($items)->contains(function (array $item) {
                return (int) ($item['quantity'] ?? 0) > 0;
            });

            if (! $hasPositiveQty) {
                $validator->errors()->add('products', 'Minimal satu item harus memiliki qty lebih dari 0.');
            }
        });
    }
}
