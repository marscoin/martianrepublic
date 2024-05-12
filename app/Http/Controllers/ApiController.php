<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use App\Models\Feed;
use App\Models\Profile;
use App\Models\User;
use App\Models\Proposals;
use App\Models\Threads;
use App\Models\Citizen;
use App\Models\HDWallet;
use App\Models\CivicWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use BitcoinPHP\BitcoinECDSA\BitcoinECDSA;
use App\Includes\MarscoinECDSA;

class ApiController extends Controller
{
    public function allPublic()
    {
        $perPage = 25;
        $cacheKey = 'all_public_cache'; // Define a unique cache key for this query
        $excludedUserIds = [6462, 7601]; //ID of the user you want to exclude, for example, a test account
    
        // Attempt to get cached data. If not available, the closure will be run to fetch and cache the data.
        $feeds = Cache::remember($cacheKey, 60, function () use ($perPage, $excludedUserIds) {
            $feeds = Feed::with(['user' => function ($query) use ($excludedUserIds) {
                $query->select('id', 'fullname', 'created_at')
                      ->where('id', '!=', $excludedUserIds); // Exclude the specific user by ID
            }, 'user.profile' => function ($query) {
                $query->select('userid', 'general_public', 'endorse_cnt', 'citizen', 'has_application' ); // Only select general_public and the foreign key
            }, 'user.citizen' => function ($query) {
                $query->select('userid', 'avatar_link', 'liveness_link') // Select the userid and avatar_link
                ->whereNotNull('avatar_link');
            }])
            ->whereHas('user.profile', function ($query) {
                $query->where('tag', 'GP');
            })
            ->whereHas('user', function ($query) use ($excludedUserIds) {
                $query->whereNotIn('id', $excludedUserIds);
            })
            ->orderByDesc('id')
            ->take($perPage) // Directly take the perPage amount, removing the need for extra data fetching
            ->get();
           
            return $feeds; // Directly return the fetched feeds
        });
    
        return response()->json($feeds);
    }


    public function allCitizen()
    {
        $perPage = 25;
        $cacheKey = 'all_citizens_cache';
        $excludedUserId = 6462;
    
        $feeds = Cache::remember($cacheKey, 60, function () use ($perPage, $excludedUserId) {
            return Feed::with([
                'user' => function ($query) use ($excludedUserId) {
                    $query->where('id', '!=', $excludedUserId)
                          ->select('id', 'fullname', 'created_at');
                },
                'user.profile' => function ($query) {
                    $query->select('userid', 'general_public', 'endorse_cnt', 'citizen', 'has_application');
                },
                'user.citizen' => function ($query) {
                    $query->select('userid', 'avatar_link', 'liveness_link')
                          ->whereNotNull('avatar_link'); // Ensure that avatar_link is not NULL
                }
            ])
            ->whereHas('user', function ($query) use ($excludedUserId) {  // Ensure user exists and is not excluded
                $query->where('id', '!=', $excludedUserId);
            })
            ->whereHas('user.citizen', function ($query) {  // Ensure user has valid citizen data
                $query->whereNotNull('avatar_link');
            })
            ->whereHas('user.profile', function ($query) {  // Additional filters for the profile
                $query->where('tag', 'CT');
            })
            ->select('id', 'address', 'userid', 'tag', 'message', 'embedded_link', 'txid', 'blockid', 'mined', 'updated_at', 'created_at')  // Only select columns from the feed table
            ->orderByDesc('id')
            ->take($perPage)
            ->get();
        });
    
        return response()->json($feeds);
    }
    
    

    

    public function allApplicants()
    {
        $perPage = 25; 
    
        $applicants = User::whereHas('profile', function ($query) {
            $query->where('has_application', 1);
        })
        ->with(['profile', 'hdWallet' => function($query) {
            // If you only need the public_addr from HdWallet, select it specifically
            $query->select('user_id', 'public_addr');
        }])
        ->orderByDesc('id')
        ->paginate($perPage, ['id', 'fullname']); 
        // Customize the result to match the raw query structure, if needed
        $customResult = $applicants->getCollection()->transform(function ($user) {
            return [
                'userid' => $user->id,
                'fullname' => $user->fullname,
                'address' => $user->hdWallet ? $user->hdWallet->public_addr : null,
            ];
        });
    
        return response()->json([
            'current_page' => $applicants->currentPage(),
            'data' => $customResult,
            'total' => $applicants->total(),
            'per_page' => $applicants->perPage(),
            'last_page' => $applicants->lastPage(),
        ]);
    }


