<?php

namespace App\Http\Controllers;

use App\Includes\AppHelper;
use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Services\CivicPinningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContentApiController extends Controller
{
    public function pinpic(Request $request): JsonResponse
    {
        $request->validate(['address' => 'required|string|max:50', 'picture' => 'required|string|max:7340032']);
        $validation = AppHelper::validateBase64Image($request->input('picture'));
        if (! $validation['valid']) {
            return response()->json(['error' => $validation['error']], 422);
        }
        $address = $this->ownedAddress($request);

        return $this->pinBytes($validation['data'], 'profile_pic.'.$validation['extension'], function ($cid) use ($address) {
            $this->saveMediaReference($address, 'avatar_link', $cid);
        });
    }

    public function pinvideo(Request $request): JsonResponse
    {
        $request->validate(['address' => 'required|string|max:50']);
        if (! $request->hasFile('file')) {
            return response()->json(['error' => 'No valid file provided.'], 422);
        }
        $file = $request->file('file');
        $validation = AppHelper::validateUploadedFile($file, AppHelper::CITIZENSHIP_VIDEO_TYPES);
        if (! $validation['valid']) {
            return response()->json(['error' => $validation['error']], 422);
        }
        $address = $this->ownedAddress($request);
        try {
            $cid = app(CivicPinningService::class)->pin($file->getRealPath(), 'profile_video.'.strtolower($file->getClientOriginalExtension()));
            $this->saveMediaReference($address, 'liveness_link', $cid);

            return response()->json(['Hash' => $cid]);
        } catch (\Throwable $error) {
            report($error);

            return $this->unavailable();
        }
    }

    public function pinjson(Request $request): JsonResponse
    {
        $data = $request->validate([
            'address' => 'required|string|max:50', 'type' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9_-]+$/'],
            'payload' => 'required|string|json|max:5242880',
        ]);
        $this->ownedAddress($request);
        if (strlen($data['payload']) > 5242880) {
            return response()->json(['error' => 'Payload exceeds maximum size of 5MB.'], 422);
        }
        if (AppHelper::containsPhpCode($data['payload'])) {
            return response()->json(['error' => 'Payload contains potentially dangerous content.'], 422);
        }

        // Each upload has its own private temporary file. Concurrent devices
        // cannot overwrite one another's metadata before IPFS reads it.
        return $this->pinBytes($data['payload'], $data['type'].'.json', fn () => null, str_contains($data['type'], 'log'));
    }

    private function ownedAddress(Request $request): string
    {
        $address = $request->input('address');
        $user = Auth::user();
        abort_unless($user && $user->status !== 'inactive' && is_string($address)
            && CivicWallet::where('user_id', $user->id)->where('public_addr', $address)->exists(),
            403, 'Uploads must use your own civic address.');

        return $address;
    }

    private function saveMediaReference(string $address, string $field, string $cid): void
    {
        // Update the profile only after successful pinning. A temporary outage
        // must never erase a previously uploaded photograph or liveness video.
        $citizen = Citizen::firstOrNew(['userid' => Auth::user()->id]);
        $citizen->public_address = $address;
        $citizen->$field = rtrim(config('blockchain.ipfs.gateway_url'), '/').'/'.$cid;
        $citizen->save();
    }

    private function pinBytes(string $bytes, string $filename, callable $afterPin, bool $wrap = false): JsonResponse
    {
        $directory = storage_path('app/private/civic-uploads');
        $path = null;
        try {
            if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
                throw new \RuntimeException('Upload storage is unavailable.');
            }
            $path = $directory.'/'.Str::uuid();
            if (file_put_contents($path, $bytes, LOCK_EX) !== strlen($bytes)) {
                throw new \RuntimeException('The upload could not be saved.');
            }
            chmod($path, 0600);
            $cid = app(CivicPinningService::class)->pin($path, $filename, $wrap);
            $afterPin($cid);

            return response()->json(['Hash' => $cid]);
        } catch (\Throwable $error) {
            report($error);

            return $this->unavailable();
        } finally {
            if ($path !== null && is_file($path)) {
                unlink($path);
            }
        }
    }

    private function unavailable(): JsonResponse
    {
        return response()->json(['error' => 'Your upload could not be pinned. Your previous profile is unchanged; retry your saved draft.'], 503);
    }
}
