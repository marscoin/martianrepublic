<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Profile;
use App\Models\HDWallet;
use App\Models\Proposals;
use App\Models\IPFSRoot;
use App\Models\User;
use App\Models\Voucher;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use App\Models\CivicWallet;
use App\Models\Feed;
use App\Models\Citizen;
use Illuminate\Support\Facades\Log;
use Exception;

class DashboardController extends Controller
{

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public function __construct()
	{
	}


	public function index()
    {
        return redirect("/wallet/dashboard");
    }


	protected function showLogout()
	{

		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();

			$profile->wallet_open = 0;
			$profile->civic_wallet_open = 0;
			$profile->save();
		}

		Auth::logout();

		return redirect('/login');
	}


	protected function show2FA(Request $request)
	{

		if (Auth::check()) {
			$email = Auth::user()->email;
			$uid = Auth::user()->id;
			$google2fa = app(Google2FA::class);
			$secret = $request->input('secret');
			if ($secret) {
				$view = View::make('wallet.hello2fa');
				$view->qrcode_image = NULL;
				$profile = Profile::where('userid', '=', Auth::user()->id)->first();
				$google2fa_secret = $profile->twofakey;
				$valid = $google2fa->verifyKey($google2fa_secret, $secret);



				if ($valid) {
					$profile->twofaset = 1;
					$profile->save();
					$view->isvalid = "Success";
					return redirect('wallet/dashboard')->with('success', 'Two-factor authentication enabled! Your account is now secured.');
				} else {
					$view->isvalid = "Failed";
				}



				return $view;
			} else {
				$profile = Profile::where('userid', '=', Auth::user()->id)->first();

				if (!$profile) {
					$key = $google2fa->generateSecretKey();
					$g2faUrl = $google2fa->getQRCodeUrl(
						'marscoinwallet',
						$email,
						$key
					);



					$profile = new Profile;
					$profile->userid = $uid;
					$profile->twofaset = 0;
					$profile->twofakey = $key;
					$profile->save();
					$writer = new Writer(
						new ImageRenderer(
							new RendererStyle(300),
							new ImagickImageBackEnd()
						)
					);

					$view = View::make('wallet.hello2fa');
					$view->isvalid = NULL;
					$view->qrcode_image = base64_encode($writer->writeString($g2faUrl));
					return $view;
				} else if ($profile && $profile->twofaset == 0) {


					$key = $google2fa->generateSecretKey();
					$g2faUrl = $google2fa->getQRCodeUrl(
						'marscoinwallet',
						$email,
						$key
					);

					$profile->userid = $uid;
					$profile->twofakey = $key;
					$profile->save();

					$writer = new Writer(
						new ImageRenderer(
							new RendererStyle(300),
							new ImagickImageBackEnd()
						)
					);
					$view = View::make('wallet.hello2fa');
					$view->isvalid = NULL;
					$view->qrcode_image = base64_encode($writer->writeString($g2faUrl));

					return $view;
				}
				// else
				// {
				// 	return redirect('wallet/dashboard');
				// }
			}
		} else {
			return redirect('/login');
		}
	}


	protected function show2FAChallenge(Request $request)
	{
		if (Auth::check()) {
			$email = Auth::user()->email;
			$uid = Auth::user()->id;
			$google2fa = app(Google2FA::class);
			$secret = $request->input('secret');



			if ($secret) {
				$profile = Profile::where('userid', '=', Auth::user()->id)->first();
				$google2fa_secret = $profile->twofakey;
				$valid = $google2fa->verifyKey($google2fa_secret, $secret);
				if ($valid) {

					$profile->openchallenge = 0;
					$profile->save();

					return redirect('wallet/dashboard');
				} else {
					$view = View::make('wallet.challenge2fa');
					return $view;
				}
			} else {
				$view = View::make('wallet.challenge2fa');
				return $view;
			}
		} else {
			return redirect('/login');
		}
	}


	protected function showChallenge()
	{
		if (Auth::check()) {
			//check if 2FA is on. If not, go to 2FA screen first
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			if (!$profile) {
				return redirect('/twofa');
			} else {
				$is2faset = $profile->twofaset;
				$profile->openchallenge = 1;
				$profile->wallet_open = 0;
				$profile->save();
				if (!$is2faset) {
					return redirect('/twofa');
				} else {
					return redirect('/twofachallenge');
				}
			}
		} else {
			return redirect('/login');
		}
	}

	protected function showDashboard()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->get();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$view = View::make('wallet.dashboard');
			$view->public_addr = "";

			// Collect ALL wallet addresses for this user
			$allAddresses = [];
			if ($civic_wallet) {
				$allAddresses[] = $civic_wallet->public_addr;
			}
			$hdWallets = HDWallet::where('user_id', '=', $uid)->get();
			foreach ($hdWallets as $hw) {
				if (!in_array($hw->public_addr, $allAddresses)) {
					$allAddresses[] = $hw->public_addr;
				}
			}

			$isWalletOpen = ($profile->civic_wallet_open > 0 || $profile->wallet_open > 0);

			if ($isWalletOpen && count($allAddresses) > 0) {
				// Primary address for transaction display (civic first)
				$view->public_addr = $civic_wallet ? $civic_wallet->public_addr : $allAddresses[0];
				// Pass all addresses for aggregated transaction view
				$view->all_addresses = $allAddresses;
				$view->has_civic_wallet = ($civic_wallet !== null);
				$view->has_wallet = true;
				$view->wallet_open = true;
			} else {
				$view->public_addr = "";
				$view->all_addresses = [];
				$view->has_civic_wallet = ($civic_wallet !== null);
				$view->has_wallet = count($allAddresses) > 0;
				$view->wallet_open = false;
			}

			$view->transactions = array();

			// Cache dashboard stats to reduce DB/API calls on every page load
			$view->coincount = AppHelper::getMarscoinTotalAmount();
			$view->forum_count = Cache::remember('forum_recent_count', 300, function () {
				return AppHelper::checkForRecentPosts();
			});
			$view->proposal_count = Cache::remember('proposal_open_count', 300, function () {
				return Proposals::countOpenProposals();
			});
			$citizenStatus = AppHelper::getCitizenStatus($uid);
			$view->citizen_status = $citizenStatus ? $citizenStatus->type : 'GP';

			return $view;
		} else {
			return redirect('/login');
		}
	}

	// Wallet history

	// Create Wallet Wizard (full-page, replaces modal)
	public function showCreateWallet()
	{
		if (!Auth::check()) return redirect('/login');
		$uid = Auth::user()->id;
		$profile = Profile::where('userid', $uid)->first();
		if (!$profile) return redirect('/twofa');
		if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) return redirect('/twofachallenge');

		$view = View::make('wallet.create');
		$data = json_decode(file_get_contents("/home/mars/constitution/marswallet.json"), true);
		$view->SALT = $data['salt'];
		$view->iv = $data['iv'];
		return $view;
	}

	// Show HD Wallet (Not Open)
	// /wallet/dashboard/hd
	protected function listHDWallet(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();

			$key = $request->input('key');
			if($key == "none")
			{
				Log::debug("Something went wrong with key storage");
				$profile->wallet_open = 0;
				$profile->civic_wallet_open = 0;
				$profile->save();
				return redirect('/wallet/dashboard');
			}

			//if wallet currently open, redirect to hd-open
			if($profile->wallet_open > 0){
				return redirect('/wallet/dashboard/hd-open');
			}
			if($profile->civic_wallet_open > 0){
				return redirect('/wallet/dashboard/hd-open');
			}

			// list of all user wallets.
			$wallets = HDWallet::where('user_id', '=', $uid)->get();

			// only one civic wallet ever...
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();

			// get balance of wallets.
			foreach($wallets as $wallet)
			{
				$wallet->balance = AppHelper::getMarscoinBalance($wallet->public_addr);
			}

			// handle redirects...
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			if ($profile->wallet_open >= 1 && !is_null($wallets)) {
				return redirect('/wallet/dashboard/hd-open');
			}

			$view = View::make('wallet.hd');
			

			if(!is_null($civic_wallet))
			{
				$view->civic_balance = AppHelper::getMarscoinBalance($civic_wallet->public_addr);
				$view->network = AppHelper::getMarscoinNetworkInfo();
			$view->user = Auth::user();
			$view->citizen = Citizen::where('userid', '=', $uid)->first();
			$view->profile = $profile;
				$view->public_addr = $civic_wallet->public_addr;

				$view->general_public = $profile->general_public;
				$view->citizen = $profile->citizen;
				$view->applied = $profile->has_application;
				$view->wallet_open = $profile->civic_wallet_open;
			}


			if (is_null($civic_wallet)) {
				$view->wallet_open = 0;
				$view->wallets = null;
				$view->encrypted_seed = null;
				$view->civic_wallet = null;
				$view->public_addr = null;
				$view->citizen = null;
				$view->applied = null;
			} else {
				$view->civic_wallet = $civic_wallet;
				$view->wallet_open = $profile->wallet_open;
				$view->encrypted_seed = $civic_wallet->encrypted_seed;
				$view->wallets = $wallets;
				$view->citizen = null;
				$view->applied = null;
			}

			$data = json_decode(file_get_contents("/home/mars/constitution/marswallet.json"), true);
			$view->SALT = $data['salt'];
			$view->iv = $data['iv'];
			return $view;
		} else {
			return redirect('/login');
		}
	}

	// Show HD Wallet (Open)
	// /wallet/dashboard/hd-open
	protected function showHDOpen(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallets = HDWallet::where('user_id', '=', $uid)->get();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$data = json_decode($request->input("wallet"));

			if ($data || ($wallets || $civic_wallet)) {
				// Fetch wallet info from profile if this is not a newly unlocked wallet
				if(is_null($data)){
					$data = HDWallet::where('id', '=', $profile->wallet_open)->first();
				}
				if(is_null($data)){
					$data = CivicWallet::where('user_id', '=', $uid)->first();
				}
				if (is_null($data)) {
					$profile->wallet_open = 0;
					$profile->civic_wallet_open = 0;
					$profile->save();
					return redirect('/wallet/dashboard/hd');
				}

				$view = View::make('wallet.hd-open');

				$codes = json_decode(file_get_contents("/home/mars/constitution/marswallet.json"), true);
				$view->SALT = $codes['salt'];
				$view->iv = $codes['iv'];

				$view->public_addr = $data->public_addr;
				$view->encrypted_seed = $data->encrypted_seed;
				$view->fullname = Auth::user()->fullname;
				$view->wallet_open = $data->id;

				$isCivicWalletOpen = $civic_wallet && $data && $civic_wallet->id === $data->id;

				if($isCivicWalletOpen){
					$profile->wallet_open = 0;
					$profile->civic_wallet_open = $civic_wallet->id;
					$view->is_civic_wallet = $isCivicWalletOpen;
				}else{
					$profile->wallet_open = $data->id;
					$profile->civic_wallet_open = 0;
					$view->is_civic_wallet = 0;
				}
				$profile->save();

				// Pass civic wallet address for HD discovery linking
				$view->civic_addr = $civic_wallet ? $civic_wallet->public_addr : '';

				return $view;
			}else{
				return redirect('/wallet/dashboard/hd');
			}

		} else {
			return redirect('/login');
		}
	}


	// Show HD Wallet (Close)
	protected function showHDClose()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			Log::info("set open wallet to zero");
			$profile->wallet_open = 0;
			$profile->civic_wallet_open = 0;
			$profile->save();

			// Render the close view so client-side JS can clear localStorage,
			// then redirect via JS. Previously this was a server redirect which
			// meant the close() JS function never ran and keys persisted.
			$view = View::make('wallet.hd-close');
			$view->wallet_open = 0;
			return $view;

		} else {
			return redirect('/login');
		}
	}


	protected function forgetWallet(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$walletid = $request->input('hdwallet_id');

			// Civic wallet reset (only if no on-chain activity)
			if ($request->input('civic_reset')) {
				$hasActivity = Feed::where('userid', $uid)->whereIn('tag', ['GP', 'CT', 'ED'])->exists();
				if ($hasActivity) {
					return response()->json(['error' => 'Cannot reset: on-chain civic activity exists'], 403);
				}
				$civic = CivicWallet::where('id', $walletid)->where('user_id', $uid)->first();
				if ($civic) {
					$profile = Profile::where('userid', $uid)->first();
					if ($profile) {
						$profile->civic_wallet_open = 0;
						$profile->has_application = 0;
						$profile->save();
					}
					Citizen::where('userid', $uid)->delete();
					$civic->delete();
					return response()->json(['success' => 'Civic wallet reset successfully']);
				}
			}

			// Regular HD wallet delete
			$wallet = HDWallet::where('id', $walletid)->where('user_id', $uid)->firstOrFail();
			$wallet->delete();

			return response()->json(['success' => 'Wallet deleted successfully']);
		}
		return response()->json(['error' => 'Unauthorized'], 403);
	}


	public function postCreateWallet(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$hd_wallet = HDWallet::where('user_id', '=', $uid)->get();
			$civic = CivicWallet::where('user_id', '=', $uid)->first();

			Log::info("See if user has civic wallet");
			// Check if the user has a Civic wallet already, even if it's not opened
			$civicExists = CivicWallet::where('user_id', '=', $uid)->exists();

			// user must insert password.. no null accepted...

			// 3 States of Wallets: 
			// 1) NO civic + No wallets
			// 2) Civic + No wallets
			// 3) Civic + wallets
			if($request->input('wallet_name') == "Imported")
			{
				Log::debug("imported wallets are not being stored ... yet");
				$new_wallet = new HDWallet;
				$new_wallet->encrypted_seed = "ADHOC";
				$new_wallet->public_addr = $request->input('public_addr');
				$new_wallet->backup = 1;
				$new_wallet->user_id = $uid;
				$new_wallet->wallet_type = $request->input('wallet_name');;
				$new_wallet->save();
				$profile->wallet_open = $new_wallet->id;
				$profile->civic_wallet_open = 0;
				$profile->save();
				return redirect('/wallet/dashboard/hd-open')->with('message', 'Wallet Successfully Opened!');
			}
			else if (!$civicExists && $profile->civic_wallet_open == 0 && $profile->wallet_open == 0) 
			{
				Log::debug("first wallet created has to be civic wallet");
				$new_civic_wallet = new CivicWallet;
				if(empty($request->input('password')))
					$new_civic_wallet->encrypted_seed = "UNSET";
				else
					$new_civic_wallet->encrypted_seed = $request->input('password');
				$new_civic_wallet->public_addr = $request->input('public_addr');

				$new_civic_wallet->backup = 1;

				$new_civic_wallet->user_id = $uid;
				$new_civic_wallet->wallet_type = $request->input('wallet_name');
				$new_civic_wallet->save();
				
				$profile->civic_wallet_open = $new_civic_wallet->id;
				$profile->wallet_open = 0;
				$profile->save();
				return redirect('/wallet/dashboard/hd-open')->with('message', 'Wallet Successfully Opened!');
			}
			else if ($civicExists || $profile->civic_wallet_open == 1) 
			{
    			Log::debug("non-civic wallet being created");
				$new_wallet = new HDWallet;

				$encseed = $request->input('password');
				if(empty($encseed))
				{
					$new_wallet->encrypted_seed = "ADHOC";
				}
				else{
					$new_wallet->encrypted_seed = $request->input('password');
				}

				$new_wallet->public_addr = $request->input('public_addr');

				$new_wallet->backup = 1;

				$new_wallet->user_id = $uid;
				$new_wallet->wallet_type = $request->input('wallet_name');
				
				$new_wallet->save();

				$profile->wallet_open = $new_wallet->id;
				$profile->civic_wallet_open = 0;
				$profile->save();
				return redirect('/wallet/dashboard/hd-open')->with('message', 'Wallet Successfully Opened!');
			}
			else
			{
				Log::debug("no wallet entry created in local db cache");
				return redirect('/wallet/dashboard/hd');
			}
			return redirect('/wallet/dashboard/hd');

		} else {

			return redirect('/login');
		}
	}

	// GET
	// Open Pre-Existing Wallet
	public function getWallet(Request $request)
	{
		if (Auth::check()) {

			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();

			// Try HD wallet first, then civic wallet
			$hd_wallet = HDWallet::where('user_id', '=', $uid)->first();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();

			if ($hd_wallet) {
				$profile->wallet_open = $hd_wallet->id;
			} elseif ($civic_wallet) {
				$profile->civic_wallet_open = $civic_wallet->id;
			}
			$profile->save();

			return redirect('wallet/dashboard/hd-open')->with('message', 'Wallet Successfully Open!');
		} else {
			return redirect('/login');
		}
	}

	public function failWallet()
	{


		return redirect('wallet/dashboard/hd')->with('error', 'Wallet unlock failed. Please check your credentials and try again.');
	}


	// Logout of Wallet ONLY
	public function walletLogout()
	{
		if (Auth::check()) {

			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$profile->wallet_open = 0;
			$profile->civic_wallet_open = 0;
			$profile->save();
			return redirect('/wallet/dashboard');
		}

	}



	protected function showProfile()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$user = Auth::user();
			$citizen = $user->citizen;
			$view = View::make('wallet.profile');
			$view->network = AppHelper::getMarscoinNetworkInfo();
			$view->user = $user;
			$view->citizen = $citizen;
			$view->profile = $profile;

			return $view;
		} else {
			return redirect('/login');
		}
	}


	protected function showReports()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
				$user = Auth::user();
				$citizen = $user->citizen;
			$view = View::make('wallet.reports');
			$view->network = AppHelper::getMarscoinNetworkInfo();
			$view->user = $user;
			$view->citizen = $citizen;
			$view->profile = $profile;
			return $view;
		} else {
			return redirect('/login');
		}
	}



	protected function anchor()
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
			$view = View::make('wallet.anchor');

			if ($wallet) {
				$view->public_address = $wallet['public_addr'];
			} else {
				$view->balance = 0;
				$view->public_address = "";
			}
			return $view;
		} else {
			return redirect('/login');
		}
	}

	protected function showCamera()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
				$user = Auth::user();
				$citizen = $user->citizen;
			$view = View::make('wallet.camera');
			$view->email = Auth::user()->email;
			$view->network = AppHelper::getMarscoinNetworkInfo();
			$view->user = $user;
			$view->citizen = $citizen;
			$view->profile = $profile;

			return $view;
		} else {
			return redirect('/login');
		}
	}


}
