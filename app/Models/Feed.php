<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Cache for embedded data located or injected into the Marscoin Blockchain.
 *
 * @var string
 */
class Feed extends Model {

    protected $table = 'feed';

    public function user()
    {
        return $this->belongsTo(User::class, 'userid'); // Assuming 'userid' is the foreign key in 'feeds' table
    }

}

?>