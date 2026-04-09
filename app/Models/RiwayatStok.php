<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Model;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Mendefinisikan class sebagai wadah logika pada file ini.
class RiwayatStok extends Model
// Membuka blok kode.
{
    // Mengimpor class atau helper yang dibutuhkan pada file ini.
    use HasFactory;

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'stok_histories';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'produk_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'supplier_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'user_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'jumlah',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'stok_sebelum',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'stok_sesudah',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'keterangan',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'tanggal',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $casts = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'tanggal' => 'datetime',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan method produk untuk menjalankan proses tertentu.
    public function produk(): BelongsTo
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Produk::class, 'produk_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method supplier untuk menjalankan proses tertentu.
    public function supplier(): BelongsTo
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Pemasok::class, 'supplier_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method user untuk menjalankan proses tertentu.
    public function user(): BelongsTo
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Pengguna::class, 'user_id');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
