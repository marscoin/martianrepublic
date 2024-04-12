<?php
namespace App\Http\Controllers\Congress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Feed;
use App\Models\IPFSRoot;
use App\Models\User;
use App\Models\HDWallet;
use App\Models\CivicWallet;
use App\Models\Proposals;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CongressController extends Controller
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
			$view = View::make('congress.dashboard');
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->civic_wallet_open;
			return $view;


		}else{
            return redirect('/login');
        }

		
	}

	// Show Voting Page
	protected function showVoting()
	{
		if (Auth::check()) {
			$startTime = microtime(true);
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$proposals = DB::table('proposals')->select('proposals.*')->where('active', '=', '1')->get();
			$oldproposals = DB::table('proposals')->select('proposals.*')->where('active', '=', '0')->get();
			
			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time: {$executionTime} seconds");
			
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			//check if any proposals have expired... if so, archive.
			Proposals::where(DB::raw('DATE_ADD(mined, INTERVAL duration DAY)'), '<', now())
        ->update(['active' => 0]);

			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time2: {$executionTime} seconds");

			if(!$profile->citizen){
				$view = View::make('congress.noteligableyet');
			}else{
				$view = View::make('congress.voting');
			}

			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time4: {$executionTime} seconds");

			$view->proposals = $proposals;
			$view->oldproposals = $oldproposals;
			$view->fullname = Auth::user()->fullname;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->civic_wallet_open;
			$view->public_address = $civic_wallet->public_addr;
			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time4a: {$executionTime} seconds");


			return $view;
		}else{
            return redirect('/login');
        }
	}


	protected function acquireBallot($propid)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();
			$proposal = Proposals::where('id', '=', $propid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			
			$view = View::make('congress.ballot');
			
			if ($wallet) {
				$cur_balance = AppHelper::file_get_contents_curl("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$view->balance = ($cur_balance * 0.00000001);
				$view->public_address = $wallet->public_addr;
			} else {
				$view->balance = 0;
			}

			$view->proposal = $proposal;
			$view->fullname = Auth::user()->fullname;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->wallet_open;
			$view->propid = $propid;
			$view->random_bytes = bin2hex(random_bytes(16));

			// echo "Hello, World";
			// die();
			$json = AppHelper::file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;

			return $view;
		}else{
            return redirect('/login');
        }
	}


}
