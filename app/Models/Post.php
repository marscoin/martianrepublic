<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $thread_id
 * @property int $post_id
 * @property-read Collection<Posts> $replies
 * @property-read Posts|null $allRepliesWithCitizen
 * @property-read Citizen $citizen
 */
class Post extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'forum_posts';

    protected $fillable = [
        'thread_id',
        'author_id',
        'content',
        'authorName',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'author_id', 'userid');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Post::class, 'post_id', 'id')->with('replies', 'citizen');
    }

    public function allRepliesWithCitizen(): HasMany
    {
        return $this->replies()->with('allRepliesWithCitizen');
    }
}
