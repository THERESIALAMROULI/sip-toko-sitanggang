<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Kategori;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Produk;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;

// Mendefinisikan class sebagai wadah logika pada file ini.
class ProdukController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $products = Produk::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('kategori')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('produk.index', compact('products'));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $kategoris = Kategori::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('produk.create', compact('kategoris'));
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
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'harga_beli' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'harga_jual' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'stok' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'stok_minimum' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'aktif' => ['nullable', 'boolean'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $validated['aktif'] = $request->boolean('aktif', true);

        // Menyimpan data baru ke database melalui model yang terkait.
        Produk::create($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('products.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Produk berhasil ditambahkan');
    // Menutup blok kode.
    }

    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Produk $product)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $kategoris = Kategori::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('produk.edit', compact('product', 'kategoris'));
    // Menutup blok kode.
    }

    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, Produk $product)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nama' => ['required', 'string', 'max:150'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'harga_beli' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'harga_jual' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'stok' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'stok_minimum' => ['required', 'integer', 'min:0'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'aktif' => ['nullable', 'boolean'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $validated['aktif'] = $request->boolean('aktif', false);

        // Memperbarui data yang sudah ada di database.
        $product->update($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('products.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Produk berhasil diperbarui');
    // Menutup blok kode.
    }

    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(Produk $product)
    // Membuka blok kode.
    {
        // Menghapus data dari database.
        $product->delete();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('products.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Produk berhasil dihapus');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
