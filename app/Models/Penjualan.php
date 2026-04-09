<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Model;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Str;

// Mendefinisikan class sebagai wadah logika pada file ini.
class Penjualan extends Model
// Membuka blok kode.
{
    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'penjualans';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'customer_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'transaction_date',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'total',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'cash_received',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'change_amount',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'payment_type',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'status',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $casts = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'transaction_date' => 'datetime',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'cash_received' => 'integer',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'change_amount' => 'integer',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan method getCustomerIdAttribute untuk menjalankan proses tertentu.
    public function getCustomerIdAttribute()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->attributes['pelanggan_id'] ?? null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setCustomerIdAttribute untuk menjalankan proses tertentu.
    public function setCustomerIdAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['pelanggan_id'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getTransactionDateAttribute untuk menjalankan proses tertentu.
    public function getTransactionDateAttribute()
    // Membuka blok kode.
    {
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! isset($this->attributes['tanggal']) || ! $this->attributes['tanggal']) {
            // Mengembalikan hasil proses dari method ini.
            return null;
        // Menutup blok kode.
        }

        // Mengembalikan hasil proses dari method ini.
        return $this->asDateTime($this->attributes['tanggal']);
    // Menutup blok kode.
    }

    // Mendefinisikan method setTransactionDateAttribute untuk menjalankan proses tertentu.
    public function setTransactionDateAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['tanggal'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getPaymentTypeAttribute untuk menjalankan proses tertentu.
    public function getPaymentTypeAttribute(): string
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->attributes['metode'] ?? 'tunai';
    // Menutup blok kode.
    }

    // Mendefinisikan method setPaymentTypeAttribute untuk menjalankan proses tertentu.
    public function setPaymentTypeAttribute(string $value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['metode'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getCashReceivedAttribute untuk menjalankan proses tertentu.
    public function getCashReceivedAttribute(): ?int
    // Membuka blok kode.
    {
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! isset($this->attributes['uang_diterima']) || $this->attributes['uang_diterima'] === null) {
            // Mengembalikan hasil proses dari method ini.
            return null;
        // Menutup blok kode.
        }

        // Mengembalikan hasil proses dari method ini.
        return (int) $this->attributes['uang_diterima'];
    // Menutup blok kode.
    }

    // Mendefinisikan method setCashReceivedAttribute untuk menjalankan proses tertentu.
    public function setCashReceivedAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['uang_diterima'] = $value === null ? null : (int) $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getChangeAmountAttribute untuk menjalankan proses tertentu.
    public function getChangeAmountAttribute(): ?int
    // Membuka blok kode.
    {
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! isset($this->attributes['kembalian']) || $this->attributes['kembalian'] === null) {
            // Mengembalikan hasil proses dari method ini.
            return null;
        // Menutup blok kode.
        }

        // Mengembalikan hasil proses dari method ini.
        return (int) $this->attributes['kembalian'];
    // Menutup blok kode.
    }

    // Mendefinisikan method setChangeAmountAttribute untuk menjalankan proses tertentu.
    public function setChangeAmountAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['kembalian'] = $value === null ? null : (int) $value;
    // Menutup blok kode.
    }

    // Baris ini merupakan bagian dari logika proses pada file ini.
    protected static function booted(): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        static::creating(function (Penjualan $transaction): void {
            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if (! isset($transaction->attributes['no_nota'])) {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $transaction->attributes['no_nota'] = 'TRX-'.strtoupper(Str::random(8));
            // Menutup blok kode.
            }

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if (! isset($transaction->attributes['user_id'])) {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $transaction->attributes['user_id'] = auth()->id() ?? 1;
            // Menutup blok kode.
            }

            // Memeriksa kondisi untuk menentukan alur proses berikutnya.
            if (! isset($transaction->attributes['status'])) {
                // Baris ini merupakan bagian dari logika proses pada file ini.
                $transaction->attributes['status'] = ($transaction->payment_type === 'utang') ? 'utang' : 'lunas';
            // Menutup blok kode.
            }
        // Menutup struktur atau rangkaian proses pada blok sebelumnya.
        });
    // Menutup blok kode.
    }

    // Mendefinisikan method details untuk menjalankan proses tertentu.
    public function details()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method customer untuk menjalankan proses tertentu.
    public function customer()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method receivable untuk menjalankan proses tertentu.
    public function receivable()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->hasOne(Piutang::class, 'penjualan_id');
    // Menutup blok kode.
    }

    // Mendefinisikan method user untuk menjalankan proses tertentu.
    public function user()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Pengguna::class, 'user_id');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
