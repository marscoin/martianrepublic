<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = ['archived_at' => 'datetime'];

    public function members(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(OrganizationTask::class);
    }
}
