<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationActivity extends Model
{
    protected $table = 'organization_activities';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = ['metadata' => 'array', 'created_at' => 'datetime'];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrganizationAttachment::class, 'activity_id');
    }
}
