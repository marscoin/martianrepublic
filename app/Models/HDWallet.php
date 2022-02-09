<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HDWallet extends Model {

   	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'hd_wallet';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $rules = array(
		'password'=>'required|between:6,32'
		);
}

?>