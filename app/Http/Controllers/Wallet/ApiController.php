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
use App\Models\Proposals;
use App\Models\Threads;

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


	public function permapinlog(Request $request){

		if (Auth::check()) {
			$hash = "";
			$public_address = $request->input('address');
			$title = $request->input('title');
			$entry = $request->input('entry');
			$uid = Auth::user()->id;

			$file_path = "./assets/citizen/" . $public_address . "/logbook/".md5($title);
			echo $file_path;
			if (!file_exists($file_path)) {
				echo "making folder";
				mkdir($file_path, 0777, true);
			}
			$file_path = "./assets/citizen/" . $public_address . "/logbook/".md5($title);
			$hash = "";

			$file = $file_path."/log.markdown";
			file_put_contents($file, $title."\n\n".$entry);
			$files = $request->file('filenames');
			if(!is_null($files))
			{
				echo count($files);
				foreach ($files as $f) {
					print_r($f);
					$name = $f->hashName(); // Generate a unique, random name...
					$f->move($file_path, $name );
				}
			}
			
			$hash = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");
			AppHelper::insertPublicationCache($uid, $file_path, $hash);

			return (new Response(json_encode(array("Hash" => $hash, "Path" => $file_path)), 200))
			->header('Content-Type', "application/json;");
		
			

		
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



	public function cacheproposal(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$txid = $request->input('txid');
			$public_address = $request->input('address');
			$embedded_link = $request->input('embedded_link');
			$json = $request->input('message');
			$data = json_decode($json);

			$proposal = new Proposals;	
			$proposal->user_id = $uid;
			$proposal->title = $data->data->title;
			$proposal->description = $data->data->description;
			$proposal->category = $data->data->category;
			$proposal->author = Auth::user()->fullname;
			$proposal->ipfs_hash = $embedded_link;
			$proposal->participation = $data->data->participation;
			$proposal->threshold = $data->data->threshold;
			$proposal->duration = $data->data->duration;
			$proposal->expiration = $data->data->expiration;
			$proposal->txid = $txid;
			$proposal->public_address = $public_address;


			$proposal->save();
			$prop_id = $proposal->id;

			$threads = new Threads;
			$threads->category_id = 2;
			$threads->author_id = $uid;
			$threads->title = $data->data->title;
			$threads->proposal_id = $prop_id;
			$threads->save();

			$thd_id = $threads->id;
			
			$proposal->where('id', $prop_id)->update(['discussion' => $thd_id]);

			return (new Response(json_encode(array("Proposal" => $prop_id, "Discussion" => $thd_id)), 200))
              ->header('Content-Type', "application/json;");

		
		}else{
            return redirect('/login');
        }
	}



}