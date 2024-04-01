<?php


namespace App\Includes;
use App\Models\Feed;
use App\Models\Publication;
use DateTime;


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
			echo $url;
			$directory = basename($filepath);
			//echo "dir: ". $directory;
			$files = scandir($filepath);
			//print_r($files);
			$data = array();
			$headers = array("Content-Type" => "multipart/form-data");
			$ch = curl_init($url);
			$i = 0;
			foreach ($files as $filep)
			{
				$i = $i + 1;
				$filename = realpath($filepath."/".$filep);
				if (!is_file($filename))
        			continue;
				//echo "path: " . $filename;
				$finfo = new \finfo(FILEINFO_MIME_TYPE);
				$mimetype = $finfo->file($filename);
				$cfile = curl_file_create($filename, $mimetype, basename($filename));
				//print_r($cfile);
				//array_push($data, array('file' => $cfile));
				$data += ['file['.$i.']' => $cfile];
				
			}			
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
			print_r($result);
			$result = explode("\n", $result);
			// $details = json_decode($result, true);
			// foreach($details as $l){
			// 	print_r($l);
			// }
			$l = count($result);
			echo $l;
			if($l > 0){
				$details = json_decode($result[$l-2], true);
				return $details['Hash'];
			}
			else return "{'Hash':''}";
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
			$feed = new Feed;
			if($uid)
				$feed->userid = $uid;
			if(!$action_tag)
				return FALSE;
			$feed->tag = $action_tag;
			$feed->address = $address;
			$feed->message = $message;
			$feed->embedded_link = $embedded_link;
			$feed->txid = $txid;
			$feed->save();
			return TRUE;
		}


		public static function insertPublicationCache($uid, $local_path, $ipfs_hash)
		{
			$pub = new Publication;
			if($uid)
				$pub->userid = $uid;
			$pub->ipfs_hash = $ipfs_hash;
			$pub->local_path = $local_path;
			$pub->save();
			return TRUE;
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


}