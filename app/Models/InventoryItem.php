<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_items';

    protected $fillable = [
        'userid',
        'name',
        'description',
        'category',
        'quantity',
        'unit',
        'location',
        'condition',
        'ipfs_hash',
        'notarization',
        'notarized_at',
        'image_path',
    ];

    protected $casts = [
        'notarized_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public const CATEGORIES = [
        'equipment' => 'Equipment & Tools',
        'materials' => 'Raw Materials',
        'supplies' => 'Supplies & Consumables',
        'electronics' => 'Electronics & Components',
        'habitat' => 'Habitat & Structure',
        'science' => 'Science & Research',
        'medical' => 'Medical',
        'other' => 'Other',
    ];

    public const CONDITIONS = [
        'new' => 'New',
        'good' => 'Good',
        'fair' => 'Fair',
        'worn' => 'Worn',
        'damaged' => 'Damaged',
        'decommissioned' => 'Decommissioned',
    ];
}
