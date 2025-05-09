<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'make',
        'model',
        'year',
        'license_plate',
        'vin',
        'color',
        'vehicle_card_image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insurances()
    {
        return $this->hasMany(UserInsurance::class);
    }
}
