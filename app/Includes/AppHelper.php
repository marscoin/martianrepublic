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