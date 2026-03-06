<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
