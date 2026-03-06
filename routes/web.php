<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
});

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::resource('transactions', TransactionController::class)->only([
        'index',
        'create',
        'store',
    ]);

    Route::resource('receivables', ReceivableController::class)->only([
        'index',
        'edit',
        'update',
    ]);
});

Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::get('/reports/sales', [ReportController::class, 'sales'])
        ->name('reports.sales');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
