<?php


namespace App\Includes;
use App\Models\Feed;
use App\Models\Publication;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use TeamTeaTime\Forum\Models\Post;
use Carbon\Carbon;
use App\Models\Profile;
use App\Models\Citizen;

class AppHelper{

		/**
		 * =========================================================================
		 * FILE UPLOAD SECURITY HELPERS
		 * =========================================================================
		 * Added to prevent malicious file uploads (PHP shells, etc.).
		 * Every upload endpoint MUST call one of these validation methods.
		 */

		/**
		 * Dangerous file extensions that must never be uploaded.
		 * This blocklist prevents execution of server-side scripts.
		 */
		private static $blockedExtensions = [
			'php', 'phtml', 'php5', 'php7', 'php8', 'phar', 'phps',
			'cgi', 'pl', 'py', 'sh', 'bash', 'exe', 'bat', 'cmd',
			'asp', 'aspx', 'jsp', 'shtml', 'htaccess', 'svg',
		];

		/**
		 * Allowed image extensions and their corresponding MIME types.
		 */
		private static $allowedImageTypes = [
			'jpg'  => ['image/jpeg'],
			'jpeg' => ['image/jpeg'],
			'png'  => ['image/png'],
			'gif'  => ['image/gif'],
			'webp' => ['image/webp'],
		];

		/**
		 * Allowed extensions for general file uploads (images + json + webm video).
		 */
		private static $allowedUploadTypes = [
			'jpg'  => ['image/jpeg'],
			'jpeg' => ['image/jpeg'],
			'png'  => ['image/png'],
			'gif'  => ['image/gif'],
			'webp' => ['image/webp'],
			'json' => ['application/json', 'text/plain'],
			'webm' => ['video/webm', 'audio/webm'],
			'markdown' => ['text/plain', 'text/markdown'],
		];

		/**
		 * Maximum upload file size in bytes (5 MB).
		 */
		private static $maxFileSize = 5242880;

		/**
		 * Check if a file extension is on the blocklist.
		 *
		 * @param string $extension
		 * @return bool True if the extension is blocked (dangerous).
		 */
		public static function isExtensionBlocked(string $extension): bool
		{
			return in_array(strtolower(trim($extension)), self::$blockedExtensions, true);
		}

		/**
		 * Validate an uploaded file (from $request->file()) for security.
		 * Checks extension blocklist, allowed extensions, MIME type, and file size.
		 *
		 * @param \Illuminate\Http\UploadedFile $file
		 * @param array|null $allowedTypes Associative array of ext => [mimes]. Defaults to $allowedUploadTypes.
		 * @return array ['valid' => bool, 'error' => string|null]
		 */
		public static function validateUploadedFile($file, ?array $allowedTypes = null): array
		{
			if (!$file || !$file->isValid()) {
				return ['valid' => false, 'error' => 'No valid file provided.'];
			}

			$allowedTypes = $allowedTypes ?? self::$allowedUploadTypes;

			// Check file size (max 5MB)
			if ($file->getSize() > self::$maxFileSize) {
				return ['valid' => false, 'error' => 'File exceeds maximum size of 5MB.'];
			}

			// Get and check extension against blocklist
			$extension = strtolower($file->getClientOriginalExtension());
			if (self::isExtensionBlocked($extension)) {
				Log::warning('Blocked file upload attempt with dangerous extension: ' . $extension);
				return ['valid' => false, 'error' => 'File type is not allowed (blocked extension: ' . $extension . ').'];
			}

			// Check extension is in the allowed list
			if (!array_key_exists($extension, $allowedTypes)) {
				return ['valid' => false, 'error' => 'File extension .' . $extension . ' is not permitted.'];
			}

			// Validate MIME type server-side using finfo (do NOT trust client-reported type)
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$detectedMime = $finfo->file($file->getRealPath());
			$allowedMimes = $allowedTypes[$extension];

			if (!in_array($detectedMime, $allowedMimes, true)) {
				Log::warning('File MIME mismatch: extension=' . $extension . ', detected=' . $detectedMime);
				return ['valid' => false, 'error' => 'File MIME type (' . $detectedMime . ') does not match expected type for .' . $extension . '.'];
			}

			// Check for embedded PHP code in the file contents
			$contents = file_get_contents($file->getRealPath());
			if ($contents !== false && self::containsPhpCode($contents)) {
				Log::warning('Blocked file upload containing PHP code');
				return ['valid' => false, 'error' => 'File contains potentially dangerous content.'];
			}

			return ['valid' => true, 'error' => null];
		}

