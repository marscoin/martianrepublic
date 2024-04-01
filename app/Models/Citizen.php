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


    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

}

?>