    public function showCitizen(Request $request, $address)
    {
        // Look up the Martian user by public address
        $martianWallet = HDWallet::where('public_addr', $address)->first();

        if (!$martianWallet) {
            return response()->json(['message' => 'Martian not found.'], 404);
        }

        // Attempt to fetch the user's profile and additional data
        $profile = Profile::where('userid', $martianWallet->user_id)->first();
        $feedItems = Feed::where('userid', $martianWallet->user_id)->whereNotNull('mined')->whereNotIn('tag', ['GP','CT'])->orderBy('created_at', 'desc')->get();
        
        // Fetch the latest 3 activities
        $activity = DB::table('feed')
            ->join('users', 'feed.userid', '=', 'users.id')
            ->join('profile', 'feed.userid', '=', 'profile.userid')
            ->select('profile.userid', 'users.fullname', 'feed.tag', 'feed.mined') 
            ->orderBy('feed.id', 'desc')
            ->limit(3)
            ->get();

        // Construct response
        $response = [
            'profile' => [
                'general_public' => $profile ? $profile->general_public : null,
                'isCitizen' => $profile ? $profile->citizen : null,
            ],
            'feedItems' => $feedItems,
            'activity' => $activity,
        ];

        return response()->json($response);
    }



    public function token(Request $request) 
    {
        $publicAddress = $request->input('a');
        $msg = $request->input('m');
        $sig = $request->input('s');
        $timestamp = $request->input('t');

        Log::debug("Address: " . $publicAddress);
        Log::debug("msg: " . $msg);
        Log::debug("sig: " . $sig);
        Log::debug("timestamp: " . $timestamp);

        if (empty($msg)){
            $data = $request->json()->all();

            if (isset($data['data'])) {
                $msg = $data['data']['m'] ?? null;
                $sig = $data['data']['s'] ?? null;
                $publicAddress = $data['data']['a'] ?? null;
                $timestamp = $data['data']['t'] ?? '0';
            }
        }
        // Validate timestamp
        if (Carbon::now()->diffInSeconds(Carbon::createFromTimestamp($timestamp)) > 600) { // 5 minutes tolerance
            return response()->json(['error' => 'Request timestamp is too old.'], 401);
        }

        $bitcoinECDSA = new BitcoinECDSA();          
        $marscoinECDSA = new MarscoinECDSA();
        if ($marscoinECDSA->checkSignatureForMessage($publicAddress, $sig, $msg))    
        {
            $wallet = CivicWallet::where('public_addr', $publicAddress)->first();

            if ($wallet) {
                // Wallet found, get the associated user
                $user = $wallet->user;
            } else {
                // Wallet not found, create a new user without a wallet
                $user = User::where('email', $publicAddress . '@martianrepublic.org')->first();
                if (!$user) {
                    $user = User::create([
                        'name' => 'UserWithoutWallet', // Or any other default or generated name
                        'email' => $publicAddress . '@martianrepublic.org',
                        'password' => Hash::make(Str::random(10)), // Random password
                    ]);
                }
            }
            
            // Generate token for the user
            $token = $user->createToken('authToken')->plainTextToken;
            
            return response()->json(['token' => $token]);
        } 
        else {
            return response()->json(['message' => 'couldnt verify message'], 406);
        }
    }

