<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class RiwayatStok extends Model
{
    use HasFactory;
    protected $table = 'stok_histories';
    protected $fillable = [
        'produk_id',
        'supplier_id',
        'user_id',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
        'tanggal',
    ];
    protected $casts = [
        'tanggal' => 'datetime',
    ];
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'supplier_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
