<?php
namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Http\Controllers\Controller;
use App\Models\HDWallet;
use App\Models\CivicWallet;
use App\Includes\AppHelper;
use Illuminate\Support\Facades\Redirect;

class InventoryController extends Controller
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
			$wallet = CivicWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			
			$view = View::make('inventory.dashboard');
			$view->wallet_open = $profile->civic_wallet_open;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->balance = 0; //for now, could move to stats helper function as well
			
			if ($wallet) {
				$cur_balance = AppHelper::file_get_contents_curl("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$view->balance = ($cur_balance * 0.00000001);
				$view->public_address = $wallet['public_addr'];
			} else {
				$view->balance = 0;
				$view->public_address = "";
			}

			return $view;


		}else{
            return redirect('/login');
        }

		
	}






}
