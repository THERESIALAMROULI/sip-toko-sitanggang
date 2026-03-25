<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Penjualan extends Model
{
    protected $table = 'penjualans';

    protected $fillable = [
        'customer_id',
        'transaction_date',
        'total',
        'cash_received',
        'change_amount',
        'payment_type',
        'status',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'cash_received' => 'integer',
        'change_amount' => 'integer',
    ];

    public function getCustomerIdAttribute()
    {
        return $this->attributes['pelanggan_id'] ?? null;
    }

    public function setCustomerIdAttribute($value): void
    {
        $this->attributes['pelanggan_id'] = $value;
    }

    public function getTransactionDateAttribute()
    {
        if (! isset($this->attributes['tanggal']) || ! $this->attributes['tanggal']) {
            return null;
        }

        return $this->asDateTime($this->attributes['tanggal']);
    }

    public function setTransactionDateAttribute($value): void
    {
        $this->attributes['tanggal'] = $value;
    }

    public function getPaymentTypeAttribute(): string
    {
        return $this->attributes['metode'] ?? 'tunai';
    }

    public function setPaymentTypeAttribute(string $value): void
    {
        $this->attributes['metode'] = $value;
    }

    public function getCashReceivedAttribute(): ?int
    {
        if (! isset($this->attributes['uang_diterima']) || $this->attributes['uang_diterima'] === null) {
            return null;
        }

        return (int) $this->attributes['uang_diterima'];
    }

    public function setCashReceivedAttribute($value): void
    {
        $this->attributes['uang_diterima'] = $value === null ? null : (int) $value;
    }

    public function getChangeAmountAttribute(): ?int
    {
        if (! isset($this->attributes['kembalian']) || $this->attributes['kembalian'] === null) {
            return null;
        }

        return (int) $this->attributes['kembalian'];
    }

    public function setChangeAmountAttribute($value): void
    {
        $this->attributes['kembalian'] = $value === null ? null : (int) $value;
    }

    protected static function booted(): void
    {
        static::creating(function (Penjualan $transaction): void {
            if (! isset($transaction->attributes['no_nota'])) {
                $transaction->attributes['no_nota'] = 'TRX-'.strtoupper(Str::random(8));
            }

            if (! isset($transaction->attributes['user_id'])) {
                $transaction->attributes['user_id'] = auth()->id() ?? 1;
            }

            if (! isset($transaction->attributes['status'])) {
                $transaction->attributes['status'] = ($transaction->payment_type === 'utang') ? 'utang' : 'lunas';
            }
        });
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    public function customer()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function receivable()
    {
        return $this->hasOne(Piutang::class, 'penjualan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
