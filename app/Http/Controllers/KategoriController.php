<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Kategori;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Validation\Rule;

// Mendefinisikan class sebagai wadah logika pada file ini.
class KategoriController extends Controller
// Membuka blok kode.
{
    // Menampilkan seluruh kategori beserta jumlah produk pada tiap kategori.
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $kategoris = Kategori::query()
            // Menghitung jumlah relasi agar bisa ditampilkan tanpa query tambahan.
            ->withCount('products')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('kategoris.index', compact('kategoris'));
    // Menutup blok kode.
    }

    // Menampilkan form tambah kategori.
    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('kategoris.create');
    // Menutup blok kode.
    }

    // Menyimpan kategori baru setelah data lolos validasi.
    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nama' => ['required', 'string', 'max:100', 'unique:kategoris,nama'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan data baru ke database melalui model yang terkait.
        Kategori::create($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('kategoris.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Kategori berhasil ditambahkan.');
    // Menutup blok kode.
    }

    // Menampilkan form edit untuk kategori yang dipilih.
    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Kategori $kategori)
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('kategoris.edit', compact('kategori'));
    // Menutup blok kode.
    }

    // Memperbarui data kategori dan menjaga agar nama tetap unik.
    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, Kategori $kategori)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nama' => [
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'required',
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'string',
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'max:100',
                // Baris ini merupakan bagian dari logika proses pada file ini.
                Rule::unique('kategoris', 'nama')->ignore($kategori->id),
            // Menandai bagian dari struktur array yang digunakan pada proses ini.
            ],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Memperbarui data yang sudah ada di database.
        $kategori->update($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('kategoris.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Kategori berhasil diperbarui.');
    // Menutup blok kode.
    }

    // Menghapus kategori hanya jika belum dipakai oleh produk mana pun.
    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(Kategori $kategori)
    // Membuka blok kode.
    {
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if ($kategori->products()->exists()) {
            // Mengalihkan pengguna ke halaman lain setelah proses selesai.
            return redirect()->route('kategoris.index')
                // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai produk.');
        // Menutup blok kode.
        }

        // Menghapus data dari database.
        $kategori->delete();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('kategoris.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Kategori berhasil dihapus.');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
