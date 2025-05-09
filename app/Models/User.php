<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'account_type',
        'membership_tier',
        'happi_coins',
        'membership_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'membership_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function insurances()
    {
        return $this->hasMany(UserInsurance::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function ownedFamilyGroups()
    {
        return $this->hasMany(FamilyGroup::class, 'owner_id');
    }

    public function familyMemberships()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function coinTransactions()
    {
        return $this->hasMany(HappiCoin::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isMembershipActive()
    {
        return $this->membership_expires_at && $this->membership_expires_at->isFuture();
    }
}
