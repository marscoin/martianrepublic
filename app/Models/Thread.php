<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * The database table used by the model.
     *
     */
    protected $table = 'forum_threads';

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'pinned',
        'locked',
        'first_post_id',
        'last_post_id',
        'reply_count',
        'proposal_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     */
    public static $rules = [
    ];
}
