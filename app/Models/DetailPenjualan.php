<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualans';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'nama_produk',
        'quantity',
        'price',
        'subtotal',
    ];

    public function getTransactionIdAttribute()
    {
        return $this->attributes['penjualan_id'] ?? null;
    }

    public function setTransactionIdAttribute($value): void
    {
        $this->attributes['penjualan_id'] = $value;
    }

    public function getProductIdAttribute()
    {
        return $this->attributes['produk_id'] ?? null;
    }

    public function setProductIdAttribute($value): void
    {
        $this->attributes['produk_id'] = $value;
    }

    public function getQuantityAttribute(): int
    {
        return (int) ($this->attributes['qty'] ?? 0);
    }

    public function setQuantityAttribute($value): void
    {
        $this->attributes['qty'] = (int) $value;
    }

    public function getPriceAttribute(): int
    {
        return (int) ($this->attributes['harga_jual'] ?? 0);
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['harga_jual'] = (int) $value;
    }

    public function setSubtotalAttribute($value): void
    {
        $this->attributes['subtotal'] = (int) $value;
    }

    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
