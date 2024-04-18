<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Cache for citizen form data to make onboarding easier
 *
 * @var string
 */
class Citizen extends Model {

    protected $table = 'citizen';
    protected $fillable = [
        'firstname', 'lastname', 'userid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function feeds() {
        return $this->hasMany(Feed::class, 'userid', 'userid');
    }

    public function posts() {
        return $this->hasMany(Posts::class, 'author_id');
    }

}

?>