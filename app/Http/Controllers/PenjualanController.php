<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Http\Requests\SimpanTransaksiRequest;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pelanggan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Produk;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Piutang;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Penjualan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\DetailPenjualan;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\DB;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Validation\ValidationException;

// Mendefinisikan class sebagai wadah logika pada file ini.
class PenjualanController extends Controller
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
            'payment_type' => ['nullable', 'in:tunai,utang'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => ['nullable', 'date'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $transactionsQuery = Penjualan::query()
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with(['customer', 'receivable', 'user']);

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['payment_type'])) {
            // Menambahkan kondisi filter pada query data.
            $transactionsQuery->where('metode', $validated['payment_type']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['start_date'])) {
            // Menambahkan kondisi filter pada query data.
            $transactionsQuery->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['end_date'])) {
            // Menambahkan kondisi filter pada query data.
            $transactionsQuery->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['q'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $search = trim($validated['q']);
            // Menambahkan kondisi filter pada query data.
            $transactionsQuery->where(function ($query) use ($search) {
                // Menambahkan kondisi filter pada query data.
                $query->whereHas('customer', function ($customerQuery) use ($search) {
                    // Menambahkan kondisi filter pada query data.
                    $customerQuery->where('nama', 'like', '%'.$search.'%');
                // Menutup struktur atau rangkaian proses pada blok sebelumnya.
                });

                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if (is_numeric($search)) {
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    $query->orWhere('id', (int) $search);
                // Menutup blok kode.
                }
            // Menutup struktur atau rangkaian proses pada blok sebelumnya.
            });
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $transactions = $transactionsQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderByDesc('tanggal')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $filters = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => $validated['q'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'payment_type' => $validated['payment_type'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'start_date' => $validated['start_date'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'end_date' => $validated['end_date'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('penjualan.index', compact('transactions', 'filters'));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $products = Produk::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $customers = Pelanggan::query()
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('nama')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('penjualan.create', compact('products', 'customers'));
    // Menutup blok kode.
    }

    // Mendefinisikan method show untuk menjalankan proses tertentu.
    public function show(Penjualan $transaction)
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $transaction->load(['customer', 'details.product', 'receivable', 'user']);

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('penjualan.show', compact('transaction'));
    // Menutup blok kode.
    }

    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(SimpanTransaksiRequest $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validated();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $transaction = DB::transaction(function () use ($validated) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $cashReceivedInput = isset($validated['cash_received']) && $validated['cash_received'] !== null
                // Baris ini merupakan bagian dari logika proses pada file ini.
                ? (int) $validated['cash_received']
                // Baris ini merupakan bagian dari logika proses pada file ini.
                : null;

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $transaction = Penjualan::create([
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'customer_id' => $validated['customer_id'] ?? null,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'transaction_date' => now(),
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'total' => 0,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'cash_received' => null,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'change_amount' => null,
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'payment_type' => $validated['payment_type'],
                // Baris ini merupakan bagian dari logika proses pada file ini.
                'status' => ($validated['payment_type'] === 'utang') ? 'utang' : 'lunas',
            // Menandai bagian dari struktur array yang digunakan pada proses ini.
            ]);

            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $total = 0;

            // Melakukan perulangan pada setiap data yang tersedia.
            foreach ($validated['products'] as $item) {
                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $quantity = (int) ($item['quantity'] ?? 0);

                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if ($quantity <= 0) {
                    // Melewati iterasi saat ini dan melanjutkan ke iterasi berikutnya.
                    continue;
                // Menutup blok kode.
                }

                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $product = Produk::query()
                    // Menambahkan kondisi filter pada query data.
                    ->whereKey($item['product_id'])
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->lockForUpdate()
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->firstOrFail();

                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if ($product->stock < $quantity) {
                    // Melempar exception ketika terjadi kondisi yang tidak valid.
                    throw ValidationException::withMessages([
                        // Baris ini merupakan bagian dari logika proses pada file ini.
                        'products' => 'Stok produk '.$product->name.' tidak cukup.',
                    // Menandai bagian dari struktur array yang digunakan pada proses ini.
                    ]);
                // Menutup blok kode.
                }

                // Baris ini merupakan bagian dari logika proses pada file ini.
                $product->decrement('stok', $quantity);

                // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
                $subtotal = $product->price * $quantity;
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $total += $subtotal;

                // Menyimpan data baru ke database melalui model yang terkait.
                DetailPenjualan::create([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'transaction_id' => $transaction->id,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'product_id' => $product->id,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'price' => $product->price,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'quantity' => $quantity,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'subtotal' => $subtotal,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'nama_produk' => $product->name,
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Menutup blok kode.
            }

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($total <= 0) {
                // Melempar exception ketika terjadi kondisi yang tidak valid.
                throw ValidationException::withMessages([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'products' => 'Minimal satu item harus memiliki qty lebih dari 0.',
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Menutup blok kode.
            }

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($validated['payment_type'] === 'tunai') {
                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if ($cashReceivedInput === null || $cashReceivedInput < $total) {
                    // Melempar exception ketika terjadi kondisi yang tidak valid.
                    throw ValidationException::withMessages([
                        // Baris ini merupakan bagian dari logika proses pada file ini.
                        'cash_received' => 'Uang diterima harus lebih besar atau sama dengan total transaksi.',
                    // Menandai bagian dari struktur array yang digunakan pada proses ini.
                    ]);
                // Menutup blok kode.
                }

                // Memperbarui data yang sudah ada di database.
                $transaction->update([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'total' => $total,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'cash_received' => $cashReceivedInput,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'change_amount' => $cashReceivedInput - $total,
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Baris ini merupakan bagian dari logika proses pada file ini.
            } else {
                // Memperbarui data yang sudah ada di database.
                $transaction->update([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'total' => $total,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'cash_received' => null,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'change_amount' => null,
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Menutup blok kode.
            }

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if ($validated['payment_type'] === 'utang') {
                // Menyimpan data baru ke database melalui model yang terkait.
                Piutang::create([
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'transaction_id' => $transaction->id,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'customer_id' => $validated['customer_id'],
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'amount' => $total,
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'due_date' => $validated['due_date'],
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    'status' => 'unpaid',
                // Menandai bagian dari struktur array yang digunakan pada proses ini.
                ]);
            // Menutup blok kode.
            }

            // Mengembalikan hasil proses dari method ini.
            return $transaction;
        // Menutup struktur atau rangkaian proses pada blok sebelumnya.
        });

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('transactions.show', $transaction)
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Transaksi berhasil disimpan');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
