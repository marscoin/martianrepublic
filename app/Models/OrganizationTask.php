<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationTask extends Model
{
    protected $table = 'organization_tasks';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = ['due_date' => 'date:Y-m-d', 'completed_at' => 'datetime', 'version' => 'integer', 'assigned_to' => 'integer', 'created_by' => 'integer'];
}
