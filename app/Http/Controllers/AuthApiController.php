<?php

namespace App\Http\Controllers;

use App\Includes\MarscoinECDSA;
use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Models\MSession;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    public function marsAuth(Request $request)
    {
        // Native wallet app only — no browser calls this, so no CORS header.
        if (! $request->isMethod('post')) {
            return $this->fastcode(400, 'bad request');
        }

        $challenge = (string) $request->query('c');
        $session_string = (string) $request->query('sid');
        $timestampHex = (string) $request->query('t');
        $timestamp = hexdec($timestampHex);
        $msg = $request->input('msg');
        $sig = $request->input('sig');
        $address = $request->input('addr');

        if ($timestamp < (time() - 600)) {
            return $this->fastcode(408, 'request timed out');
        }

        // The wallet must sign exactly this canonical, domain-bound message,
        // built from the request's own query values (t is the hex string as
        // produced by dechex(time())). Without this binding marsAuth would
        // accept any signature an address ever produced — including sample
        // signatures published in the repo, or any a citizen posted publicly —
        // and let it be replayed to log in as that citizen.
        $expected = "Martian Republic login\n"
            ."domain: martianrepublic.org\n"
            ."sid: {$session_string}\n"
            ."challenge: {$challenge}\n"
            ."issued: {$timestampHex}";

        if (! is_string($msg) || ! hash_equals($expected, $msg)) {
            return $this->fastcode(406, "couldn't verify message");
        }

        $session = MSession::where('sid', $session_string)->first();

        if ($session && in_array($challenge, explode(',', $session->v), true)) {
            $marscoinECDSA = new MarscoinECDSA;
            if ($marscoinECDSA->checkSignatureForMessage($address, $sig, $msg)) {
                $session->s = $address;
                // Single-use: consume the challenge so a captured request
                // cannot be replayed within the freshness window.
                $remaining = array_values(array_filter(
                    explode(',', $session->v),
                    fn ($c) => $c !== $challenge
                ));
                $session->v = implode(',', $remaining);
                $session->save();

                return $this->fastcode(200, 'successfully authenticated');
            } else {
                return $this->fastcode(406, "couldn't verify message");
            }
        } else {
            return $this->fastcode(404, 'no challenge found');
        }
    }

    public function checkAuth(Request $request)
    {
        $session_string = (string) $request->query('sid');
        $session = MSession::where('sid', $session_string)->first();

        $authenticated = (! is_null($session) && ! empty($session->s)) ? true : false;

        // Attack probes send invalid UTF-8 in ?sid= (e.g. %EF%BF%BD injection
        // payloads). Reflecting that back through json_encode throws
        // InvalidArgumentException (500). Such a sid never matches a session
        // anyway, so blank it rather than echo malformed bytes.
        if (! mb_check_encoding($session_string, 'UTF-8')) {
            $session_string = '';
        }

        return response()->json(['sid' => $session_string, 'authenticated' => $authenticated]);
    }

    public function wauth(Request $request)
    {
        Log::debug('MarsAuth');
        $sid = $request->query('sid');
        Log::debug('MarsAuth');

        if (! empty($sid)) {
            $mars_session = MSession::where('sid', $sid)->first(); // Retrieve the session using Eloquent

            if ($mars_session && ! is_null($mars_session->s) && ! empty($mars_session->s)) {
                // Attempt to retrieve a user via the public address
                $citizen = Citizen::where('public_address', $mars_session->s)->first();

                if ($citizen) {
                    // If a citizen record exists, retrieve the associated user
                    $user = User::find($citizen->userid);
                } else {
                    // Create a new user and citizen if no citizen record exists
                    $user = User::create([
                        'email' => $mars_session->s.'@martianrepublic.org',
                        'password' => Hash::make(Str::random(16)), // Generate a random password
                    ]);

                    $citizen = new Citizen([
                        'userid' => $user->id,
                        'firstname' => 'Unknown', // Set default or use input
                        'lastname' => 'Uknown',
                        'public_address' => $mars_session->s,
                    ]);

                    $citizen->save();
                }

                Auth::login($user); // Authenticate the user
                session()->save(); // Ensure the session is saved immediately

                Log::debug('User authenticated immediately after login: '.(Auth::check() ? 'true' : 'false'));

                return redirect('/wallet/dashboard');
            }

            return redirect('/login')->withErrors('Could not find User.');
        } else {
            // Handle invalid or expired SID
            return redirect('/login')->withErrors('Your session has expired or is invalid.');
        }
    }

    public function token(Request $request)
    {
        $payload = $request->input('data');
        if (! is_array($payload)) {
            $payload = $request->all();
        }
        $data = Validator::make($payload, [
            'a' => ['required', 'string', 'regex:/^M[1-9A-HJ-NP-Za-km-z]{24,34}$/'],
            'm' => 'required|string|max:256', 's' => 'required|string|max:128',
            't' => 'required|integer|min:0|max:4102444800',
        ])->validate();
        $publicAddress = $data['a'];
        $msg = $data['m'];
        $sig = $data['s'];
        $timestamp = $data['t'];
        // The timestamp and identity must be inside the signed message. Merely
        // checking an unsigned `t` lets an old signature mint fresh sessions.
        $expected = 'https://martianrepublic.org/api/token?a='.$publicAddress.'&t='.$timestamp;
        if (! hash_equals($expected, $msg) || abs(time() - (int) $timestamp) > 600) {
            return response()->json(['message' => 'The sign-in request has expired or does not match this identity.'], 401);
        }
        try {
            $valid = (new MarscoinECDSA)->checkSignatureForMessage($publicAddress, $sig, $msg);
        } catch (\Throwable) {
            $valid = false;
        }
        if ($valid) {
            $wallet = CivicWallet::where('public_addr', $publicAddress)->first();

            if ($wallet) {
                // Wallet found, get the associated user
                $user = $wallet->user;
            } else {
                // Wallet not found, create a new user without a wallet
                $user = User::where('email', $publicAddress.'@martianrepublic.org')->first();
                if (! $user) {
                    $user = User::create([
                        'fullname' => 'UserWithoutWallet', // Or any other default or generated name
                        'email' => $publicAddress.'@martianrepublic.org',
                        'password' => Hash::make(Str::random(10)), // Random password
                    ]);
                    Log::debug('..created user');
                }
                $profile = Profile::where('userid', $user->id)->first();
                if (! $profile) {
                    $profile = Profile::create([
                        'userid' => $user->id,
                        'wallet_open' => 0,
                        'civic_wallet_open' => 0,
                        'has_application' => 0,
                    ]);
                    Log::debug('..created profile');
                }
                // create a citcache entry
                $citcache = Citizen::where('userid', $user->id)->first();
                if (! $citcache) {
                    $citizen = Citizen::create([
                        'userid' => $user->id,
                        'public_address' => $publicAddress,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::debug('..created citizen cache');
                }
                // register civic wallet for the new user
                $wallet = CivicWallet::create([
                    'user_id' => $user->id,
                    'wallet_type' => 'MARS',
                    'backup' => 0,
                    'encrypted_seed' => '',
                    'public_addr' => $publicAddress,
                    'created_at' => now(),
                    'opened_at' => now(),
                ]);
                Log::debug('..created civic wallet');
            }
            if ($user->status == 'inactive') {
                return response()->json(['token' => 'inactive']);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $token]);
        } else {
            return response()->json(['message' => 'couldnt verify message'], 406);
        }
    }

    private function fastcode($status, $message)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
