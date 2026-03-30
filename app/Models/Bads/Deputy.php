<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Deputy extends Model
{
    protected $fillable = ['user_id', 'committee_id', 'civic_address', 'role_tag', 'appointment_proposal_id', 'appointment_txid', 'status'];

    public function user() { return $this->belongsTo(User::class); }
    public function committee() { return $this->belongsTo(OversightCommittee::class, 'committee_id'); }
    public function instruments() { return $this->hasMany(Instrument::class, 'certified_by_deputy_id'); }
}
