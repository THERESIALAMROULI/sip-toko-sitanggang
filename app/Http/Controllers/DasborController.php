<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Piutang;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;

class DasborController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $todaySales = Penjualan::query()
            ->whereDate('tanggal', $today)
            ->sum('total');

        $todayTransactionCount = Penjualan::query()
            ->whereDate('tanggal', $today)
            ->count();

        $totalProducts = Produk::query()->count();
        $totalCustomers = Pelanggan::query()->count();

        $outstandingReceivables = Piutang::query()
            ->where('status', 'belum')
            ->sum('jumlah');

        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->subMonthNoOverflow()->endOfMonth();

        $thisMonthSales = Penjualan::query()
            ->whereBetween('tanggal', [$thisMonthStart, now()])
            ->sum('total');

        $lastMonthSales = Penjualan::query()
            ->whereBetween('tanggal', [$lastMonthStart, $lastMonthEnd])
            ->sum('total');

        $salesGrowthPercent = 0.0;
        if ($lastMonthSales > 0) {
            $salesGrowthPercent = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        } elseif ($thisMonthSales > 0) {
            $salesGrowthPercent = 100.0;
        }

        $latestTransactions = Penjualan::query()
            ->with('customer')
            ->latest('tanggal')
            ->limit(6)
            ->get();

        $lowStockProducts = Produk::query()
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->orderBy('nama')
            ->limit(6)
            ->get();

        $outOfStockCount = Produk::query()
            ->where('aktif', true)
            ->where('stok', '<=', 0)
            ->count();

        $overdueBaseQuery = Piutang::query()
            ->where('status', 'belum')
            ->whereDate('jatuh_tempo', '<', now()->toDateString());

        $overdueReceivablesCount = (clone $overdueBaseQuery)->count();
        $overdueReceivablesAmount = (clone $overdueBaseQuery)->sum('jumlah');
        $overdueReceivables = (clone $overdueBaseQuery)
            ->with('transaction.customer')
            ->orderBy('created_at')
            ->limit(6)
            ->get();

        $ownerTrendStart = now()->startOfMonth()->subMonths(11);
        $ownerTrendEnd = now()->endOfMonth();

        $ownerMonths = collect(range(0, 11))
            ->map(fn (int $i) => $ownerTrendStart->copy()->addMonths($i));

        $ownerMonthKeys = $ownerMonths
            ->map(fn ($date) => $date->format('Y-m'));

        $ownerTrendLabels = $ownerMonths
            ->map(fn ($date) => $date->format('M Y'))
            ->values()
            ->all();

        $salesByMonth = Penjualan::query()
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as month_key, SUM(total) as total_sales")
            ->whereBetween('tanggal', [$ownerTrendStart, $ownerTrendEnd])
            ->groupBy('month_key')
            ->pluck('total_sales', 'month_key');

        $receivablesByMonth = Piutang::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, SUM(jumlah) as total_receivables")
            ->whereBetween('created_at', [$ownerTrendStart, $ownerTrendEnd])
            ->groupBy('month_key')
            ->pluck('total_receivables', 'month_key');

        $ownerSalesTrendValues = $ownerMonthKeys
            ->map(fn (string $key) => (int) ($salesByMonth[$key] ?? 0))
            ->values()
            ->all();

        $ownerReceivableTrendValues = $ownerMonthKeys
            ->map(fn (string $key) => (int) ($receivablesByMonth[$key] ?? 0))
            ->values()
            ->all();

        $ownerTopProducts = DetailPenjualan::query()
            ->selectRaw('produks.id, produks.nama as product_name, SUM(detail_penjualans.qty) as qty_sold, SUM(detail_penjualans.subtotal) as total_sales')
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
            ->whereBetween('penjualans.tanggal', [$ownerTrendStart, $ownerTrendEnd])
            ->groupBy('produks.id', 'produks.nama')
            ->orderByDesc('qty_sold')
            ->limit(5)
            ->get();

        $ownerOldestReceivables = Piutang::query()
            ->with('transaction.customer')
            ->where('status', 'belum')
            ->orderByRaw('COALESCE(jatuh_tempo, created_at) asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'todayTransactionCount',
            'totalProducts',
            'totalCustomers',
            'outstandingReceivables',
            'thisMonthSales',
            'lastMonthSales',
            'salesGrowthPercent',
            'latestTransactions',
            'lowStockProducts',
            'outOfStockCount',
            'overdueReceivablesCount',
            'overdueReceivablesAmount',
            'overdueReceivables',
            'ownerTrendLabels',
            'ownerSalesTrendValues',
            'ownerReceivableTrendValues',
            'ownerTopProducts',
            'ownerOldestReceivables'
        ));
    }
}
