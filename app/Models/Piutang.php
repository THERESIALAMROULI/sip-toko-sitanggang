<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class sebagai wadah logika pada file ini.
class Piutang extends Model
// Membuka blok kode.
{
    // Mengimpor class atau helper yang dibutuhkan pada file ini.
    use HasFactory;

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'piutangs';

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'transaction_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'customer_id',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'amount',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'due_date',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'status',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'paid_at',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'paid_by',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $casts = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'due_date' => 'date',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'paid_at' => 'datetime',
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

    // Mendefinisikan method getAmountAttribute untuk menjalankan proses tertentu.
    public function getAmountAttribute(): int
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return (int) ($this->attributes['jumlah'] ?? 0);
    // Menutup blok kode.
    }

    // Mendefinisikan method setAmountAttribute untuk menjalankan proses tertentu.
    public function setAmountAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['jumlah'] = (int) $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getDueDateAttribute untuk menjalankan proses tertentu.
    public function getDueDateAttribute()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return isset($this->attributes['jatuh_tempo']) && $this->attributes['jatuh_tempo']
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ? $this->asDateTime($this->attributes['jatuh_tempo'])
            // Baris ini merupakan bagian dari logika proses pada file ini.
            : null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setDueDateAttribute untuk menjalankan proses tertentu.
    public function setDueDateAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['jatuh_tempo'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method getStatusAttribute untuk menjalankan proses tertentu.
    public function getStatusAttribute(): string
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return ($this->attributes['status'] ?? 'belum') === 'lunas'
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ? 'paid'
            // Baris ini merupakan bagian dari logika proses pada file ini.
            : 'unpaid';
    // Menutup blok kode.
    }

    // Mendefinisikan method setStatusAttribute untuk menjalankan proses tertentu.
    public function setStatusAttribute(string $value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['status'] = in_array($value, ['paid', 'lunas'], true)
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ? 'lunas'
            // Baris ini merupakan bagian dari logika proses pada file ini.
            : 'belum';
    // Menutup blok kode.
    }

    // Mendefinisikan method getPaidAtAttribute untuk menjalankan proses tertentu.
    public function getPaidAtAttribute()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return isset($this->attributes['tgl_lunas']) && $this->attributes['tgl_lunas']
            // Baris ini merupakan bagian dari logika proses pada file ini.
            ? $this->asDateTime($this->attributes['tgl_lunas'])
            // Baris ini merupakan bagian dari logika proses pada file ini.
            : null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setPaidAtAttribute untuk menjalankan proses tertentu.
    public function setPaidAtAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['tgl_lunas'] = $value;
    // Menutup blok kode.
    }

    // Mendefinisikan method setPaidByAttribute untuk menjalankan proses tertentu.
    public function setPaidByAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['dilunasi_oleh'] = $value;
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

    // Mendefinisikan method customer untuk menjalankan proses tertentu.
    public function customer()
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