		/**
		 * Validate a base64-decoded image for security.
		 * Checks the extracted extension against blocklist and allowed list,
		 * validates the decoded binary data MIME type via finfo, and checks size.
		 *
		 * @param string $dataUri The full data URI (e.g. "data:image/png;base64,iVBOR...")
		 * @return array ['valid' => bool, 'error' => string|null, 'extension' => string|null, 'data' => string|null]
		 */
		public static function validateBase64Image(string $dataUri): array
		{
			// Parse the data URI -- expected format: "data:image/png;base64,XXXX"
			$parts = explode(';', $dataUri, 2);
			if (count($parts) < 2) {
				return ['valid' => false, 'error' => 'Invalid data URI format.', 'extension' => null, 'data' => null];
			}

			// Extract the MIME type from the data URI header (e.g. "data:image/png")
			$typePart = $parts[0];
			$mimeFromUri = '';
			if (strpos($typePart, '/') !== false) {
				$colonPos = strpos($typePart, ':');
				if ($colonPos !== false) {
					$mimeFromUri = substr($typePart, $colonPos + 1);
				}
			}

			// Extract the extension from the MIME type
			$extension = '';
			if (strpos($mimeFromUri, '/') !== false) {
				list(, $extension) = explode('/', $mimeFromUri, 2);
			}
			$extension = strtolower(trim($extension));

			// Normalize jpeg -> jpg for consistency
			$lookupExt = ($extension === 'jpeg') ? 'jpg' : $extension;

			// Check blocked extensions
			if (self::isExtensionBlocked($extension) || self::isExtensionBlocked($lookupExt)) {
				Log::warning('Blocked base64 upload attempt with dangerous type: ' . $extension);
				return ['valid' => false, 'error' => 'File type is not allowed (blocked type: ' . $extension . ').', 'extension' => null, 'data' => null];
			}

			// Check allowed image types
			if (!array_key_exists($lookupExt, self::$allowedImageTypes)) {
				return ['valid' => false, 'error' => 'Image type .' . $lookupExt . ' is not permitted. Allowed: jpg, png, gif, webp.', 'extension' => null, 'data' => null];
			}

			// Decode the base64 data
			$dataPart = $parts[1];
			if (strpos($dataPart, ',') === false) {
				return ['valid' => false, 'error' => 'Invalid base64 data format.', 'extension' => null, 'data' => null];
			}
			list(, $base64Data) = explode(',', $dataPart, 2);
			$decodedData = base64_decode($base64Data, true);

			if ($decodedData === false) {
				return ['valid' => false, 'error' => 'Failed to decode base64 data.', 'extension' => null, 'data' => null];
			}

			// Check file size (max 5MB)
			if (strlen($decodedData) > self::$maxFileSize) {
				return ['valid' => false, 'error' => 'Image exceeds maximum size of 5MB.', 'extension' => null, 'data' => null];
			}

			// Validate actual MIME type of the decoded binary data using finfo
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$detectedMime = $finfo->buffer($decodedData);

			$acceptableMimes = self::$allowedImageTypes[$lookupExt] ?? [];
			if (!in_array($detectedMime, $acceptableMimes, true)) {
				Log::warning('Base64 image MIME mismatch: claimed=' . $extension . ', detected=' . $detectedMime);
				return ['valid' => false, 'error' => 'Detected MIME type (' . $detectedMime . ') does not match claimed image type (' . $lookupExt . ').', 'extension' => null, 'data' => null];
			}

			// Check for embedded PHP code
			if (self::containsPhpCode($decodedData)) {
				Log::warning('Blocked base64 image upload containing PHP code');
				return ['valid' => false, 'error' => 'Image contains potentially dangerous content.', 'extension' => null, 'data' => null];
			}

			return ['valid' => true, 'error' => null, 'extension' => $lookupExt, 'data' => $decodedData];
		}

