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

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DasborController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', ManajemenPenggunaController::class)->except(['show']);
    Route::patch('/users/{user}/toggle-status', [ManajemenPenggunaController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    Route::resource('kategoris', KategoriController::class)->except(['show']);
    Route::resource('suppliers', PemasokController::class)->except(['show']);
    Route::resource('products', ProdukController::class)->except(['show']);
    Route::resource('customers', PelangganController::class)->except(['show']);
    Route::resource('expense_categories', KategoriPengeluaranController::class)->except(['show']);
    Route::resource('expenses', PengeluaranController::class)->except(['show']);

    Route::resource('stok_histories', RiwayatStokController::class)->except(['show']);
});

Route::middleware(['auth', 'role:kasir'])->group(function () {
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

Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::get('/reports/receivables', [LaporanController::class, 'receivables'])
        ->name('reports.receivables');
    Route::get('/reports/stock', [LaporanController::class, 'stock'])
        ->name('reports.stock');
    Route::get('/reports/expenses', [LaporanController::class, 'expenses'])
        ->name('reports.expenses');
    Route::get('/reports/sales', [LaporanController::class, 'sales'])
        ->name('reports.sales');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfilController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfilController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
