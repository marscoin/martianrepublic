<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationAttachment extends Model
{
    protected $table = 'organization_attachments';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime', 'size' => 'integer'];
}
