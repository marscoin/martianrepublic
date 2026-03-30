<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model {

    protected $table = 'vouchers';

    protected $fillable = [
        'user_id',
        'user_account',
        'redeemed',
    ];

}

?>