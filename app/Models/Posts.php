<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Citizen;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id 
 * @property int $thread_id
 * @property int $post_id
 * @property-read \Illuminate\Database\Eloquent\Collection<Posts> $replies 
 * @property-read Posts|null $allRepliesWithCitizen
 * @property-read Citizen $citizen
 */
class Posts extends Model {

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
	public static $rules = array();


	public function citizen(): BelongsTo 
    {
        return $this->belongsTo(Citizen::class, 'author_id', 'userid');
    }

    public function replies(): HasMany 
    {
        return $this->hasMany(Posts::class, 'post_id', 'id')->with('replies', 'citizen');
    }

    public function allRepliesWithCitizen(): HasMany
    {
        return $this->replies()->with('allRepliesWithCitizen');
    }


}

?>