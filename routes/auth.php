<?php

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\Autentikasi\KataSandiController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\Autentikasi\SesiMasukController;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Route;

// Route pada grup guest hanya bisa diakses saat pengguna belum login.
// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::middleware('guest')->group(function () {
    // Menampilkan halaman login.
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::get('login', [SesiMasukController::class, 'create'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('login');

    // Memproses data login yang dikirim dari form.
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::post('login', [SesiMasukController::class, 'store']);
// Menutup struktur atau rangkaian proses pada blok sebelumnya.
});

// Route pada grup auth hanya bisa diakses oleh pengguna yang sudah login.
// Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
Route::middleware('auth')->group(function () {
    // Memproses perubahan password akun yang sedang aktif.
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::put('password', [KataSandiController::class, 'update'])->name('password.update');

    // Mengakhiri sesi login pengguna.
    // Mendaftarkan route agar URL dapat diarahkan ke logika yang sesuai.
    Route::post('logout', [SesiMasukController::class, 'destroy'])
        // Memberikan nama route agar mudah dipanggil dari controller atau view.
        ->name('logout');
// Menutup struktur atau rangkaian proses pada blok sebelumnya.
});
