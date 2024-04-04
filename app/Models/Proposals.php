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
	public static $rules = [];

	/**
     * Check for open proposals.
     * 
     * @return int Number of open proposals.
     */
    public static function countOpenProposals()
    {
        // Count the number of proposals where 'active' is true (1).
        return self::where('active', 1)->count();
    }

}

?>