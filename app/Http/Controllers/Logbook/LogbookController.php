<?php
namespace App\Http\Controllers\Logbook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Http\Controllers\Controller;
use App\Models\HDWallet;
use App\Models\CivicWallet;
use App\Models\Publication;
use App\Includes\AppHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LogbookController extends Controller
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
    public function showAll()
	{
		$view = View::make('logbook.dashboard');
		$view->allPublications = Publication::orderBy('id', 'desc')->get();

		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = CivicWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return Redirect::to('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return Redirect::to('/twofachallenge');
				}
			}

			$view->wallet_open = $profile->civic_wallet_open;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->myPublications = Publication::where('userid', '=', $uid)->orderBy('created_at', 'desc')->get();
			$view->balance = 0;

			if ($wallet) {
				$view->balance = AppHelper::getMarscoinBalance($wallet['public_addr']);
				$view->public_address = $wallet['public_addr'];
			} else {
				$view->balance = 0;
				$view->public_address = "";
			}
		} else {
			$view->wallet_open = false;
			$view->isCitizen = false;
			$view->isGP = false;
			$view->myPublications = collect();
			$view->balance = 0;
			$view->public_address = "";
		}

		return $view;

		
	}






}
