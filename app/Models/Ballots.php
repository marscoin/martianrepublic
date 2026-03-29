<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ballots extends Model
{
    protected $table = 'ballots';

    protected $fillable = [
        'userid', 'proposalid', 'btxid', 'status',
        'encrypted_key', 'encryption_iv', 'ballot_txid',
        'confirmed_at', 'notified', 'hidden_target'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'notified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposals::class, 'proposalid');
    }

    /**
     * Get pending ballots that are confirmed but not yet voted on.
     */
    public static function pendingForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('userid', $userId)
            ->where('status', 'received')
            ->whereNotNull('confirmed_at')
            ->whereNotNull('encrypted_key')
            ->get();
    }
}
