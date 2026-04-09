<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Produk;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\RiwayatStok;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pemasok;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\DB;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Validation\ValidationException;

// Mendefinisikan class sebagai wadah logika pada file ini.
class RiwayatStokController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'produk_id' => ['nullable', 'integer', 'exists:produks,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => ['nullable', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $stokHistoriesQuery = RiwayatStok::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with(['produk', 'supplier', 'user']);

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['produk_id'])) {
            // Menambahkan kondisi filter pada query data.
            $stokHistoriesQuery->where('produk_id', $validated['produk_id']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['supplier_id'])) {
            // Menambahkan kondisi filter pada query data.
            $stokHistoriesQuery->where('supplier_id', $validated['supplier_id']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $stokHistoriesQuery->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $stokHistoriesQuery->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $stokHistories = $stokHistoriesQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('tanggal')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $products = Produk::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $suppliers = Pemasok::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $filters = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'produk_id' => $validated['produk_id'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'supplier_id' => $validated['supplier_id'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => $validated['start_date'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => $validated['end_date'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('stok_histories.index', compact('stokHistories', 'products', 'suppliers', 'filters'));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $products = Produk::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $suppliers = Pemasok::query()
            // Menambahkan kondisi filter pada query data.
            ->where('aktif', true)
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('stok_histories.create', compact('products', 'suppliers'));
    // Menutup blok kode.
    }

    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'jenis' => ['required', 'in:masuk,koreksi_tambah,koreksi_kurang'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'jumlah' => ['required', 'integer', 'min:1'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'keterangan' => ['nullable', 'string', 'max:255'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        DB::transaction(function () use ($validated) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $product = Produk::query()
                // Menambahkan kondisi filter pada query data.
                ->whereKey($validated['produk_id'])
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->lockForUpdate()
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->firstOrFail();

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $signedQty = $this->signedQty($validated['jenis'], (int) $validated['jumlah']);

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $stokSebelum = (int) $product->stok;
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $stokSesudah = $stokSebelum + $signedQty;

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($stokSesudah < 0) {
                // Melempar exception ketika terjadi kondisi yang tidak valid.
                throw ValidationException::withMessages([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'jumlah' => 'Koreksi mengurangi stok melebihi stok yang tersedia.',
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Menutup blok kode.
            }

            // Memperbarui data yang sudah ada di database.
            $product->update([
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'stok' => $stokSesudah,
            // Menandai bagian dari struktur array yang digunakan pada proses ini.
            ]);

            // Menyimpan data baru ke database melalui model yang terkait.
            RiwayatStok::create([
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'produk_id' => $product->id,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'supplier_id' => $validated['supplier_id'] ?? null,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'user_id' => auth()->id() ?? 1,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'jumlah' => $signedQty,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'stok_sebelum' => $stokSebelum,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'stok_sesudah' => $stokSesudah,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'keterangan' => $validated['keterangan'] ?? null,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'tanggal' => now(),
            // Menandai bagian dari struktur array yang digunakan pada proses ini.
            ]);
        // Menutup struktur atau rangkaian proses pada blok sebelumnya.
        });

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('stok_histories.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Mutasi stok berhasil disimpan.');
    // Menutup blok kode.
    }

    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(RiwayatStok $stokHistory)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $products = Produk::query()
            // Menambahkan kondisi filter pada query data.
            ->where(function ($query) use ($stokHistory) {
                // Menambahkan kondisi filter pada query data.
                $query->where('aktif', true)
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->orWhere('id', $stokHistory->produk_id);
            // Baris ini merupakan bagian dari logika proses pada file ini.
            })
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $suppliers = Pemasok::query()
            // Menambahkan kondisi filter pada query data.
            ->where(function ($query) use ($stokHistory) {
                // Menambahkan kondisi filter pada query data.
                $query->where('aktif', true)
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->orWhere('id', $stokHistory->supplier_id);
            // Baris ini merupakan bagian dari logika proses pada file ini.
            })
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('stok_histories.edit', compact('stokHistory', 'products', 'suppliers'));
    // Menutup blok kode.
    }

    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, RiwayatStok $stokHistory)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'jenis' => ['required', 'in:masuk,koreksi_tambah,koreksi_kurang'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'jumlah' => ['required', 'integer', 'min:1'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'keterangan' => ['nullable', 'string', 'max:255'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Baris ini merupakan bagian dari logika proses pada file ini.
        DB::transaction(function () use ($validated, $stokHistory) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $history = RiwayatStok::query()
                // Menambahkan kondisi filter pada query data.
                ->whereKey($stokHistory->id)
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->lockForUpdate()
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->firstOrFail();

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $oldProduct = Produk::query()
                // Menambahkan kondisi filter pada query data.
                ->whereKey($history->produk_id)
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->lockForUpdate()
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->firstOrFail();

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $oldSignedQty = (int) $history->jumlah;
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $revertedOldStock = (int) $oldProduct->stok - $oldSignedQty;

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($revertedOldStock < 0) {
                // Melempar exception ketika terjadi kondisi yang tidak valid.
                throw ValidationException::withMessages([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'jumlah' => 'Data stok saat ini tidak konsisten, perubahan tidak dapat diproses.',
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Menutup blok kode.
            }

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $newSignedQty = $this->signedQty($validated['jenis'], (int) $validated['jumlah']);

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ((int) $validated['produk_id'] === (int) $history->produk_id) {
                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $newStock = $revertedOldStock + $newSignedQty;

                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if ($newStock < 0) {
                    // Melempar exception ketika terjadi kondisi yang tidak valid.
                    throw ValidationException::withMessages([
                        // Baris ini merupakan bagian dari logika proses pada file ini.
                        'jumlah' => 'Koreksi mengurangi stok melebihi stok yang tersedia.',
                    // Menandai bagian dari struktur array yang digunakan pada proses ini.
                    ]);
                // Menutup blok kode.
                }

                // Memperbarui data yang sudah ada di database.
                $oldProduct->update(['stok' => $newStock]);

                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $stokSebelum = $revertedOldStock;
                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $stokSesudah = $newStock;
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } else {
                // Memperbarui data yang sudah ada di database.
                $oldProduct->update(['stok' => $revertedOldStock]);

                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $newProduct = Produk::query()
                    // Menambahkan kondisi filter pada query data.
                    ->whereKey($validated['produk_id'])
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->lockForUpdate()
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->firstOrFail();

                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $stokSebelum = (int) $newProduct->stok;
                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $stokSesudah = $stokSebelum + $newSignedQty;

                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if ($stokSesudah < 0) {
                    // Melempar exception ketika terjadi kondisi yang tidak valid.
                    throw ValidationException::withMessages([
                        // Baris ini merupakan bagian dari logika proses pada file ini.
                        'jumlah' => 'Koreksi mengurangi stok melebihi stok yang tersedia pada produk terpilih.',
                    // Menandai bagian dari struktur array yang digunakan pada proses ini.
                    ]);
                // Menutup blok kode.
                }

                // Memperbarui data yang sudah ada di database.
                $newProduct->update(['stok' => $stokSesudah]);
            // Menutup blok kode.
            }

            // Memperbarui data yang sudah ada di database.
            $history->update([
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'produk_id' => (int) $validated['produk_id'],
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'supplier_id' => $validated['supplier_id'] ?? null,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'jumlah' => $newSignedQty,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'stok_sebelum' => $stokSebelum,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'stok_sesudah' => $stokSesudah,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'keterangan' => $validated['keterangan'] ?? null,
            // Menandai bagian dari struktur array yang digunakan pada proses ini.
            ]);
        // Menutup struktur atau rangkaian proses pada blok sebelumnya.
        });

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('stok_histories.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Mutasi stok berhasil diperbarui.');
    // Menutup blok kode.
    }

    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(RiwayatStok $stokHistory)
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        DB::transaction(function () use ($stokHistory) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $history = RiwayatStok::query()
                // Menambahkan kondisi filter pada query data.
                ->whereKey($stokHistory->id)
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->lockForUpdate()
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->firstOrFail();

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $product = Produk::query()
                // Menambahkan kondisi filter pada query data.
                ->whereKey($history->produk_id)
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->lockForUpdate()
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ->firstOrFail();

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $revertedStock = (int) $product->stok - (int) $history->jumlah;

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($revertedStock < 0) {
                // Melempar exception ketika terjadi kondisi yang tidak valid.
                throw ValidationException::withMessages([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'jumlah' => 'Mutasi tidak dapat dihapus karena menyebabkan stok produk menjadi negatif.',
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Menutup blok kode.
            }

            // Memperbarui data yang sudah ada di database.
            $product->update(['stok' => $revertedStock]);
            // Menghapus data dari database.
            $history->delete();
        // Menutup struktur atau rangkaian proses pada blok sebelumnya.
        });

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('stok_histories.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Mutasi stok berhasil dihapus.');
    // Menutup blok kode.
    }

    // Mendefinisikan method signedQty untuk menjalankan proses tertentu.
    private function signedQty(string $jenis, int $jumlah): int
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $jenis === 'koreksi_kurang' ? -1 * $jumlah : $jumlah;
    // Menutup blok kode.
    }
// Menutup blok kode.
}
