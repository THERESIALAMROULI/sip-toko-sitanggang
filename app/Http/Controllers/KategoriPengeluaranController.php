<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\KategoriPengeluaran;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Validation\Rule;

// Mendefinisikan class sebagai wadah logika pada file ini.
class KategoriPengeluaranController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenseCategories = KategoriPengeluaran::query()
            // Menghitung jumlah relasi agar bisa ditampilkan tanpa query tambahan.
            ->withCount('expenses')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('kategori_pengeluaran.index', compact('expenseCategories'));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('kategori_pengeluaran.create');
    // Menutup blok kode.
    }

    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nama' => ['required', 'string', 'max:100', 'unique:expense_categories,nama'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'deskripsi' => ['nullable', 'string', 'max:255'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'aktif' => ['nullable', 'boolean'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $validated['aktif'] = $request->boolean('aktif', true);

        // Menyimpan data baru ke database melalui model yang terkait.
        KategoriPengeluaran::create($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('expense_categories.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Kategori biaya berhasil ditambahkan.');
    // Menutup blok kode.
    }

    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(KategoriPengeluaran $expenseCategory)
    // Membuka blok kode.
    {
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('kategori_pengeluaran.edit', compact('expenseCategory'));
    // Menutup blok kode.
    }

    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, KategoriPengeluaran $expenseCategory)
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
                Rule::unique('expense_categories', 'nama')->ignore($expenseCategory->id),
            // Menandai bagian dari struktur array yang digunakan pada proses ini.
            ],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'deskripsi' => ['nullable', 'string', 'max:255'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'aktif' => ['nullable', 'boolean'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        $validated['aktif'] = $request->boolean('aktif', false);

        // Memperbarui data yang sudah ada di database.
        $expenseCategory->update($validated);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('expense_categories.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Kategori biaya berhasil diperbarui.');
    // Menutup blok kode.
    }

    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(KategoriPengeluaran $expenseCategory)
    // Membuka blok kode.
    {
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if ($expenseCategory->expenses()->exists()) {
            // Mengalihkan pengguna ke halaman lain setelah proses selesai.
            return redirect()->route('expense_categories.index')
                // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
                ->with('error', 'Kategori biaya tidak bisa dihapus karena sudah dipakai di data biaya.');
        // Menutup blok kode.
        }

        // Menghapus data dari database.
        $expenseCategory->delete();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('expense_categories.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Kategori biaya berhasil dihapus.');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
