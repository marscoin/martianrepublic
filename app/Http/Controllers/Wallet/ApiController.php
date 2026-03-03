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

			// --- SECURITY: Sanitize the public_address to prevent directory traversal ---
			$safeAddress = AppHelper::sanitizePathSegment($public_address);
			if ($safeAddress === null) {
				return response()->json(['error' => 'Invalid address format.'], 400);
			}

			// --- SECURITY: Validate the base64 image data (extension, MIME, size, PHP code) ---
			$validation = AppHelper::validateBase64Image($dataPic);
			if (!$validation['valid']) {
				Log::warning('permapinpic upload rejected: ' . $validation['error'] . ' (user: ' . $uid . ')');
				return response()->json(['error' => $validation['error']], 422);
			}

			$safeExtension = $validation['extension'];
			$decodedData = $validation['data'];

			$file_path = "./assets/citizen/" . $safeAddress . "/";
			if (!file_exists($file_path)) {
				mkdir($file_path, 0755, true);
			}

			// --- SECURITY: Write .htaccess to prevent PHP execution in upload dir ---
			AppHelper::writeUploadHtaccess($file_path);

			// Use validated extension, not user-supplied one
			$file_path = "./assets/citizen/" . $safeAddress . "/profile_pic." . $safeExtension;

			file_put_contents($file_path, $decodedData);
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

			// --- SECURITY: Sanitize the public_address to prevent directory traversal ---
			$safeAddress = AppHelper::sanitizePathSegment($public_address);
			if ($safeAddress === null) {
				return response()->json(['error' => 'Invalid address format.'], 400);
			}

			if ($request->hasFile('file'))
			{
				// --- SECURITY: Validate the uploaded file (extension, MIME, size, PHP code) ---
				$uploadedFile = $request->file('file');
				$validation = AppHelper::validateUploadedFile($uploadedFile, [
					'webm' => ['video/webm', 'audio/webm'],
				]);
				if (!$validation['valid']) {
					Log::warning('permapinvideo upload rejected: ' . $validation['error'] . ' (user: ' . $uid . ')');
					return response()->json(['error' => $validation['error']], 422);
				}

				$file_path = "./assets/citizen/" . $safeAddress . "/";
				if (!file_exists($file_path)) {
					mkdir($file_path, 0755, true);
				}

				// --- SECURITY: Write .htaccess to prevent PHP execution in upload dir ---
				AppHelper::writeUploadHtaccess($file_path);

				$file_path = "./assets/citizen/" . $safeAddress  . "/";
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

		// --- SECURITY: Sanitize the public_address to prevent directory traversal ---
		$safeAddress = AppHelper::sanitizePathSegment($public_address);
		if ($safeAddress === null) {
			return response()->json(['error' => 'Invalid address format.'], 400);
		}

		// --- SECURITY: Sanitize title for use in path (md5 hash is safe, but validate title exists) ---
		if (empty($title)) {
			return response()->json(['error' => 'Title is required.'], 400);
		}

		$file_path = "./assets/citizen/" . $safeAddress . "/logbook/" . md5($title);

		if (!file_exists($file_path)) {
			mkdir($file_path, 0755, true); // More secure permissions
		}

		// --- SECURITY: Write .htaccess to prevent PHP execution in upload dir ---
		AppHelper::writeUploadHtaccess($file_path);

		// --- SECURITY: Check content for PHP code ---
		$logContent = $title . "\n\n" . $entry;
		if (AppHelper::containsPhpCode($logContent)) {
			Log::warning('permapinlog rejected: content contains PHP code (user: ' . $uid . ')');
			return response()->json(['error' => 'Content contains potentially dangerous code.'], 422);
		}

		// --- SECURITY: Check content size (max 5MB) ---
		if (strlen($logContent) > 5242880) {
			return response()->json(['error' => 'Content exceeds maximum size of 5MB.'], 422);
		}

		$file = $file_path . "/log.markdown";
		file_put_contents($file, $logContent);

		$files = $request->file('filenames');
		if ($files && is_array($files)) {
			foreach ($files as $f) {
				// --- SECURITY: Validate each uploaded file ---
				$validation = AppHelper::validateUploadedFile($f);
				if (!$validation['valid']) {
					Log::warning('permapinlog file rejected: ' . $validation['error'] . ' (user: ' . $uid . ', file: ' . $f->getClientOriginalName() . ')');
					// Skip invalid files but continue processing valid ones
					continue;
				}

				$name = $f->hashName(); // Generates a unique, random name...

				// --- SECURITY: Ensure the generated filename does not have a dangerous extension ---
				$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
				if (AppHelper::isExtensionBlocked($ext)) {
					Log::warning('permapinlog blocked dangerous file extension: ' . $ext . ' (user: ' . $uid . ')');
					continue;
				}

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

		// Validate CID format to prevent injection into the IPFS API URL
		if (!AppHelper::isValidCID($cid)) {
			return response()->json(["error" => "Invalid CID format"], 400);
		}

		// Verify the publication belongs to the current user
		$uid = Auth::user()->id;
		$publication = Publication::where('ipfs_hash', $cid)->first();
		if ($publication && $publication->userid != $uid) {
			return response()->json(["error" => "Unauthorized: you can only remove your own publications."], 403);
		}

		$ipfsApiUrl = 'http://127.0.0.1:5001/api/v0/pin/rm?arg=' . urlencode($cid) . "&recursive=true";
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

		// --- SECURITY: Sanitize the public_address to prevent directory traversal ---
		$safeAddress = AppHelper::sanitizePathSegment($public_address);
		if ($safeAddress === null) {
			return response()->json(['error' => 'Invalid address format.'], 400);
		}

		// --- SECURITY: Sanitize the type parameter to prevent path traversal ---
		$safeType = AppHelper::sanitizePathSegment($type);
		if ($safeType === null) {
			return response()->json(['error' => 'Invalid type format.'], 400);
		}

		// --- SECURITY: Check file size (max 5MB) ---
		if (strlen($json) > 5242880) {
			return response()->json(['error' => 'Payload exceeds maximum size of 5MB.'], 422);
		}

		// --- SECURITY: Reject payloads containing PHP code ---
		if (AppHelper::containsPhpCode($json)) {
			Log::warning('permapinjson rejected: payload contains PHP code');
			return response()->json(['error' => 'Payload contains potentially dangerous content.'], 422);
		}

		// --- SECURITY: Validate that payload is valid JSON ---
		$decodedJson = json_decode($json);
		if ($json !== '' && json_last_error() !== JSON_ERROR_NONE) {
			return response()->json(['error' => 'Invalid JSON payload.'], 422);
		}

		$projectRoot = config('app.project_root', base_path());
		$base_path =  $projectRoot . "/assets/citizen/" . $safeAddress;

		// Check and create the directory if it doesn't exist
		Log::info($base_path);
		clearstatcache();
		if (!is_dir($base_path)) {
			Log::info("Trying to create directory: " . $base_path);
			if (!mkdir($base_path, 0755, true)) {
				Log::error("Failed to create directory: " . $base_path);
				return response()->json(["error" => "Failed to create directory. Check permissions."], 500);
			}
			Log::info("Directory created: " . $base_path);
		}

		// Check if the directory is writable, regardless of whether it was just created or already existed
		if (!is_writable($base_path)) {
			Log::error("Directory not writable: " . $base_path);
			return response()->json(["error" => "Directory is not writable. Check permissions."], 500);
		}

		// --- SECURITY: Write .htaccess to prevent PHP execution in upload dir ---
		AppHelper::writeUploadHtaccess($base_path);

		$file_path = $base_path . "/" . $safeType . ".json";

		// Attempt to write the JSON data to the file
		if (file_put_contents($file_path, $json) === false) {
			return response()->json(["error" => "Failed to write to file."], 500);
		}

		try {
			Log::info("PermaJson: " . $file_path);

			// Check if the type contains the word 'log'
			if (strpos($safeType, 'log') !== false) {
				// The type contains 'log', use uploadFolder
				$apiResponse = AppHelper::uploadFolder($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true&recursive=true&wrap-with-directory=true&quieter");
			} else {
				// The type does not contain 'log', use upload
				$apiResponse = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");
			}

			if (is_string($apiResponse)) {
				$formattedResponse = ['Hash' => $apiResponse];
			} else {
				Log::error("Upload error: Formatting");
				return response()->json(["error"=>"formatting error"], 500);
			}

			return response()->json($formattedResponse, 200)->header('Content-Type', "application/json;");
		} catch (\Exception $e) {
			// Handle exceptions during the upload and pinning process
			Log::error("Upload error: " . $e->getMessage());
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
			$endorserId = Auth::user()->id;
			$targetUserId = $request->input("id");

			// Prevent self-endorsement
			if ((int) $endorserId === (int) $targetUserId) {
				return response()->json(['error' => 'Cannot endorse yourself.'], 400);
			}

			// Only citizens can endorse
			$endorserProfile = Profile::where('userid', '=', $endorserId)->first();
			if (!$endorserProfile || !$endorserProfile->citizen) {
				return response()->json(['error' => 'Only citizens can endorse.'], 403);
			}

			$profile = Profile::where('userid', '=', $targetUserId)->first();
			if (!$profile) {
				return response()->json(['error' => 'User not found.'], 404);
			}
			$profile->endorse_cnt = ($profile->endorse_cnt ?? 0) + 1;
			$profile->save();
			return response()->json(['success' => true]);
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
			$citcache = Citizen::where('userid', '=', $uid)->first();

			if (!$this->isValidCID($embedded_link)) {
				return response()->json(['error' => 'Invalid IPFS hash'], 400);
			}

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

			$post = new Posts;
			$post->thread->id = 2;
			$post->author_id = $uid;
			$post->content = $proposal->description;
			$post->authorName = $citcache->firstname . " " . $citcache->lastname;
			$post->save();

			$post_id = $post->id;

			$threads = new Threads;
			$threads->category_id = 2;
			$threads->author_id = $uid;
			$threads->title = $data->data->title;
			$threads->first_post_id = $post_id;
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


	protected function isValidCID($input)
	{
		// Extract CID from the URL if embedded in a typical IPFS link format
		$parsedUrl = parse_url($input);
		$path = $parsedUrl['path'] ?? '';
		// Regex to extract potential CIDs from paths; adjust according to typical link structures
		preg_match('/(Qm[a-zA-Z1-9]{44}|b[a-z2-7]{58})/', $path, $matches);
		$hash = $matches[0] ?? '';

		// Regex to check CIDv0; starts with 'Qm' and followed by 44 characters from Base58 set
		$cidv0Regex = '/^Qm[a-zA-Z1-9]{44}$/';
		// Regex for basic validation of CIDv1; broader due to variability in encoding and version
		$cidv1Regex = '/^b[a-z2-7]{58}$/';  // Example for base32 encoding

		// Validate extracted hash
		return preg_match($cidv0Regex, $hash) || preg_match($cidv1Regex, $hash);
	}



}
