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
    protected $appends = ['profile_image'];
    protected $dates = ['mined'];
    protected $casts = [
        'mined' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid'); // Assuming 'userid' is the foreign key in 'feeds' table
    }

    public function getProfileImageAttribute()
    {
        if (!$this->address) {
            return null;
        }

        return 'https://martianrepublic.org/assets/citizen/' . $this->address . '/profile_pic.png';
    }

    public function citizen()
    {
        return $this->belongsTo(Citizen::class, 'userid');
    }

}

?>