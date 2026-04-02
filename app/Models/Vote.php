<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * The database table used by the model.
     *
     */
    protected $table = 'votes';

    protected $fillable = [
        'vote',
        'proposal_id',
        'txid',
        'mined',
        'block',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     */
    public static $rules = [
    ];
}
