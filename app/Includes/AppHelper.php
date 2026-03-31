<?php

namespace App\Includes;

use App\Models\Citizen;
use App\Models\Feed;
use App\Models\Profile;
use App\Models\Publication;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use TeamTeaTime\Forum\Models\Post;

class AppHelper
{
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
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'gif' => ['image/gif'],
        'webp' => ['image/webp'],
    ];

    /**
     * Allowed extensions for general file uploads (images + json + webm video).
     */
    private static $allowedUploadTypes = [
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'gif' => ['image/gif'],
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
     * @param  UploadedFile  $file
     * @param  array|null  $allowedTypes  Associative array of ext => [mimes]. Defaults to $allowedUploadTypes.
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public static function validateUploadedFile($file, ?array $allowedTypes = null): array
    {
        if (! $file || ! $file->isValid()) {
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
            Log::warning('Blocked file upload attempt with dangerous extension: '.$extension);

            return ['valid' => false, 'error' => 'File type is not allowed (blocked extension: '.$extension.').'];
        }

        // Check extension is in the allowed list
        if (! array_key_exists($extension, $allowedTypes)) {
            return ['valid' => false, 'error' => 'File extension .'.$extension.' is not permitted.'];
        }

        // Validate MIME type server-side using finfo (do NOT trust client-reported type)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $detectedMime = $finfo->file($file->getRealPath());
        $allowedMimes = $allowedTypes[$extension];

        if (! in_array($detectedMime, $allowedMimes, true)) {
            Log::warning('File MIME mismatch: extension='.$extension.', detected='.$detectedMime);

            return ['valid' => false, 'error' => 'File MIME type ('.$detectedMime.') does not match expected type for .'.$extension.'.'];
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
     * @param  string  $dataUri  The full data URI (e.g. "data:image/png;base64,iVBOR...")
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
            [, $extension] = explode('/', $mimeFromUri, 2);
        }
        $extension = strtolower(trim($extension));

        // Normalize jpeg -> jpg for consistency
        $lookupExt = ($extension === 'jpeg') ? 'jpg' : $extension;

        // Check blocked extensions
        if (self::isExtensionBlocked($extension) || self::isExtensionBlocked($lookupExt)) {
            Log::warning('Blocked base64 upload attempt with dangerous type: '.$extension);

            return ['valid' => false, 'error' => 'File type is not allowed (blocked type: '.$extension.').', 'extension' => null, 'data' => null];
        }

        // Check allowed image types
        if (! array_key_exists($lookupExt, self::$allowedImageTypes)) {
            return ['valid' => false, 'error' => 'Image type .'.$lookupExt.' is not permitted. Allowed: jpg, png, gif, webp.', 'extension' => null, 'data' => null];
        }

        // Decode the base64 data
        $dataPart = $parts[1];
        if (strpos($dataPart, ',') === false) {
            return ['valid' => false, 'error' => 'Invalid base64 data format.', 'extension' => null, 'data' => null];
        }
        [, $base64Data] = explode(',', $dataPart, 2);
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
        if (! in_array($detectedMime, $acceptableMimes, true)) {
            Log::warning('Base64 image MIME mismatch: claimed='.$extension.', detected='.$detectedMime);

            return ['valid' => false, 'error' => 'Detected MIME type ('.$detectedMime.') does not match claimed image type ('.$lookupExt.').', 'extension' => null, 'data' => null];
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
     * @return string|null Returns sanitized string or null if invalid.
     */
    public static function sanitizePathSegment(string $input): ?string
    {
        // Use basename to strip any directory components
        $sanitized = basename($input);
        // Only allow alphanumeric characters, underscores, and hyphens
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $sanitized) || empty($sanitized)) {
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
     * @return bool True if content contains PHP code indicators.
     */
    public static function containsPhpCode(string $content): bool
    {
        // Only check the first 256 bytes (header area) for PHP signatures
        // Binary image data often contains random byte sequences that match
        // PHP patterns, causing false positives on valid camera photos
        $header = substr($content, 0, 256);
        $patterns = ['<?php', '<?=', '<script language="php"', '<script language=\'php\''];
        foreach ($patterns as $pattern) {
            if (stripos($header, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Write an .htaccess file in the upload directory to prevent PHP execution.
     * This is a defense-in-depth measure.
     */
    public static function writeUploadHtaccess(string $directory): void
    {
        $htaccessPath = rtrim($directory, '/').'/.htaccess';
        if (! file_exists($htaccessPath)) {
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
        $periods = ['second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade'];
        $lengths = ['60', '60', '24', '7', '4.35', '12', '10'];

        $now = time();

        $difference = $now - $time;
        $tense = 'ago';

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= 's';
        }

        return "$difference $periods[$j] ago ";
    }

    public static function stats()
    {
        $array['coincount'] = 35000000;
        $json = AppHelper::file_get_contents_curl(config('blockchain.explorer.primary_url').'/api/status?q=getInfo');
        $array['network'] = json_decode($json, true);
        $json2 = AppHelper::file_get_contents_curl(config('blockchain.explorer.primary_url').'/api/status?q=getTxOutSetInfo');
        $total = json_decode($json2, true);
        if ($total && count($total) > 0) {
            $array['coincount'] = round($total['txoutsetinfo']['total_amount'], 2);
        }

        return $array;
    }

    public static function file_get_contents_curl($url, $timeout = 10)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-CMC_PRO_API_KEY: cf191ba7-4840-4a9a-bee4-617608afd8a4']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public static function uploadFile($name, $url)
    {

        $postField = [];
        $tmpfile = $_FILES[$name]['tmp_name'];
        $filename = basename($_FILES[$name]['name']);
        $postField['files'] = curl_file_create($tmpfile, $_FILES[$name]['type'], $filename);
        $headers = ['Content-Type' => 'multipart/form-data'];
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);

        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handle, CURLOPT_POST, true);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postField);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $returned_fileName = curl_exec($curl_handle);
        curl_close($curl_handle);

        return json_decode($returned_fileName);
    }

    public static function upload($filepath, $url)
    {
        $filename = realpath($filepath);
        if (! $filename || ! str_starts_with($filename, public_path()) && ! str_starts_with($filename, storage_path())) {
            throw new \RuntimeException('Upload path outside allowed directories');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimetype = $finfo->file($filename);
        $ch = curl_init($url);
        $cfile = curl_file_create($filename, $mimetype, basename($filename));
        $data = ['file' => $cfile];
        $headers = ['Content-Type' => $mimetype];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $r = curl_getinfo($ch);
        if ($r['http_code'] != 200) {
            $detais = json_decode($result, true);
            if (isset($detais['msg'])) {
                throw new \Exception($detais['msg'], 1);
            } else {
                return 'Error';
            }
        }
        $details = json_decode($result, true);
        $res = [];

        return $details['Hash'];
    }

    public static function uploadFolder($filepath, $url)
    {
        if (! is_dir($filepath) || ! is_readable($filepath)) {
            throw new \Exception('Directory is not accessible');
        }

        $files = scandir($filepath);
        $data = [];
        $headers = ['Content-Type: multipart/form-data'];
        $ch = curl_init($url);

        foreach ($files as $i => $filep) {
            $filename = realpath($filepath.'/'.$filep);
            if (! $filename || (! str_starts_with($filename, public_path()) && ! str_starts_with($filename, storage_path()))) {
                continue;
            }
            if (! is_file($filename)) {
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

        // print_r($result);
        // die;
        // Prepare the result string by wrapping with brackets and replacing the '}{'
        // between JSON objects with '},{', which creates a JSON array
        $resultArrayString = '['.preg_replace('/}\s*{/', '},{', $result).']';

        // Decode the JSON array string
        $jsonResult = json_decode($resultArrayString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON returned from API');
        }

        // Now $jsonResult is a proper array
        // Search for the folder hash by finding the object with an empty "Name"
        $folderHash = '';
        foreach ($jsonResult as $item) {
            if ($item['Name'] === '') {
                $folderHash = $item['Hash'];
                break; // No need to continue if the folder hash is found
            }
        }

        // If the folder hash was found, return it as a JSON object
        if ($folderHash !== '') {
            return $folderHash;
        } else {
            // Handle the case where no folder hash was found
            throw new \Exception('Folder hash not found in API response');
        }

    }

    public static function file_post_content($url, $data)
    {

        $postdata = http_build_query($data);

        $opts = ['http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata,
        ],
        ];

        $context = stream_context_create($opts);

        $result = file_get_contents($url, false, $context);

        return $result;
    }

    public static function getUserFromCache($address)
    {
        $user = [];
        $file_path = './assets/citizen/'.$address.'/';

        if (! file_exists($file_path)) {
            mkdir($file_path);
            AppHelper::addUserToLocalCache($address);
        }

        $json_string = file_get_contents($file_path.'data.json');
        $user['data'] = json_decode($json_string);
        $user['pic'] = '/assets/citizen/'.$address.'/profile_pic.png';
        $user['vid'] = '/assets/citizen/'.$address.'/profile_video.webm';

        return $user;
    }

    /**
     * @todo Pulls in user data from IPFS links as per blockchain
     */
    public static function addUserToLocalCache($address)
    {
        // find user's GP transaction in cache
        $transaction_gp = Feed::where('address', '=', $address)->where('tag', '=', 'GP')->first();
        // pull up transaction using blockchain explorer
        $json = AppHelper::file_get_contents_curl(config('blockchain.explorer.fallback_url').'/api/tx/'.$transaction_gp['txid']);
        if ($json) {
            $tx = json_decode($json);
            $op_return = $tx->vout[0]->scriptPubKey->asm;
            $parts = explode(' ', $op_return);
            if (count($parts) > 0) {
                $ipfs_gp_hash = AppHelper::hex2str($parts[1]);
                $p = explode('_', $ipfs_gp_hash);
                if (count($p) > 0) {
                    $ipfs_hash = $p[1];
                    $data = AppHelper::file_get_contents_curl(config('blockchain.ipfs.gateway_url').$ipfs_hash);
                    $sanitizedAddr = preg_replace('/[^A-Za-z0-9]/', '', $address);
                    $file_path = public_path("assets/citizen/{$sanitizedAddr}/data.json");
                    if (! is_dir(dirname($file_path))) {
                        mkdir(dirname($file_path), 0755, true);
                    }
                    file_put_contents($file_path, $data);
                    $d = json_decode($data);

                    $img = AppHelper::file_get_contents_curl($d->data->picture);
                    $file_path = public_path("assets/citizen/{$sanitizedAddr}/profile_pic.png");
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
        if (! $action_tag) {
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
        if ($uid) {
            $pub->userid = $uid;
            $pub->ipfs_hash = $ipfs_hash;
            $pub->local_path = $local_path;
            $pub->title = $title;
            $pub->save();

            return true;
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

        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k.' '.$v.($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (! $full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string).' ago' : 'just now';
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
        for ($i = 0; $i < strlen($hex); $i += 2) {
            $str .= chr(hexdec(substr($hex, $i, 2)));
        }

        return $str;
    }

    // Function to get the price from CoinGecko with caching
    public static function getMarscoinPrice()
    {
        $url = config('blockchain.price.coingecko_url');

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
        $cacheKey = 'marscoin_balance_'.$publicAddr;

        // Cache for 2 minutes to reduce API load while staying reasonably fresh
        return Cache::remember($cacheKey, 120, function () use ($publicAddr) {
            // Primary: use local pebas (Electrum-based, more reliable)
            try {
                $ctx = stream_context_create(['http' => ['timeout' => 5]]);
                $response = Http::timeout(5)->get(config('blockchain.pebas.url')."/api/mars/balance?address={$publicAddr}")->body();
                if ($response) {
                    $data = json_decode($response, true);
                    if (isset($data['balance'])) {
                        return (float) $data['balance'];
                    }
                }
            } catch (\Exception $e) {
                // Fall through to explorer
            }

            // Fallback: explorer API (returns balance in satoshis)
            try {
                $response = Http::timeout(5)->get(config('blockchain.explorer.primary_url')."/api/addr/{$publicAddr}/balance")->body();
                if ($response !== false && is_numeric($response)) {
                    return $response * 0.00000001;
                }
            } catch (\Exception $e) {
                // Both failed
            }

            return 0;
        });
    }

    public static function getMarscoinTotalReceived($publicAddr)
    {
        $url = config('blockchain.explorer.primary_url')."/api/addr/{$publicAddr}/totalReceived";
        $cacheKey = 'marscoin_total_received_'.$publicAddr;

        $totalReceived = Cache::remember($cacheKey, 300, function () use ($url) {
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
        $url = config('blockchain.explorer.primary_url')."/api/addr/{$publicAddr}/totalSent";
        $cacheKey = 'marscoin_total_sent_'.$publicAddr;

        $totalSent = Cache::remember($cacheKey, 300, function () use ($url) {
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
        $cacheKey = 'marscoin_total_amount';

        $totalAmount = Cache::remember($cacheKey, 180, function () {
            // Primary: marscoin-cli (fastest, most reliable)
            try {
                $output = shell_exec(config('blockchain.rpc.cli_path').' -datadir='.config('blockchain.rpc.data_dir').' gettxoutsetinfo 2>/dev/null');
                if ($output) {
                    $data = json_decode($output, true);
                    if ($data && isset($data['total_amount'])) {
                        return round($data['total_amount'], 2);
                    }
                }
            } catch (\Exception $e) {
                // Fall through to explorer
            }

            // Fallback: explorer API
            try {
                $url = config('blockchain.explorer.primary_url').'/api/status?q=getTxOutSetInfo';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                if ($data && isset($data['txoutsetinfo']['total_amount'])) {
                    return round($data['txoutsetinfo']['total_amount'], 2);
                }
            } catch (\Exception $e) {
                return 39000000;
            }

            return 39000000;
        });

        return $totalAmount;
    }

    public static function getMarscoinNetworkInfo()
    {
        $url = config('blockchain.explorer.secondary_url').'/api/status?q=getInfo';
        $cacheKey = 'marscoin_network_info';

        $networkInfo = Cache::remember($cacheKey, 60, function () use ($url) {
            try {
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                if (is_array($data)) {
                    return $data; // Return the network info if the response is valid
                }
                Log::debug('Set Network status cache');
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
     * @param  int  $userId  The ID of the user.
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

        return (object) $statusDetails;
    }

    public static function createSlug($id, $title)
    {
        // Step 1: Concatenate
        $combined = $id.'-'.$title;

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
