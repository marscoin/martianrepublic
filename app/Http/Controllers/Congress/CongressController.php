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
			$proposals = Proposals::all();
			$IPFS = IPFSRoot::all();

			// print_r($IPFS);
			// die();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			
			$view = View::make('congress.voting');
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));

			if (count($IPFS) > 0)
				$view->ipfs_root_hash = $IPFS->last()->folder_hash;
			else
				$view->ipfs_root_hash = null;

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


	protected function postCreateProposal()
	{
		//$req = new Request;

		$uid = Auth::user()->id;
		$profile = Profile::where('userid', '=', $uid)->first();
		$hd_wallet = HDWallet::where('user_id', '=', $uid)->get();
		$prop_count = Proposals::all()->count();

		$IPFS = new IPFSRoot;
		$proposal = new Proposals;

		// print_r($proposal);
		// die();

		$proposal->user_id = $uid;
		$proposal->title = Input::get('title');
		$proposal->description = Input::get('description');
		$proposal->category = Input::get('category');
		$proposal->discussion = Input::get('discussion');
		$proposal->author = Auth::user()->fullname;

		$proposal->title = str_replace(' ', '_', $proposal->title);

		// $proposal->yes_vote_addr = Input::get('yes_vote');
		// $proposal->no_vote_addr = Input::get('no_vote');
		// $proposal->null_vote_addr = Input::get('null_vote');

		// now.. Place in ipfs folder before we re-publish the ipfs folder location
		// DEVELOPMENT ENV
		$dir = "/var/www/marswallet2/ipfs_proposals/";
		$ipfs_filename =  "Proposal_#" . $prop_count . "_" . $proposal->title;

		$full_name = $dir . $ipfs_filename;
		//1) store folder hash and find hash pertaining to newly added 
		// Try: Placing proposal in directory
		try {
			file_put_contents($full_name . '.json', $proposal);

			try {
				//$ipfs_cmd = 'curl -X POST -H "Content-Type: multipart/form-data" -F file=@' . $dir . ' "http://127.0.0.1:5001/api/v0/add?recursive=true"';
				$ipfs_cmd = "runuser -l  user -c 'ipfs add -r -p /var/www/marswallet2/ipfs_proposals'";
				$post_ipfs = shell_exec($ipfs_cmd);

				// parse ipfs add command return;
				$post_ipfs = explode("added ", $post_ipfs);



				$ipfs_final = [];
				// traverse ipfs hashes, store in object
				foreach ($post_ipfs as $ipfs_obj) {
					if (empty($ipfs_obj))
						continue;
					trim($ipfs_obj);


					$cut_ipfs = explode(" ", $ipfs_obj);
					$ipfs_final[$cut_ipfs[0]] = $cut_ipfs[1];
				}


				// traverse ipfs object and pull: Root dir && uploaded proposal hash
				foreach ($ipfs_final as $hash => $name) {

					if (str_contains($name, $ipfs_filename))
						$ipfs_proposal_hash = $hash;

					if (strcmp($name, "ipfs_proposals"))
						$ipfs_dir_hash = $hash;
				}
				//die();



				$proposal->ipfs_hash = $ipfs_proposal_hash;

				$IPFS->folder_hash = $ipfs_dir_hash;
				$IPFS->author = Auth::user()->fullname;
				$IPFS->save();
				$proposal->save();
			} catch (Exception $e) {
				$er =  $e->getMessage();
				$post_ipfs = null;
				throw new Exception("Error Occured while executing ipfs add command: \n" . $er);
			}
		} catch (Exception $e) {
			$er = $e->getMessage();
			throw new Exception("Error Occured while trying to place proposal in ipfs dir: \n" . $er);
		}

		//$post_ipfs = json_decode($post_ipfs);

		// echo "<pre>";
		// print_r($post_ipfs);
		// echo "</pre>";
		// die();

		//$proposal->ipfs_hash = $post_ipfs;

		// try{
		// 	$IPFS->save();
		// 	$proposal->save();
		// }
		// catch(Exception $e)
		// {
		// 	echo "Error Saving models...";
		// }

		return redirect('congress/voting')->with('message', 'Proposal Created!');
	}







}
