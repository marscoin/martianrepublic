<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ballots extends Model {

   	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ballots';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $rules = array(
		);
}

?>