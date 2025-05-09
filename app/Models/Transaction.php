<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_type',
        'reference_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'transaction_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
