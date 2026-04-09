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
class Pengeluaran extends Model
// Membuka blok kode.
{
    // Mengimpor class atau helper yang dibutuhkan pada file ini.
    use HasFactory;

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'expenses';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'expense_category_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'user_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'nominal',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'tanggal',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'catatan',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $casts = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'tanggal' => 'date',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'nominal' => 'integer',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan method category untuk menjalankan proses tertentu.
    public function category(): BelongsTo
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(KategoriPengeluaran::class, 'expense_category_id');
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
