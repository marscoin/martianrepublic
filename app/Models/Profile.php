<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    protected $table = 'profile';

    protected $fillable = [
        'userid',
        'twofaset',
        'twofakey',
        'openchallenge',
        'wallet_open',
        'civic_wallet_open',
        'general_public',
        'endorse_cnt',
        'citizen',
        'has_application'
    ];

}

?>