<?php
namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use App\Models\Feed;
use App\Models\Profile;
use App\Models\User;

class ApiController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public function __construct() {
	    if (!Auth::check())
			redirect('/login');
	}


	public function addipfs($data){

		$IPFS = new IPFSRoot;

	}


	public function permapinpic(Request $request){

		if (Auth::check()) {
			$hash = "";
			$dataPic = $request->input('picture');
			$type = $request->input('type');
			$public_address = $request->input('address');
			$file_path = "./assets/citizen/" . $public_address . "/";
			if (!file_exists($file_path)) {
				mkdir($file_path);
			}
			list($type, $dataPic) = explode(';', $dataPic);
			list(, $type) = explode('/', $type);
			$file_path = "./assets/citizen/" . $public_address . "/profile_pic." . $type;
			//if (!file_exists($file_path)) { overwrite by default
			
			list(, $dataPic) = explode(',', $dataPic);
			$dataPic = base64_decode($dataPic);
			file_put_contents($file_path, $dataPic);
			$hash = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");

			return (new Response(json_encode(array("Hash" => $hash)), 200))
              ->header('Content-Type', "application/json;");

			//}
		}else{
            return redirect('/login');
        }
			
	}


	public function permapinvideo(Request $request){

		if (Auth::check()) {
			$hash = "";
			$dataPic = $request->input('file');
			$type = $request->input('type');
			$public_address = $request->input('address');
			if ($request->hasFile('file'))
			{
				$file_path = "./assets/citizen/" . $public_address . "/";
				if (!file_exists($file_path)) {
					mkdir($file_path);
				}
				$file_path = "./assets/citizen/" . $public_address  . "/";
				$request->file('file')->move($file_path, "profile_video.webm" );
				$file_path = $file_path . "profile_video.webm";
				$hash = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");

				return (new Response(json_encode(array("Hash" => $hash)), 200))
				->header('Content-Type', "application/json;");
			}
			

		
		}else{
            return redirect('/login');
        }
			
	}



	public function permapinjson(Request $request)
	{
		if (Auth::check()) {
			$hash = "";
			$json = $request->input('payload');
			$type = $request->input('type');
			$public_address = $request->input('address');
			$file_path = "./assets/citizen/" . $public_address . "/";
			if (!file_exists($file_path)) {
				mkdir($file_path);
			}
			
			$file_path = "./assets/citizen/" . $public_address . "/".$type.".json";

			file_put_contents($file_path, $json);
			$hash = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");

			return (new Response(json_encode(array("Hash" => $hash)), 200))
              ->header('Content-Type', "application/json;");

		
		}else{
            return redirect('/login');
        }
	}


	public function setfeed(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$txid = $request->input('txid');
			$action_tag = $request->input('type');
			$public_address = $request->input('address');
			$embedded_link = $request->input('embedded_link');
			$message = $request->input('message');

			AppHelper::insertBlockchainCache($public_address, $uid, $action_tag, $message, $embedded_link, $txid);

			$profile = Profile::where('userid', '=', $uid)->first();
			$profile->general_public = 1;
			$profile->save();

			return (new Response(json_encode(array("Hash" => $txid)), 200))
              ->header('Content-Type', "application/json;");

		
		}else{
            return redirect('/login');
        }
	}



	public function getTransactions(Request $request)
	{
		if (Auth::check()) {
			
			$address = $request->input('address');
			$json = AppHelper::file_get_contents_curl("http://explore1.marscoin.org/api/txs/?address={$address}");
			
			return (new Response($json))
              ->header('Content-Type', "application/json;");

		
		}else{
            return redirect('/login');
        }
	}


	public function setfullname(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$firstname = $request->input('firstname');
			$lastname = $request->input('lastname');
			if(!isset($firstname))
				return;
			if(!isset($lastname))
				return;

			$fullname = $firstname . " " . $lastname;

			$user = User::where('id', '=', $uid)->first();
			$user->fullname = $fullname;
			$user->save();
			return;
		}
	}


	public function closewallet(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$profile->wallet_open = 0;
			$profile->save();
			return;
		}
	}


	public function setendorsed(Request $request)
	{
		if (Auth::check()) {
			$uid = $request->input("id");
			$profile = Profile::where('userid', '=', $uid)->first();
			$cnt = $profile->endorse_cnt;
			$profile->endorse_cnt = $cnt + 1;
			$profile->save();
			return;
		}
	}



}