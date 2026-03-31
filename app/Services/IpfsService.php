<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * IPFS operations — upload, pin, validate, retrieve.
 * Wraps the local IPFS node API (kubo on port 5001).
 */
class IpfsService
{
    private string $apiUrl;
    private string $gatewayUrl;

    public function __construct()
    {
        $this->apiUrl = config('blockchain.ipfs.api_url', 'http://127.0.0.1:5001');
        $this->gatewayUrl = config('blockchain.ipfs.gateway_url', 'https://ipfs.marscoin.org/ipfs/');
    }

    /**
     * Pin a file to IPFS. Returns the CID hash.
     */
    public function pinFile(string $filePath, ?string $mimeType = null): ?string
    {
        if (! file_exists($filePath)) {
            Log::warning('IpfsService: file not found', ['path' => $filePath]);
            return null;
        }

        try {
            $response = Http::timeout(30)
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post($this->apiUrl . '/api/v0/add?pin=true');

            if ($response->successful()) {
                $data = $response->json();
                $hash = $data['Hash'] ?? null;
                if ($hash) {
                    Log::info('IpfsService: pinned', ['hash' => $hash, 'size' => $data['Size'] ?? 0]);
                }
                return $hash;
            }
        } catch (\Exception $e) {
            Log::error('IpfsService: pin failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Pin raw content (base64-decoded) to IPFS.
     */
    public function pinContent(string $content, string $filename = 'data'): ?string
    {
        try {
            $response = Http::timeout(30)
                ->attach('file', $content, $filename)
                ->post($this->apiUrl . '/api/v0/add?pin=true');

            if ($response->successful()) {
                return $response->json()['Hash'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('IpfsService: pin content failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Pin a folder to IPFS. Returns the root CID.
     */
    public function pinFolder(string $folderPath): ?string
    {
        if (! is_dir($folderPath)) {
            return null;
        }

        try {
            $multipart = [];
            $files = scandir($folderPath);

            foreach ($files as $file) {
                $fullPath = realpath($folderPath . '/' . $file);
                if (! $fullPath || ! is_file($fullPath)) continue;
                if (! str_starts_with($fullPath, realpath($folderPath))) continue;

                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $multipart[] = [
                    'name' => 'file',
                    'contents' => file_get_contents($fullPath),
                    'filename' => $file,
                    'headers' => ['Content-Type' => $finfo->file($fullPath)],
                ];
            }

            $response = Http::timeout(60)
                ->asMultipart()
                ->post($this->apiUrl . '/api/v0/add?pin=true&wrap-with-directory=true', $multipart);

            if ($response->successful()) {
                // Last line contains the directory hash
                $lines = array_filter(explode("\n", $response->body()));
                $last = json_decode(end($lines), true);
                return $last['Hash'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('IpfsService: pin folder failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Retrieve content from IPFS by CID.
     */
    public function get(string $cid): ?string
    {
        try {
            $response = Http::timeout(15)->get($this->gatewayUrl . $cid);
            return $response->successful() ? $response->body() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validate a CID format.
     */
    public function isValidCID(string $cid): bool
    {
        $cidv0 = '/^Qm[123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz]{44}$/';
        $cidv1 = '/^b[a-z2-7]{58,}$/';

        return (bool) (preg_match($cidv0, $cid) || preg_match($cidv1, $cid));
    }

    /**
     * Get the public gateway URL for a CID.
     */
    public function gatewayUrl(string $cid): string
    {
        return $this->gatewayUrl . $cid;
    }
}
