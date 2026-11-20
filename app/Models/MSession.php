<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MSession extends Model
{
    protected $table = 'mars_sessions';

    protected $primaryKey = 'sid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['sid', 'v', 's'];

    public $timestamps = false;
}
