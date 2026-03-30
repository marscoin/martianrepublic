<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalibrationRecord extends Model
{
    protected $fillable = ['instrument_id', 'calibrator_deputy_id', 'txid', 'new_dice_cdi_hash', 'calibration_data', 'calibrated_at', 'due_at'];
    protected $casts = ['calibration_data' => 'array', 'calibrated_at' => 'datetime', 'due_at' => 'datetime'];

    /** @return BelongsTo<Instrument, self> */
    public function instrument(): BelongsTo { return $this->belongsTo(Instrument::class); }

    /** @return BelongsTo<Deputy, self> */
    public function calibrator(): BelongsTo { return $this->belongsTo(Deputy::class, 'calibrator_deputy_id'); }
}
