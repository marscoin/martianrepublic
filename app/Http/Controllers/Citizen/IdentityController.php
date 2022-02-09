<?php
namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Feed;
use App\Models\User;
use App\Models\HDWallet;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;



class IdentityController extends Controller
{

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public function __construct()
	{
	}


	//Get all public registrations and display table
	//
    protected function showAll()
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();


			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('citizen.registry');
			$view->wallet_open = $profile->wallet_open;
			$view->gravtar_link  = $gravtar_link;
			$view->network = AppHelper::stats()['network'];
			$view->coincount = AppHelper::stats()['coincount'];
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->mePublic = Feed::where('userid', '=', $uid)->where('tag', '=', "GP")->first();
			$view->meCitizen = Feed::where('userid', '=', $uid)->where('tag', '=', "CT")->first();
			$view->everyPublic = DB::select('select * from feed, users, profile where feed.userid = profile.userid and profile.userid = users.id and users.id = ? and feed.tag = "GP"', array($uid));
			$view->everyCitizen = DB::select('select * from feed, users, profile where feed.userid = profile.userid and profile.userid = users.id and users.id = ? and feed.tag = "CT"', array($uid));

			if ($wallet) {
				$cur_balance = AppHelper::file_get_contents_curl("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$view->balance = ($cur_balance * 0.00000001);
				$view->public_address = $wallet['public_addr'];
			} else {
				$view->balance = 0;
				$view->public_address = "";
			}

			if($profile->general_public ){
				$view->user = AppHelper::getUserFromCache($wallet['public_addr']);
			}
			return $view;


		}else{
            return redirect('/login');
        }

		
	}



	protected function printout()
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();
			$view = View::make('citizen.printout');
			$view->fullname = Auth::user()->fullname;
			
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