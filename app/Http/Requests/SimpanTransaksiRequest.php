<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
class SimpanTransaksiRequest extends FormRequest
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
                'exists:pelanggans,id',
            ],
            'payment_type' => ['required', Rule::in(['tunai', 'utang'])],
            'due_date' => ['nullable', 'date', 'after_or_equal:today', 'required_if:payment_type,utang'],
            'cash_received' => ['nullable', 'numeric', 'min:0', 'required_if:payment_type,tunai'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:produks,id', 'distinct'],
            'products.*.quantity' => ['nullable', 'integer', 'min:0'],
        ];
    }
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $paymentType = $this->input('payment_type');
            $customerId = $this->input('customer_id');

            if ($paymentType === 'utang' && blank($customerId)) {
                $validator->errors()->add('customer_id', 'Pilih pelanggan untuk transaksi utang.');
                $validator->errors()->add('payment_type', 'Transaksi umum hanya bisa menggunakan pembayaran tunai.');
            }

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
