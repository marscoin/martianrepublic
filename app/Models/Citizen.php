<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Cache for citizen form data to make onboarding easier
 *
 */
class Citizen extends Model
{
    protected $table = 'citizen';

    protected $fillable = [
        'firstname', 'lastname', 'userid', 'displayname', 'public_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class, 'userid', 'userid');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}
