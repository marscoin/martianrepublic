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
use App\Models\Proposals;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use Illuminate\Support\Facades\DB;

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
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('congress.dashboard');
			$view->gravtar_link  = $gravtar_link;
			$view->network = AppHelper::stats()['network'];
			$view->coincount = AppHelper::stats()['coincount'];
			$view->balance = 0; //for now, could move to stats helper function as well
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			return $view;


		}else{
            return redirect('/login');
        }

		
	}




	// Show Voting Page
	protected function showVoting()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();
			$proposals = DB::table('proposals')->leftJoin('ballots', 'proposals.id', '=', 'ballots.proposalid')->select('proposals.*', 'ballots.btxid', 'ballots.proposalid')->where('active', '=', '1')->get();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			
			$view = View::make('congress.voting');
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));

			if ($wallet) {
				$cur_balance = AppHelper::file_get_contents_curl("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$view->balance = ($cur_balance * 0.00000001);
				$view->public_address = $wallet->public_addr;
			} else {
				$view->balance = 0;
			}

			$view->proposals = $proposals;
			$view->gravtar_link  = $gravtar_link;
			$view->fullname = Auth::user()->fullname;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->wallet_open;



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
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));

			if ($wallet) {
				$cur_balance = AppHelper::file_get_contents_curl("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$view->balance = ($cur_balance * 0.00000001);
				$view->public_address = $wallet->public_addr;
			} else {
				$view->balance = 0;
			}

			$view->proposal = $proposal;
			$view->gravtar_link  = $gravtar_link;
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
