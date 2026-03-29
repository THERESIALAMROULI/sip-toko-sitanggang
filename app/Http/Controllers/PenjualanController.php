<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanTransaksiRequest;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Piutang;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'payment_type' => ['nullable', 'in:tunai,utang'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $transactionsQuery = Penjualan::query()
            ->with(['customer', 'receivable', 'user']);

        if (! empty($validated['payment_type'])) {
            $transactionsQuery->where('metode', $validated['payment_type']);
        }

        if (! empty($validated['start_date'])) {
            $transactionsQuery->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        }

        if (! empty($validated['end_date'])) {
            $transactionsQuery->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        }

        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $transactionsQuery->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('nama', 'like', '%'.$search.'%');
                });

                if (is_numeric($search)) {
                    $query->orWhere('id', (int) $search);
                }
            });
        }

        $transactions = $transactionsQuery
            ->orderByDesc('tanggal')
            ->get();

        $filters = [
            'q' => $validated['q'] ?? null,
            'payment_type' => $validated['payment_type'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ];

        return view('penjualan.index', compact('transactions', 'filters'));
    }

    public function create()
    {
        $products = Produk::query()
            ->orderBy('nama')
            ->get();
        $customers = Pelanggan::query()
            ->orderBy('nama')
            ->get();

        return view('penjualan.create', compact('products', 'customers'));
    }

    public function show(Penjualan $transaction)
    {
        $transaction->load(['customer', 'details.product', 'receivable', 'user']);

        return view('penjualan.show', compact('transaction'));
    }

    public function store(SimpanTransaksiRequest $request)
    {
        $validated = $request->validated();

        $transaction = DB::transaction(function () use ($validated) {
            $cashReceivedInput = isset($validated['cash_received']) && $validated['cash_received'] !== null
                ? (int) $validated['cash_received']
                : null;

            $transaction = Penjualan::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'transaction_date' => now(),
                'total' => 0,
                'cash_received' => null,
                'change_amount' => null,
                'payment_type' => $validated['payment_type'],
                'status' => ($validated['payment_type'] === 'utang') ? 'utang' : 'lunas',
            ]);

            $total = 0;

            foreach ($validated['products'] as $item) {
                $quantity = (int) ($item['quantity'] ?? 0);

                if ($quantity <= 0) {
                    continue;
                }

                $product = Produk::query()
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'products' => 'Stok produk '.$product->name.' tidak cukup.',
                    ]);
                }

                $product->decrement('stok', $quantity);

                $subtotal = $product->price * $quantity;
                $total += $subtotal;

                DetailPenjualan::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'nama_produk' => $product->name,
                ]);
            }

            if ($total <= 0) {
                throw ValidationException::withMessages([
                    'products' => 'Minimal satu item harus memiliki qty lebih dari 0.',
                ]);
            }

            if ($validated['payment_type'] === 'tunai') {
                if ($cashReceivedInput === null || $cashReceivedInput < $total) {
                    throw ValidationException::withMessages([
                        'cash_received' => 'Uang diterima harus lebih besar atau sama dengan total transaksi.',
                    ]);
                }

                $transaction->update([
                    'total' => $total,
                    'cash_received' => $cashReceivedInput,
                    'change_amount' => $cashReceivedInput - $total,
                ]);
            } else {
                $transaction->update([
                    'total' => $total,
                    'cash_received' => null,
                    'change_amount' => null,
                ]);
            }

            if ($validated['payment_type'] === 'utang') {
                Piutang::create([
                    'transaction_id' => $transaction->id,
                    'customer_id' => $validated['customer_id'],
                    'amount' => $total,
                    'due_date' => $validated['due_date'],
                    'status' => 'unpaid',
                ]);
            }

            return $transaction;
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil disimpan');
    }
}
