<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemasok extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'nama',
        'telp',
        'alamat',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function stokHistories(): HasMany
    {
        return $this->hasMany(RiwayatStok::class, 'supplier_id');
    }
}
