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
class CekStokController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => ['nullable', 'string', 'max:100'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'kategori_id' => ['nullable', 'integer', 'exists:kategoris,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => ['nullable', 'in:normal,low,out,inactive'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $productsQuery = Produk::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('kategori');

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['q'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $search = trim($validated['q']);
            // Menambahkan kondisi filter pada query data.
            $productsQuery->where('nama', 'like', '%'.$search.'%');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['kategori_id'])) {
            // Menambahkan kondisi filter pada query data.
            $productsQuery->where('kategori_id', (int) $validated['kategori_id']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['status'])) {
            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($validated['status'] === 'inactive') {
                // Menambahkan kondisi filter pada query data.
                $productsQuery->where('aktif', false);
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } elseif ($validated['status'] === 'out') {
                // Menambahkan kondisi filter pada query data.
                $productsQuery->where('aktif', true)->where('stok', '<=', 0);
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } elseif ($validated['status'] === 'low') {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $productsQuery
                    // Menambahkan kondisi filter pada query data.
                    ->where('aktif', true)
                    // Menambahkan kondisi filter pada query data.
                    ->where('stok', '>', 0)
                    // Menambahkan kondisi filter pada query data.
                    ->whereColumn('stok', '<=', 'stok_minimum');
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } elseif ($validated['status'] === 'normal') {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $productsQuery
                    // Menambahkan kondisi filter pada query data.
                    ->where('aktif', true)
                    // Menambahkan kondisi filter pada query data.
                    ->whereColumn('stok', '>', 'stok_minimum');
            // Menutup blok kode.
            }
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $products = $productsQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $kategoris = Kategori::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $filters = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => $validated['q'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'kategori_id' => $validated['kategori_id'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => $validated['status'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('stok.check', compact('products', 'kategoris', 'filters'));
    // Menutup blok kode.
    }
// Menutup blok kode.
}
