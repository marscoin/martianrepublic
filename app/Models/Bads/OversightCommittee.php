<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proposals;

class OversightCommittee extends Model
{
    protected $fillable = ['name', 'slug', 'role_tag', 'description', 'proposal_id', 'proposal_txid', 'device_types', 'status'];
    protected $casts = ['device_types' => 'array'];

    public function proposal() { return $this->belongsTo(Proposals::class, 'proposal_id'); }
    public function deputies() { return $this->hasMany(Deputy::class, 'committee_id'); }
    public function instruments() { return $this->hasManyThrough(Instrument::class, Deputy::class, 'committee_id', 'certified_by_deputy_id'); }
}
