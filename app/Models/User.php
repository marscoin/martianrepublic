<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\HDWallet;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // Define the relationship to Profile
    public function profile() {
        return $this->hasOne(Profile::class, 'userid', 'id');
    }

    // Define the relationship to Feed
    public function feeds() {
        return $this->hasMany(Feed::class, 'userid', 'id');
    }

    public function hdWallet()
    {
        return $this->hasOne(HDWallet::class, 'user_id');
    }

    public function citizen()
    {
        return $this->hasOne(Citizen::class, 'userid');
    }

    /**
     * Get the user's civic wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function civicWallet()
    {
        return $this->hasOne(CivicWallet::class, 'user_id');
    }

}
