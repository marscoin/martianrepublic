<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Threads extends Model {

   	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'forum_threads';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $rules = array(
		);
}

?>