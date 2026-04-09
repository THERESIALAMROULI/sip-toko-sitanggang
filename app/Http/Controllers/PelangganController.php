<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pelanggan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;

// Mendefinisikan class sebagai wadah logika pada file ini.
class PelangganController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $customers = Pelanggan::query()->orderBy('nama')->get();
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pelanggan.index', compact('customers'));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pelanggan.create');
    // Menutup blok kode.
    }

    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'name' => 'required|string|max:120',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'phone' => 'required|string|max:25',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'address' => 'nullable|string|max:1000',
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan data baru ke database melalui model yang terkait.
        Pelanggan::create($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('customers.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Pelanggan berhasil ditambahkan');
    // Menutup blok kode.
    }

    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Pelanggan $customer)
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pelanggan.edit', compact('customer'));
    // Menutup blok kode.
    }

    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, Pelanggan $customer)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'name' => 'required|string|max:120',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'phone' => 'required|string|max:25',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'address' => 'nullable|string|max:1000',
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Memperbarui data yang sudah ada di database.
        $customer->update($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('customers.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Pelanggan berhasil diperbarui');
    // Menutup blok kode.
    }

    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(Pelanggan $customer)
    // Membuka blok kode.
    {
        // Menghapus data dari database.
        $customer->delete();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('customers.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Pelanggan berhasil dihapus');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
