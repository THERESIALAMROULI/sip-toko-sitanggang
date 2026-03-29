<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_category_id',
        'user_id',
        'nominal',
        'tanggal',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'expense_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
