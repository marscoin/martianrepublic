<?php
namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Http\Controllers\Controller;
use App\Models\HDWallet;

class InventoryController extends BaseController
{

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public function __construct()
	{
	}


	//Get all inventory data and display table
	//
    protected function showAll()
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return Redirect::to('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return Redirect::to('/twofachallenge');
				}
			}
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('inventory.dashboard');
			$view->gravtar_link  = $gravtar_link;
			$view->network = AppHelper::stats()['network'];
			$view->coincount = AppHelper::stats()['coincount'];
			$view->balance = 0; //for now, could move to stats helper function as well
			return $view;


		}else{
            return Redirect::to('/login');
        }

		
	}






}