		/**
		 * Sanitize a path segment (e.g. a public address) for safe use in file paths.
		 * Prevents directory traversal attacks by stripping path separators and
		 * enforcing an alphanumeric-only pattern.
		 *
		 * @param string $input
		 * @return string|null Returns sanitized string or null if invalid.
		 */
		public static function sanitizePathSegment(string $input): ?string
		{
			// Use basename to strip any directory components
			$sanitized = basename($input);
			// Only allow alphanumeric characters, underscores, and hyphens
			if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $sanitized) || empty($sanitized)) {
				return null;
			}
			return $sanitized;
		}

		/**
		 * Validate a Marscoin address format (Base58Check starting with 'M').
		 * Valid Marscoin addresses are 25-35 characters, start with 'M',
		 * and only use Base58 characters (no 0, O, I, l).
		 */
		public static function isValidMarscoinAddress(string $address): bool
		{
			return (bool) preg_match('/^M[123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz]{24,34}$/', $address);
		}

		/**
		 * Validate an IPFS CID format (CIDv0 starting with 'Qm' or CIDv1 starting with 'b').
		 */
		public static function isValidCID(string $cid): bool
		{
			// CIDv0: starts with Qm, 46 chars total, Base58
			$cidv0 = '/^Qm[123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz]{44}$/';
			// CIDv1: starts with b, base32 encoded
			$cidv1 = '/^b[a-z2-7]{58,}$/';
			return (bool) (preg_match($cidv0, $cid) || preg_match($cidv1, $cid));
		}

		/**
		 * Check if content contains PHP code markers that could be executed
		 * if the file is somehow served through the web server.
		 *
		 * @param string $content
		 * @return bool True if content contains PHP code indicators.
		 */
		public static function containsPhpCode(string $content): bool
		{
			$patterns = ['<?php', '<?=', '<? ', "<?\n", "<?\r", '<%', '<script language="php"', '<script language=\'php\''];
			foreach ($patterns as $pattern) {
				if (stripos($content, $pattern) !== false) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Write an .htaccess file in the upload directory to prevent PHP execution.
		 * This is a defense-in-depth measure.
		 *
		 * @param string $directory
		 * @return void
		 */
		public static function writeUploadHtaccess(string $directory): void
		{
			$htaccessPath = rtrim($directory, '/') . '/.htaccess';
			if (!file_exists($htaccessPath)) {
				$htaccessContent = "# Prevent PHP execution in upload directories\n";
				$htaccessContent .= "php_flag engine off\n";
				$htaccessContent .= "<FilesMatch \"\\.(php|phtml|php5|php7|php8|phar|phps|cgi|pl|py|sh|asp|aspx|jsp)$\">\n";
				$htaccessContent .= "    Require all denied\n";
				$htaccessContent .= "</FilesMatch>\n";
				$htaccessContent .= "AddHandler default-handler .php .phtml .php5 .phar\n";
				@file_put_contents($htaccessPath, $htaccessContent);
			}
		}


		public static function ago($time)
		{
		   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		   $lengths = array("60","60","24","7","4.35","12","10");

		   $now = time();

		       $difference     = $now - $time;
		       $tense         = "ago";

		   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		       $difference /= $lengths[$j];
		   }

		   $difference = round($difference);

		   if($difference != 1) {
		       $periods[$j].= "s";
		   }

		   return "$difference $periods[$j] ago ";
		}


		public static function stats()
		{
			$array["coincount"] = 35000000;
			$json = AppHelper::file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
			$array["network"] = json_decode($json, true);
			$json2 = AppHelper::file_get_contents_curl('http://explore.marscoin.org/api/status?q=getTxOutSetInfo');
			$total = json_decode($json2, true);
			if ($total && count($total) > 0)
				$array["coincount"] = round($total['txoutsetinfo']['total_amount'], 2);

			return $array;
		}

		public static function file_get_contents_curl($url)
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


		public static function uploadFile($name, $url){

			$postField = array();
			$tmpfile = $_FILES[$name]['tmp_name'];
			$filename = basename($_FILES[$name]['name']);
			$postField['files'] =  curl_file_create($tmpfile, $_FILES[$name]['type'], $filename);
			$headers = array("Content-Type" => "multipart/form-data");
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, $url);

			curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl_handle, CURLOPT_POST, TRUE);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postField);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
			$returned_fileName = curl_exec($curl_handle);
			curl_close($curl_handle);
			return json_decode($returned_fileName);
		}


		public static function upload($filepath, $url)
		{
			$filename = realpath($filepath);
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$mimetype = $finfo->file($filename);
			$ch = curl_init($url);
			$cfile = curl_file_create($filename, $mimetype, basename($filename));
			$data = ['file' => $cfile];
			$headers = array("Content-Type" => $mimetype);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			$r = curl_getinfo($ch);
			if ($r["http_code"] != 200) {
				$detais = json_decode($result, true);
				if (isset($detais["msg"])) {
					throw new \Exception($detais["msg"], 1);
				} else {
					return "Error";
				}
			}
			$details = json_decode($result, true);
			$res = array();
			return $details['Hash'];
		}


		public static function uploadFolder($filepath, $url)
		{
			if (!is_dir($filepath) || !is_readable($filepath)) {
				throw new \Exception("Directory is not accessible");
			}

			$files = scandir($filepath);
			$data = [];
			$headers = ["Content-Type: multipart/form-data"];
			$ch = curl_init($url);

			foreach ($files as $i => $filep) {
				$filename = realpath($filepath . "/" . $filep);
				if (!is_file($filename)) {
					continue;
				}

				$finfo = new \finfo(FILEINFO_MIME_TYPE);
				$mimetype = $finfo->file($filename);
				$cfile = curl_file_create($filename, $mimetype, basename($filename));
				$data['file['.$i.']'] = $cfile;
			}

			curl_setopt_array($ch, [
				CURLOPT_URL => $url,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_RETURNTRANSFER => true,
			]);

			$result = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpCode < 200 || $httpCode >= 300) {
				$details = json_decode($result, true);
				$errorMsg = $details['msg'] ?? 'Unknown error occurred';
				throw new \Exception($errorMsg);
			}

			//print_r($result);
			//die;
			// Prepare the result string by wrapping with brackets and replacing the '}{'
			// between JSON objects with '},{', which creates a JSON array
			$resultArrayString = '[' . preg_replace('/}\s*{/', '},{', $result) . ']';

			// Decode the JSON array string
			$jsonResult = json_decode($resultArrayString, true);

			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new \Exception('Invalid JSON returned from API');
			}

			// Now $jsonResult is a proper array
			// Search for the folder hash by finding the object with an empty "Name"
			$folderHash = "";
			foreach ($jsonResult as $item) {
				if ($item['Name'] === "") {
					$folderHash = $item['Hash'];
					break; // No need to continue if the folder hash is found
				}
			}

			// If the folder hash was found, return it as a JSON object
			if ($folderHash !== "") {
				return $folderHash;
			} else {
				// Handle the case where no folder hash was found
				throw new \Exception('Folder hash not found in API response');
			}

		}


		public static function file_post_content($url, $data)
		{

			$postdata = http_build_query($data);

			$opts = array('http' =>
		    	array(
		        	'method'  => 'POST',
		        	'header'  => 'Content-Type: application/x-www-form-urlencoded',
		        	'content' => $postdata
		    	)
			);

			$context  = stream_context_create($opts);

			$result = file_get_contents($url, false, $context);
			return $result;
		}


		public static function getUserFromCache($address)
		{
			$user = array();
			$file_path = "./assets/citizen/" . $address . "/";

			if (!file_exists($file_path)) {
				mkdir($file_path);
				AppHelper::addUserToLocalCache($address);
			}

			$json_string = file_get_contents($file_path . "data.json");
			$user['data'] = json_decode($json_string);
			$user['pic'] = "/assets/citizen/" . $address . "/profile_pic.png";
			$user['vid'] = "/assets/citizen/" . $address . "/profile_video.webm";
			return $user;
		}


		/**
		 * @todo Pulls in user data from IPFS links as per blockchain
		 */
		public static function addUserToLocalCache($address)
		{
			//find user's GP transaction in cache
			$transaction_gp = Feed::where('address', '=', $address)->where('tag', '=', "GP")->first();
			//pull up transaction using blockchain explorer
			$json = AppHelper::file_get_contents_curl("http://explore1.marscoin.org/api/tx/".$transaction_gp['txid']);
			if($json)
			{
				$tx = json_decode($json);
				$op_return = $tx->vout[0]->scriptPubKey->asm;
				$parts = explode(" ", $op_return);
				if(count($parts)>0)
				{
					$ipfs_gp_hash = AppHelper::hex2str($parts[1]);
					$p = explode("_", $ipfs_gp_hash);
					if(count($p) > 0)
					{
						$ipfs_hash = $p[1];
						$data = AppHelper::file_get_contents_curl("https://ipfs.marscoin.org/ipfs/".$ipfs_hash);
						$file_path = "./assets/citizen/" . $address . "/data.json";
						file_put_contents($file_path, $data);
						$d = json_decode($data);

						$img = AppHelper::file_get_contents_curl($d->data->picture);
						$file_path = "./assets/citizen/" . $address . "/profile_pic.png";
						file_put_contents($file_path, $img);
					}
				}

			}

		}


		/**
		 * Helper function keeping a local cache of the Marscoin blockchain embedded data feed
		 * as it pertains to MartianRepublic protocol anchors.
		 */
		public static function insertBlockchainCache($address, $uid, $action_tag, $message, $embedded_link, $txid)
		{
			// First, check if a duplicate entry exists
			$existingFeed = Feed::where('address', $address)
								->where('tag', $action_tag)
								->where('txid', $txid)
								->first();

			if ($existingFeed) {
				// If a duplicate is found, return the txid
				return $existingFeed->txid;
			}

			// If no duplicate, proceed to create a new entry
			$feed = new Feed;
			if ($uid) {
				$feed->userid = $uid;
			}
			if (!$action_tag) {
				return false;
			}
			$feed->tag = $action_tag;
			$feed->address = $address;
			$feed->message = $message;
			$feed->embedded_link = $embedded_link;
			$feed->txid = $txid;
			$feed->save();

			return true;
		}



		public static function insertPublicationCache($uid, $local_path, $ipfs_hash, $title)
		{
			$pub = new Publication;
			if($uid)
			{
				$pub->userid = $uid;
				$pub->ipfs_hash = $ipfs_hash;
				$pub->local_path = $local_path;
				$pub->title = $title;
				$pub->save();
				return TRUE;
			}
		}


		public static function time_elapsed_string($datetime, $full = false)
		{
			date_default_timezone_set('America/New_York');
		    $now = new DateTime;
		    $ago = new DateTime($datetime);
				$diff = $now->diff($ago);

		    $diff->w = floor($diff->d / 7);
		    $diff->d -= $diff->w * 7;

		    $string = array(
		        'y' => 'year',
		        'm' => 'month',
		        'w' => 'week',
		        'd' => 'day',
		        'h' => 'hour',
		        'i' => 'minute',
		        's' => 'second',
		    );
		    foreach ($string as $k => &$v) {
		        if ($diff->$k) {
		            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		        } else {
		            unset($string[$k]);
		        }
		    }

		    if (!$full) $string = array_slice($string, 0, 1);
		    return $string ? implode(', ', $string) . ' ago' : 'just now';
		}

		public static function days_elapsed_string($datetime, $full = false)
		{
			date_default_timezone_set('America/New_York');
		    $now = new DateTime;
		    $ago = new DateTime($datetime);
				$diff = $now->diff($ago);

				return $diff->days;
		}


		public static function hex2str($hex)
		{
		    $str = '';
		    for($i=0;$i<strlen($hex);$i+=2) $str .= chr(hexdec(substr($hex,$i,2)));
		    return $str;
		}


			// Function to get the price from CoinGecko with caching
		public static function getMarscoinPrice()
		{
			$url = "https://api.coingecko.com/api/v3/simple/price?ids=marscoin&vs_currencies=usd";

			// Use the Cache facade with the remember method
			$marsPriceData = Cache::remember('marscoin_price', 5, function () use ($url) {
				// Inside the closure, fetch the data from CoinGecko
				try {
					$response = file_get_contents($url);
					return json_decode($response);
				} catch (\Exception $e) {
					// Handle the exception if the API call fails
					return null;
				}
			});

			if ($marsPriceData) {
				return $marsPriceData->marscoin->usd;
			}

			// Handle the case where the API call was not successful or caching failed
			return 0;
		}


		public static function getMarscoinBalance($publicAddr)
		{
			$url = "https://explore.marscoin.org/api/addr/{$publicAddr}/balance";

			// Unique cache key to store the balance for each address
			$cacheKey = 'marscoin_balance_' . $publicAddr;

			// Use the Cache facade with the remember method
			$curBalance = Cache::remember($cacheKey, 5, function () use ($url) {
				// Inside the closure, fetch the balance from the explorer
				try {
					$response = file_get_contents($url);
					return $response; // Assuming the response is the balance
				} catch (\Exception $e) {
					// Handle the exception if the API call fails
					return null;
				}
			});

			if ($curBalance !== null) {
				// Assuming the balance is returned in satoshis, convert to Marscoin if necessary
				// The conversion logic depends on the API's response format
				return $curBalance * 0.00000001; // Example conversion, adjust based on actual response
			}

			// Handle the case where the API call was not successful or caching failed
			return 0;
		}

		public static function getMarscoinTotalReceived($publicAddr)
		{
			$url = "https://explore.marscoin.org/api/addr/{$publicAddr}/totalReceived";
			$cacheKey = 'marscoin_total_received_' . $publicAddr;

			$totalReceived = Cache::remember($cacheKey, 5, function () use ($url) {
				try {
					$response = file_get_contents($url);
					return $response; // Assuming the response is the total amount received
				} catch (\Exception $e) {
					return null;
				}
			});

			if ($totalReceived !== null) {
				return $totalReceived * 0.00000001; // Convert from satoshis to Marscoin if necessary
			}

			return null;
		}


		public static function getMarscoinTotalSent($publicAddr)
		{
			$url = "https://explore.marscoin.org/api/addr/{$publicAddr}/totalSent";
			$cacheKey = 'marscoin_total_sent_' . $publicAddr;

			$totalSent = Cache::remember($cacheKey, 5, function () use ($url) {
				try {
					$response = file_get_contents($url);
					return $response; // Assuming the response is the total amount sent
				} catch (\Exception $e) {
					return null;
				}
			});

			if ($totalSent !== null) {
				return $totalSent * 0.00000001; // Convert from satoshis to Marscoin if necessary
			}

			return null;
		}


		public static function getMarscoinTotalAmount()
		{
			$url = "https://explore.marscoin.org/api/status?q=getTxOutSetInfo";
			$cacheKey = 'marscoin_total_amount';

			$totalAmount = Cache::remember($cacheKey, 180, function () use ($url) {
				try {
					$response = file_get_contents($url);
					$data = json_decode($response, true);
					if ($data && count($data) > 0) {
						return round($data['txoutsetinfo']['total_amount'], 2);
					}
				} catch (\Exception $e) {
					return 39000000; // Default value in case of an error
				}
				return 39000000; // Default value if the API does not return a valid response
			});

			return $totalAmount;
		}

		public static function getMarscoinNetworkInfo()
		{
			$url = "http://explore2.marscoin.org/api/status?q=getInfo";
			$cacheKey = 'marscoin_network_info';

			$networkInfo = Cache::remember($cacheKey, 60, function () use ($url) {
				try {
					$response = file_get_contents($url);
					$data = json_decode($response, true);
					if (is_array($data)) {
						return $data; // Return the network info if the response is valid
					}
					Log::debug("Set Network status cache");
				} catch (\Exception $e) {
					return []; // Return an empty array in case of an error
				}
				return []; // Also return an empty array if the API does not return a valid response
			});

			return $networkInfo;
		}

		 /**
		 * Check for recent posts in the forum.
		 *
		 * @return int Number of recent posts.
		 */
		public static function checkForRecentPosts()
		{
			// Define the time frame for "recent" posts. For example, within the last 24 hours.
			$recentThreshold = Carbon::now()->subDay();

			// Count the number of posts created after the recent threshold.
			$recentPostsCount = Post::where('created_at', '>', $recentThreshold)->count();

			return $recentPostsCount;
		}


		/**
		 * Determines the Citizen Status of a user.
		 *
		 * @param int $userId The ID of the user.
		 * @return object An associative array containing the 'status' and 'type'.
		 */
		public static function getCitizenStatus(int $userId)
		{
			// Default status for users without a profile entry.
			$statusDetails = [
				'status' => 'Newcomer',
				'type' => 'NC',
			];

			// Attempt to retrieve the user's profile.
			$profile = Profile::where('userid', $userId)->first();

			if ($profile) {
				if ($profile->has_application > 0) {
					$statusDetails = [
						'status' => 'Applicant',
						'type' => 'AP',
					];
				} elseif ($profile->general_public > 0) {
					$statusDetails = [
						'status' => 'General Public',
						'type' => 'GP',
					];
				}

				// Check if the user is a citizen.
				$isCitizen = Citizen::where('userid', $userId)->exists();
				if ($isCitizen) {
					$statusDetails = [
						'status' => 'Citizen',
						'type' => 'CT',
					];
				}
			}

			return (object)$statusDetails;
		}


		public static function createSlug($id, $title) {
			// Step 1: Concatenate
			$combined = $id . '-' . $title;

			// Step 2: Lowercase
			$combined = strtolower($combined);

			// Step 3: Remove special characters
			$combined = preg_replace('/[^a-z0-9\s-]/', '', $combined);

			// Step 4: Trim whitespace
			$combined = trim($combined);

			// Step 5: Replace spaces and underscores with hyphens
			$combined = preg_replace('/[\s_]+/', '-', $combined);

			// Step 6: Ensure uniqueness (not implemented here, depends on context)

			return $combined;
		}



}
