<?php
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\DasborController;
use App\Http\Controllers\KategoriPengeluaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CekStokController;
use App\Http\Controllers\RiwayatStokController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ManajemenPenggunaController;
use Illuminate\Support\Facades\Route;
// Halaman awal langsung diarahkan ke dashboard agar pengguna masuk ke modul utama sistem.
Route::get('/', function () {
    return redirect()->route('dashboard');
});
// Dashboard menampilkan ringkasan operasional toko setelah pengguna login.
Route::get('/dashboard', [DasborController::class, 'index'])
    ->middleware(['auth', 'page.cache'])
    ->name('dashboard');
// Modul admin berisi pengelolaan data master yang menjadi fondasi proses bisnis toko.
Route::middleware(['auth', 'role:admin', 'page.cache'])->group(function () {
    Route::resource('users', ManajemenPenggunaController::class)->except(['show']);
    Route::patch('/users/{user}/toggle-status', [ManajemenPenggunaController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::resource('kategoris', KategoriController::class)->except(['show']);
    Route::resource('suppliers', PemasokController::class)->except(['show']);
    Route::resource('products', ProdukController::class)->except(['show']);
    Route::resource('customers', PelangganController::class)->except(['show']);
    Route::resource('expense_categories', KategoriPengeluaranController::class)->except(['show']);
    Route::resource('expenses', PengeluaranController::class)->except(['show']);
    Route::get('stok_histories/{stokHistory}/correction', [RiwayatStokController::class, 'createCorrection'])
        ->name('stok_histories.correction');
    Route::resource('stok_histories', RiwayatStokController::class)->only([
        'index',
        'create',
        'store',
    ]);
});
// Modul kasir fokus pada proses operasional harian: cek stok, transaksi, dan pelunasan piutang.
Route::middleware(['auth', 'role:kasir', 'page.cache'])->group(function () {
    Route::get('/stocks/check', [CekStokController::class, 'index'])
        ->name('stocks.check');
    Route::resource('transactions', PenjualanController::class)->only([
        'index',
        'show',
        'create',
        'store',
    ]);
    Route::resource('receivables', PiutangController::class)->only([
        'index',
        'edit',
        'update',
    ]);
});
// Laporan stok tetap dipakai admin dan owner untuk keputusan persediaan harian.
Route::middleware(['auth', 'role:admin,owner', 'page.cache'])->group(function () {
    Route::get('/reports/stock', [LaporanController::class, 'stock'])
        ->name('reports.stock');
});
// Laporan strategis lain dipusatkan di owner agar fokus admin tetap operasional.
Route::middleware(['auth', 'role:owner', 'page.cache'])->group(function () {
    Route::get('/reports/receivables', [LaporanController::class, 'receivables'])
        ->name('reports.receivables');
    Route::get('/reports/expenses', [LaporanController::class, 'expenses'])
        ->name('reports.expenses');
    Route::get('/reports/sales', [LaporanController::class, 'sales'])
        ->name('reports.sales');
});
// Profil dipakai untuk memperbarui identitas akun pengguna yang sedang login.
Route::middleware(['auth', 'page.cache'])->group(function () {
    Route::get('/profile', [ProfilController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfilController::class, 'update'])->name('profile.update');
});
require __DIR__.'/auth.php';
