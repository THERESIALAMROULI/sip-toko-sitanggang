<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Piutang;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DasborController extends Controller
{
    // Dashboard difokuskan untuk membantu admin dan owner membaca pola ramai, prioritas stok, dan risiko toko.
    public function index(Request $request)
    {
        $validated = $request->validate([
            'scope' => ['nullable', 'in:day,week,month'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $role = auth()->user()->role ?? 'kasir';
        $isAdmin = $role === 'admin';
        $isOwner = $role === 'owner';
        $needsInventoryAnalytics = in_array($role, ['admin', 'owner'], true);
        $dashboardScope = $validated['scope'] ?? 'day';
        $now = now();
        $today = $now->toDateString();
        $inputStartDate = $validated['start_date'] ?? null;
        $inputEndDate = $validated['end_date'] ?? null;
        $requestedStartDate = ! empty($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : null;
        $requestedEndDate = ! empty($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : null;

        $todaySales = (int) Penjualan::query()
            ->whereDate('tanggal', $today)
            ->sum('total');

        $todayTransactionCount = (int) Penjualan::query()
            ->whereDate('tanggal', $today)
            ->count();

        $totalProducts = (int) Produk::query()
            ->where('aktif', true)
            ->count();

        $totalCustomers = (int) Pelanggan::query()->count();

        $outstandingReceivables = (int) Piutang::query()
            ->where('status', 'belum')
            ->sum('jumlah');

        $latestTransactions = Penjualan::query()
            ->with('customer')
            ->latest('tanggal')
            ->limit(6)
            ->get();

        $lowStockProducts = Produk::query()
            ->where('aktif', true)
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->orderBy('nama')
            ->limit(6)
            ->get();

        $overdueBaseQuery = Piutang::query()
            ->where('status', 'belum')
            ->whereDate('jatuh_tempo', '<', $today);

        $overdueReceivablesCount = (int) (clone $overdueBaseQuery)->count();
        $overdueReceivablesAmount = (int) (clone $overdueBaseQuery)->sum('jumlah');
        $overdueReceivables = (clone $overdueBaseQuery)
            ->with('transaction.customer')
            ->orderByRaw('COALESCE(jatuh_tempo, created_at) asc')
            ->limit(6)
            ->get();

        $decisionWindowLabel = null;
        $outOfStockCount = 0;
        $lowStockCount = 0;
        $overstockCandidateCount = 0;
        $overstockCandidates = collect();
        $slowStockValue = 0;
        $todayMutationCount = 0;
        $todayStockIn = 0;
        $todayStockOut = 0;
        $recentMutations = collect();
        $supplierLeaders = collect();
        $categoryStockAlerts = collect();
        $adminHighlights = collect();
        $scopeOptionLabel = 'Harian';
        $scopeRangeLabel = '';
        $dashboardStartDate = null;
        $dashboardEndDate = null;
        $periodSalesTotal = 0;
        $periodExpenseTotal = 0;
        $periodTransactionCount = 0;
        $periodStockInTotal = 0;
        $periodStockOutTotal = 0;
        $adminSalesChartLabels = [];
        $adminSalesChartValues = [];
        $adminMutationChartLabels = [];
        $adminMutationInValues = [];
        $adminMutationOutValues = [];
        $stockHealthChartLabels = [];
        $stockHealthChartValues = [];
        $restockChartLabels = [];
        $restockChartValues = [];
        $categorySalesChartLabels = [];
        $categorySalesChartValues = [];
        $overstockChartLabels = [];
        $overstockChartValues = [];
        $peakMonth = null;
        $peakMonthLabel = null;
        $peakMonthTopProducts = collect();
        $peakMonthLeader = null;
        $peakDay = null;
        $peakDayLabel = null;
        $peakDayLeader = null;
        $salesTrendLabels = [];
        $salesTrendValues = [];
        $categoryLeaders = collect();
        $categoryChartLabels = [];
        $categoryChartValues = [];
        $restockPriorityCount = 0;
        $restockPriorities = collect();
        $slowMoverCount = 0;
        $slowMovingProducts = collect();
        $decisionHighlights = collect();
        $adminCategoryCheckProducts = collect();

        if ($needsInventoryAnalytics) {
            $decisionWindowStart = $now->copy()->subDays(29)->startOfDay();
            $decisionWindowEnd = $now->copy()->endOfDay();
            $decisionWindowLabel = $decisionWindowStart->translatedFormat('d M Y').' - '.$decisionWindowEnd->translatedFormat('d M Y');

            $salesLast30Sub = DetailPenjualan::query()
                ->selectRaw('detail_penjualans.produk_id, SUM(detail_penjualans.qty) as qty_sold_30')
                ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
                ->whereBetween('penjualans.tanggal', [$decisionWindowStart, $decisionWindowEnd])
                ->groupBy('detail_penjualans.produk_id');

            $lastSaleSub = DetailPenjualan::query()
                ->selectRaw('detail_penjualans.produk_id, MAX(penjualans.tanggal) as last_sold_at')
                ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
                ->groupBy('detail_penjualans.produk_id');

            $productDecisionBase = Produk::query()
                ->leftJoinSub($salesLast30Sub, 'sales_30', function ($join) {
                    $join->on('produks.id', '=', 'sales_30.produk_id');
                })
                ->leftJoinSub($lastSaleSub, 'last_sales', function ($join) {
                    $join->on('produks.id', '=', 'last_sales.produk_id');
                })
                ->leftJoin('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
                ->where('produks.aktif', true)
                ->select(
                    'produks.id',
                    'produks.nama',
                    'produks.stok',
                    'produks.stok_minimum',
                    'produks.harga_beli',
                    DB::raw("kategoris.nama as category_name"),
                    DB::raw('COALESCE(sales_30.qty_sold_30, 0) as qty_sold_30'),
                    DB::raw('last_sales.last_sold_at'),
                    DB::raw('COALESCE(produks.stok * produks.harga_beli, 0) as inventory_value'),
                    DB::raw('CASE WHEN COALESCE(sales_30.qty_sold_30, 0) > 0 THEN ROUND(COALESCE(sales_30.qty_sold_30, 0) / 30, 2) ELSE 0 END as avg_daily_sales'),
                    DB::raw('CASE WHEN COALESCE(sales_30.qty_sold_30, 0) > 0 THEN ROUND(produks.stok / NULLIF(COALESCE(sales_30.qty_sold_30, 0) / 30, 0), 1) ELSE NULL END as stock_cover_days')
                );

            $restockCriteria = function ($query) {
                $query->whereRaw('COALESCE(sales_30.qty_sold_30, 0) > 0')
                    ->where(function ($inner) {
                        $inner->whereColumn('produks.stok', '<=', 'produks.stok_minimum')
                            ->orWhereRaw('produks.stok / NULLIF(COALESCE(sales_30.qty_sold_30, 0) / 30, 0) <= 14');
                    });
            };

            $overstockCriteria = function ($query) {
                $query->where('produks.stok', '>', 0)
                    ->where(function ($inner) {
                        $inner->whereRaw('COALESCE(sales_30.qty_sold_30, 0) = 0 AND produks.stok > produks.stok_minimum * 2')
                            ->orWhereRaw('COALESCE(sales_30.qty_sold_30, 0) > 0 AND produks.stok / NULLIF(COALESCE(sales_30.qty_sold_30, 0) / 30, 0) >= 45');
                    });
            };

            $restockPriorityCount = (int) (clone $productDecisionBase)
                ->where($restockCriteria)
                ->count();

            $restockPriorities = (clone $productDecisionBase)
                ->where($restockCriteria)
                ->orderByRaw('CASE WHEN produks.stok <= produks.stok_minimum THEN 0 ELSE 1 END')
                ->orderByRaw('CASE WHEN COALESCE(sales_30.qty_sold_30, 0) > 0 THEN produks.stok / NULLIF(COALESCE(sales_30.qty_sold_30, 0) / 30, 0) ELSE 999999 END asc')
                ->orderByDesc('qty_sold_30')
                ->limit(8)
                ->get()
                ->map(function ($product) {
                    $avgDailySales = (float) $product->avg_daily_sales;
                    $targetStock = max((int) $product->stok_minimum * 2, (int) ceil($avgDailySales * 14));
                    $product->suggested_restock = max(0, $targetStock - (int) $product->stok);
                    $product->stock_cover_days = $product->stock_cover_days !== null ? (float) $product->stock_cover_days : null;

                    return $product;
                });

            $slowMoverCount = (int) (clone $productDecisionBase)
                ->where('produks.stok', '>', 0)
                ->whereRaw('COALESCE(sales_30.qty_sold_30, 0) = 0')
                ->count();

            $slowMovingProducts = (clone $productDecisionBase)
                ->where('produks.stok', '>', 0)
                ->whereRaw('COALESCE(sales_30.qty_sold_30, 0) = 0')
                ->orderByDesc('produks.stok')
                ->orderBy('produks.nama')
                ->get()
                ->map(function ($product) use ($now) {
                    $product->days_since_last_sale = $product->last_sold_at
                        ? Carbon::parse($product->last_sold_at)->diffInDays($now)
                        : null;

                    return $product;
                });

            $overstockCandidateCount = (int) (clone $productDecisionBase)
                ->where($overstockCriteria)
                ->count();

            $overstockCandidates = (clone $productDecisionBase)
                ->where($overstockCriteria)
                ->orderByDesc('inventory_value')
                ->orderByDesc('produks.stok')
                ->limit(8)
                ->get()
                ->map(function ($product) use ($now) {
                    $product->stock_cover_days = $product->stock_cover_days !== null ? (float) $product->stock_cover_days : null;
                    $product->days_since_last_sale = $product->last_sold_at
                        ? Carbon::parse($product->last_sold_at)->diffInDays($now)
                        : null;

                    return $product;
                });

            $slowStockValue = (int) round((clone $productDecisionBase)
                ->where($overstockCriteria)
                ->sum(DB::raw('COALESCE(produks.stok * produks.harga_beli, 0)')));

            $outOfStockCount = (int) Produk::query()
                ->where('aktif', true)
                ->where('stok', '<=', 0)
                ->count();

            $lowStockCount = (int) Produk::query()
                ->where('aktif', true)
                ->where('stok', '>', 0)
                ->whereColumn('stok', '<=', 'stok_minimum')
                ->count();

            $todayMutationBase = RiwayatStok::query()
                ->whereDate('tanggal', $today);

            $todayMutationCount = (int) (clone $todayMutationBase)->count();
            $todayStockIn = (int) (clone $todayMutationBase)
                ->selectRaw('COALESCE(SUM(CASE WHEN jumlah > 0 THEN jumlah ELSE 0 END), 0) as total')
                ->value('total');
            $todayStockOut = (int) (clone $todayMutationBase)
                ->selectRaw('COALESCE(SUM(CASE WHEN jumlah < 0 THEN ABS(jumlah) ELSE 0 END), 0) as total')
                ->value('total');

            $recentMutations = RiwayatStok::query()
                ->with(['produk', 'supplier', 'user'])
                ->orderByDesc('tanggal')
                ->limit(8)
                ->get();

            $supplierLeaders = RiwayatStok::query()
                ->selectRaw('suppliers.id, suppliers.nama as supplier_name, COUNT(*) as mutation_count, SUM(CASE WHEN stok_histories.jumlah > 0 THEN stok_histories.jumlah ELSE 0 END) as incoming_qty')
                ->join('suppliers', 'suppliers.id', '=', 'stok_histories.supplier_id')
                ->whereBetween('stok_histories.tanggal', [$decisionWindowStart, $decisionWindowEnd])
                ->groupBy('suppliers.id', 'suppliers.nama')
                ->orderByDesc('incoming_qty')
                ->orderByDesc('mutation_count')
                ->limit(5)
                ->get();

            $categoryStockAlerts = Produk::query()
                ->join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
                ->where('produks.aktif', true)
                ->selectRaw('kategoris.id, kategoris.nama as category_name, COUNT(*) as total_products, SUM(CASE WHEN produks.stok <= 0 THEN 1 ELSE 0 END) as out_count, SUM(CASE WHEN produks.stok > 0 AND produks.stok <= produks.stok_minimum THEN 1 ELSE 0 END) as low_count')
                ->groupBy('kategoris.id', 'kategoris.nama')
                ->havingRaw('(SUM(CASE WHEN produks.stok <= 0 THEN 1 ELSE 0 END) + SUM(CASE WHEN produks.stok > 0 AND produks.stok <= produks.stok_minimum THEN 1 ELSE 0 END)) > 0')
                ->orderByDesc('out_count')
                ->orderByDesc('low_count')
                ->limit(6)
                ->get();

            if ($isAdmin) {
                $adminHighlights->push([
                    'title' => 'Prioritas restok',
                    'text' => number_format($restockPriorityCount, 0, ',', '.').' produk perlu restok cepat karena stok menipis atau daya tahannya kurang dari 14 hari.',
                ]);

                if ($overstockCandidateCount > 0) {
                    $adminHighlights->push([
                        'title' => 'Modal tertahan di stok',
                        'text' => number_format($overstockCandidateCount, 0, ',', '.').' produk terindikasi overstock atau lambat bergerak dengan nilai persediaan sekitar Rp '.number_format($slowStockValue, 0, ',', '.').'.',
                    ]);
                }

                if ($todayMutationCount > 0) {
                    $adminHighlights->push([
                        'title' => 'Aktivitas stok hari ini',
                        'text' => number_format($todayMutationCount, 0, ',', '.').' mutasi tercatat hari ini, terdiri dari +'.number_format($todayStockIn, 0, ',', '.').' item masuk dan '.number_format($todayStockOut, 0, ',', '.').' item keluar.',
                    ]);
                }

                if ($supplierLeaders->isNotEmpty()) {
                    $adminHighlights->push([
                        'title' => 'Pemasok paling aktif',
                        'text' => $supplierLeaders->first()->supplier_name.' menjadi pemasok dengan suplai terbanyak pada '.$decisionWindowLabel.'.',
                    ]);
                }
            }
        }

        if ($isAdmin) {
            $scopeConfig = $this->resolveDashboardScope($dashboardScope, $now, $requestedStartDate, $requestedEndDate);
            $scopeOptionLabel = $scopeConfig['label'];
            $scopeRangeLabel = $scopeConfig['range_label'];
            $dashboardStartDate = $inputStartDate;
            $dashboardEndDate = $inputEndDate;

            $adminInventoryAnalytics = $this->buildAdminPeriodInventoryAnalytics($scopeConfig['start'], $scopeConfig['end']);
            $adminPeriodProducts = $adminInventoryAnalytics['products'];
            $outOfStockCount = $adminInventoryAnalytics['out_of_stock_count'];
            $lowStockCount = $adminInventoryAnalytics['low_stock_count'];
            $overstockCandidateCount = $adminInventoryAnalytics['overstock_candidate_count'];
            $overstockCandidates = $adminInventoryAnalytics['overstock_candidates'];
            $slowStockValue = $adminInventoryAnalytics['slow_stock_value'];
            $restockPriorityCount = $adminInventoryAnalytics['restock_priority_count'];
            $restockPriorities = $adminInventoryAnalytics['restock_priorities'];
            $categoryStockAlerts = $adminInventoryAnalytics['category_stock_alerts'];
            $topCategoryAlert = $categoryStockAlerts->first();
            $adminCategoryCheckProducts = $topCategoryAlert
                ? $adminPeriodProducts
                    ->filter(function ($product) use ($topCategoryAlert) {
                        $needsAttention = (int) $product->stok <= 0
                            || ((int) $product->stok > 0 && (int) $product->stok <= (int) $product->stok_minimum);

                        return $needsAttention && ($product->category_name ?? 'Tanpa Kategori') === $topCategoryAlert->category_name;
                    })
                    ->sort(function ($a, $b) {
                        $statusA = (int) $a->stok <= 0 ? 0 : 1;
                        $statusB = (int) $b->stok <= 0 ? 0 : 1;
                        $statusCompare = $statusA <=> $statusB;
                        if ($statusCompare !== 0) {
                            return $statusCompare;
                        }

                        return (int) $a->stok <=> (int) $b->stok;
                    })
                    ->values()
                : collect();

            $recentMutations = RiwayatStok::query()
                ->with(['produk', 'supplier', 'user'])
                ->whereBetween('tanggal', [$scopeConfig['start'], $scopeConfig['end']])
                ->orderByDesc('tanggal')
                ->limit(8)
                ->get();

            $periodSalesRows = Penjualan::query()
                ->select('tanggal', 'total')
                ->whereBetween('tanggal', [$scopeConfig['start'], $scopeConfig['end']])
                ->get();

            $periodSalesTotal = (int) $periodSalesRows->sum('total');
            $periodTransactionCount = (int) $periodSalesRows->count();
            $periodExpenseTotal = (int) Pengeluaran::query()
                ->whereBetween('tanggal', [$scopeConfig['start']->toDateString(), $scopeConfig['end']->toDateString()])
                ->sum('nominal');

            $salesByBucket = $periodSalesRows
                ->groupBy(fn ($sale) => $this->resolveScopeKey(Carbon::parse($sale->tanggal), $dashboardScope))
                ->map(fn ($items) => (int) $items->sum('total'));

            $mutationRows = RiwayatStok::query()
                ->select('tanggal', 'jumlah')
                ->whereBetween('tanggal', [$scopeConfig['start'], $scopeConfig['end']])
                ->get();

            $periodStockInTotal = (int) $mutationRows
                ->filter(fn ($item) => (int) $item->jumlah > 0)
                ->sum('jumlah');
            $periodStockOutTotal = (int) abs($mutationRows
                ->filter(fn ($item) => (int) $item->jumlah < 0)
                ->sum('jumlah'));

            $mutationInByBucket = $mutationRows
                ->filter(fn ($item) => (int) $item->jumlah > 0)
                ->groupBy(fn ($item) => $this->resolveScopeKey(Carbon::parse($item->tanggal), $dashboardScope))
                ->map(fn ($items) => (int) $items->sum('jumlah'));

            $mutationOutByBucket = $mutationRows
                ->filter(fn ($item) => (int) $item->jumlah < 0)
                ->groupBy(fn ($item) => $this->resolveScopeKey(Carbon::parse($item->tanggal), $dashboardScope))
                ->map(fn ($items) => (int) abs($items->sum('jumlah')));

            $adminSalesChartLabels = $scopeConfig['buckets']->pluck('label')->values()->all();
            $adminMutationChartLabels = $scopeConfig['buckets']->pluck('label')->values()->all();
            $adminSalesChartValues = $scopeConfig['buckets']
                ->map(fn ($bucket) => (int) ($salesByBucket[$bucket['key']] ?? 0))
                ->values()
                ->all();
            $adminMutationInValues = $scopeConfig['buckets']
                ->map(fn ($bucket) => (int) ($mutationInByBucket[$bucket['key']] ?? 0))
                ->values()
                ->all();
            $adminMutationOutValues = $scopeConfig['buckets']
                ->map(fn ($bucket) => (int) ($mutationOutByBucket[$bucket['key']] ?? 0))
                ->values()
                ->all();

            $normalStockCount = max($adminInventoryAnalytics['product_count'] - $outOfStockCount - $lowStockCount - $overstockCandidateCount, 0);
            $stockHealthChartLabels = ['Habis', 'Menipis', 'Aman', 'Terlalu Banyak'];
            $stockHealthChartValues = [
                $outOfStockCount,
                $lowStockCount,
                $normalStockCount,
                $overstockCandidateCount,
            ];

            $restockChartLabels = $restockPriorities
                ->take(5)
                ->pluck('nama')
                ->map(fn ($name) => mb_strimwidth($name, 0, 18, '...'))
                ->values()
                ->all();
            $restockChartValues = $restockPriorities
                ->take(5)
                ->pluck('suggested_restock')
                ->map(fn ($value) => (int) $value)
                ->values()
                ->all();

            $categoryPeriodSales = DetailPenjualan::query()
                ->selectRaw('kategoris.nama as category_name, SUM(detail_penjualans.subtotal) as total_sales')
                ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
                ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
                ->join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
                ->whereBetween('penjualans.tanggal', [$scopeConfig['start'], $scopeConfig['end']])
                ->groupBy('kategoris.nama')
                ->orderByDesc('total_sales')
                ->limit(5)
                ->get();

            $categorySalesChartLabels = $categoryPeriodSales
                ->pluck('category_name')
                ->map(fn ($name) => mb_strimwidth($name, 0, 18, '...'))
                ->values()
                ->all();
            $categorySalesChartValues = $categoryPeriodSales
                ->pluck('total_sales')
                ->map(fn ($value) => (int) $value)
                ->values()
                ->all();

            $overstockChartLabels = $overstockCandidates
                ->take(5)
                ->pluck('nama')
                ->map(fn ($name) => mb_strimwidth($name, 0, 18, '...'))
                ->values()
                ->all();
            $overstockChartValues = $overstockCandidates
                ->take(5)
                ->pluck('inventory_value')
                ->map(fn ($value) => (int) $value)
                ->values()
                ->all();

            $adminHighlights = collect();

            if ($restockPriorityCount > 0) {
                $adminHighlights->push([
                    'title' => 'Perlu Dibeli Lagi',
                    'metric' => number_format($restockPriorityCount, 0, ',', '.').' produk',
                    'text' => 'Dahulukan barang yang stoknya tipis atau sudah habis.',
                    'tone' => 'red',
                    'target' => 'restock',
                ]);
            } else {
                $adminHighlights->push([
                    'title' => 'Perlu Dibeli Lagi',
                    'metric' => 'Aman',
                    'text' => 'Belum ada barang yang mendesak untuk dibeli lagi.',
                    'tone' => 'green',
                    'target' => 'restock',
                ]);
            }

            if ($slowMoverCount > 0) {
                $adminHighlights->push([
                    'title' => 'Barang Sepi',
                    'metric' => number_format($slowMoverCount, 0, ',', '.').' produk',
                    'text' => 'Coba tahan belanja ulang atau bantu dengan promo sederhana.',
                    'tone' => 'amber',
                    'target' => 'slow',
                ]);
            } else {
                $adminHighlights->push([
                    'title' => 'Barang Sepi',
                    'metric' => 'Terkendali',
                    'text' => 'Perputaran barang masih sehat pada periode ini.',
                    'tone' => 'blue',
                    'target' => 'slow',
                ]);
            }

            if ($topCategoryAlert) {
                $adminHighlights->push([
                    'title' => 'Kategori Perlu Dicek',
                    'metric' => number_format($adminCategoryCheckProducts->count(), 0, ',', '.').' produk',
                    'text' => $topCategoryAlert->category_name.' paling banyak butuh perhatian stok.',
                    'tone' => 'blue',
                    'target' => 'category',
                ]);
            } else {
                $adminHighlights->push([
                    'title' => 'Kategori Perlu Dicek',
                    'metric' => 'Tidak Ada',
                    'text' => 'Belum ada kategori yang sedang banyak bermasalah.',
                    'tone' => 'green',
                    'target' => 'category',
                ]);
            }

        }

        if ($isOwner) {
            $trendStart = $now->copy()->startOfMonth()->subMonths(11);
            $trendEnd = $now->copy()->endOfMonth();

            $monthlyPerformance = Penjualan::query()
                ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as month_key, SUM(total) as total_sales, COUNT(*) as transaction_count")
                ->whereBetween('tanggal', [$trendStart, $trendEnd])
                ->groupBy('month_key')
                ->get()
                ->keyBy('month_key');

            $trendMonths = collect(range(0, 11))
                ->map(fn (int $i) => $trendStart->copy()->addMonths($i));

            $salesTrendLabels = $trendMonths
                ->map(fn (Carbon $date) => $date->translatedFormat('M Y'))
                ->values()
                ->all();

            $salesTrendValues = $trendMonths
                ->map(function (Carbon $date) use ($monthlyPerformance) {
                    $record = $monthlyPerformance->get($date->format('Y-m'));

                    return (int) ($record->total_sales ?? 0);
                })
                ->values()
                ->all();

            $peakMonth = $monthlyPerformance
                ->sortByDesc('total_sales')
                ->first();

            if ($peakMonth) {
                $peakMonthStart = Carbon::createFromFormat('Y-m', $peakMonth->month_key)->startOfMonth();
                $peakMonthEnd = $peakMonthStart->copy()->endOfMonth();
                $peakMonthLabel = $peakMonthStart->translatedFormat('F Y');

                $peakMonthTopProducts = DetailPenjualan::query()
                    ->selectRaw('produks.id, produks.nama as product_name, SUM(detail_penjualans.qty) as qty_sold, SUM(detail_penjualans.subtotal) as total_sales')
                    ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
                    ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
                    ->whereBetween('penjualans.tanggal', [$peakMonthStart, $peakMonthEnd])
                    ->groupBy('produks.id', 'produks.nama')
                    ->orderByDesc('qty_sold')
                    ->orderByDesc('total_sales')
                    ->limit(5)
                    ->get();

                $peakMonthLeader = $peakMonthTopProducts->first();
            }

            $dailyPerformance = Penjualan::query()
                ->selectRaw('DATE(tanggal) as day_key, SUM(total) as total_sales, COUNT(*) as transaction_count')
                ->whereBetween('tanggal', [$trendStart, $decisionWindowEnd])
                ->groupBy('day_key')
                ->get()
                ->sort(function ($a, $b) {
                    $transactionComparison = (int) $b->transaction_count <=> (int) $a->transaction_count;
                    if ($transactionComparison !== 0) {
                        return $transactionComparison;
                    }

                    return (int) $b->total_sales <=> (int) $a->total_sales;
                });

            $peakDay = $dailyPerformance->first();

            if ($peakDay) {
                $peakDayDate = Carbon::parse($peakDay->day_key);
                $peakDayLabel = $peakDayDate->translatedFormat('d F Y');

                $peakDayLeader = DetailPenjualan::query()
                    ->selectRaw('produks.nama as product_name, SUM(detail_penjualans.qty) as qty_sold, SUM(detail_penjualans.subtotal) as total_sales')
                    ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
                    ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
                    ->whereDate('penjualans.tanggal', $peakDayDate->toDateString())
                    ->groupBy('produks.id', 'produks.nama')
                    ->orderByDesc('qty_sold')
                    ->orderByDesc('total_sales')
                    ->first();
            }

            $categoryLeader = $categoryLeaders->first();
            if ($peakMonth && $peakMonthLeader) {
                $decisionHighlights->push([
                    'title' => 'Musim ramai toko',
                    'text' => $peakMonthLabel.' menjadi periode omzet tertinggi. Produk terlaris pada periode ini adalah '.$peakMonthLeader->product_name.' dengan penjualan '.number_format($peakMonthLeader->qty_sold, 0, ',', '.').' item.',
                ]);
            }

            if ($peakDay && $peakDayLeader) {
                $decisionHighlights->push([
                    'title' => 'Hari paling sibuk',
                    'text' => $peakDayLabel.' mencatat '.number_format($peakDay->transaction_count, 0, ',', '.').' transaksi. Produk yang paling banyak dibeli hari itu adalah '.$peakDayLeader->product_name.'.',
                ]);
            }

            if ($restockPriorityCount > 0) {
                $decisionHighlights->push([
                    'title' => 'Prioritas pembelian',
                    'text' => number_format($restockPriorityCount, 0, ',', '.').' produk aktif perlu diprioritaskan untuk restok karena stok menipis atau daya tahannya kurang dari 14 hari.',
                ]);
            }

            if ($slowMoverCount > 0) {
                $decisionHighlights->push([
                    'title' => 'Barang lambat laku',
                    'text' => number_format($slowMoverCount, 0, ',', '.').' produk belum terjual dalam 30 hari terakhir. Item-item ini sebaiknya ditahan pembelian ulangnya atau dipromosikan.',
                ]);
            }

            if ($categoryLeader) {
                $decisionHighlights->push([
                    'title' => 'Kategori penggerak omzet',
                    'text' => 'Dalam '.$decisionWindowLabel.', kategori '.$categoryLeader->category_name.' memberi kontribusi penjualan terbesar.',
                ]);
            }
        }

        return view('dashboard', compact(
            'role',
            'dashboardScope',
            'scopeOptionLabel',
            'scopeRangeLabel',
            'dashboardStartDate',
            'dashboardEndDate',
            'todaySales',
            'todayTransactionCount',
            'totalProducts',
            'totalCustomers',
            'outstandingReceivables',
            'latestTransactions',
            'lowStockProducts',
            'decisionWindowLabel',
            'outOfStockCount',
            'lowStockCount',
            'overstockCandidateCount',
            'overstockCandidates',
            'slowStockValue',
            'todayMutationCount',
            'todayStockIn',
            'todayStockOut',
            'periodSalesTotal',
            'periodExpenseTotal',
            'periodTransactionCount',
            'periodStockInTotal',
            'periodStockOutTotal',
            'adminSalesChartLabels',
            'adminSalesChartValues',
            'adminMutationChartLabels',
            'adminMutationInValues',
            'adminMutationOutValues',
            'stockHealthChartLabels',
            'stockHealthChartValues',
            'restockChartLabels',
            'restockChartValues',
            'categorySalesChartLabels',
            'categorySalesChartValues',
            'overstockChartLabels',
            'overstockChartValues',
            'recentMutations',
            'supplierLeaders',
            'categoryStockAlerts',
            'adminHighlights',
            'peakMonth',
            'peakMonthLabel',
            'peakMonthTopProducts',
            'peakMonthLeader',
            'peakDay',
            'peakDayLabel',
            'peakDayLeader',
            'salesTrendLabels',
            'salesTrendValues',
            'categoryLeaders',
            'categoryChartLabels',
            'categoryChartValues',
            'restockPriorityCount',
            'restockPriorities',
            'slowMoverCount',
            'slowMovingProducts',
            'adminCategoryCheckProducts',
            'overdueReceivablesCount',
            'overdueReceivablesAmount',
            'overdueReceivables',
            'decisionHighlights'
        ));
    }

    private function resolveDashboardScope(string $scope, Carbon $now, ?Carbon $requestedStartDate = null, ?Carbon $requestedEndDate = null): array
    {
        [$customStartDate, $customEndDate] = $this->resolveRequestedDateRange($scope, $now, $requestedStartDate, $requestedEndDate);

        if ($customStartDate !== null && $customEndDate !== null) {
            return [
                'scope' => $scope,
                'label' => $this->resolveScopeLabel($scope),
                'range_label' => $customStartDate->translatedFormat('d M Y').' - '.$customEndDate->translatedFormat('d M Y'),
                'start' => $customStartDate,
                'end' => $customEndDate,
                'buckets' => $this->buildDashboardBuckets($scope, $customStartDate, $customEndDate),
            ];
        }

        if ($scope === 'week') {
            $buckets = collect(range(7, 0))
                ->map(function (int $i) use ($now) {
                    $start = $now->copy()->startOfWeek()->subWeeks($i)->startOfDay();
                    $end = $start->copy()->endOfWeek()->endOfDay();

                    return [
                        'key' => $start->format('Y-m-d'),
                        'label' => $start->translatedFormat('d M'),
                        'start' => $start,
                        'end' => $end,
                    ];
                });

            return [
                'scope' => $scope,
                'label' => 'Data Mingguan',
                'range_label' => '8 Minggu Terakhir',
                'start' => $buckets->first()['start'],
                'end' => $buckets->last()['end'],
                'buckets' => $buckets,
            ];
        }

        if ($scope === 'month') {
            $buckets = collect(range(5, 0))
                ->map(function (int $i) use ($now) {
                    $start = $now->copy()->startOfMonth()->subMonths($i)->startOfDay();
                    $end = $start->copy()->endOfMonth()->endOfDay();

                    return [
                        'key' => $start->format('Y-m'),
                        'label' => $start->translatedFormat('M Y'),
                        'start' => $start,
                        'end' => $end,
                    ];
                });

            return [
                'scope' => $scope,
                'label' => 'Data Bulanan',
                'range_label' => '6 Bulan Terakhir',
                'start' => $buckets->first()['start'],
                'end' => $buckets->last()['end'],
                'buckets' => $buckets,
            ];
        }

        $buckets = collect(range(6, 0))
            ->map(function (int $i) use ($now) {
                $start = $now->copy()->subDays($i)->startOfDay();
                $end = $start->copy()->endOfDay();

                return [
                    'key' => $start->format('Y-m-d'),
                    'label' => $start->translatedFormat('d M'),
                    'start' => $start,
                    'end' => $end,
                ];
            });

        return [
            'scope' => 'day',
            'label' => 'Data Harian',
            'range_label' => '7 Hari Terakhir',
            'start' => $buckets->first()['start'],
            'end' => $buckets->last()['end'],
            'buckets' => $buckets,
        ];
    }

    private function resolveRequestedDateRange(string $scope, Carbon $now, ?Carbon $requestedStartDate, ?Carbon $requestedEndDate): array
    {
        if ($requestedStartDate === null && $requestedEndDate === null) {
            return [null, null];
        }

        $endDate = $requestedEndDate?->copy()->endOfDay() ?? $now->copy()->endOfDay();

        if ($requestedStartDate !== null) {
            $startDate = $requestedStartDate->copy()->startOfDay();
        } elseif ($scope === 'week') {
            $startDate = $endDate->copy()->subWeeks(7)->startOfWeek()->startOfDay();
        } elseif ($scope === 'month') {
            $startDate = $endDate->copy()->subMonths(5)->startOfMonth()->startOfDay();
        } else {
            $startDate = $endDate->copy()->subDays(6)->startOfDay();
        }

        return [$startDate, $endDate];
    }

    private function resolveScopeLabel(string $scope): string
    {
        if ($scope === 'week') {
            return 'Data Mingguan';
        }

        if ($scope === 'month') {
            return 'Data Bulanan';
        }

        return 'Data Harian';
    }

    private function buildDashboardBuckets(string $scope, Carbon $startDate, Carbon $endDate)
    {
        if ($scope === 'week') {
            $bucketStart = $startDate->copy()->startOfWeek()->startOfDay();
            $bucketEnd = $endDate->copy()->startOfWeek()->startOfDay();
            $buckets = collect();

            while ($bucketStart->lte($bucketEnd)) {
                $buckets->push([
                    'key' => $bucketStart->format('Y-m-d'),
                    'label' => $bucketStart->translatedFormat('d M'),
                    'start' => $bucketStart->copy(),
                    'end' => $bucketStart->copy()->endOfWeek()->endOfDay(),
                ]);

                $bucketStart->addWeek();
            }

            return $buckets;
        }

        if ($scope === 'month') {
            $bucketStart = $startDate->copy()->startOfMonth()->startOfDay();
            $bucketEnd = $endDate->copy()->startOfMonth()->startOfDay();
            $buckets = collect();

            while ($bucketStart->lte($bucketEnd)) {
                $buckets->push([
                    'key' => $bucketStart->format('Y-m'),
                    'label' => $bucketStart->translatedFormat('M Y'),
                    'start' => $bucketStart->copy(),
                    'end' => $bucketStart->copy()->endOfMonth()->endOfDay(),
                ]);

                $bucketStart->addMonth();
            }

            return $buckets;
        }

        $bucketStart = $startDate->copy()->startOfDay();
        $bucketEnd = $endDate->copy()->startOfDay();
        $buckets = collect();

        while ($bucketStart->lte($bucketEnd)) {
            $buckets->push([
                'key' => $bucketStart->format('Y-m-d'),
                'label' => $bucketStart->translatedFormat('d M'),
                'start' => $bucketStart->copy(),
                'end' => $bucketStart->copy()->endOfDay(),
            ]);

            $bucketStart->addDay();
        }

        return $buckets;
    }

    private function buildAdminPeriodInventoryAnalytics(Carbon $periodStart, Carbon $periodEnd): array
    {
        $periodDays = max($periodStart->copy()->startOfDay()->diffInDays($periodEnd->copy()->startOfDay()) + 1, 1);

        $salesInPeriodSub = DetailPenjualan::query()
            ->selectRaw('detail_penjualans.produk_id, SUM(detail_penjualans.qty) as qty_sold_period')
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            ->whereBetween('penjualans.tanggal', [$periodStart, $periodEnd])
            ->groupBy('detail_penjualans.produk_id');

        $salesAfterPeriodSub = DetailPenjualan::query()
            ->selectRaw('detail_penjualans.produk_id, SUM(detail_penjualans.qty) as qty_sold_after_period')
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            ->where('penjualans.tanggal', '>', $periodEnd)
            ->groupBy('detail_penjualans.produk_id');

        $mutationAfterPeriodSub = RiwayatStok::query()
            ->selectRaw('stok_histories.produk_id, SUM(stok_histories.jumlah) as mutation_after_period')
            ->where('stok_histories.tanggal', '>', $periodEnd)
            ->groupBy('stok_histories.produk_id');

        $products = Produk::query()
            ->leftJoinSub($salesInPeriodSub, 'period_sales', function ($join) {
                $join->on('produks.id', '=', 'period_sales.produk_id');
            })
            ->leftJoinSub($salesAfterPeriodSub, 'after_sales', function ($join) {
                $join->on('produks.id', '=', 'after_sales.produk_id');
            })
            ->leftJoinSub($mutationAfterPeriodSub, 'after_mutations', function ($join) {
                $join->on('produks.id', '=', 'after_mutations.produk_id');
            })
            ->leftJoin('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
            ->where('produks.aktif', true)
            ->where('produks.created_at', '<=', $periodEnd)
            ->select(
                'produks.id',
                'produks.nama',
                'produks.stok',
                'produks.stok_minimum',
                'produks.harga_beli',
                DB::raw('kategoris.nama as category_name'),
                DB::raw('COALESCE(period_sales.qty_sold_period, 0) as qty_sold_period'),
                DB::raw('COALESCE(after_sales.qty_sold_after_period, 0) as qty_sold_after_period'),
                DB::raw('COALESCE(after_mutations.mutation_after_period, 0) as mutation_after_period')
            )
            ->orderBy('produks.nama')
            ->get()
            ->map(function ($product) use ($periodDays) {
                $stockAtPeriodEnd = (int) $product->stok + (int) $product->qty_sold_after_period - (int) $product->mutation_after_period;
                $product->stok = max($stockAtPeriodEnd, 0);
                $product->qty_sold_period = (int) $product->qty_sold_period;
                $product->avg_daily_sales = $product->qty_sold_period > 0 ? round($product->qty_sold_period / $periodDays, 2) : 0;
                $product->stock_cover_days = $product->avg_daily_sales > 0 ? round($product->stok / $product->avg_daily_sales, 1) : null;
                $product->inventory_value = (int) $product->stok * (int) $product->harga_beli;
                $targetStock = max((int) $product->stok_minimum * 2, (int) ceil($product->avg_daily_sales * 14));
                $product->suggested_restock = max(0, $targetStock - (int) $product->stok);

                return $product;
            })
            ->values();

        $restockPriorities = $products
            ->filter(function ($product) {
                return $product->qty_sold_period > 0
                    && ((int) $product->stok <= (int) $product->stok_minimum
                        || ($product->stock_cover_days !== null && (float) $product->stock_cover_days <= 14));
            })
            ->sort(function ($a, $b) {
                $belowMinCompare = ((int) $a->stok <= (int) $a->stok_minimum ? 0 : 1) <=> ((int) $b->stok <= (int) $b->stok_minimum ? 0 : 1);
                if ($belowMinCompare !== 0) {
                    return $belowMinCompare;
                }

                $coverA = $a->stock_cover_days ?? 999999;
                $coverB = $b->stock_cover_days ?? 999999;
                $coverCompare = $coverA <=> $coverB;
                if ($coverCompare !== 0) {
                    return $coverCompare;
                }

                return (int) $b->qty_sold_period <=> (int) $a->qty_sold_period;
            })
            ->values();

        $overstockCandidates = $products
            ->filter(function ($product) {
                return (int) $product->stok > 0
                    && (((int) $product->qty_sold_period === 0 && (int) $product->stok > ((int) $product->stok_minimum * 2))
                        || ((int) $product->qty_sold_period > 0 && $product->stock_cover_days !== null && (float) $product->stock_cover_days >= 45));
            })
            ->sort(function ($a, $b) {
                $valueCompare = (int) $b->inventory_value <=> (int) $a->inventory_value;
                if ($valueCompare !== 0) {
                    return $valueCompare;
                }

                return (int) $b->stok <=> (int) $a->stok;
            })
            ->values();

        $categoryStockAlerts = $products
            ->groupBy(fn ($product) => $product->category_name ?: 'Tanpa Kategori')
            ->map(function ($items, $categoryName) {
                return (object) [
                    'category_name' => $categoryName,
                    'out_count' => $items->filter(fn ($product) => (int) $product->stok <= 0)->count(),
                    'low_count' => $items->filter(fn ($product) => (int) $product->stok > 0 && (int) $product->stok <= (int) $product->stok_minimum)->count(),
                    'total_products' => $items->count(),
                ];
            })
            ->filter(fn ($category) => ((int) $category->out_count + (int) $category->low_count) > 0)
            ->sort(function ($a, $b) {
                $outCompare = (int) $b->out_count <=> (int) $a->out_count;
                if ($outCompare !== 0) {
                    return $outCompare;
                }

                return (int) $b->low_count <=> (int) $a->low_count;
            })
            ->take(6)
            ->values();

        return [
            'products' => $products,
            'product_count' => $products->count(),
            'out_of_stock_count' => $products->filter(fn ($product) => (int) $product->stok <= 0)->count(),
            'low_stock_count' => $products->filter(fn ($product) => (int) $product->stok > 0 && (int) $product->stok <= (int) $product->stok_minimum)->count(),
            'restock_priority_count' => $restockPriorities->count(),
            'restock_priorities' => $restockPriorities->values(),
            'overstock_candidate_count' => $overstockCandidates->count(),
            'overstock_candidates' => $overstockCandidates->take(8)->values(),
            'slow_stock_value' => (int) round($overstockCandidates->sum('inventory_value')),
            'category_stock_alerts' => $categoryStockAlerts,
        ];
    }

    private function resolveScopeKey(Carbon $date, string $scope): string
    {
        if ($scope === 'week') {
            return $date->copy()->startOfWeek()->format('Y-m-d');
        }

        if ($scope === 'month') {
            return $date->format('Y-m');
        }

        return $date->format('Y-m-d');
    }
}
