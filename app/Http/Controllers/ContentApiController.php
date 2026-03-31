<?php

namespace App\Http\Controllers;

use App\Includes\AppHelper;
use App\Models\Citizen;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContentApiController extends Controller
{
    public function pinpic(Request $request)
    {
        $uid = Auth::user()->id;
        $hash = '';
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
        if (! $validation['valid']) {
            Log::warning('pinpic upload rejected: '.$validation['error'].' (user: '.$uid.')');

            return response()->json(['error' => $validation['error']], 422);
        }

        $safeExtension = $validation['extension'];
        $decodedData = $validation['data'];

        $file_path = './assets/citizen/'.$safeAddress.'/';
        if (! file_exists($file_path)) {
            mkdir($file_path, 0755, true);
        }

        // --- SECURITY: Write .htaccess to prevent PHP execution in upload dir ---
        AppHelper::writeUploadHtaccess($file_path);

        // Use validated extension, not user-supplied one
        $file_path = './assets/citizen/'.$safeAddress.'/profile_pic.'.$safeExtension;

        file_put_contents($file_path, $decodedData);
        $hash = AppHelper::upload($file_path, config('blockchain.ipfs.api_url').'/api/v0/add?pin=true');

        $citcache = Citizen::where('userid', '=', $uid)->first();
        if (is_null($citcache)) {
            $citcache = new Citizen;
        }
        $citcache->userid = $uid;
        $citcache->avatar_link = config('blockchain.ipfs.gateway_url').$hash;
        $citcache->save();

        return (new Response(json_encode(['Hash' => $hash]), 200))
            ->header('Content-Type', 'application/json;');

    }

    public function pinvideo(Request $request)
    {

        Log::debug('pinvid');
        $uid = Auth::user()->id;
        $hash = '';
        $dataPic = $request->input('file');
        $type = $request->input('type');
        $public_address = $request->input('address');

        // --- SECURITY: Sanitize the public_address to prevent directory traversal ---
        $safeAddress = AppHelper::sanitizePathSegment($public_address);
        if ($safeAddress === null) {
            return response()->json(['error' => 'Invalid address format.'], 400);
        }

        Log::debug('public: '.$safeAddress);
        if ($request->hasFile('file')) {
            // --- SECURITY: Validate the uploaded file (extension, MIME, size, PHP code) ---
            $uploadedFile = $request->file('file');
            $validation = AppHelper::validateUploadedFile($uploadedFile, [
                'webm' => ['video/webm', 'audio/webm'],
            ]);
            if (! $validation['valid']) {
                Log::warning('pinvideo upload rejected: '.$validation['error'].' (user: '.$uid.')');

                return response()->json(['error' => $validation['error']], 422);
            }

            Log::debug('storing file');
            $file_path = './assets/citizen/'.$safeAddress.'/';
            if (! file_exists($file_path)) {
                mkdir($file_path, 0755, true);
                Log::debug('making dir');
            }

            // --- SECURITY: Write .htaccess to prevent PHP execution in upload dir ---
            AppHelper::writeUploadHtaccess($file_path);

            $file_path = './assets/citizen/'.$safeAddress.'/';
            $request->file('file')->move($file_path, 'profile_video.webm');
            $file_path = $file_path.'profile_video.webm';
            $hash = AppHelper::upload($file_path, config('blockchain.ipfs.api_url').'/api/v0/add?pin=true');
            Log::debug('upload complete: '.$hash);
            $citcache = Citizen::where('userid', '=', $uid)->first();
            if (is_null($citcache)) {
                $citcache = new Citizen;
            }
            $citcache->userid = $uid;
            $citcache->liveness_link = config('blockchain.ipfs.gateway_url').$hash;
            $citcache->save();
            Log::debug('saved cit data');

            return (new Response(json_encode(['Hash' => $hash]), 200))
                ->header('Content-Type', 'application/json;');
        } else {
            Log::debug('no file found!');
        }

    }

    /**
     * Handles JSON storage and pinning to a distributed file system.
     *
     * @hideFromAPIDocumentation
     */
    public function pinjson(Request $request)
    {
        Log::info('in function');
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
            Log::warning('pinjson rejected: payload contains PHP code');

            return response()->json(['error' => 'Payload contains potentially dangerous content.'], 422);
        }

        // --- SECURITY: Validate that payload is valid JSON ---
        $decodedJson = json_decode($json);
        if ($json !== '' && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON payload.'], 422);
        }

        $projectRoot = config('app.project_root', base_path());
        $base_path = $projectRoot.'/assets/citizen/'.$safeAddress;

        // Check and create the directory if it doesn't exist
        Log::info($base_path);
        clearstatcache();
        if (! is_dir($base_path)) {
            Log::info('Trying to create directory: '.$base_path);
            if (! mkdir($base_path, 0755, true)) {
                Log::error('Failed to create directory: '.$base_path);

                return response()->json(['error' => 'Failed to create directory. Check permissions.'], 500);
            }
            Log::info('Directory created: '.$base_path);
        }

        // Check if the directory is writable, regardless of whether it was just created or already existed
        if (! is_writable($base_path)) {
            Log::error('Directory not writable: '.$base_path);

            return response()->json(['error' => 'Directory is not writable. Check permissions.'], 500);
        }

        // --- SECURITY: Write .htaccess to prevent PHP execution in upload dir ---
        AppHelper::writeUploadHtaccess($base_path);

        $file_path = $base_path.'/'.$safeType.'.json';

        // Attempt to write the JSON data to the file
        if (file_put_contents($file_path, $json) === false) {
            return response()->json(['error' => 'Failed to write to file.'], 500);
        }

        try {
            Log::info('PermaJson: '.$file_path);

            // Check if the type contains the word 'log'
            if (strpos($safeType, 'log') !== false) {
                // The type contains 'log', use uploadFolder
                $apiResponse = AppHelper::uploadFolder($file_path, config('blockchain.ipfs.api_url').'/api/v0/add?pin=true&recursive=true&wrap-with-directory=true&quieter');
            } else {
                // The type does not contain 'log', use upload
                $apiResponse = AppHelper::upload($file_path, config('blockchain.ipfs.api_url').'/api/v0/add?pin=true');
            }

            if (is_string($apiResponse)) {
                $formattedResponse = ['Hash' => $apiResponse];
            } else {
                Log::error('Upload error: Formatting');

                return response()->json(['error' => 'formatting error'], 500);
            }

            return response()->json($formattedResponse, 200)->header('Content-Type', 'application/json;');
        } catch (\Exception $e) {
            // Handle exceptions during the upload and pinning process
            Log::error('Upload error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
