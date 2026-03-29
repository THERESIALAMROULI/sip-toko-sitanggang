<?php

use App\Http\Controllers\Autentikasi\KonfirmasiKataSandiController;
use App\Http\Controllers\Autentikasi\NotifikasiVerifikasiSurelController;
use App\Http\Controllers\Autentikasi\PromptVerifikasiSurelController;
use App\Http\Controllers\Autentikasi\KataSandiController;
use App\Http\Controllers\Autentikasi\SesiMasukController;
use App\Http\Controllers\Autentikasi\VerifikasiSurelController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [SesiMasukController::class, 'create'])
        ->name('login');

    Route::post('login', [SesiMasukController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', PromptVerifikasiSurelController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifikasiSurelController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [NotifikasiVerifikasiSurelController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [KonfirmasiKataSandiController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [KonfirmasiKataSandiController::class, 'store']);

    Route::put('password', [KataSandiController::class, 'update'])->name('password.update');

    Route::post('logout', [SesiMasukController::class, 'destroy'])
        ->name('logout');
});
