<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class sebagai wadah logika pada file ini.
class DetailPenjualan extends Model
// Membuka blok kode.
{
    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'detail_penjualans';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'transaction_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'product_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'nama_produk',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'quantity',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'price',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'subtotal',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan method getTransactionIdAttribute untuk menjalankan proses tertentu.
    public function getTransactionIdAttribute()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->attributes['penjualan_id'] ?? null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setTransactionIdAttribute untuk menjalankan proses tertentu.
    public function setTransactionIdAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['penjualan_id'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getProductIdAttribute untuk menjalankan proses tertentu.
    public function getProductIdAttribute()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->attributes['produk_id'] ?? null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setProductIdAttribute untuk menjalankan proses tertentu.
    public function setProductIdAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['produk_id'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getQuantityAttribute untuk menjalankan proses tertentu.
    public function getQuantityAttribute(): int
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return (int) ($this->attributes['qty'] ?? 0);
    // Menutup blok kode.
    }

    // Mendefinisikan method setQuantityAttribute untuk menjalankan proses tertentu.
    public function setQuantityAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['qty'] = (int) $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getPriceAttribute untuk menjalankan proses tertentu.
    public function getPriceAttribute(): int
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return (int) ($this->attributes['harga_jual'] ?? 0);
    // Menutup blok kode.
    }

    // Mendefinisikan method setPriceAttribute untuk menjalankan proses tertentu.
    public function setPriceAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['harga_jual'] = (int) $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method setSubtotalAttribute untuk menjalankan proses tertentu.
    public function setSubtotalAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['subtotal'] = (int) $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method product untuk menjalankan proses tertentu.
    public function product()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Produk::class, 'produk_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method transaction untuk menjalankan proses tertentu.
    public function transaction()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
