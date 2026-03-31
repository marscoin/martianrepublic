<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Cache for embedded data located or injected into the Marscoin Blockchain.
 *
 * @var string
 */
class Publication extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'userid',
        'title',
        'ipfs_hash',
        'local_path',
        'notarization',
        'notarized_at',
        'blockid',
    ];
}
