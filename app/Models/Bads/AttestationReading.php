<?php

namespace App\Models\Bads;

use Illuminate\Database\Eloquent\Model;

class AttestationReading extends Model
{
    protected $fillable = ['attestation_id', 'reading_index', 'metrics', 'quality', 'reading_hash', 'recorded_at'];

    protected $casts = ['metrics' => 'array', 'recorded_at' => 'datetime'];

    public function attestation()
    {
        return $this->belongsTo(Attestation::class);
    }
}
