<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pemasok;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;

// Mendefinisikan class sebagai wadah logika pada file ini.
class PemasokController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $suppliers = Pemasok::query()
            // Menghitung jumlah relasi agar bisa ditampilkan tanpa query tambahan.
            ->withCount('stokHistories')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pemasok.index', compact('suppliers'));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pemasok.create');
    // Menutup blok kode.
    }

    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nama' => ['required', 'string', 'max:150'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'telp' => ['nullable', 'string', 'max:20'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'alamat' => ['nullable', 'string', 'max:255'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'keterangan' => ['nullable', 'string', 'max:255'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'aktif' => ['nullable', 'boolean'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $validated['aktif'] = $request->boolean('aktif', true);

        // Menyimpan data baru ke database melalui model yang terkait.
        Pemasok::create($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('suppliers.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Pemasok berhasil ditambahkan.');
    // Menutup blok kode.
    }

    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Pemasok $supplier)
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pemasok.edit', compact('supplier'));
    // Menutup blok kode.
    }

    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, Pemasok $supplier)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nama' => ['required', 'string', 'max:150'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'telp' => ['nullable', 'string', 'max:20'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'alamat' => ['nullable', 'string', 'max:255'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'keterangan' => ['nullable', 'string', 'max:255'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'aktif' => ['nullable', 'boolean'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $validated['aktif'] = $request->boolean('aktif', false);

        // Memperbarui data yang sudah ada di database.
        $supplier->update($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('suppliers.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Pemasok berhasil diperbarui.');
    // Menutup blok kode.
    }

    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(Pemasok $supplier)
    // Membuka blok kode.
    {
        // Menghapus data dari database.
        $supplier->delete();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('suppliers.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Pemasok berhasil dihapus.');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
