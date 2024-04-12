<?php
namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Feed;
use App\Models\User;
use App\Models\Proposals;
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
			
			$view->everyApplicant = DB::select('SELECT profile.userid, users.fullname, citizen.*, civic_wallet.public_addr AS address FROM users, profile, civic_wallet, citizen WHERE profile.userid = users.id AND users.id = civic_wallet.user_id AND citizen.userid = users.id AND profile.has_application = 1 AND users.id NOT IN (6462)');
			
			$view->recentActivityCount = Feed::where('userid', $uid)->where('mined', '>=', Carbon::now()->subDay())->count();

			if ($wallet) {
				$view->balance = 0;// AppHelper::getMarscoinBalance($wallet['public_addr']);
				$view->public_address = $wallet['public_addr'];
				$view->endorsed = Feed::where('message', '=', $wallet['public_addr'])->where('tag', '=', "ED")->get();
			} else {
				$view->balance = 0;
				$view->public_address = "";
			}

			// if($profile->general_public )
			// {
			// 	try
			// 	{
			// 		$view->user = AppHelper::getUserFromCache($wallet['public_addr']);
			// 	}
			// 	catch(Exception $e) {
			// 		$view->user = AppHelper::addUserToLocalCache($wallet['public_addr']);
			// 		$view->user = AppHelper::getUserFromCache($wallet['public_addr']);
			// 	}
			// }
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
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$view = View::make('citizen.printout');
			$citcache = Citizen::where('userid', '=', $uid)->first();
			$view->fullname = $citcache->firstname . " " . $citcache->lastname;
			
			if ($civic_wallet) {
				$view->public_address = $civic_wallet['public_addr'];
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
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$view = View::make('citizen.themes.printout2');
			$citcache = Citizen::where('userid', '=', $uid)->first();
			$view->fullname = $citcache->firstname . " " . $citcache->lastname;
			
			if ($civic_wallet) {
				$view->public_address = $civic_wallet['public_addr'];
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
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$view = View::make('citizen.themes.printout3');
			$citcache = Citizen::where('userid', '=', $uid)->first();
			$view->fullname = $citcache->firstname . " " . $citcache->lastname;
			
			if ($civic_wallet) {
				$view->public_address = $civic_wallet['public_addr'];
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
			$martian = Citizen::where('public_address', '=', $address)->first();
			$martian_profile = Profile::where('userid', '=', $martian->userid)->first();
			$martian_proposals = Proposals::where('user_id', '=', $martian->userid)->count();
			$myprofile = Profile::where('userid', '=', $uid)->first();

			if (!$myprofile) {
				return redirect('/twofa');
			} else {
				if ($myprofile->openchallenge == 1 || is_null($myprofile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$view = View::make('citizen.martian');
			$view->wallet_open = $myprofile->civic_wallet_open;


			$view->isCitizen = $martian_profile->citizen;
			$view->endorsements = $martian_profile->endorse_cnt;
			$view->proposals = $martian_proposals;
			$view->isGP  = $martian_profile->general_public;
			$view->mePublic = Feed::where('userid', '=', $martian->userid)->where('tag', '=', "GP")->first();
			$view->meCitizen = Feed::where('userid', '=', $martian->userid)->where('tag', '=', "CT")->first();
			$view->endorsed = Feed::where('userid', '=', $martian->userid)->where('tag', '=', "ED")->count();


			$view->citcache = $martian;

			return $view;


		}else{
            return redirect('/login');
        }

		
	}



}
