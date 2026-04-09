<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers\Autentikasi;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\Controller;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Requests\Autentikasi\PermintaanMasuk;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\RedirectResponse;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Auth;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\View\View;

// Mendefinisikan class sebagai wadah logika pada file ini.
class SesiMasukController extends Controller
// Membuka blok kode.
{
    /**
     * Display the login view.
     */
    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create(): View
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('autentikasi.masuk');
    // Menutup blok kode.
    }

    /**
     * Handle an incoming authentication request.
     */
    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(PermintaanMasuk $request): RedirectResponse
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $request->authenticate();

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $request->session()->regenerate();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->intended(route('dashboard', absolute: false));
    // Menutup blok kode.
    }

    /**
     * Destroy an authenticated session.
     */
    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(Request $request): RedirectResponse
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        Auth::guard('web')->logout();

        // Memvalidasi data masukan sebelum diproses lebih lanjut.
        $request->session()->invalidate();

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $request->session()->regenerateToken();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect('/');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
