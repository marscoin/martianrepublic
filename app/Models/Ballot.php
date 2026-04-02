<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ballot extends Model
{
    protected $table = 'ballots';

    protected $fillable = [
        'userid', 'proposalid', 'btxid', 'status',
        'encrypted_key', 'encryption_iv', 'ballot_txid',
        'confirmed_at', 'notified', 'hidden_target',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'notified' => 'boolean',
        'encrypted_key' => 'encrypted',
        'encryption_iv' => 'encrypted',
    ];

    /** @return BelongsTo<User, self> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }

    /** @return BelongsTo<Proposals, self> */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'proposalid');
    }

    /**
     * Get pending ballots that are confirmed but not yet voted on.
     */
    public static function pendingForUser(int $userId): Collection
    {
        return static::where('userid', $userId)
            ->where('status', 'received')
            ->whereNotNull('confirmed_at')
            ->whereNotNull('encrypted_key')
            ->get();
    }
}
