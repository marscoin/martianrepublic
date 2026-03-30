<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Proposals;

class OversightCommittee extends Model
{
    protected $fillable = ['name', 'slug', 'role_tag', 'description', 'proposal_id', 'proposal_txid', 'device_types', 'status'];
    protected $casts = ['device_types' => 'array'];

    /** @return BelongsTo<Proposals, self> */
    public function proposal(): BelongsTo { return $this->belongsTo(Proposals::class, 'proposal_id'); }

    /** @return HasMany<Deputy> */
    public function deputies(): HasMany { return $this->hasMany(Deputy::class, 'committee_id'); }

    /** @return HasManyThrough<Instrument> */
    public function instruments(): HasManyThrough { return $this->hasManyThrough(Instrument::class, Deputy::class, 'committee_id', 'certified_by_deputy_id'); }
}
