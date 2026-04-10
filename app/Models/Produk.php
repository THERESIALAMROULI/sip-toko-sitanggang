<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Produk extends Model
{
    use HasFactory;
    // Model ini menjadi pusat data barang: nama, harga, stok, status aktif, dan relasinya.
    protected $table = 'produks';
    protected $fillable = [
        'nama',
        'kategori_id',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'aktif',
        'name',
        'price',
        'stock',
    ];
    protected $casts = [
        'aktif' => 'boolean',
    ];
    // Accessor dan mutator berikut menjaga agar nama atribut di kode tetap konsisten meski nama kolom database berbeda.
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }
    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }
    public function getPriceAttribute(): int
    {
        return (int) ($this->attributes['harga_jual'] ?? 0);
    }
    public function setPriceAttribute($value): void
    {
        $price = (int) $value;
        $this->attributes['harga_jual'] = $price;
        if (! isset($this->attributes['harga_beli']) || (int) $this->attributes['harga_beli'] === 0) {
            $this->attributes['harga_beli'] = $price;
        }
    }
    public function getStockAttribute(): int
    {
        return (int) ($this->attributes['stok'] ?? 0);
    }
    public function setStockAttribute($value): void
    {
        $this->attributes['stok'] = (int) $value;
    }
    // Nilai default memastikan produk baru tetap valid walaupun beberapa field belum diisi manual.
    protected static function booted(): void
    {
        static::creating(function (Produk $product): void {
            if (! isset($product->attributes['kategori_id'])) {
                $product->attributes['kategori_id'] = 1;
            }
            if (! isset($product->attributes['stok_minimum'])) {
                $product->attributes['stok_minimum'] = 10;
            }
            if (! isset($product->attributes['aktif'])) {
                $product->attributes['aktif'] = 1;
            }
        });
    }
    // Relasi ini menghubungkan produk dengan kategori, detail transaksi, dan riwayat perubahan stok.
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'produk_id');
    }
    public function stokHistories(): HasMany
    {
        return $this->hasMany(RiwayatStok::class, 'produk_id');
    }
}
