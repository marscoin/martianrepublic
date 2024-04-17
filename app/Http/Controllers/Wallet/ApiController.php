<?php
namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Includes\AppHelper;
use App\Models\Posts;
use App\Models\Profile;
use App\Models\User;
use App\Models\Proposals;
use App\Models\Publication;
use App\Models\Threads;
use App\Models\Citizen;
use App\Models\HDWallet;
use Illuminate\Support\Facades\Log;

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


	/**
	 * Internal
	 *
	 * @ignore
	 * @hideFromAPIDocumentation
	 */
	public function permapinpic(Request $request){

		if (Auth::check()) {
			$uid = Auth::user()->id;
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

			$citcache = Citizen::where('userid', '=', $uid)->first();
			if(is_null($citcache)) $citcache = new Citizen;	
			$citcache->userid = $uid;
			$citcache->avatar_link = "https://ipfs.marscoin.org/ipfs/".$hash;
			$citcache->save();

			return (new Response(json_encode(array("Hash" => $hash)), 200))
              ->header('Content-Type', "application/json;");

			//}
		}else{
            return redirect('/login');
        }
			
	}


	/**
	 * Internal
	 *
	 * @ignore
	 * @hideFromAPIDocumentation
	 */
	public function permapinvideo(Request $request){

		if (Auth::check()) {
			$uid = Auth::user()->id;
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

				$citcache = Citizen::where('userid', '=', $uid)->first();
				if(is_null($citcache)) $citcache = new Citizen;	
				$citcache->userid = $uid;
				$citcache->liveness_link = "https://ipfs.marscoin.org/ipfs/".$hash;
				$citcache->save();

				return (new Response(json_encode(array("Hash" => $hash)), 200))
				->header('Content-Type', "application/json;");
			}
			

		
		}else{
            return redirect('/login');
        }
			
	}


	/**
	 * Internal
	 *
	 * @hideFromAPIDocumentation
	 */
	public function permapinlog(Request $request)
	{
		if (!Auth::check()) {
			return redirect('/login');
		}

		$public_address = $request->input('address');
		$title = $request->input('title');
		$entry = $request->input('entry');
		$uid = Auth::user()->id;
		$file_path = "./assets/citizen/" . $public_address . "/logbook/" . md5($title);

		if (!file_exists($file_path)) {
			mkdir($file_path, 0755, true); // More secure permissions
		}

		$file = $file_path . "/log.markdown";
		file_put_contents($file, $title . "\n\n" . $entry);

		$files = $request->file('filenames');
		if ($files && is_array($files)) {
			foreach ($files as $f) {
				$name = $f->hashName(); // Generates a unique, random name...
				$f->move($file_path, $name);
			}
		}

		try {
			$hash = AppHelper::uploadFolder($file_path, 'http://127.0.0.1:5001/api/v0/add?pin=true&recursive=true&wrap-with-directory=true&quieter'); // Example: use a config value or env variable
			AppHelper::insertPublicationCache($uid, $file_path, $hash, $title);
		} catch (\Exception $e) {
			// Handle error; possibly log it and return a user-friendly message
			return response()->json(["error" => $e->getMessage()], 500);
		}

		return response()->json(["Hash" => $hash, "Path" => $file_path], 200)
			->header('Content-Type', "application/json;");
	}




	public function removepinlog(Request $request)
	{
		if (!Auth::check()) {
			return redirect('/login');
		}

		$cid = $request->input('cid'); // The CID to unpin

		if (!$cid) {
			return response()->json(["error" => "CID is required"], 400);
		}

		$uid = Auth::user()->id;
		$ipfsApiUrl = 'http://127.0.0.1:5001/api/v0/pin/rm?arg=' . $cid . "&recursive=true";
		Log::debug($ipfsApiUrl);
		try {
			// Initialize cURL session
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ipfsApiUrl);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Ensure this is POST

			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			Log::debug($response);

			if ($httpCode != 200) {
				// Error handling, IPFS might return error messages as JSON
				$errorMsg = "Failed to unpin CID. HTTP status code: $httpCode";
				if ($responseJson = json_decode($response)) {
					if (!empty($responseJson->Message)) {
						$errorMsg = $responseJson->Message;
					}
				}
				throw new \Exception($errorMsg);
			}

			$publicationDeleted = Publication::where('ipfs_hash', $cid)->delete();
			if (!$publicationDeleted) {
				throw new \Exception("Failed to delete publication from the database.");
			}

		} catch (\Exception $e) {
			// Close cURL session
			if (isset($ch)) {
				curl_close($ch);
			}

			// Handle error; possibly log it and return a user-friendly message
			return response()->json(["error" => $e->getMessage()], 500);
		}

		// Close cURL session
		curl_close($ch);

		// Respond to the client
		return response()->json(["message" => "Successfully unpinned CID: $cid"], 200)
			->header('Content-Type', "application/json;");
	}

	


	/**
	 * Handles JSON storage and pinning to a distributed file system.
	 *
	 * @hideFromAPIDocumentation
	 */
	public function permapinjson(Request $request)
	{
		if (!Auth::check()) {
			return redirect('/login');
		}
		Log::info("in function");
		$public_address = $request->input('address');
		$type = $request->input('type');
		$json = $request->input('payload');
		$rootPath = base_path();
		$base_path = $rootPath . "/assets/citizen/" . $public_address;

		// Check and create the directory if it doesn't exist
		if (!file_exists($base_path)) {
			Log::info("Trying to create directory: " . $base_path);
			if (!mkdir($base_path, 0755, true)) {
				Log::error("Failed to create directory: " . $base_path);
				return response()->json(["error" => "Failed to create directory. Check permissions."], 500);
			}
			Log::info("Directory created or already exists: " . $base_path);

			if (!is_writable($base_path)) {
				Log::error("Directory not writable: " . $base_path);
				return response()->json(["error" => "Directory is not writable."], 500);
			}
		} elseif (!is_writable($base_path)) {
			return response()->json(["error" => "Directory is not writable."], 500);
		}

		$file_path = $base_path . "/" . $type . ".json";

		// Attempt to write the JSON data to the file
		if (file_put_contents($file_path, $json) === false) {
			return response()->json(["error" => "Failed to write to file."], 500);
		}

		try {
			// Upload the folder and pin the file
			Log::info("PermaJson: ".$file_path);
			$apiResponse = AppHelper::uploadFolder($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true&recursive=true&wrap-with-directory=true&quieter");
			return response()->json($apiResponse, 200)->header('Content-Type', "application/json;");
		} catch (\Exception $e) {
			// Handle exceptions during the upload and pinning process
			return response()->json(["error" => $e->getMessage()], 500);
		}
	}




	/**
	 * Internal
	 *
	 * @hideFromAPIDocumentation
	 */
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


	/**
	 * Internal
	 *
	 * @hideFromAPIDocumentation
	 */
	public function getBalance($address)
	{
		if (Auth::check()) {
			$balance = AppHelper::getMarscoinBalance($address);
			return (new Response(json_encode(array("balance" => $balance)), 200))
              ->header('Content-Type', "application/json;");

		
		}else{
            return redirect('/login');
        }
	}



	/**
	 * Internal
	 *
	 * @hideFromAPIDocumentation
	 */
	public function dismissAlert(Request $request)
    {
        $alertType = $request->alertType;
        session()->put($alertType, true);

        return response()->json(['success' => true]);
    }



	/**
	 * Internal
	 *
	 * @hideFromAPIDocumentation
	 */
	public function getPrice(Request $request)
	{
		if (Auth::check()) {
			$price = AppHelper::getMarscoinPrice();
			return (new Response(json_encode(array("mars_price" => $price)), 200))
              ->header('Content-Type', "application/json;");

		
		}else{
            return redirect('/login');
        }
	}


	/**
	 * Internal
	 *
	 * @hideFromAPIDocumentation
	 */
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

	/**
	 * Internal
	 *
	 * @hideFromAPIDocumentation
	 */
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

			$citcache = Citizen::where('userid', '=', $uid)->first();
			if(is_null($citcache)) $citcache = new Citizen;	
			
			$citcache->userid = $uid;
			$citcache->firstname = $firstname;
			$citcache->lastname = $lastname;
			$citcache->save();

			$user = User::where('id', '=', $uid)->first();
			$user->fullname = $fullname;
			$user->save();
			return;
		}
	}

	/**
	 *
	 * @hideFromAPIDocumentation
	 */
	public function cacheonboarding(Request $request)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$shortbio = $request->input('shortbio');
			$displayname = $request->input('displayname');
			$publicaddress = $request->input('publicaddress');
			
			$citcache = Citizen::where('userid', '=', $uid)->first();
			if(is_null($citcache)) $citcache = new Citizen;	
			
			$citcache->userid = $uid;
			$citcache->shortbio = $shortbio;
			$citcache->displayname = $displayname;
			$citcache->public_address = $publicaddress;
			$citcache->save();

			return;
		}
	}


	public function rejectApplication(Request $request)
	{
		if (Auth::check()) 
		{
			// Check if the user has citizen status
			$user = Auth::user();
			$profile = Profile::where('userid', $user->id)->first();
			$reporter = Citizen::where('userid', '=',  $user->id)->first();
			
			$rejectionReasons = [
				'avatar_link' => 'Missing Personal Image',
				'liveness_link' => 'Incomplete Video',
				'duplicate' => 'Duplicate Entry'
			];
			
			if ($profile && $profile->citizen) 
			{
				// Fetch the request data
				$applicantUserId = $request->input('applicantUserId');
				$fieldToUpdate = $request->input('field');
	
				// Validate the field to update
				if (!in_array($fieldToUpdate, ['avatar_link', 'liveness_link'])) {
					return response()->json(['error' => 'Invalid field specified.'], 400);
				}
	
				// Update the citizen table, setting the specified field to NULL for the applicant
				Citizen::where('userid', $applicantUserId)->update([$fieldToUpdate => NULL]);

				$applicant = Citizen::where('userid', '=',  $applicantUserId)->first();
	
				// Insert a new forum post indicating the rejection
				$content = "The application of {$applicant->public_address} has been rejected due to " . $rejectionReasons[$fieldToUpdate] . ".";

				
				$fullname = $reporter->firstname . "" . $reporter->lastname;
				Posts::create([
					'thread_id' => 27, // Thread ID for application commentary
					'author_id' => $user->id, // The ID of the citizen performing the rejection
					'content' => $content,
					'authorName' => $fullname,
					'created_at' => now(),
					'updated_at' => now(),
				]);
	
				return response()->json(['success' => 'Application has been rejected and recorded.']);
			} else {
				// User is not a citizen or not authenticated
				return response()->json(['error' => 'Unauthorized access.'], 403);
			}
		} else {
			// User is not logged in
			return response()->json(['error' => 'User not authenticated.'], 401);
		}
		
	}

	/**
	 *
	 * @hideFromAPIDocumentation
	 */
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

	/**
	 *
	 * @hideFromAPIDocumentation
	 */
	public function renameWallet(Request $request)
	{
		$request->validate([
			'hdwallet_id' => 'required',
			'new_name' => 'required|string|max:500',
		]);

		if (Auth::check()) {
			$wallet = HDWallet::where('id', $request->hdwallet_id)
								->where('user_id', Auth::id())
								->firstOrFail();
			$wallet->wallet_type = $request->new_name;
			$wallet->save();
			return response()->json(['success' => 'Wallet renamed successfully']);
		}

		return response()->json(['error' => 'Unauthorized'], 403);
	}


	/**
	 * @hideFromAPIDocumentation
	 */
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


	/**
	 * @hideFromAPIDocumentation
	 */
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