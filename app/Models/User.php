<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'commission_percentage',
    ];

    /**
     * Hotels onboarded by this user (as distributor).
     */
    public function hotels()
    {
        return $this->hasMany(HotelAdmin::class, 'distributor_id');
    }

    /**
     * Package sales executed by this user (as distributor).
     */
    public function distributorSales()
    {
        return $this->hasMany(DistributorSale::class, 'distributor_id');
    }

    /**
     * Dedicated digital wallet for distributor.
     */
    public function wallet()
    {
        return $this->hasOne(DistributorWallet::class, 'distributor_id');
    }

    /**
     * Distributor wallet transactions.
     */
    public function walletTransactions()
    {
        return $this->hasMany(DistributorWalletTransaction::class, 'distributor_id');
    }

    /**
     * Distributor withdrawal requests.
     */
    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'distributor_id');
    }

    /**
     * Resolve effective commission percentage (individual override > global default).
     */
    public function getEffectiveCommissionPercentageAttribute(): float
    {
        if ($this->commission_percentage !== null) {
            return (float) $this->commission_percentage;
        }

        return (float) CommissionSetting::getSetting()->default_percentage;
    }

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
            'password' => 'hashed',
        ];
    }
}
