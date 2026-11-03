<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMutation extends Model
{
    protected $table = 'organization_mutations';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = ['response' => 'array', 'created_at' => 'datetime', 'status_code' => 'integer'];
}
