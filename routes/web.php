<?php

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\PelangganController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\DasborController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\KategoriPengeluaranController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\PengeluaranController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\KategoriController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\ProdukController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\ProfilController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\PiutangController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\LaporanController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\CekStokController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\RiwayatStokController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\PemasokController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\PenjualanController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\ManajemenPenggunaController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Route;

// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::get('/', function () {
    // Mengalihkan pengguna ke halaman lain setelah proses selesai.
    return redirect()->route('dashboard');
// Menutup struktur atau rangkaian proses pada blok sebelumnya.
});

// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::get('/dashboard', [DasborController::class, 'index'])
    // Menambahkan middleware untuk membatasi atau memeriksa akses pengguna.
    ->middleware(['auth'])
    // Memberikan nama route agar mudah dipanggil dari controller atau view.
    ->name('dashboard');

// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('users', ManajemenPenggunaController::class)->except(['show']);
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::patch('/users/{user}/toggle-status', [ManajemenPenggunaController::class, 'toggleStatus'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('users.toggle-status');

    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('kategoris', KategoriController::class)->except(['show']);
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('suppliers', PemasokController::class)->except(['show']);
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('products', ProdukController::class)->except(['show']);
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('customers', PelangganController::class)->except(['show']);
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('expense_categories', KategoriPengeluaranController::class)->except(['show']);
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('expenses', PengeluaranController::class)->except(['show']);

    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('stok_histories', RiwayatStokController::class)->except(['show']);
// Menutup struktur atau rangkaian proses pada blok sebelumnya.
});

// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::middleware(['auth', 'role:kasir'])->group(function () {
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::get('/stocks/check', [CekStokController::class, 'index'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('stocks.check');

    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('transactions', PenjualanController::class)->only([
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'index',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'show',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'create',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'store',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ]);

    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::resource('receivables', PiutangController::class)->only([
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'index',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'edit',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'update',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ]);
// Menutup struktur atau rangkaian proses pada blok sebelumnya.
});

// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::get('/reports/receivables', [LaporanController::class, 'receivables'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('reports.receivables');
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::get('/reports/stock', [LaporanController::class, 'stock'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('reports.stock');
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::get('/reports/expenses', [LaporanController::class, 'expenses'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('reports.expenses');
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::get('/reports/sales', [LaporanController::class, 'sales'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('reports.sales');
// Menutup struktur atau rangkaian proses pada blok sebelumnya.
});

// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::middleware('auth')->group(function () {
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::get('/profile', [ProfilController::class, 'edit'])->name('profile.edit');
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::patch('/profile', [ProfilController::class, 'update'])->name('profile.update');
// Menutup struktur atau rangkaian proses pada blok sebelumnya.
});

// Baris ini merupakan bagian dari logika proses pada file ini.
require __DIR__.'/auth.php';
