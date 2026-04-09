<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers\Autentikasi;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Controllers\Controller;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\RedirectResponse;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Hash;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Validation\Rules\Password;

// Mendefinisikan class sebagai wadah logika pada file ini.
class KataSandiController extends Controller
// Membuka blok kode.
{
    /**
     * Update the user's password.
     */
    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request): RedirectResponse
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validateWithBag('updatePassword', [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'current_password' => ['required', 'current_password'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'password' => ['required', Password::defaults(), 'confirmed'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Memperbarui data yang sudah ada di database.
        $request->user()->update([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'password' => Hash::make($validated['password']),
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Mengembalikan pengguna ke halaman sebelumnya dengan membawa status proses.
        return back()->with('status', 'password-updated');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
