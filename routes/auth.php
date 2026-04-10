<?php
use App\Http\Controllers\Autentikasi\KataSandiController;
use App\Http\Controllers\Autentikasi\SesiMasukController;
use Illuminate\Support\Facades\Route;
Route::middleware('guest')->group(function () {
    Route::get('login', [SesiMasukController::class, 'create'])
        ->name('login');
    Route::post('login', [SesiMasukController::class, 'store']);
});
Route::middleware('auth')->group(function () {
    Route::put('password', [KataSandiController::class, 'update'])->name('password.update');
    Route::post('logout', [SesiMasukController::class, 'destroy'])
        ->name('logout');
});
