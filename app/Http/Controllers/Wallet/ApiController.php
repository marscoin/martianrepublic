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
			
			$file_path = "./assets/citizen/" . $public_address . "/data.json";

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

			AppHelper::insertBlockchainCache($public_address, $uid, $action_tag, "", $embedded_link, $txid);

			$profile = Profile::where('userid', '=', $uid)->first();
			$profile->general_public = 1;
			$profile->save();

			return (new Response(json_encode(array("Hash" => $hash)), 200))
              ->header('Content-Type', "application/json;");

		
		}else{
            return redirect('/login');
        }
	}



}