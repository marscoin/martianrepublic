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
use App\Models\CivicWallet;
use App\Models\Citizen;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use App\Exceptions\Handler;
use Exception;
use Carbon\Carbon;


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
			$wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$citcache = Citizen::where('userid', '=', $uid)->first();
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$view = View::make('citizen.registry');
			$view->wallet_open = $profile->civic_wallet_open;
			// $view->network = AppHelper::stats()['network'];
			// $view->coincount = AppHelper::stats()['coincount'];
			$view->isCitizen = $profile->citizen;
			$view->citcache = $citcache;
			$view->isGP  = $profile->general_public;
			$view->mePublic = Feed::where('userid', '=', $uid)->where('tag', '=', "GP")->first();
			$view->meCitizen = Feed::where('userid', '=', $uid)->where('tag', '=', "CT")->first();
			$view->feed = Feed::where('userid', '=', $uid)->whereNotNull('mined')->whereNotIn('tag', ['GP','CT'])->orderBy('created_at', 'desc')->get();
			$view->endorsed = array();
			
			//6462 is our test account Roberta Draper
			$view->everyPublic = DB::select('SELECT u.*, p.*, c.*, ( SELECT f.txid FROM feed f WHERE f.userid = u.id AND f.tag = "GP" ORDER BY f.id DESC LIMIT 1) AS txid, ( SELECT f.mined FROM feed f WHERE f.userid = u.id AND f.tag = "GP" ORDER BY f.id DESC LIMIT 1) AS mined FROM users u JOIN profile p ON u.id = p.userid JOIN citizen c ON u.id = c.userid WHERE EXISTS ( SELECT 1 FROM feed f WHERE f.userid = u.id AND f.tag = "GP") AND u.id NOT IN (6462, 7601) ORDER BY mined DESC;');
			$view->everyCitizen = DB::select('SELECT u.*, p.*, c.*, ( SELECT f.txid FROM feed f WHERE f.userid = u.id AND f.tag = "CT" ORDER BY f.id DESC LIMIT 1 ) AS txid, ( SELECT f.mined FROM feed f WHERE f.userid = u.id AND f.tag = "CT" ORDER BY f.id DESC LIMIT 1 ) AS mined FROM users u JOIN profile p ON u.id = p.userid JOIN citizen c ON u.id = c.userid WHERE EXISTS ( SELECT 1 FROM feed f WHERE f.userid = u.id AND f.tag = "CT" ) AND u.id NOT IN (6462) ORDER BY mined DESC; ');
			$view->everyApplicant = DB::select('select profile.userid, users.fullname, hd_wallet.public_addr as address from users, profile, hd_wallet where profile.userid = users.id and users.id = hd_wallet.user_id and profile.has_application = 1 ORDER BY profile.userid DESC ');
			$view->recentActivityCount = Feed::where('userid', $uid)->where('mined', '>=', Carbon::now()->subDay())->count();

			if ($wallet) {
				$view->balance = AppHelper::getMarscoinBalance($wallet['public_addr']);
				$view->public_address = $wallet['public_addr'];
				$view->endorsed = Feed::where('message', '=', $wallet['public_addr'])->where('tag', '=', "ED")->get();
			} else {
				$view->balance = 0;
				$view->public_address = "";
			}

			if($profile->general_public )
			{
				try
				{
					$view->user = AppHelper::getUserFromCache($wallet['public_addr']);
				}
				catch(Exception $e) {
					$view->user = AppHelper::addUserToLocalCache($wallet['public_addr']);
					$view->user = AppHelper::getUserFromCache($wallet['public_addr']);
				}
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
				$view->balance = AppHelper::getMarscoinBalance($wallet->public_addr);
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


	protected function printout2()
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();
			$view = View::make('citizen.themes.printout2');
			$view->fullname = Auth::user()->fullname;
			
			if ($wallet) {
				$view->balance = AppHelper::getMarscoinBalance($wallet->public_addr);
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



	protected function printout3()
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();
			$view = View::make('citizen.themes.printout3');
			$view->fullname = Auth::user()->fullname;
			
			if ($wallet) {
				$view->balance = AppHelper::getMarscoinBalance($wallet->public_addr);
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




	//Get profile view of another Martian Citizen
	//
    protected function showId($address)
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			//Martian user we are looking at
			$martian = HDWallet::where('public_addr', '=', $address)->first();
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$view = View::make('citizen.martian');
			$view->wallet_open = $profile->wallet_open;
			$view->network = AppHelper::stats()['network'];
			$view->coincount = AppHelper::stats()['coincount'];
			$view->isCitizen = $profile->citizen;

			
			//print_r($view->isCitizen);
			//die();
			$view->isGP  = $profile->general_public;
			$view->mePublic = Feed::where('userid', '=', $martian->user_id)->where('tag', '=', "GP")->first();
			$view->meCitizen = Feed::where('userid', '=', $martian->user_id)->where('tag', '=', "CT")->first();
			$view->feed = Feed::where('userid', '=', $martian->user_id)->whereNotNull('mined')->whereNotIn('tag', ['GP','CT'])->orderBy('created_at', 'desc')->get();
			$view->endorsed = Feed::where('userid', '=', $martian->user_id)->where('tag', '=', "ED")->get();
			
			//print_r(is_null($view->meCitizen));
			//die();
			$view->activity = DB::select('select profile.userid, users.fullname, feed.tag, feed.mined  from feed, users, profile where feed.userid = profile.userid and profile.userid = users.id ORDER BY feed.id DESC limit 3');


			if ($wallet) {
				$cur_balance = AppHelper::file_get_contents_curl("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$view->balance = ($cur_balance * 0.00000001);
				$view->public_address = $wallet['public_addr'];
				$view->endorsed = Feed::where('message', '=', $wallet['public_addr'])->where('tag', '=', "ED")->get();
			} else {
				$view->balance = 0;
				$view->public_address = "";
			}

			$view->public_address = $address;

			if($profile->general_public ){
				try
				{
					$view->user = AppHelper::getUserFromCache($address);
				}
				catch(Exception $e) {
					$view->user = AppHelper::addUserToLocalCache($address);
					$view->user = AppHelper::getUserFromCache($address);
				}
			}

			return $view;


		}else{
            return redirect('/login');
        }

		
	}



}
