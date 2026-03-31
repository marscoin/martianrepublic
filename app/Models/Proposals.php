<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposals extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'proposals';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'tier',
        'discussion',
        'mined',
        'author',
        'ipfs_hash',
        'original_ipfs_hash',
        'original_description',
        'threshold',
        'participation',
        'duration',
        'expiration',
        'txid',
        'git_hash',
        'public_address',
        'mars_paid',
        'active',
        'voting_extended',
        'quiet_ending_triggered_at',
        'status',
        'votes_required',
        'citizen_count',
        'closed_reason',
        'screening_ends_at',
        'voting_ends_at',
        'timelock_ends_at',
        'enacted_at',
        'sunset_at',
        'amended_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * Check for open proposals.
     *
     * @return int Number of open proposals.
     */
    public static function countOpenProposals()
    {
        // Count the number of proposals where 'active' is true (1).
        return self::where('active', 1)->count();
    }

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
