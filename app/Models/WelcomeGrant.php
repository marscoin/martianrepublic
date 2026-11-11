<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeGrant extends Model
{
    protected $table = 'welcome_grants';

    protected $guarded = ['id'];

    protected $casts = [
        'amount_zubrins' => 'integer',
        'attempts' => 'integer',
        'attempted_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}
