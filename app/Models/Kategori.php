<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Model;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Relations\HasMany;

// Mendefinisikan class sebagai wadah logika pada file ini.
class Kategori extends Model
// Membuka blok kode.
{
    // Mengimpor class atau helper yang dibutuhkan pada file ini.
    use HasFactory;

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'kategoris';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'nama',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan method products untuk menjalankan proses tertentu.
    public function products(): HasMany
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->hasMany(Produk::class, 'kategori_id');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
