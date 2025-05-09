<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInsurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'insurance_product_id',
        'policy_number',
        'price_paid',
        'starts_at',
        'expires_at',
        'status',
        'policy_data',
        'vehicle_id',
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'policy_data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insuranceProduct()
    {
        return $this->belongsTo(InsuranceProduct::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->expires_at->isFuture();
    }
}
