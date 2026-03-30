<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;

class CalibrationRecord extends Model
{
    protected $fillable = ['instrument_id', 'calibrator_deputy_id', 'txid', 'new_dice_cdi_hash', 'calibration_data', 'calibrated_at', 'due_at'];
    protected $casts = ['calibration_data' => 'array', 'calibrated_at' => 'datetime', 'due_at' => 'datetime'];

    public function instrument() { return $this->belongsTo(Instrument::class); }
    public function calibrator() { return $this->belongsTo(Deputy::class, 'calibrator_deputy_id'); }
}
