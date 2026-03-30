<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Deputy extends Model
{
    protected $fillable = ['user_id', 'committee_id', 'civic_address', 'role_tag', 'appointment_proposal_id', 'appointment_txid', 'status'];

    /** @return BelongsTo<User, self> */
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    /** @return BelongsTo<OversightCommittee, self> */
    public function committee(): BelongsTo { return $this->belongsTo(OversightCommittee::class, 'committee_id'); }

    /** @return HasMany<Instrument> */
    public function instruments(): HasMany { return $this->hasMany(Instrument::class, 'certified_by_deputy_id'); }
}
