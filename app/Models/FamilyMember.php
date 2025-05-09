<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_group_id',
        'user_id',
        'relationship',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function familyGroup()
    {
        return $this->belongsTo(FamilyGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
