<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;

    protected $table = 'piutangs';

    protected $fillable = [
        'transaction_id',
        'customer_id',
        'amount',
        'due_date',
        'status',
        'paid_at',
        'paid_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function getTransactionIdAttribute()
    {
        return $this->attributes['penjualan_id'] ?? null;
    }

    public function setTransactionIdAttribute($value): void
    {
        $this->attributes['penjualan_id'] = $value;
    }

    public function getCustomerIdAttribute()
    {
        return $this->attributes['pelanggan_id'] ?? null;
    }

    public function setCustomerIdAttribute($value): void
    {
        $this->attributes['pelanggan_id'] = $value;
    }

    public function getAmountAttribute(): int
    {
        return (int) ($this->attributes['jumlah'] ?? 0);
    }

    public function setAmountAttribute($value): void
    {
        $this->attributes['jumlah'] = (int) $value;
    }

    public function getDueDateAttribute()
    {
        return isset($this->attributes['jatuh_tempo']) && $this->attributes['jatuh_tempo']
            ? $this->asDateTime($this->attributes['jatuh_tempo'])
            : null;
    }

    public function setDueDateAttribute($value): void
    {
        $this->attributes['jatuh_tempo'] = $value;
    }

    public function getStatusAttribute(): string
    {
        return ($this->attributes['status'] ?? 'belum') === 'lunas'
            ? 'paid'
            : 'unpaid';
    }

    public function setStatusAttribute(string $value): void
    {
        $this->attributes['status'] = in_array($value, ['paid', 'lunas'], true)
            ? 'lunas'
            : 'belum';
    }

    public function getPaidAtAttribute()
    {
        return isset($this->attributes['tgl_lunas']) && $this->attributes['tgl_lunas']
            ? $this->asDateTime($this->attributes['tgl_lunas'])
            : null;
    }

    public function setPaidAtAttribute($value): void
    {
        $this->attributes['tgl_lunas'] = $value;
    }

    public function setPaidByAttribute($value): void
    {
        $this->attributes['dilunasi_oleh'] = $value;
    }

    public function transaction()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function customer()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
