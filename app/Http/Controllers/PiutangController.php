<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pelanggan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Piutang;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;

// Mendefinisikan class sebagai wadah logika pada file ini.
class PiutangController extends Controller
// Membuka blok kode.
{
    // Menampilkan daftar piutang beserta fitur filter dan ringkasannya.
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index(Request $request)
    // Membuka blok kode.
    {
        // Validasi memastikan filter yang masuk aman dan sesuai format.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => ['nullable', 'string', 'max:100'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => ['nullable', 'in:unpaid,paid'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'overdue_only' => ['nullable', 'in:1'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Relasi transaksi dan pelanggan dimuat agar data siap ditampilkan di view.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $receivablesQuery = Piutang::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('transaction.customer');

        // Filter status dipakai untuk membedakan piutang lunas dan belum lunas.
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['status'])) {
            // Menambahkan kondisi filter pada query data.
            $receivablesQuery->where('status', $validated['status'] === 'paid' ? 'lunas' : 'belum');
        // Menutup blok kode.
        }

        // Filter pelanggan membatasi data piutang berdasarkan pelanggan tertentu.
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['customer_id'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $customerId = (int) $validated['customer_id'];
            // Menambahkan kondisi filter pada query data.
            $receivablesQuery->whereHas('transaction', function ($query) use ($customerId) {
                // Menambahkan kondisi filter pada query data.
                $query->where('pelanggan_id', $customerId);
            // Menutup struktur atau rangkaian proses pada blok sebelumnya.
            });
        // Menutup blok kode.
        }

        // Filter ini hanya menampilkan piutang yang sudah melewati jatuh tempo.
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['overdue_only'])) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $receivablesQuery
                // Menambahkan kondisi filter pada query data.
                ->where('status', 'belum')
                // Menambahkan kondisi filter pada query data.
                ->whereDate('jatuh_tempo', '<', now()->toDateString());
        // Menutup blok kode.
        }

        // Pencarian bisa dilakukan berdasarkan nama pelanggan atau id penjualan.
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['q'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $search = trim($validated['q']);
            // Menambahkan kondisi filter pada query data.
            $receivablesQuery->where(function ($query) use ($search) {
                // Menambahkan kondisi filter pada query data.
                $query->whereHas('transaction.customer', function ($customerQuery) use ($search) {
                    // Menambahkan kondisi filter pada query data.
                    $customerQuery->where('nama', 'like', '%'.$search.'%');
                // Menutup struktur atau rangkaian proses pada blok sebelumnya.
                });

                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if (is_numeric($search)) {
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    $query->orWhere('penjualan_id', (int) $search);
                // Menutup blok kode.
                }
            // Menutup struktur atau rangkaian proses pada blok sebelumnya.
            });
        // Menutup blok kode.
        }

        // Data diurutkan agar piutang yang perlu perhatian lebih mudah terlihat.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $receivables = $receivablesQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('status')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('created_at')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Daftar pelanggan dipakai untuk dropdown filter pada halaman piutang.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $customers = Pelanggan::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Ringkasan ini dipakai sebagai kartu statistik pada halaman piutang.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $summaryUnpaidAmount = $receivables
            // Menambahkan kondisi filter pada query data.
            ->where('status', 'unpaid')
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('amount');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $summaryOverdueAmount = $receivables
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ->filter(fn (Piutang $receivable) => $receivable->status === 'unpaid'
                // Baris ini merupakan bagian dari logika proses pada file ini.
                && $receivable->due_date
                // Baris ini merupakan bagian dari logika proses pada file ini.
                && $receivable->due_date->lt(now()))
            // Menjumlahkan nilai data sesuai kebutuhan perhitungan.
            ->sum('amount');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $summaryPaidCount = $receivables
            // Menambahkan kondisi filter pada query data.
            ->where('status', 'paid')
            // Menghitung jumlah data yang sesuai dengan kondisi query.
            ->count();

        // Nilai filter disimpan agar form tetap menampilkan pilihan pengguna sebelumnya.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $filters = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => $validated['q'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => $validated['status'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'customer_id' => $validated['customer_id'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'overdue_only' => $validated['overdue_only'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Semua data dikirim ke view index untuk ditampilkan ke kasir.
        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('piutang.index', compact(
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'receivables',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'customers',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'summaryUnpaidAmount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'summaryOverdueAmount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'summaryPaidCount',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'filters'
        // Baris ini merupakan bagian dari logika proses pada file ini.
        ));
    // Menutup blok kode.
    }

    // Menampilkan detail satu piutang yang akan diedit status pembayarannya.
    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Piutang $receivable)
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $receivable->load('transaction.customer');

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('piutang.edit', compact('receivable'));
    // Menutup blok kode.
    }

    // Memperbarui status piutang, lalu mencatat waktu dan pengguna yang melunasi.
    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, Piutang $receivable)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => 'required|in:unpaid,paid',
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Jika status diubah menjadi lunas, sistem otomatis menyimpan waktu dan petugasnya.
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $payload = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => $validated['status'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'paid_at' => $validated['status'] === 'paid' ? now() : null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'paid_by' => $validated['status'] === 'paid' ? auth()->id() : null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Memperbarui data yang sudah ada di database.
        $receivable->update($payload);

        // Setelah berhasil, pengguna diarahkan kembali ke daftar piutang.
        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('receivables.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Status piutang berhasil diperbarui.');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
