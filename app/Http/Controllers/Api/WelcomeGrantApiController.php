<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WelcomeGrant\WelcomeGrantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Welcome grant for newcomers (mobile). Every outcome, including "not now",
 * is a 200 answer with a status and a plain reason. See docs/WELCOME_GRANT.md.
 */
class WelcomeGrantApiController extends Controller
{
    public function __construct(private WelcomeGrantService $grants) {}

    public function challenge(Request $request): JsonResponse
    {
        try {
            return $this->answer($this->grants->challenge($request->user(), $request->ip()));
        } catch (\Throwable $error) {
            report($error);

            return $this->answer([
                'eligible' => false, 'reason' => 'The welcome grant cannot be offered at the moment.', 'status' => 'unavailable',
                'address' => (string) rescue(fn () => $this->grants->civicAddress($request->user()), '', false),
                'amount_zubrins' => $this->grants->amount(),
                'nonce' => null, 'message' => null, 'expires_at' => null, 'txid' => null,
            ]);
        }
    }

    public function claim(Request $request): JsonResponse
    {
        try {
            return $this->answer($this->grants->claim(
                $request->user(), $request->input('address'), $request->input('nonce'), $request->input('signature'), $request->ip()
            ));
        } catch (\Throwable $error) {
            report($error);

            return $this->answer([
                'status' => 'unavailable', 'txid' => null, 'amount_zubrins' => null,
                'reason' => 'The welcome grant cannot be paid at the moment.',
            ]);
        }
    }

    public function status(Request $request): JsonResponse
    {
        try {
            return $this->answer($this->grants->status($request->user()));
        } catch (\Throwable $error) {
            report($error);

            return $this->answer(['granted' => false, 'txid' => null, 'amount_zubrins' => null, 'granted_at' => null]);
        }
    }

    private function answer(array $data): JsonResponse
    {
        return response()->json($data)->header('Cache-Control', 'private, no-store');
    }
}
