<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CivicWallet extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     */
    protected $table = 'civic_wallet';

    protected $fillable = [
        'user_id', 'wallet_type', 'backup', 'encrypted_seed', 'public_addr',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     */
    public static $rules = [
        'password' => 'required|between:6,32',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'opened_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
