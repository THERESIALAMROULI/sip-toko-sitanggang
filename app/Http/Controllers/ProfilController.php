<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Requests\PerbaruiProfilRequest;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\RedirectResponse;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Redirect;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\View\View;

// Mendefinisikan class sebagai wadah logika pada file ini.
class ProfilController extends Controller
// Membuka blok kode.
{
    /**
     * Display the user's profile form.
     */
    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Request $request): View
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('profil.edit', [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'user' => $request->user(),
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);
    // Menutup blok kode.
    }

    /**
     * Update the user's profile information.
     */
    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(PerbaruiProfilRequest $request): RedirectResponse
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $request->user()->fill($request->validated());

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $request->user()->save();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
