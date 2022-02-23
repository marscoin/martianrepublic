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
use App\Models\Profile;
use App\Models\HDWallet;
use App\Models\IPFSRoot;
use App\Models\User;
use App\Models\Voucher;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;

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


	protected function showLogout()
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();

			$profile->wallet_open = 0;
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


					$key = $profile->twofakey;
					$g2faUrl = $google2fa->getQRCodeUrl(
						'marscoinwallet',
						$email,
						$key
					);
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
					$local = $request->input('local');

					if($local == "true"){
						$profile->wallet_open = 1;
					}
					else if($local == "false"){
						$profile->wallet_open = 0;
					}
				

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
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('wallet.dashboard');
			$view->gravtar_link  = $gravtar_link;
			$view->public_addr = "";
			try{

				if ($wallet) {
					$cur_balance = file_get_contents("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
					$view->balance = ($cur_balance * 0.00000001);
					$view->public_addr = $wallet->public_addr;
					$json = $this->file_get_contents_curl("http://explore.marscoin.org/api/addr/{$wallet['public_addr']}/totalReceived");
					$received = json_decode($json, true);
					$view->received = ($received * 0.00000001);
					$json = $this->file_get_contents_curl("http://explore.marscoin.org/api/addr/{$wallet['public_addr']}/totalSent");
					$sent = json_decode($json, true);
					$view->sent = ($sent * 0.00000001);
				} else {
					$view->balance = 0;
					$view->received = 0;
					$view->sent = 0;
				}


				$view->transactions = array();
				$cur_price = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=marscoin&vs_currencies=usd"));
				$view->mars_price = $cur_price->marscoin->usd;
				
				$view->wallet_open = $profile->wallet_open;

				// $json = $this->file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
				// $network = json_decode($json, true);
				$json2 = $this->file_get_contents_curl('http://explore.marscoin.org/api/status?q=getTxOutSetInfo');
				$total = json_decode($json2, true);
				if ($total && count($total) > 0)
					$view->coincount = round($total['txoutsetinfo']['total_amount'], 2);
				else
					$view->coincount = 35000000;

				$incomes = array();
				$expenses = array();
				$balances = array();
				$balance = 0;
				$firstdate = "";
				$lastdate = 0;
				$a = 0;
				$b = 0;

				$view->voucher = false;
				$voucher = Voucher::where('user_account', '=', Auth::user()->email)->first();
				if ($voucher != null)
					$view->voucher = true;

				return $view;
			} 
			catch (Exception $e)
			{
				$view = View::make('wallet.downtime');
				$view->gravtar_link  = $gravtar_link;
				return $view;
			}

		} else {
			return redirect('/login');
		}
	}

	// Wallet history



	// ==================================================================================
	// ==================================================================================
	// Sebastian F Wallet Updates

	// Show HD Wallet (Not Open)
	protected function showHDWAllet()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			// echo "<pre>";
			// print_r($wallet);
			// echo "</pre>";
			// die();


			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}

			if ($profile->wallet_open == 1 && !is_null($wallet)) {
				return redirect('wallet/dashboard/hd-open');
			} else {
				$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));

				$view = View::make('wallet.hd');
				$view->gravtar_link  = $gravtar_link;
				$data = json_decode(file_get_contents("/home/mars/constitution/marswallet.json"), true);
				$view->SALT = $data['salt'];
				$view->iv = $data['iv'];

				// echo "<pre>";
				// print_r($data['iv']);
				// echo "</pre>";
				// die();


				if ($wallet) {
					$view->encrypted_seed = $wallet->encrypted_seed;
					$view->public_addr = $wallet->public_addr;
				} else {
					$view->encrypted_seed = null;
					$view->public_addr = null;
				}



				$view->balance = 0;
				$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
				$network = json_decode($json, true);
				$view->network = $network;
				if(is_null($wallet))
					$view->wallet_open = 0;
				else
					$view->wallet_open = $profile->wallet_open;

				return $view;
			}
		} else {
			return redirect('/login');
		}
	}

	// Show HD Wallet (Open)
	protected function showHDOpen()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			// echo "<pre>";
			// print_r($uid);
			// echo "</pre>";
			// die();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			// ===============================================================================================
			// ===============================================================================================
			// Start Rendering open wallet data!
			//

			if ($profile->wallet_open == 1 && $wallet) {
				$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));

				$view = View::make('wallet.hd-open');
				$view->gravtar_link  = $gravtar_link;
				// echo "Hello, World";
				// die();
				$json = $this->file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
				$network = json_decode($json, true);

				$cur_balance = file_get_contents("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$cur_price = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=marscoin&vs_currencies=usd"));

				$view->mars_price = $cur_price->marscoin->usd;
				$view->balance = ($cur_balance * 0.00000001);
				$view->network = $network;
				$view->public_addr = $wallet->public_addr;
				$view->encrypted_seed = $wallet->encrypted_seed;
				$view->fullname = Auth::user()->fullname;
				$view->wallet_open = $profile->wallet_open;

				return $view;
			} else if ($profile->wallet_open == 0 || is_null($wallet)) {

				$profile->wallet_open = 0;
				return redirect('wallet/dashboard/hd');
			}


			// ===============================================================================================
			// ===============================================================================================
			// ===============================================================================================



		}else{
			return redirect('/login');
		}
	}


	// Show HD Wallet (Open)
	protected function showHDClose()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			// echo "<pre>";
			// print_r($uid);
			// echo "</pre>";
			// die();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			// ===============================================================================================
			// ===============================================================================================
			// Start Rendering open wallet data!
			//

			if ($profile->wallet_open == 1 && $wallet) {
				$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));

				$view = View::make('wallet.hd-close');
				$view->gravtar_link  = $gravtar_link;
				// echo "Hello, World";
				// die();
				$json = $this->file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
				$network = json_decode($json, true);

				$cur_balance = file_get_contents("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
				$cur_price = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=marscoin&vs_currencies=usd"));

				$view->mars_price = $cur_price->marscoin->usd;
				$view->balance = ($cur_balance * 0.00000001);
				$view->network = $network;
				$view->public_addr = $wallet->public_addr;
				$view->encrypted_seed = $wallet->encrypted_seed;
				$view->fullname = Auth::user()->fullname;
				$view->wallet_open = $profile->wallet_open;

				return $view;
			} else if ($profile->wallet_open == 0 || is_null($wallet)) {

				$profile->wallet_open = 0;
				return redirect('wallet/dashboard/hd');
			}


			// ===============================================================================================
			// ===============================================================================================
			// ===============================================================================================



		}else{
			return redirect('/login');
		}
	}



	// POST
	// Create Wallet
	public function postCreateWallet(Request $request)
	{
		if (Auth::check()) 
		{
			$uid = Auth::user()->id;

			$profile = Profile::where('userid', '=', $uid)->first();

			$hd_wallet = HDWallet::where('user_id', '=', $uid)->get();



			if ($profile->wallet_open == 1 && count($hd_wallet) >= 1) {

				// Error... User has already opened wallet in the past...
				return redirect('wallet/dashboard/hd')->with('message', 'Error! Wallet has already been opened.');
			} else if (count($hd_wallet) == 0) {
				$wallet = new HDWallet;
				$wallet->encrypted_seed = $request->input('password');

				$wallet->public_addr = $request->input('public_addr');

				if (empty($wallet->encrypted_seed))
					$wallet->backup = 0;
				else
					$wallet->backup = 1;


				$wallet->user_id = $uid;
				$wallet->wallet_type = "MARS";
				$wallet->save();

				$profile->wallet_open = 1;
				$profile->save();

				return redirect('wallet/dashboard/hd-open')->with('message', 'Wallet Successfully Open!');
			}
			// } 
			// else 
			// {
			// 	return redirect('wallet/dashboard/hd')->with('message', 'The following errors occurred')->withErrors($validator->messages())->withInput();
			// }

		}else{
			return redirect('wallet/dashboard/hd');
		}

	}

	// GET
	// Open Pre-Existing Wallet
	public function getWallet()
	{

		$uid = Auth::user()->id;
		$profile = Profile::where('userid', '=', $uid)->first();
		$hd_wallet = HDWallet::where('user_id', '=', $uid)->get();
		$profile->wallet_open = 1;
		$profile->save();

		return redirect('wallet/dashboard/hd-open')->with('message', 'Wallet Successfully Open!');
	}

	public function failWallet(){


		return redirect('wallet/dashboard/hd')->with('message', 'Wallet Unsuccessful!');
	}


	// Logout of Wallet ONLY
	public function walletLogout()
	{
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
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('wallet.profile');
			$view->gravtar_link  = $gravtar_link;

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;

			return $view;
		} else {
			return redirect('/login');
		}
	}

	
	protected function showChart()
	{
		$json = $this->file_get_contents_curl('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/historical?id=154');
		$latest = json_decode($json, true);
		print_r($latest);
		die();
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
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('wallet.reports');
			$view->gravtar_link  = $gravtar_link;

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;
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
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('wallet.anchor');
			$view->gravtar_link  = $gravtar_link;
			$view->network = AppHelper::stats()['network'];
			$view->coincount = AppHelper::stats()['coincount'];

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
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('wallet.camera');
			$view->gravtar_link  = $gravtar_link;
			$view->email = Auth::user()->email;

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;

			return $view;
		} else {
			return redirect('/login');
		}
	}



	private function file_get_contents_curl($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-CMC_PRO_API_KEY: cf191ba7-4840-4a9a-bee4-617608afd8a4'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}
}
