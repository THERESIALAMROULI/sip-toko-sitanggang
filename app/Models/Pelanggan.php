<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class sebagai wadah logika pada file ini.
class Pelanggan extends Model
// Membuka blok kode.
{
    // Mengimpor class atau helper yang dibutuhkan pada file ini.
    use HasFactory;

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'pelanggans';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'name',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'phone',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'address',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan method getNameAttribute untuk menjalankan proses tertentu.
    public function getNameAttribute(): ?string
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->attributes['nama'] ?? null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setNameAttribute untuk menjalankan proses tertentu.
    public function setNameAttribute(?string $value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['nama'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getPhoneAttribute untuk menjalankan proses tertentu.
    public function getPhoneAttribute(): ?string
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->attributes['telp'] ?? null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setPhoneAttribute untuk menjalankan proses tertentu.
    public function setPhoneAttribute(?string $value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['telp'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getAddressAttribute untuk menjalankan proses tertentu.
    public function getAddressAttribute(): ?string
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->attributes['alamat'] ?? null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setAddressAttribute untuk menjalankan proses tertentu.
    public function setAddressAttribute(?string $value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['alamat'] = $value;
    // Menutup blok kode.
    }
// Menutup blok kode.
}
