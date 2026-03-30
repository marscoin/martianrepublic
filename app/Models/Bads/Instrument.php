<?php
namespace App\Models\Bads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instrument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'address', 'did', 'pubkey_hex', 'crypto_suite_id', 'device_type', 'device_type_name',
        'device_category', 'make', 'model', 'serial', 'dice_cdi_hash', 'status',
        'certified_by_deputy_id', 'cert_txid', 'mqtt_namespace', 'operational_params',
        'location', 'firmware_version', 'revoke_txid', 'revoke_reason', 'revoke_notes'
    ];

    protected $casts = ['operational_params' => 'array'];

    public const DEVICE_TYPES = [
        0x0101 => 'O2 Partial Pressure Sensor',
        0x0102 => 'CO2 Concentration Sensor',
        0x0103 => 'Atmospheric Pressure Sensor',
        0x0104 => 'Humidity Sensor',
        0x0105 => 'Temperature Sensor',
        0x0201 => 'Kilopower Fission Reactor Monitor',
        0x0202 => 'Solar Array Monitor',
        0x0203 => 'Battery Bank Monitor',
        0x0301 => 'Nutrient Solution Analyzer',
        0x0302 => 'Water Quality Sensor',
        0x0303 => 'Growth Light Spectrum Analyzer',
        0x0401 => 'Pressure Decay Tester',
        0x0501 => 'Water Quality Sensor (Potable)',
        0x0601 => 'Vital Signs Monitor',
        0x0801 => 'External Temperature Sensor',
        0x0802 => 'Radiation Dosimeter',
        0x0803 => 'Seismometer',
    ];

    public const REVOKE_REASONS = [
        0x01 => 'Malfunction', 0x02 => 'Calibration Expired', 0x03 => 'Decommissioned',
        0x04 => 'Compromised', 0x05 => 'Firmware Mismatch', 0x06 => 'Policy Change',
        0x07 => 'Deputy Cascade', 0x08 => 'Replaced',
    ];

    public function certifiedBy() { return $this->belongsTo(Deputy::class, 'certified_by_deputy_id'); }
    public function attestations() { return $this->hasMany(Attestation::class); }
    public function anomalies() { return $this->hasMany(Anomaly::class); }
    public function calibrations() { return $this->hasMany(CalibrationRecord::class); }
}
