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


	
	protected function showSignup()
	{
		return view('wallet.signup');
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
			return redirect('wallet/login');
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
			return redirect('wallet/login');
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
				$profile->save();
				if (!$is2faset) {
					return redirect('/twofa');
				} else {
					return redirect('/twofachallenge');
				}
			}
		} else {
			return redirect('wallet/login');
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

			try{
				$RPC_Host = "127.0.0.1";         // host for marscoin rpc
				$RPC_Port = "8337";              // port for marscoin rpc
				$RPC_User = "marscoinrpcb";     // username for marscoin rpc
				$RPC_Pass = "DPFXH8vFxzzIAYSwHF1ZLpzS8RKjjoFhPjz4VW2Yo3DM8";     // password for marscoin rpc

				$nu92u5p9u2np8uj5wr = "http://" . $RPC_User . ":" . $RPC_Pass . "@" . $RPC_Host . ":" . $RPC_Port . "/";
				$Marscoind = new jsonRPCClient($nu92u5p9u2np8uj5wr);
				$user = new User;
				$view->address = $Marscoind->getaccountaddress(Auth::user()->email);

				// echo "<pre>";
				// print_r($wallet);
				// echo"</pre>";
				// die();

				if ($wallet) {
					$cur_balance = file_get_contents("https://explore.marscoin.org/api/addr/{$wallet['public_addr']}/balance");
					$view->balance = ($cur_balance * 0.00000001);
				} else {
					$view->balance = 0;
				}

				//$view->balance = $Marscoind->getbalance(Auth::user()->email);
				$view->received = $Marscoind->getreceivedbyaccount(Auth::user()->email);
				$view->sent = floatval($view->received) - floatval($view->balance);
				$view->transactions = array_reverse($Marscoind->listtransactions(Auth::user()->email, 10));
				$view->wallet_open = $profile->wallet_open;

				// print_r($view->wallet_open);
				// die();

				$json = $this->file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
				$network = json_decode($json, true);
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
				$view->transactions = array_reverse($view->transactions);
				foreach ($view->transactions as $transaction) {
					if ($transaction['amount'] > 0) {
						$t = $transaction['time'] . "000";
						if (empty($firstdate))
							$firstdate = $t;
						if ($lastdate < $t)
							$lastdate = $t;
						$a = intval($a) + floor($transaction['amount']);
						$item = array("time" => $t, "amount" => $a);
						array_push($incomes, $item);
						$balance = floatval($balance) + floatval($transaction['amount']);
						$item = array("time" => $t, "amount" => $balance);
						array_push($balances, $item);
					} else if ($transaction['amount'] < 0) {
						$t = $transaction['time'] . "000";
						if (empty($firstdate))
							$firstdate = $t;
						if ($lastdate < $t)
							$lastdate = $t;
						$b = intval($b) + abs(floor($transaction['amount']));
						$item = array("time" => $t, "amount" => $b);
						array_push($expenses, $item);
						$balance = floatval($balance) + floatval($transaction['amount']);
						$item = array("time" => $t, "amount" => $balance);
						array_push($balances, $item);
					}
				}
				$view->firstdate = $firstdate;
				$view->lastdate = $lastdate;
				$view->incomes = $incomes;
				$view->expenses = $expenses;
				$view->balances = $balances;
				$view->voucher = false;
				$view->network = $network;
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
			return redirect('wallet/login');
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
			return redirect('wallet/login');
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
			return redirect('wallet/login');
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

	// protected function createTransaction()
	// {


	// }

	// Logout of Wallet ONLY
	public function walletLogout()
	{
	}









	// Done
	// ==================================================================================
	// ==================================================================================




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
			return redirect('wallet/login');
		}
	}

	protected function showBuy()
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
			$view = View::make('wallet.buy');
			$view->gravtar_link  = $gravtar_link;
			$view->email = Auth::user()->email;

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;
			return $view;
		} else {
			return redirect('wallet/login');
		}
	}

	protected function showSend()
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
			$view = View::make('wallet.send');
			$view->gravtar_link  = $gravtar_link;

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;


			$json = $this->file_get_contents_curl('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?slug=marscoin');
			$latest = json_decode($json, true);
			$view->latest = $latest;


			return $view;
		} else {
			return redirect('wallet/login');
		}
	}

	protected function showChart()
	{
		$json = $this->file_get_contents_curl('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/historical?id=154');
		$latest = json_decode($json, true);
		print_r($latest);
		die();
	}

	protected function showReceive()
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
			$view = View::make('wallet.receive');
			$view->gravtar_link  = $gravtar_link;
			$data = json_decode(file_get_contents("/home/mars/constitution/marswallet.json"), true);
			$RPC_Host = $data['rpc_host'];          // host for marscoin rpc
			$RPC_Port = $data['rpc_port'];              // port for marscoin rpc
			$RPC_User = $data['rpc_user'];      // username for marscoin rpc
			$RPC_Pass = $data['rpc_pass'];     // password for marscoin rpc

			$nu92u5p9u2np8uj5wr = "http://" . $RPC_User . ":" . $RPC_Pass . "@" . $RPC_Host . ":" . $RPC_Port . "/";
			$Marscoind = new jsonRPCClient($nu92u5p9u2np8uj5wr);
			$user = new User;
			$view->addresses = array_reverse($Marscoind->getaddressesbyaccount(Auth::user()->email));

			$json = $this->file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;
			return $view;
		} else {
			return redirect('wallet/login');
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
			$gravtar_link = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email)));
			$view = View::make('wallet.reports');
			$view->gravtar_link  = $gravtar_link;

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;
			return $view;
		} else {
			return redirect('wallet/login');
		}
	}

	protected function showTransactions()
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
			$view = View::make('wallet.transactions');
			$view->gravtar_link  = $gravtar_link;
			$RPC_Host = "127.0.0.1";         // host for marscoin rpc
			$RPC_Port = "8337";              // port for marscoin rpc
			$RPC_User = "marscoinrpcb";     // username for marscoin rpc
			$RPC_Pass = "DPFXH8vFxzzIAYSwHF1ZLpzS8RKjjoFhPjz4VW2Yo3DM8";     // password for marscoin rpc

			$nu92u5p9u2np8uj5wr = "http://" . $RPC_User . ":" . $RPC_Pass . "@" . $RPC_Host . ":" . $RPC_Port . "/";
			$Marscoind = new jsonRPCClient($nu92u5p9u2np8uj5wr);
			$user = new User;
			$view->transactions = array_reverse($Marscoind->listtransactions(Auth::user()->email, 100));
			//print_r($view->transactions);

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;

			return $view;
		} else {
			return redirect('wallet/login');
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
            return redirect('wallet/login');
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
			return redirect('wallet/login');
		}
	}


	protected function showFeatures()
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
			$view = View::make('wallet.features');
			$view->gravtar_link  = $gravtar_link;
			$view->email = Auth::user()->email;

			$json = $this->file_get_contents_curl('http://explore2.marscoin.org/api/status?q=getInfo');
			$network = json_decode($json, true);
			$view->network = $network;

			return $view;
		} else {
			return redirect('wallet/login');
		}
	}



	protected function showForgot()
	{
		return View::make('wallet.forgot');
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
