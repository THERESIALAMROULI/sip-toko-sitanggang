<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pelanggan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pengeluaran;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\KategoriPengeluaran;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Kategori;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Produk;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Piutang;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\RiwayatStok;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Penjualan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\DetailPenjualan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;

// Mendefinisikan class sebagai wadah logika pada file ini.
class LaporanController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method sales untuk menjalankan proses tertentu.
    public function sales(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => ['nullable', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'payment_type' => ['nullable', 'in:tunai,utang'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $query = Penjualan::query();

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['payment_type'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('metode', $validated['payment_type']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['customer_id'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('pelanggan_id', (int) $validated['customer_id']);
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $transactions = (clone $query)
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('customer')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('tanggal')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalSales = $transactions->sum('total');
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalTransactions = $transactions->count();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $averagePerTransaction = $totalTransactions > 0
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ? (int) round($totalSales / $totalTransactions)
            // Baris ini merupakan bagian dari logika proses pada file ini.
            : 0;
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $creditSales = $transactions
            // Menambahkan kondisi filter pada query data.
            ->where('payment_type', 'utang')
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('total');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $chartData = $transactions
            // Mengelompokkan data agar dapat dihitung atau diringkas per kategori tertentu.
            ->groupBy(fn ($transaction) => $transaction->transaction_date->format('Y-m'))
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->map(fn ($items) => $items->sum('total'))
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->sortKeys();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $chartLabels = $chartData->keys()->values();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $chartTotals = $chartData->values();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $topProductsQuery = DetailPenjualan::query()
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->selectRaw('produks.id, produks.nama as product_name, SUM(detail_penjualans.qty) as qty_sold, SUM(detail_penjualans.subtotal) as total_sales')
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id');

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $topProductsQuery->where('penjualans.tanggal', '>=', $validated['start_date'].' 00:00:00');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $topProductsQuery->where('penjualans.tanggal', '<=', $validated['end_date'].' 23:59:59');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['payment_type'])) {
            // Menambahkan kondisi filter pada query data.
            $topProductsQuery->where('penjualans.metode', $validated['payment_type']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['customer_id'])) {
            // Menambahkan kondisi filter pada query data.
            $topProductsQuery->where('penjualans.pelanggan_id', (int) $validated['customer_id']);
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $topProducts = $topProductsQuery
            // Mengelompokkan data agar dapat dihitung atau diringkas per kategori tertentu.
            ->groupBy('produks.id', 'produks.nama')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('qty_sold')
            // Membatasi jumlah data yang diambil agar tampilan tetap ringkas.
            ->limit(5)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $customers = Pelanggan::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('laporan.sales', compact(
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'transactions',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalSales',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalTransactions',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'averagePerTransaction',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'creditSales',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'chartLabels',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'chartTotals',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'topProducts',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'customers'
        // Baris ini merupakan bagian dari logika proses pada file ini.
        ));
    // Menutup blok kode.
    }

    // Mendefinisikan method receivables untuk menjalankan proses tertentu.
    public function receivables(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => ['nullable', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => ['nullable', 'in:unpaid,paid'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $query = Piutang::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('transaction.customer');

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('created_at', '>=', $validated['start_date'].' 00:00:00');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('created_at', '<=', $validated['end_date'].' 23:59:59');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['status'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('status', $validated['status'] === 'paid' ? 'lunas' : 'belum');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['customer_id'])) {
            // Menambahkan kondisi filter pada query data.
            $query->where('pelanggan_id', (int) $validated['customer_id']);
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $receivables = $query
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('created_at')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalUnpaid = $receivables
            // Menambahkan kondisi filter pada query data.
            ->where('status', 'unpaid')
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('amount');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalPaid = $receivables
            // Menambahkan kondisi filter pada query data.
            ->where('status', 'paid')
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('amount');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $overdueUnpaid = $receivables
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->filter(fn (Piutang $receivable) => $receivable->status === 'unpaid'
                // Baris ini merupakan bagian dari logika proses pada file ini.
                && $receivable->due_date
                // Baris ini merupakan bagian dari logika proses pada file ini.
                && $receivable->due_date->lt(now()))
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('amount');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $customers = Pelanggan::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('laporan.receivables', compact(
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'receivables',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalUnpaid',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalPaid',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'overdueUnpaid',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'customers'
        // Baris ini merupakan bagian dari logika proses pada file ini.
        ));
    // Menutup blok kode.
    }

    // Mendefinisikan method stock untuk menjalankan proses tertentu.
    public function stock(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => ['nullable', 'string', 'max:100'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => ['nullable', 'in:normal,low,out,inactive'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'kategori_id' => ['nullable', 'exists:kategoris,id'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $productsQuery = Produk::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('kategori');

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['q'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $search = trim($validated['q']);
            // Menambahkan kondisi filter pada query data.
            $productsQuery->where('nama', 'like', '%'.$search.'%');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['kategori_id'])) {
            // Menambahkan kondisi filter pada query data.
            $productsQuery->where('kategori_id', (int) $validated['kategori_id']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['status'])) {
            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($validated['status'] === 'inactive') {
                // Menambahkan kondisi filter pada query data.
                $productsQuery->where('aktif', false);
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } elseif ($validated['status'] === 'out') {
                // Menambahkan kondisi filter pada query data.
                $productsQuery->where('aktif', true)->where('stok', '<=', 0);
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } elseif ($validated['status'] === 'low') {
                // Menambahkan kondisi filter pada query data.
                $productsQuery->where('aktif', true)->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum');
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } elseif ($validated['status'] === 'normal') {
                // Menambahkan kondisi filter pada query data.
                $productsQuery->where('aktif', true)->whereColumn('stok', '>', 'stok_minimum');
            // Menutup blok kode.
            }
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $products = $productsQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalProducts = Produk::query()->count();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $lowStockCount = Produk::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Menambahkan kondisi filter pada query data.
            ->where('stok', '>', 0)
            // Menambahkan kondisi filter pada query data.
            ->whereColumn('stok', '<=', 'stok_minimum')
            // Menghitung jumlah data yang sesuai dengan kondisi query.
            ->count();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $outStockCount = Produk::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Menambahkan kondisi filter pada query data.
            ->where('stok', '<=', 0)
            // Menghitung jumlah data yang sesuai dengan kondisi query.
            ->count();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $stockValue = Produk::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get()
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum(fn (Produk $product) => ((int) $product->stok) * ((int) $product->harga_beli));

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $recentMutations = RiwayatStok::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with(['produk', 'supplier', 'user'])
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('tanggal')
            // Membatasi jumlah data yang diambil agar tampilan tetap ringkas.
            ->limit(10)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $kategoris = Kategori::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('laporan.stock', compact(
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'products',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalProducts',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'lowStockCount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'outStockCount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'stockValue',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'recentMutations',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'kategoris'
        // Baris ini merupakan bagian dari logika proses pada file ini.
        ));
    // Menutup blok kode.
    }

    // Mendefinisikan method expenses untuk menjalankan proses tertentu.
    public function expenses(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => ['nullable', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expense_category_id' => ['nullable', 'exists:expense_categories,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => ['nullable', 'string', 'max:100'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expensesQuery = Pengeluaran::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with(['category', 'user']);

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->whereDate('tanggal', '>=', $validated['start_date']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->whereDate('tanggal', '<=', $validated['end_date']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['expense_category_id'])) {
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->where('expense_category_id', (int) $validated['expense_category_id']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['q'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $search = trim($validated['q']);
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->where(function ($query) use ($search) {
                // Menambahkan kondisi filter pada query data.
                $query->where('catatan', 'like', '%'.$search.'%')
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        // Menambahkan kondisi filter pada query data.
                        $categoryQuery->where('nama', 'like', '%'.$search.'%');
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    })
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        // Menambahkan kondisi filter pada query data.
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    // Menutup struktur atau rangkaian proses pada blok sebelumnya.
                    });
            // Menutup struktur atau rangkaian proses pada blok sebelumnya.
            });
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenses = $expensesQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('tanggal')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('id')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalExpense = (int) $expenses->sum('nominal');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $salesQuery = Penjualan::query();

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $salesQuery->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $salesQuery->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalSales = (int) (clone $salesQuery)->sum('total');
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalTransactions = (int) (clone $salesQuery)->count();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $netProfit = $totalSales - $totalExpense;

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenseByCategory = $expenses
            // Mengelompokkan data agar dapat dihitung atau diringkas per kategori tertentu.
            ->groupBy(fn (Pengeluaran $expense) => $expense->category->nama ?? 'Tanpa Kategori')
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->map(fn ($items) => (int) $items->sum('nominal'))
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->sortDesc();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $categoryLabels = $expenseByCategory->keys()->values();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $categoryTotals = $expenseByCategory->values();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenseCategories = KategoriPengeluaran::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('laporan.expenses', compact(
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expenses',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalExpense',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalSales',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'netProfit',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalTransactions',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expenseByCategory',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'categoryLabels',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'categoryTotals',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expenseCategories'
        // Baris ini merupakan bagian dari logika proses pada file ini.
        ));
    // Menutup blok kode.
    }
// Menutup blok kode.
}
