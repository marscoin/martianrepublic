<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proposals extends Model {

   	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'proposals';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $rules = array(
		);
}

?>