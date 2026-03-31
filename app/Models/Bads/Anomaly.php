<?php

namespace App\Models\Bads;

use Illuminate\Database\Eloquent\Model;

class Anomaly extends Model
{
    protected $fillable = ['attestation_id', 'instrument_id', 'reading_index', 'anomaly_type', 'severity', 'status', 'reviewed_by_user_id', 'notes'];

    public function attestation()
    {
        return $this->belongsTo(Attestation::class);
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
