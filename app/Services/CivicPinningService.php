<?php

namespace App\Services;

use App\Includes\AppHelper;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CivicPinningService
{
    public function pin(string $path, string $filename, bool $wrap = false): string
    {
        $stream = fopen($path, 'rb');
        if ($stream === false) {
            throw new RuntimeException('The upload could not be read.');
        }
        try {
            $url = rtrim(config('blockchain.ipfs.api_url'), '/').'/api/v0/add?pin=true';
            if ($wrap) {
                $url .= '&recursive=true&wrap-with-directory=true&quieter';
            }
            // Never retry a write implicitly. IPFS content addressing makes an
            // explicit retry of these same bytes return the same reference.
            $response = Http::connectTimeout(10)->timeout(90)->attach('file', $stream, $filename)->post($url);
            $response->throw();
            $lines = preg_split('/\r?\n/', trim($response->body()));
            $last = json_decode(end($lines), true);
            $cid = is_array($last) ? ($last['Hash'] ?? null) : null;
            if (! is_string($cid) || ! AppHelper::isValidCID($cid)) {
                throw new RuntimeException('The pinning service returned an invalid reference.');
            }

            return $cid;
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }
}