    public function test()
    {

        $messageM="https://martianrepublic.org/api/token?a=MCHe2XTUEegyqsYc5ePe2dQiPtixLfhppR&t=1715354045";
        $addressM="MCHe2XTUEegyqsYc5ePe2dQiPtixLfhppR";
        $signatureM="H+Qrav6eWNrB0P5DiRaGRRR/RVtD8qd5dKlWA3FOfeiFc4h04769HtfMbsmxrrNPk0MeTJmwPCR9xg67f1NatOA=";

        $bitcoinECDSA = new BitcoinECDSA(); 
        $bitcoinECDSA->generateRandomPrivateKey(); //generate new random private key
        $address = $bitcoinECDSA->getAddress();
        $message = "Test message";
        $signedMessage = $bitcoinECDSA->signMessage($message, true);
        echo "message: " . PHP_EOL . "<br>";
        echo $message . PHP_EOL . "<br>";
        echo "signed message: " . PHP_EOL . "<br>";
        echo $signedMessage . PHP_EOL . "<br>";
        //$signedMessage = "random";

        //loading Bitcoin crypto library
        if ($bitcoinECDSA->checkSignatureForMessage($address, $signedMessage, $message))       //verifying signature
        {
            Log::debug("True");
            echo "True<br>";
        }else{
            Log::debug("False");
            echo "False<br>";
        }

        echo "mars test";
        $marscoinECDSA = new MarscoinECDSA();
        echo "Address; " . $addressM;
        echo "Message:" . $messageM;
        echo "sig:" . $signatureM;
         //loading Bitcoin crypto library
         if ($marscoinECDSA->checkSignatureForMessage($addressM, $signatureM, $messageM))       //verifying signature
         {
             Log::debug("True");
             echo "True<br>";
         }else{
             Log::debug("False");
             echo "False<br>";
         }
    }



    //token access
    public function sfname(Request $request)
	{
		
        $uid = Auth::user()->id;
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        if(!isset($firstname))
            return;
        if(!isset($lastname))
            return;

        $fullname = $firstname . " " . $lastname;

        $citcache = Citizen::where('userid', '=', $uid)->first();
        if(is_null($citcache)) $citcache = new Citizen;	
        
        $citcache->userid = $uid;
        $citcache->firstname = $firstname;
        $citcache->lastname = $lastname;
        $citcache->save();

        $user = User::where('id', '=', $uid)->first();
        $user->fullname = $fullname;
        $user->save();
		
	}


    public function pinpic(Request $request)
    {
		$uid = Auth::user()->id;
        $hash = "";
        $dataPic = $request->input('picture');
        $type = $request->input('type');
        $public_address = $request->input('address');
        $file_path = "./assets/citizen/" . $public_address . "/";
        if (!file_exists($file_path)) {
            mkdir($file_path);
        }
        list($type, $dataPic) = explode(';', $dataPic);
        list(, $type) = explode('/', $type);
        $file_path = "./assets/citizen/" . $public_address . "/profile_pic." . $type;
        //if (!file_exists($file_path)) { overwrite by default
        
        list(, $dataPic) = explode(',', $dataPic);
        $dataPic = base64_decode($dataPic);
        file_put_contents($file_path, $dataPic);
        $hash = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");

        $citcache = Citizen::where('userid', '=', $uid)->first();
        if(is_null($citcache)) $citcache = new Citizen;	
        $citcache->userid = $uid;
        $citcache->avatar_link = "https://ipfs.marscoin.org/ipfs/".$hash;
        $citcache->save();

        return (new Response(json_encode(array("Hash" => $hash)), 200))
            ->header('Content-Type', "application/json;");

	}



	public function pinvideo(Request $request){

        $uid = Auth::user()->id;
        $hash = "";
        $dataPic = $request->input('file');
        $type = $request->input('type');
        $public_address = $request->input('address');
        if ($request->hasFile('file'))
        {
            $file_path = "./assets/citizen/" . $public_address . "/";
            if (!file_exists($file_path)) {
                mkdir($file_path);
            }
            $file_path = "./assets/citizen/" . $public_address  . "/";
            $request->file('file')->move($file_path, "profile_video.webm" );
            $file_path = $file_path . "profile_video.webm";
            $hash = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");

            $citcache = Citizen::where('userid', '=', $uid)->first();
            if(is_null($citcache)) $citcache = new Citizen;	
            $citcache->userid = $uid;
            $citcache->liveness_link = "https://ipfs.marscoin.org/ipfs/".$hash;
            $citcache->save();

            return (new Response(json_encode(array("Hash" => $hash)), 200))
            ->header('Content-Type', "application/json;");
        }
			
	}






}
