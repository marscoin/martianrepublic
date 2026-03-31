<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HDWallet extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hd_wallet';

    protected $fillable = [
        'user_id',
        'wallet_type',
        'backup',
        'encrypted_seed',
        'public_addr',
        'opened_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [
        'password' => 'required|between:6,32',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
