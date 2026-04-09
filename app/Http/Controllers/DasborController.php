<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pelanggan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Produk;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Piutang;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Penjualan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\DetailPenjualan;

// Mendefinisikan class sebagai wadah logika pada file ini.
class DasborController extends Controller
// Membuka blok kode.
{
    // Menyiapkan seluruh data ringkasan yang ditampilkan pada dashboard.
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index()
    // Membuka blok kode.
    {
        // Tanggal hari ini dipakai untuk menghitung penjualan harian.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $today = now()->toDateString();

        // Total penjualan dan jumlah transaksi hari ini.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $todaySales = Penjualan::query()
            // Menambahkan kondisi filter pada query data.
            ->whereDate('tanggal', $today)
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('total');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $todayTransactionCount = Penjualan::query()
            // Menambahkan kondisi filter pada query data.
            ->whereDate('tanggal', $today)
            // Menghitung jumlah data yang sesuai dengan kondisi query.
            ->count();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalProducts = Produk::query()->count();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalCustomers = Pelanggan::query()->count();

        // Total piutang yang belum lunas.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $outstandingReceivables = Piutang::query()
            // Menambahkan kondisi filter pada query data.
            ->where('status', 'belum')
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('jumlah');

        // Periode bulan ini dan bulan lalu dipakai untuk membandingkan performa penjualan.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $thisMonthStart = now()->startOfMonth();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $lastMonthEnd = now()->subMonthNoOverflow()->endOfMonth();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $thisMonthSales = Penjualan::query()
            // Menambahkan kondisi filter pada query data.
            ->whereBetween('tanggal', [$thisMonthStart, now()])
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('total');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $lastMonthSales = Penjualan::query()
            // Menambahkan kondisi filter pada query data.
            ->whereBetween('tanggal', [$lastMonthStart, $lastMonthEnd])
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('total');

        // Persentase pertumbuhan dihitung agar owner bisa melihat tren penjualan.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $salesGrowthPercent = 0.0;
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if ($lastMonthSales > 0) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $salesGrowthPercent = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        // Baris ini merupakan bagian dari logika proses pada file ini.
        } elseif ($thisMonthSales > 0) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $salesGrowthPercent = 100.0;
        // Menutup blok kode.
        }

        // Menampilkan transaksi terbaru untuk monitoring cepat.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $latestTransactions = Penjualan::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('customer')
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->latest('tanggal')
            // Membatasi jumlah data yang diambil agar tampilan tetap ringkas.
            ->limit(6)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Produk dengan stok minimum ditampilkan agar segera direstok.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $lowStockProducts = Produk::query()
            // Menambahkan kondisi filter pada query data.
            ->whereColumn('stok', '<=', 'stok_minimum')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('stok')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Membatasi jumlah data yang diambil agar tampilan tetap ringkas.
            ->limit(6)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menghitung jumlah produk aktif yang stoknya benar-benar habis.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $outOfStockCount = Produk::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Menambahkan kondisi filter pada query data.
            ->where('stok', '<=', 0)
            // Menghitung jumlah data yang sesuai dengan kondisi query.
            ->count();

        // Query dasar untuk piutang lewat jatuh tempo, lalu dipakai ulang untuk count, sum, dan list.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $overdueBaseQuery = Piutang::query()
            // Menambahkan kondisi filter pada query data.
            ->where('status', 'belum')
            // Menambahkan kondisi filter pada query data.
            ->whereDate('jatuh_tempo', '<', now()->toDateString());

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $overdueReceivablesCount = (clone $overdueBaseQuery)->count();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $overdueReceivablesAmount = (clone $overdueBaseQuery)->sum('jumlah');
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $overdueReceivables = (clone $overdueBaseQuery)
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('transaction.customer')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('created_at')
            // Membatasi jumlah data yang diambil agar tampilan tetap ringkas.
            ->limit(6)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Rentang 12 bulan terakhir dipakai untuk grafik owner.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerTrendStart = now()->startOfMonth()->subMonths(11);
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerTrendEnd = now()->endOfMonth();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerMonths = collect(range(0, 11))
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->map(fn (int $i) => $ownerTrendStart->copy()->addMonths($i));

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerMonthKeys = $ownerMonths
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->map(fn ($date) => $date->format('Y-m'));

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerTrendLabels = $ownerMonths
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->map(fn ($date) => $date->format('M Y'))
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->values()
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->all();

        // Mengelompokkan total penjualan per bulan untuk ditampilkan dalam grafik.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $salesByMonth = Penjualan::query()
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as month_key, SUM(total) as total_sales")
            // Menambahkan kondisi filter pada query data.
            ->whereBetween('tanggal', [$ownerTrendStart, $ownerTrendEnd])
            // Mengelompokkan data agar dapat dihitung atau diringkas per kategori tertentu.
            ->groupBy('month_key')
            // Mengambil kolom tertentu dari hasil query dalam bentuk yang lebih ringkas.
            ->pluck('total_sales', 'month_key');

        // Mengelompokkan total piutang per bulan untuk perbandingan dengan penjualan.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $receivablesByMonth = Piutang::query()
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, SUM(jumlah) as total_receivables")
            // Menambahkan kondisi filter pada query data.
            ->whereBetween('created_at', [$ownerTrendStart, $ownerTrendEnd])
            // Mengelompokkan data agar dapat dihitung atau diringkas per kategori tertentu.
            ->groupBy('month_key')
            // Mengambil kolom tertentu dari hasil query dalam bentuk yang lebih ringkas.
            ->pluck('total_receivables', 'month_key');

        // Data bulanan disusun ulang agar urutannya selalu konsisten dengan label grafik.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerSalesTrendValues = $ownerMonthKeys
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->map(fn (string $key) => (int) ($salesByMonth[$key] ?? 0))
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->values()
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->all();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerReceivableTrendValues = $ownerMonthKeys
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->map(fn (string $key) => (int) ($receivablesByMonth[$key] ?? 0))
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->values()
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->all();

        // Menampilkan produk terlaris berdasarkan kuantitas terjual.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerTopProducts = DetailPenjualan::query()
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->selectRaw('produks.id, produks.nama as product_name, SUM(detail_penjualans.qty) as qty_sold, SUM(detail_penjualans.subtotal) as total_sales')
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->join('penjualans', 'penjualans.id', '=', 'detail_penjualans.penjualan_id')
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
            // Menambahkan kondisi filter pada query data.
            ->whereBetween('penjualans.tanggal', [$ownerTrendStart, $ownerTrendEnd])
            // Mengelompokkan data agar dapat dihitung atau diringkas per kategori tertentu.
            ->groupBy('produks.id', 'produks.nama')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('qty_sold')
            // Membatasi jumlah data yang diambil agar tampilan tetap ringkas.
            ->limit(5)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menampilkan piutang tertua agar owner tahu tagihan mana yang perlu diprioritaskan.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $ownerOldestReceivables = Piutang::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('transaction.customer')
            // Menambahkan kondisi filter pada query data.
            ->where('status', 'belum')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByRaw('COALESCE(jatuh_tempo, created_at) asc')
            // Membatasi jumlah data yang diambil agar tampilan tetap ringkas.
            ->limit(5)
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Semua variabel dikirim ke halaman dashboard untuk dirender sesuai kartu dan grafiknya.
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('dashboard', compact(
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'todaySales',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'todayTransactionCount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalProducts',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalCustomers',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'outstandingReceivables',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'thisMonthSales',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'lastMonthSales',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'salesGrowthPercent',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'latestTransactions',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'lowStockProducts',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'outOfStockCount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'overdueReceivablesCount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'overdueReceivablesAmount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'overdueReceivables',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'ownerTrendLabels',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'ownerSalesTrendValues',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'ownerReceivableTrendValues',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'ownerTopProducts',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'ownerOldestReceivables'
        // Baris ini merupakan bagian dari logika proses pada file ini.
        ));
    // Menutup blok kode.
    }
// Menutup blok kode.
}
