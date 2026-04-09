<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pengeluaran;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\KategoriPengeluaran;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;

// Mendefinisikan class sebagai wadah logika pada file ini.
class PengeluaranController extends Controller
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
            'expense_category_id' => ['nullable', 'integer', 'exists:expense_categories,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => ['nullable', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expensesQuery = Pengeluaran::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with(['category', 'user']);

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['expense_category_id'])) {
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->where('expense_category_id', (int) $validated['expense_category_id']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->whereDate('tanggal', '>=', $validated['start_date']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->whereDate('tanggal', '<=', $validated['end_date']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['q'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $search = trim($validated['q']);
            // Menambahkan kondisi filter pada query data.
            $expensesQuery->where(function ($query) use ($search) {
                // Menambahkan kondisi filter pada query data.
                $query->where('catatan', 'like', '%'.$search.'%')
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        // Menambahkan kondisi filter pada query data.
                        $categoryQuery->where('nama', 'like', '%'.$search.'%');
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    })
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        // Menambahkan kondisi filter pada query data.
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    // Menutup struktur atau rangkaian proses pada blok sebelumnya.
                    });
            // Menutup struktur atau rangkaian proses pada blok sebelumnya.
            });
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenses = $expensesQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('tanggal')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('id')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $totalExpense = $expenses->sum('nominal');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenseCategories = KategoriPengeluaran::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $filters = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => $validated['q'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expense_category_id' => $validated['expense_category_id'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => $validated['start_date'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => $validated['end_date'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pengeluaran.index', compact(
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expenses',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'totalExpense',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expenseCategories',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'filters'
        // Baris ini merupakan bagian dari logika proses pada file ini.
        ));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenseCategories = KategoriPengeluaran::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pengeluaran.create', compact('expenseCategories'));
    // Menutup blok kode.
    }

    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nominal' => ['required', 'integer', 'min:1'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'tanggal' => ['required', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'catatan' => ['nullable', 'string', 'max:255'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan data baru ke database melalui model yang terkait.
        Pengeluaran::create([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expense_category_id' => (int) $validated['expense_category_id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'user_id' => auth()->id() ?? 1,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nominal' => (int) $validated['nominal'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'tanggal' => $validated['tanggal'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'catatan' => $validated['catatan'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('expenses.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Biaya operasional berhasil ditambahkan.');
    // Menutup blok kode.
    }

    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Pengeluaran $expense)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $expenseCategories = KategoriPengeluaran::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->orWhere('id', $expense->expense_category_id)
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pengeluaran.edit', compact('expense', 'expenseCategories'));
    // Menutup blok kode.
    }

    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, Pengeluaran $expense)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nominal' => ['required', 'integer', 'min:1'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'tanggal' => ['required', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'catatan' => ['nullable', 'string', 'max:255'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Memperbarui data yang sudah ada di database.
        $expense->update([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'expense_category_id' => (int) $validated['expense_category_id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'nominal' => (int) $validated['nominal'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'tanggal' => $validated['tanggal'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'catatan' => $validated['catatan'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('expenses.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Biaya operasional berhasil diperbarui.');
    // Menutup blok kode.
    }

    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(Pengeluaran $expense)
    // Membuka blok kode.
    {
        // Menghapus data dari database.
        $expense->delete();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('expenses.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Biaya operasional berhasil dihapus.');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
