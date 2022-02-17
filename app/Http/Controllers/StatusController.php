<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use Illuminate\Support\Facades\View;


class StatusController extends Controller {

/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public function __construct() {
	}



	protected function showStatus()
	{
		$view = View::make('status');
		return $view;
	}






}


?>