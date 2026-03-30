<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;

class Attestation extends Model
{
    protected $fillable = [
        'txid', 'instrument_id', 'block_height', 'reading_count', 'merkle_root',
        'data_cid', 'prev_attestation_txid', 'signature', 'verified', 'batch_start', 'batch_end'
    ];
    protected $casts = ['verified' => 'boolean', 'batch_start' => 'datetime', 'batch_end' => 'datetime'];

    public function instrument() { return $this->belongsTo(Instrument::class); }
    public function readings() { return $this->hasMany(AttestationReading::class); }
    public function anomalies() { return $this->hasMany(Anomaly::class); }
}
