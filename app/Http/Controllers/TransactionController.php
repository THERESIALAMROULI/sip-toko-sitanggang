<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Receivable;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('customer')
            ->orderByDesc('transaction_date')
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();

        return view('transactions.create', compact('products', 'customers'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $transaction = Transaction::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'transaction_date' => now(),
                'total' => 0,
                'payment_type' => $validated['payment_type'],
            ]);

            $total = 0;

            foreach ($validated['products'] as $item) {
                $quantity = (int) ($item['quantity'] ?? 0);

                if ($quantity <= 0) {
                    continue;
                }

                $product = Product::query()
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'products' => 'Stok produk '.$product->name.' tidak cukup.',
                    ]);
                }

                $product->decrement('stock', $quantity);

                $subtotal = $product->price * $quantity;
                $total += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $quantity,
                ]);
            }

            if ($total <= 0) {
                throw ValidationException::withMessages([
                    'products' => 'Minimal satu item harus memiliki qty lebih dari 0.',
                ]);
            }

            $transaction->update(['total' => $total]);

            if ($validated['payment_type'] === 'credit') {
                Receivable::create([
                    'transaction_id' => $transaction->id,
                    'amount' => $total,
                    'due_date' => now()->addDays(30)->toDateString(),
                    'status' => 'unpaid',
                ]);
            }
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan');
    }
}
