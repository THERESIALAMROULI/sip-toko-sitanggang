<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Model;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Relations\HasMany;

// Mendefinisikan class sebagai wadah logika pada file ini.
class Produk extends Model
// Membuka blok kode.
{
    // Mengimpor class atau helper yang dibutuhkan pada file ini.
    use HasFactory;

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'produks';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'nama',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'kategori_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'harga_beli',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'harga_jual',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'stok',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'stok_minimum',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'aktif',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'name',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'price',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'stock',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $casts = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'aktif' => 'boolean',
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
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $price = (int) $value;
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['harga_jual'] = $price;

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! isset($this->attributes['harga_beli']) || (int) $this->attributes['harga_beli'] === 0) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $this->attributes['harga_beli'] = $price;
        // Menutup blok kode.
        }
    // Menutup blok kode.
    }

    // Mendefinisikan method getStockAttribute untuk menjalankan proses tertentu.
    public function getStockAttribute(): int
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return (int) ($this->attributes['stok'] ?? 0);
    // Menutup blok kode.
    }

    // Mendefinisikan method setStockAttribute untuk menjalankan proses tertentu.
    public function setStockAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['stok'] = (int) $value;
    // Menutup blok kode.
    }

    // Baris ini merupakan bagian dari logika proses pada file ini.
    protected static function booted(): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        static::creating(function (Produk $product): void {
            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if (! isset($product->attributes['kategori_id'])) {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $product->attributes['kategori_id'] = 1;
            // Menutup blok kode.
            }

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if (! isset($product->attributes['stok_minimum'])) {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $product->attributes['stok_minimum'] = 10;
            // Menutup blok kode.
            }

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if (! isset($product->attributes['aktif'])) {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $product->attributes['aktif'] = 1;
            // Menutup blok kode.
            }
        // Menutup struktur atau rangkaian proses pada blok sebelumnya.
        });
    // Menutup blok kode.
    }

    // Mendefinisikan method kategori untuk menjalankan proses tertentu.
    public function kategori(): BelongsTo
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Kategori::class, 'kategori_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method transactionDetails untuk menjalankan proses tertentu.
    public function transactionDetails(): HasMany
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->hasMany(DetailPenjualan::class, 'produk_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method stokHistories untuk menjalankan proses tertentu.
    public function stokHistories(): HasMany
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->hasMany(RiwayatStok::class, 'produk_id');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
