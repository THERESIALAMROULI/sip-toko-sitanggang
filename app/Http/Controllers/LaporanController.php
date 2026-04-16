<?php
namespace App\Http\Controllers;
use App\Models\Pelanggan;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use App\Models\Kategori;
use App\Models\Pemasok;
use App\Models\Produk;
use App\Models\Piutang;
use App\Models\RiwayatStok;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class LaporanController extends Controller
{
    // Laporan penjualan merangkum transaksi, total omzet, grafik bulanan, dan produk terlaris.
    public function sales(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', 'in:all,daily,weekly,monthly,custom'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_type' => ['nullable', 'in:tunai,utang'],
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
            'product_id' => ['nullable', 'exists:produks,id'],
            'category_id' => ['nullable', 'exists:kategoris,id'],
            'q' => ['nullable', 'string', 'max:100'],
            'sort_by' => ['nullable', 'in:date,total,quantity'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
            'sale_status' => ['nullable', 'in:lunas,utang'],
        ]);

        $selectedPeriod = $validated['period'] ?? ((isset($validated['start_date']) || isset($validated['end_date'])) ? 'custom' : 'all');

        $resolvedStartDate = null;
        $resolvedEndDate = null;
        if ($selectedPeriod === 'daily') {
            $resolvedStartDate = now()->startOfDay();
            $resolvedEndDate = now()->endOfDay();
        } elseif ($selectedPeriod === 'weekly') {
            $resolvedStartDate = now()->startOfWeek(Carbon::MONDAY)->startOfDay();
            $resolvedEndDate = now()->endOfWeek(Carbon::SUNDAY)->endOfDay();
        } elseif ($selectedPeriod === 'monthly') {
            $resolvedStartDate = now()->startOfMonth()->startOfDay();
            $resolvedEndDate = now()->endOfMonth()->endOfDay();
        } elseif ($selectedPeriod === 'custom') {
            $startDateInput = $validated['start_date'] ?? $validated['end_date'] ?? null;
            $endDateInput = $validated['end_date'] ?? $validated['start_date'] ?? null;

            if ($startDateInput && $endDateInput) {
                $resolvedStartDate = Carbon::parse($startDateInput)->startOfDay();
                $resolvedEndDate = Carbon::parse($endDateInput)->endOfDay();
            }
        }

        $displayStartDate = $resolvedStartDate?->toDateString() ?? ($validated['start_date'] ?? null);
        $displayEndDate = $resolvedEndDate?->toDateString() ?? ($validated['end_date'] ?? null);
        $search = trim((string) ($validated['q'] ?? ''));
        $sortBy = $validated['sort_by'] ?? 'date';
        $sortDirection = $validated['sort_direction'] ?? 'desc';
        $selectedSaleStatus = $validated['sale_status'] ?? 'lunas';

        $applyTransactionFilters = function ($query, ?Carbon $startDate, ?Carbon $endDate) use ($validated, $search) {
            if ($startDate) {
                $query->where('tanggal', '>=', $startDate->toDateTimeString());
            }
            if ($endDate) {
                $query->where('tanggal', '<=', $endDate->toDateTimeString());
            }
            if (! empty($validated['payment_type'])) {
                $query->where('metode', $validated['payment_type']);
            }
            if (! empty($validated['customer_id'])) {
                $query->where('pelanggan_id', (int) $validated['customer_id']);
            }
            if (! empty($validated['product_id'])) {
                $query->whereHas('details', function ($detailQuery) use ($validated) {
                    $detailQuery->where('produk_id', (int) $validated['product_id']);
                });
            }
            if (! empty($validated['category_id'])) {
                $query->whereHas('details.product', function ($productQuery) use ($validated) {
                    $productQuery->where('kategori_id', (int) $validated['category_id']);
                });
            }
            if ($search !== '') {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->whereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('nama', 'like', '%'.$search.'%');
                    })
                        ->orWhereHas('details', function ($detailQuery) use ($search) {
                            $detailQuery->where('nama_produk', 'like', '%'.$search.'%')
                                ->orWhereHas('product.kategori', function ($categoryQuery) use ($search) {
                                    $categoryQuery->where('nama', 'like', '%'.$search.'%');
                                });
                        })
                        ->orWhere('no_nota', 'like', '%'.$search.'%');

                    if (is_numeric($search)) {
                        $searchQuery->orWhere('id', (int) $search);
                    }
                });
            }
        };

        $applyDetailFilters = function ($query, ?Carbon $startDate, ?Carbon $endDate) use ($validated, $search) {
            if ($startDate) {
                $query->where('penjualans.tanggal', '>=', $startDate->toDateTimeString());
            }
            if ($endDate) {
                $query->where('penjualans.tanggal', '<=', $endDate->toDateTimeString());
            }
            if (! empty($validated['payment_type'])) {
                $query->where('penjualans.metode', $validated['payment_type']);
            }
            if (! empty($validated['customer_id'])) {
                $query->where('penjualans.pelanggan_id', (int) $validated['customer_id']);
            }
            if (! empty($validated['product_id'])) {
                $query->where('detail_penjualans.produk_id', (int) $validated['product_id']);
            }
            if (! empty($validated['category_id'])) {
                $query->where('produks.kategori_id', (int) $validated['category_id']);
            }
            if ($search !== '') {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('detail_penjualans.nama_produk', 'like', '%'.$search.'%')
                        ->orWhere('pelanggans.nama', 'like', '%'.$search.'%')
                        ->orWhere('kategoris.nama', 'like', '%'.$search.'%')
                        ->orWhere('penjualans.no_nota', 'like', '%'.$search.'%');

                    if (is_numeric($search)) {
                        $searchQuery->orWhere('penjualans.id', (int) $search);
                    }
                });
            }
        };

        $transactionsQuery = Penjualan::query()
            ->with(['customer', 'details.product.kategori']);
        $applyTransactionFilters($transactionsQuery, $resolvedStartDate, $resolvedEndDate);

        $transactions = (clone $transactionsQuery)
            ->orderByDesc('tanggal')
            ->get();

        $salesDetailBaseQuery = DetailPenjualan::query()
            ->select('detail_penjualans.*')
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
            ->leftJoin('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
            ->with(['transaction.customer', 'product.kategori']);
        $applyDetailFilters($salesDetailBaseQuery, $resolvedStartDate, $resolvedEndDate);

        $sortColumn = match ($sortBy) {
            'total' => 'detail_penjualans.subtotal',
            'quantity' => 'detail_penjualans.qty',
            default => 'penjualans.tanggal',
        };

        $filteredSalesDetailsQuery = clone $salesDetailBaseQuery;
        $filteredSalesDetailsQuery->where('penjualans.status', $selectedSaleStatus);

        $salesDetails = (clone $filteredSalesDetailsQuery)
            ->orderBy($sortColumn, $sortDirection)
            ->orderByDesc('penjualans.id')
            ->paginate(10)
            ->withQueryString();
        $salesDetails->getCollection()->transform(function (DetailPenjualan $detail) {
            $detail->status_label = match ($detail->transaction->status ?? null) {
                'lunas' => 'Lunas',
                'utang' => 'Utang',
                default => 'Diproses',
            };
            $detail->status_badge = match ($detail->transaction->status ?? null) {
                'lunas' => 'badge-green',
                'utang' => 'badge-amber',
                default => 'badge-gray',
            };

            return $detail;
        });

        $exportSalesDetails = (clone $filteredSalesDetailsQuery)
            ->orderByDesc('penjualans.tanggal')
            ->orderByDesc('penjualans.id')
            ->get();
        $detailGroups = collect([
            [
                'key' => 'lunas',
                'label' => 'Lunas',
                'badge' => 'badge-green',
                'count' => (clone $salesDetailBaseQuery)
                    ->where('penjualans.status', 'lunas')
                    ->count(),
            ],
            [
                'key' => 'utang',
                'label' => 'Utang',
                'badge' => 'badge-amber',
                'count' => (clone $salesDetailBaseQuery)
                    ->where('penjualans.status', 'utang')
                    ->count(),
            ],
        ]);

        $totalSales = (int) $transactions->sum('total');
        $totalTransactions = (int) $transactions->count();
        $totalItemsSold = (int) $transactions
            ->flatMap(fn (Penjualan $transaction) => $transaction->details)
            ->sum('quantity');
        $averagePerTransaction = $totalTransactions > 0
            ? (int) round($totalSales / $totalTransactions)
            : 0;
        $creditSales = (int) $transactions
            ->where('payment_type', 'utang')
            ->sum('total');

        $comparisonStartDate = null;
        $comparisonEndDate = null;
        $comparisonTransactions = collect();
        $comparisonTotalSales = null;
        if ($resolvedStartDate && $resolvedEndDate) {
            $daysInRange = $resolvedStartDate->copy()->startOfDay()->diffInDays($resolvedEndDate->copy()->startOfDay()) + 1;
            $comparisonStartDate = $resolvedStartDate->copy()->subDays($daysInRange)->startOfDay();
            $comparisonEndDate = $resolvedStartDate->copy()->subDay()->endOfDay();

            $comparisonTransactionsQuery = Penjualan::query()
                ->with('details');
            $applyTransactionFilters($comparisonTransactionsQuery, $comparisonStartDate, $comparisonEndDate);
            $comparisonTransactions = $comparisonTransactionsQuery
                ->orderByDesc('tanggal')
                ->get();
            $comparisonTotalSales = (int) $comparisonTransactions->sum('total');
        }

        $profitDetails = (clone $salesDetailBaseQuery)->get();
        $totalProfit = (int) $profitDetails->sum(function (DetailPenjualan $detail) {
            $storedPurchasePrice = (int) ($detail->harga_beli ?? 0);
            $purchasePrice = $storedPurchasePrice > 0
                ? $storedPurchasePrice
                : (int) ($detail->product->harga_beli ?? 0);

            return (((int) $detail->price) - $purchasePrice) * ((int) $detail->quantity);
        });
        $salesDifferenceAmount = $comparisonTotalSales !== null
            ? $totalSales - $comparisonTotalSales
            : null;

        $topProductsQuery = DetailPenjualan::query()
            ->selectRaw('
                produks.id,
                produks.nama as product_name,
                kategoris.nama as category_name,
                SUM(detail_penjualans.qty) as qty_sold,
                SUM(detail_penjualans.subtotal) as total_sales
            ')
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
            ->leftJoin('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id');
        $applyDetailFilters($topProductsQuery, $resolvedStartDate, $resolvedEndDate);

        $topProducts = $topProductsQuery
            ->groupBy('produks.id', 'produks.nama', 'kategoris.nama')
            ->orderByDesc('qty_sold')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        $chartLabels = collect();
        $chartTotals = collect();
        $comparisonChartTotals = collect();
        $chartGranularityLabel = '12 Bulan';
        $chartEndMonth = ($resolvedEndDate ?? now())->copy()->endOfMonth();
        $chartStartMonth = $chartEndMonth->copy()->subMonths(11)->startOfMonth();
        $chartComparisonStartMonth = $chartStartMonth->copy()->subYear();
        $chartComparisonEndMonth = $chartEndMonth->copy()->subYear()->endOfMonth();

        $chartTransactionsQuery = Penjualan::query()->with('details');
        $applyTransactionFilters($chartTransactionsQuery, $chartStartMonth, $chartEndMonth);
        $chartTransactions = $chartTransactionsQuery
            ->orderByDesc('tanggal')
            ->get();

        $chartComparisonTransactionsQuery = Penjualan::query()->with('details');
        $applyTransactionFilters($chartComparisonTransactionsQuery, $chartComparisonStartMonth, $chartComparisonEndMonth);
        $chartComparisonTransactions = $chartComparisonTransactionsQuery
            ->orderByDesc('tanggal')
            ->get();

        for ($offset = 0; $offset < 12; $offset++) {
            $currentMonth = $chartStartMonth->copy()->addMonths($offset);
            $comparisonMonth = $chartComparisonStartMonth->copy()->addMonths($offset);

            $chartLabels->push($currentMonth->translatedFormat('M Y'));
            $chartTotals->push((int) $chartTransactions
                ->filter(fn (Penjualan $transaction) => optional($transaction->transaction_date)?->format('Y-m') === $currentMonth->format('Y-m'))
                ->sum('total'));
            $comparisonChartTotals->push((int) $chartComparisonTransactions
                ->filter(fn (Penjualan $transaction) => optional($transaction->transaction_date)?->format('Y-m') === $comparisonMonth->format('Y-m'))
                ->sum('total'));
        }

        $chartCurrentPeriodLabel = $chartStartMonth->translatedFormat('M Y').' - '.$chartEndMonth->translatedFormat('M Y');
        $chartComparisonPeriodLabel = $chartComparisonStartMonth->translatedFormat('M Y').' - '.$chartComparisonEndMonth->translatedFormat('M Y');

        $currentPeriodLabel = $resolvedStartDate && $resolvedEndDate
            ? $resolvedStartDate->format('d M Y').' - '.$resolvedEndDate->format('d M Y')
            : 'Semua Periode';
        $comparisonPeriodLabel = $comparisonStartDate && $comparisonEndDate
            ? $comparisonStartDate->format('d M Y').' - '.$comparisonEndDate->format('d M Y')
            : null;

        $customers = Pelanggan::query()
            ->orderBy('nama')
            ->get();
        $products = Produk::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();
        $categories = Kategori::query()
            ->orderBy('nama')
            ->get();

        return view('laporan.sales', compact(
            'transactions',
            'salesDetails',
            'exportSalesDetails',
            'totalSales',
            'totalTransactions',
            'totalItemsSold',
            'averagePerTransaction',
            'creditSales',
            'chartLabels',
            'chartTotals',
            'comparisonChartTotals',
            'chartGranularityLabel',
            'topProducts',
            'customers',
            'products',
            'categories',
            'selectedPeriod',
            'selectedSaleStatus',
            'displayStartDate',
            'displayEndDate',
            'sortBy',
            'sortDirection',
            'search',
            'detailGroups',
            'chartCurrentPeriodLabel',
            'chartComparisonPeriodLabel',
            'comparisonTotalSales',
            'salesDifferenceAmount',
            'totalProfit',
            'currentPeriodLabel',
            'comparisonPeriodLabel'
        ));
    }
    // Laporan piutang memusatkan perhatian pada tagihan belum lunas, yang sudah dibayar, dan yang terlambat.
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
        $currentUnpaid = max((int) $totalUnpaid - (int) $overdueUnpaid, 0);
        $receivableStatusChartLabels = ['Belum Lunas', 'Sudah Lunas', 'Lewat Jatuh Tempo'];
        $receivableStatusChartValues = [
            $currentUnpaid,
            (int) $totalPaid,
            (int) $overdueUnpaid,
        ];
        $receivableStatusTotal = max(array_sum($receivableStatusChartValues), 1);
        $receivableUnpaidPercent = (int) round((((int) $currentUnpaid + (int) $overdueUnpaid) / $receivableStatusTotal) * 100);
        $receivableOverduePercent = (int) round(((int) $overdueUnpaid / $receivableStatusTotal) * 100);
        $receivableHealthLabel = $receivableOverduePercent >= 25 ? 'Perlu perhatian' : 'Masih aman';
        $customers = Pelanggan::query()
            ->orderBy('nama')
            ->get();
        return view('laporan.receivables', compact(
            'receivables',
            'totalUnpaid',
            'totalPaid',
            'overdueUnpaid',
            'customers',
            'currentUnpaid',
            'receivableStatusChartLabels',
            'receivableStatusChartValues',
            'receivableUnpaidPercent',
            'receivableOverduePercent',
            'receivableHealthLabel'
        ));
    }
    // Laporan stok dipakai untuk melihat kondisi persediaan, nilai stok, dan mutasi barang terbaru.
    public function stock(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:all,normal,menipis,habis,nonaktif,low,out,inactive'],
            'kategori_id' => ['nullable', 'exists:kategoris,id'],
            'mutation_product_id' => ['nullable', 'exists:produks,id'],
            'mutation_supplier_id' => ['nullable', 'exists:suppliers,id'],
            'mutation_start_date' => ['nullable', 'date'],
            'mutation_end_date' => ['nullable', 'date', 'after_or_equal:mutation_start_date'],
        ]);

        $rawStatus = $validated['status'] ?? 'all';

        $selectedStatus = match ($rawStatus) {
            'low' => 'menipis',
            'out' => 'habis',
            'inactive' => 'nonaktif',
            'all' => 'all',
            default => $rawStatus,
        };

        $filteredProductsQuery = Produk::query()
            ->with('kategori');
        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $filteredProductsQuery->where('nama', 'like', '%'.$search.'%');
        }
        if (! empty($validated['kategori_id'])) {
            $filteredProductsQuery->where('kategori_id', (int) $validated['kategori_id']);
        }

        $productsQuery = clone $filteredProductsQuery;
        if ($selectedStatus === 'nonaktif') {
            $productsQuery->where('aktif', false);
        } elseif ($selectedStatus === 'habis') {
            $productsQuery->where('aktif', true)->where('stok', '<=', 0);
        } elseif ($selectedStatus === 'menipis') {
            $productsQuery->where('aktif', true)->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum');
        } elseif ($selectedStatus === 'normal') {
            $productsQuery->where('aktif', true)->whereColumn('stok', '>', 'stok_minimum');
        }
        $exportProducts = (clone $productsQuery)
            ->orderBy('nama')
            ->get();
        $products = (clone $productsQuery)
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString()
            ->through(function (Produk $product) {
                if ((int) $product->stok <= 0) {
                    $product->stock_status_label = 'Habis';
                    $product->stock_badge = 'badge-red';
                } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                    $product->stock_status_label = 'Menipis';
                    $product->stock_badge = 'badge-amber';
                } else {
                    $product->stock_status_label = 'Normal';
                    $product->stock_badge = 'badge-green';
                }

                $product->stock_value = ((int) $product->stok) * ((int) $product->harga_beli);

                return $product;
            });
        $productGroups = collect([
            [
                'key' => 'all',
                'label' => 'Semua',
                'badge' => 'badge-blue',
                'count' => (clone $filteredProductsQuery)->count(),
            ],
            [
                'key' => 'habis',
                'label' => 'Habis',
                'badge' => 'badge-red',
                'count' => (clone $filteredProductsQuery)
                    ->where('aktif', true)
                    ->where('stok', '<=', 0)
                    ->count(),
            ],
            [
                'key' => 'menipis',
                'label' => 'Menipis',
                'badge' => 'badge-amber',
                'count' => (clone $filteredProductsQuery)
                    ->where('aktif', true)
                    ->where('stok', '>', 0)
                    ->whereColumn('stok', '<=', 'stok_minimum')
                    ->count(),
            ],
            [
                'key' => 'normal',
                'label' => 'Normal',
                'badge' => 'badge-green',
                'count' => (clone $filteredProductsQuery)
                    ->where('aktif', true)
                    ->whereColumn('stok', '>', 'stok_minimum')
                    ->count(),
            ],
            [
                'key' => 'nonaktif',
                'label' => 'Nonaktif',
                'badge' => 'badge-gray',
                'count' => (clone $filteredProductsQuery)
                    ->where('aktif', false)
                    ->count(),
            ],
        ]);
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
        $normalStockCount = Produk::query()
            ->where('aktif', true)
            ->whereColumn('stok', '>', 'stok_minimum')
            ->count();
        $stockValue = Produk::query()
            ->where('aktif', true)
            ->get()
            ->sum(fn (Produk $product) => ((int) $product->stok) * ((int) $product->harga_beli));
        $stockMutationsQuery = RiwayatStok::query()
            ->with(['produk', 'supplier', 'user'])
            ->orderByDesc('tanggal');
        if (! empty($validated['mutation_product_id'])) {
            $stockMutationsQuery->where('produk_id', (int) $validated['mutation_product_id']);
        }
        if (! empty($validated['mutation_supplier_id'])) {
            $stockMutationsQuery->where('supplier_id', (int) $validated['mutation_supplier_id']);
        }
        if (! empty($validated['mutation_start_date'])) {
            $stockMutationsQuery->whereDate('tanggal', '>=', $validated['mutation_start_date']);
        }
        if (! empty($validated['mutation_end_date'])) {
            $stockMutationsQuery->whereDate('tanggal', '<=', $validated['mutation_end_date']);
        }

        $incomingMutations = (clone $stockMutationsQuery)
            ->where('jumlah', '>', 0)
            ->paginate(5, ['*'], 'incoming_mutation_page')
            ->withQueryString();
        $salesOutgoingMutations = (clone $stockMutationsQuery)
            ->where('jumlah', '<', 0)
            ->where('keterangan', 'like', 'Penjualan #%')
            ->paginate(5, ['*'], 'sales_mutation_page')
            ->withQueryString();
        $supplierOutgoingMutations = (clone $stockMutationsQuery)
            ->where('jumlah', '<', 0)
            ->where(function ($query) {
                $query->whereNull('keterangan')
                    ->orWhere('keterangan', 'not like', 'Penjualan #%');
            })
            ->paginate(5, ['*'], 'supplier_outgoing_mutation_page')
            ->withQueryString();
        $exportIncomingMutations = (clone $stockMutationsQuery)
            ->where('jumlah', '>', 0)
            ->get();
        $exportSalesOutgoingMutations = (clone $stockMutationsQuery)
            ->where('jumlah', '<', 0)
            ->where('keterangan', 'like', 'Penjualan #%')
            ->get();
        $exportSupplierOutgoingMutations = (clone $stockMutationsQuery)
            ->where('jumlah', '<', 0)
            ->where(function ($query) {
                $query->whereNull('keterangan')
                    ->orWhere('keterangan', 'not like', 'Penjualan #%');
            })
            ->get();
        $stockStatusChartLabels = ['Normal', 'Menipis', 'Habis'];
        $stockStatusChartValues = [
            $normalStockCount,
            $lowStockCount,
            $outStockCount,
        ];
        $kategoris = Kategori::query()
            ->orderBy('nama')
            ->get();
        $mutationProducts = Produk::query()
            ->orderBy('nama')
            ->get();
        $mutationSuppliers = Pemasok::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();
        return view('laporan.stock', compact(
            'products',
            'exportProducts',
            'productGroups',
            'selectedStatus',
            'totalProducts',
            'lowStockCount',
            'outStockCount',
            'normalStockCount',
            'stockValue',
            'incomingMutations',
            'salesOutgoingMutations',
            'supplierOutgoingMutations',
            'exportIncomingMutations',
            'exportSalesOutgoingMutations',
            'exportSupplierOutgoingMutations',
            'stockStatusChartLabels',
            'stockStatusChartValues',
            'kategoris',
            'mutationProducts',
            'mutationSuppliers'
        ));
    }
    // Laporan pengeluaran membandingkan beban operasional dengan penjualan agar laba bersih bisa dipantau.
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
        // Pengelompokan per kategori membantu owner melihat pos pengeluaran yang paling besar.
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
        return view('laporan.expenses', compact(
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
