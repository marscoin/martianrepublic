<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

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

	// public function replies()
    // {
    //     return $this->hasMany(Posts::class, 'post_id', 'id');
    // }

	public function replies()
	{
		return $this->hasMany(Posts::class, 'post_id', 'id');
	}


}

?>