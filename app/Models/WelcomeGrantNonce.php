<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeGrantNonce extends Model
{
    protected $table = 'welcome_grant_nonces';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];
}
