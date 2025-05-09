<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HappiCoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'transaction_type',
        'reference_id',
        'description',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
