<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Piutang;
use App\Models\RiwayatStok;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function sales(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_type' => ['nullable', 'in:tunai,utang'],
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
        ]);

        $query = Penjualan::query();

        if (! empty($validated['start_date'])) {
            $query->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        }

        if (! empty($validated['end_date'])) {
            $query->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        }

        if (! empty($validated['payment_type'])) {
            $query->where('metode', $validated['payment_type']);
        }

        if (! empty($validated['customer_id'])) {
            $query->where('pelanggan_id', (int) $validated['customer_id']);
        }

        $transactions = (clone $query)
            ->with('customer')
            ->orderByDesc('tanggal')
            ->get();

        $totalSales = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        $averagePerTransaction = $totalTransactions > 0
            ? (int) round($totalSales / $totalTransactions)
            : 0;
        $creditSales = $transactions
            ->where('payment_type', 'utang')
            ->sum('total');

        $chartData = $transactions
            ->groupBy(fn ($transaction) => $transaction->transaction_date->format('Y-m'))
            ->map(fn ($items) => $items->sum('total'))
            ->sortKeys();

        $chartLabels = $chartData->keys()->values();
        $chartTotals = $chartData->values();

        $topProductsQuery = DetailPenjualan::query()
            ->selectRaw('produks.id, produks.nama as product_name, SUM(detail_penjualans.qty) as qty_sold, SUM(detail_penjualans.subtotal) as total_sales')
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id');

        if (! empty($validated['start_date'])) {
            $topProductsQuery->where('penjualans.tanggal', '>=', $validated['start_date'].' 00:00:00');
        }

        if (! empty($validated['end_date'])) {
            $topProductsQuery->where('penjualans.tanggal', '<=', $validated['end_date'].' 23:59:59');
        }

        if (! empty($validated['payment_type'])) {
            $topProductsQuery->where('penjualans.metode', $validated['payment_type']);
        }

        if (! empty($validated['customer_id'])) {
            $topProductsQuery->where('penjualans.pelanggan_id', (int) $validated['customer_id']);
        }

        $topProducts = $topProductsQuery
            ->groupBy('produks.id', 'produks.nama')
            ->orderByDesc('qty_sold')
            ->limit(5)
            ->get();

        $customers = Pelanggan::query()
            ->orderBy('nama')
            ->get();

        return view('reports.sales', compact(
            'transactions',
            'totalSales',
            'totalTransactions',
            'averagePerTransaction',
            'creditSales',
            'chartLabels',
            'chartTotals',
            'topProducts',
            'customers'
        ));
    }

    public function receivables(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:unpaid,paid'],
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
        ]);

        $query = Piutang::query()
            ->with('transaction.customer');

        if (! empty($validated['start_date'])) {
            $query->where('created_at', '>=', $validated['start_date'].' 00:00:00');
        }

        if (! empty($validated['end_date'])) {
            $query->where('created_at', '<=', $validated['end_date'].' 23:59:59');
        }

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status'] === 'paid' ? 'lunas' : 'belum');
        }

        if (! empty($validated['customer_id'])) {
            $query->where('pelanggan_id', (int) $validated['customer_id']);
        }

        $receivables = $query
            ->orderByDesc('created_at')
            ->get();

        $totalUnpaid = $receivables
            ->where('status', 'unpaid')
            ->sum('amount');

        $totalPaid = $receivables
            ->where('status', 'paid')
            ->sum('amount');

        $overdueUnpaid = $receivables
            ->filter(fn (Piutang $receivable) => $receivable->status === 'unpaid'
                && $receivable->due_date
                && $receivable->due_date->lt(now()))
            ->sum('amount');

        $customers = Pelanggan::query()
            ->orderBy('nama')
            ->get();

        return view('reports.receivables', compact(
            'receivables',
            'totalUnpaid',
            'totalPaid',
            'overdueUnpaid',
            'customers'
        ));
    }

    public function stock(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:normal,low,out,inactive'],
            'kategori_id' => ['nullable', 'exists:kategoris,id'],
        ]);

        $productsQuery = Produk::query()
            ->with('kategori');

        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $productsQuery->where('nama', 'like', '%'.$search.'%');
        }

        if (! empty($validated['kategori_id'])) {
            $productsQuery->where('kategori_id', (int) $validated['kategori_id']);
        }

        if (! empty($validated['status'])) {
            if ($validated['status'] === 'inactive') {
                $productsQuery->where('aktif', false);
            } elseif ($validated['status'] === 'out') {
                $productsQuery->where('aktif', true)->where('stok', '<=', 0);
            } elseif ($validated['status'] === 'low') {
                $productsQuery->where('aktif', true)->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum');
            } elseif ($validated['status'] === 'normal') {
                $productsQuery->where('aktif', true)->whereColumn('stok', '>', 'stok_minimum');
            }
        }

        $products = $productsQuery
            ->orderBy('nama')
            ->get();

        $totalProducts = Produk::query()->count();
        $lowStockCount = Produk::query()
            ->where('aktif', true)
            ->where('stok', '>', 0)
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->count();
        $outStockCount = Produk::query()
            ->where('aktif', true)
            ->where('stok', '<=', 0)
            ->count();
        $stockValue = Produk::query()
            ->where('aktif', true)
            ->get()
            ->sum(fn (Produk $product) => ((int) $product->stok) * ((int) $product->harga_beli));

        $recentMutations = RiwayatStok::query()
            ->with(['produk', 'supplier', 'user'])
            ->orderByDesc('tanggal')
            ->limit(10)
            ->get();

        $kategoris = Kategori::query()
            ->orderBy('nama')
            ->get();

        return view('reports.stock', compact(
            'products',
            'totalProducts',
            'lowStockCount',
            'outStockCount',
            'stockValue',
            'recentMutations',
            'kategoris'
        ));
    }

    public function expenses(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'expense_category_id' => ['nullable', 'exists:expense_categories,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $expensesQuery = Pengeluaran::query()
            ->with(['category', 'user']);

        if (! empty($validated['start_date'])) {
            $expensesQuery->whereDate('tanggal', '>=', $validated['start_date']);
        }

        if (! empty($validated['end_date'])) {
            $expensesQuery->whereDate('tanggal', '<=', $validated['end_date']);
        }

        if (! empty($validated['expense_category_id'])) {
            $expensesQuery->where('expense_category_id', (int) $validated['expense_category_id']);
        }

        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $expensesQuery->where(function ($query) use ($search) {
                $query->where('catatan', 'like', '%'.$search.'%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        $expenses = $expensesQuery
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $totalExpense = (int) $expenses->sum('nominal');

        $salesQuery = Penjualan::query();

        if (! empty($validated['start_date'])) {
            $salesQuery->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        }

        if (! empty($validated['end_date'])) {
            $salesQuery->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        }

        $totalSales = (int) (clone $salesQuery)->sum('total');
        $totalTransactions = (int) (clone $salesQuery)->count();
        $netProfit = $totalSales - $totalExpense;

        $expenseByCategory = $expenses
            ->groupBy(fn (Pengeluaran $expense) => $expense->category->nama ?? 'Tanpa Kategori')
            ->map(fn ($items) => (int) $items->sum('nominal'))
            ->sortDesc();

        $categoryLabels = $expenseByCategory->keys()->values();
        $categoryTotals = $expenseByCategory->values();

        $expenseCategories = KategoriPengeluaran::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('reports.expenses', compact(
            'expenses',
            'totalExpense',
            'totalSales',
            'netProfit',
            'totalTransactions',
            'expenseByCategory',
            'categoryLabels',
            'categoryTotals',
            'expenseCategories'
        ));
    }
}
