<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Citizen;

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


	public function citizen() {
        return $this->belongsTo(Citizen::class, 'author_id', 'userid');
    }

    public function replies() {
        return $this->hasMany(Posts::class, 'post_id', 'id')->with('replies', 'citizen');
    }

    public function allRepliesWithCitizen() {
        return $this->replies()->with('allRepliesWithCitizen');
    }


}

?>