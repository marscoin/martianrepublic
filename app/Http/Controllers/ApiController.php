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
use App\Models\Posts;
use App\Models\Citizen;
use App\Models\HDWallet;
use App\Models\CivicWallet;
use App\Models\MSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use BitcoinPHP\BitcoinECDSA\BitcoinECDSA;
use App\Includes\MarscoinECDSA;
use App\Livewire\CitizenStats;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Includes\IPFSRoot; 

class ApiController extends Controller
{
    public function allPublic()
    {
        $perPage = 25;
        $cacheKey = 'all_public_cache'; // Define a unique cache key for this query
        $excludedUserIds = [6462, 7601]; // ID of the user you want to exclude, for example, a test account
    
        // Attempt to get cached data. If not available, the closure will be run to fetch and cache the data.
        $feeds = Cache::remember($cacheKey, 60, function () use ($perPage, $excludedUserIds) {
            $feeds = Feed::with(['user' => function ($query) use ($excludedUserIds) {
                $query->select('id', 'fullname', 'created_at')
                      ->where('id', '!=', $excludedUserIds); // Exclude the specific user by ID
            }, 'user.profile' => function ($query) {
                $query->select('userid', 'general_public', 'endorse_cnt', 'citizen', 'has_application');
            }, 'user.citizen' => function ($query) {
                $query->select('userid', 'avatar_link', 'liveness_link')
                      ->whereNotNull('avatar_link');
            }])
            ->joinSub(
                Feed::selectRaw('max(id) as latest_id, userid')
                    ->where('tag', 'GP') // Ensure the tag is 'GP'
                    ->groupBy('userid'),
                'latest_feeds',
                function($join) {
                    $join->on('feed.id', '=', 'latest_feeds.latest_id');
                }
            )
            ->whereHas('user.profile', function ($query) {
                $query->where('tag', 'GP');
            })
            ->whereHas('user', function ($query) use ($excludedUserIds) {
                $query->whereNotIn('id', $excludedUserIds);
            })
            ->orderByDesc('id')
            ->take($perPage)
            ->distinct()
            ->get();
    
            return $feeds; // Directly return the fetched feeds
        });
    
        return response()->json($feeds);
    }
    
    


    public function allCitizen()
    {
        $perPage = 25;
        $cacheKey = 'all_citizens_cache';
        $excludedUserId = '';
    
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
    
    

    

    // public function allApplicants()
    // {
    //     $perPage = 25; 
    
    //     $applicants = User::whereHas('profile', function ($query) {
    //         $query->where('has_application', 1);
    //     })
    //     ->with(['profile', 'hdWallet' => function($query) {
    //         // If you only need the public_addr from HdWallet, select it specifically
    //         $query->select('user_id', 'public_addr');
    //     }])
    //     ->orderByDesc('id')
    //     ->paginate($perPage, ['id', 'fullname']); 
    //     // Customize the result to match the raw query structure, if needed
    //     $customResult = $applicants->getCollection()->transform(function ($user) {
    //         return [
    //             'userid' => $user->id,
    //             'fullname' => $user->fullname,
    //             'address' => $user->hdWallet ? $user->hdWallet->public_addr : null,
    //         ];
    //     });
    
    //     return response()->json([
    //         'current_page' => $applicants->currentPage(),
    //         'data' => $customResult,
    //         'total' => $applicants->total(),
    //         'per_page' => $applicants->perPage(),
    //         'last_page' => $applicants->lastPage(),
    //     ]);
    // }

    public function allApplicants()
    {
        $perPage = 25; 

        $applicants = User::whereHas('profile', function ($query) {
            $query->where('has_application', 1);
        })
        ->where('id', '!=', 6462) // Exclude user with id 6462
        ->with(['profile', 'hdWallet' => function($query) {
            $query->select('user_id', 'public_addr');
        }, 'citizen']) // Add citizen relationship
        ->orderByDesc('id')
        ->paginate($perPage, ['id', 'fullname']); 

        $customResult = $applicants->getCollection()->transform(function ($user) {
            return [
                'userid' => $user->id,
                'fullname' => $user->fullname,
                'address' => $user->hdWallet ? $user->hdWallet->public_addr : null,
                'citizen' => $user->citizen, // Include citizen data
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
        $martianWallet = CivicWallet::where('public_addr', $address)->first();

        if (!$martianWallet) {
            return response()->json(['message' => 'Martian not found.'], 404);
        }

        // Attempt to fetch the user's profile and additional data
        $citizen = Citizen::where('public_address', $address)->first();
        $profile = Profile::where('userid', $martianWallet->user_id)->first();
        $feedItems = Feed::where('userid', $martianWallet->user_id)->whereNotNull('mined')->whereNotIn('tag', ['GP','CT'])->orderBy('created_at', 'desc')->get();
        
        // Fetch the latest 3 activities
        $activity = DB::table('feed')
            ->join('users', 'feed.userid', '=', 'users.id')
            ->join('profile', 'feed.userid', '=', 'profile.userid')
            ->select('profile.userid', 'users.fullname', 'feed.tag', 'feed.mined') 
            ->where('feed.userid', $martianWallet->user_id)
            ->orderBy('feed.id', 'desc')
            ->get();

        // Construct response
        $response = [
            'citizen' => $citizen,
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
                        'fullname' => 'UserWithoutWallet', // Or any other default or generated name
                        'email' => $publicAddress . '@martianrepublic.org',
                        'password' => Hash::make(Str::random(10)), // Random password
                    ]);
                    Log::debug("..created user");
                }
                $profile = Profile::where('userid', $user->id)->first();
                if (!$profile) {
                    $profile = Profile::create([
                        'userid' => $user->id,
                        'wallet_open' => 0,
                        'civic_wallet_open' => 0,
                        'has_application' => 0,
                    ]);
                    Log::debug("..created profile");
                }
                //create a citcache entry
                $citcache = Citizen::where('userid', $user->id)->first();
                if (!$citcache) {
                    $citizen = Citizen::create([
                        'userid' => $user->id,
                        'public_address' => $publicAddress,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::debug("..created citizen cache");
                }
                // register civic wallet for the new user
                $wallet = CivicWallet::create([
                    'user_id' => $user->id,
                    'wallet_type' => 'MARS',
                    'backup' => 0,
                    'encrypted_seed' => '',
                    'public_addr' => $publicAddress,
                    'created_at' => now(),
                    'opened_at' => now()
                ]);
                Log::debug("..created civic wallet");
            }
            if($user->status == 'inactive')
                return response()->json(['token' => 'inactive']);

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
    public function scitizen(Request $request)
	{
		
        $uid = Auth::user()->id;
        $citcache = Citizen::where('userid', '=', $uid)->first();
        if(is_null($citcache)) $citcache = new Citizen;	
        $citcache->userid = $uid;
        
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $shortbio = $request->input('shortbio');
        $displayname = $request->input('displayname');
        
        if(isset($firstname) && isset($lastname))
        {
            $fullname = $firstname . " " . $lastname;
            
            $citcache->firstname = $firstname;
            $citcache->lastname = $lastname;
            $user = User::where('id', '=', $uid)->first();
            $user->fullname = $fullname;
            $user->save();
            $profile = Profile::where('userid', '=', $uid)->first();
            $profile->has_application = 1;
            $profile->save();
        }
        if(isset($shortbio))
        {
            $citcache->shortbio = $shortbio;
            $profile = Profile::where('userid', '=', $uid)->first();
            $profile->has_application = 1;
            $profile->save();
        }
        if(isset($displayname))
        {
            $citcache->displayname = $displayname;
            $profile = Profile::where('userid', '=', $uid)->first();
            $profile->has_application = 1;
            $profile->save();
        }
        
        $citcache->save();
        // Fetch profile data
        $profile = Profile::where('userid', '=', $uid)->first();

        // Merge citizen and profile data
        $response = [
            'citizen' => $citcache,
            'profile' => [
                'citizen' => $profile->citizen ?? null,
                'endorse_cnt' => $profile->endorse_cnt ?? null,
                'general_public' => $profile->general_public ?? null,
                'has_application' => $profile->has_application ?? null,
            ]
        ];

        // Return the merged data as a JSON response
        return response()->json($response);
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

        Log::debug('pinvid');
        $uid = Auth::user()->id;
        $hash = "";
        $dataPic = $request->input('file');
        $type = $request->input('type');
        $public_address = $request->input('address');
        Log::debug('public: ' . $public_address);
        if ($request->hasFile('file'))
        {
            Log::debug('storing file');
            $file_path = "./assets/citizen/" . $public_address . "/";
            if (!file_exists($file_path)) {
                mkdir($file_path);
                Log::debug('making dir');
            }
            $file_path = "./assets/citizen/" . $public_address  . "/";
            $request->file('file')->move($file_path, "profile_video.webm" );
            $file_path = $file_path . "profile_video.webm";
            $hash = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");
            Log::debug('upload complete: ' . $hash);
            $citcache = Citizen::where('userid', '=', $uid)->first();
            if(is_null($citcache)) $citcache = new Citizen;	
            $citcache->userid = $uid;
            $citcache->liveness_link = "https://ipfs.marscoin.org/ipfs/".$hash;
            $citcache->save();
            Log::debug('saved cit data');
            return (new Response(json_encode(array("Hash" => $hash)), 200))
            ->header('Content-Type', "application/json;");
        }else{
            Log::debug('no file found!');
        }
			
	}

    public function marsAuth(Request $request)
    {
        $this->setCorsHeaders();

        if (!$request->isMethod('post')) {
            return $this->fastcode(400, "bad request");
        }

        $challenge = $request->query('c');
        $session_string = $request->query('sid');
        $timestamp = hexdec($request->query('t'));
        $pubk = $request->input('pubk');
        $msg = $request->input('msg');
        $sig = $request->input('sig');
        $address = $request->input('addr');

        if ($timestamp < (time() - 600)) {
            return $this->fastcode(408, "request timed out");
        }

        $session = MSession::where('sid', $session_string)->first();

        if ($session && in_array($challenge, explode(",", $session->v))) {
            $marscoinECDSA = new MarscoinECDSA();
            if ($marscoinECDSA->checkSignatureForMessage($address, $sig, $msg)) {
                $session->s = $address;
                $session->save();
                return $this->fastcode(200, "successfully authenticated");
            } else {
                return $this->fastcode(406, "couldn't verify message");
            }
        } else {
            return $this->fastcode(404, "no challenge found");
        }
    }

    public function checkAuth(Request $request)
    {
        $session_string = $request->query('sid');
        $session = MSession::where('sid', $session_string)->first();
    
        $authenticated = (!is_null($session) && !empty($session->s)) ? true : false; 
    
        return response()->json(['sid' => $session_string, 'authenticated' => $authenticated]);
    }
    
    private function fastcode($status, $message)
    {
        return response()->json(["status" => $status, "message" => $message], $status);
    }

    private function setCorsHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
    }

    public function wauth(Request $request)
    {
        Log::debug('MarsAuth');
        $sid = $request->query('sid');
        Log::debug('MarsAuth');

        if (!empty($sid)) {
            $mars_session = MSession::where('sid', $sid)->first(); // Retrieve the session using Eloquent

            if ($mars_session && !is_null($mars_session->s) && !empty($mars_session->s)) {
                // Attempt to retrieve a user via the public address
                $citizen = Citizen::where('public_address', $mars_session->s)->first();

                if ($citizen) {
                    // If a citizen record exists, retrieve the associated user
                    $user = User::find($citizen->userid);
                } else {
                    // Create a new user and citizen if no citizen record exists
                    $user = User::create([
                        'email' => $mars_session->s . '@martianrepublic.org',
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

                Log::debug('User authenticated immediately after login: ' . (Auth::check() ? 'true' : 'false'));
                return redirect('/wallet/dashboard');
            }

            return redirect('/login')->withErrors('Could not find User.');
        } else {
            // Handle invalid or expired SID
            return redirect('/login')->withErrors('Your session has expired or is invalid.');
        }
    }


    /**
	 * Handles JSON storage and pinning to a distributed file system.
	 *
	 * @hideFromAPIDocumentation
	 */
	public function pinjson(Request $request)
	{
		Log::info("in function");
		$public_address = $request->input('address');
		$type = $request->input('type');
		$json = $request->input('payload');
		$projectRoot = config('app.project_root', base_path());
		$base_path =  $projectRoot . "/assets/citizen/" . $public_address;

		// Check and create the directory if it doesn't exist
		Log::info($base_path);
		clearstatcache();
		if (!is_dir($base_path)) {
			Log::info("Trying to create directory: " . $base_path);
			if (!mkdir($base_path, 0755, true)) {
				Log::error("Failed to create directory: " . $base_path);
				return response()->json(["error" => "Failed to create directory. Check permissions."], 500);
			}
			Log::info("Directory created: " . $base_path);
		}
		
		// Check if the directory is writable, regardless of whether it was just created or already existed
		if (!is_writable($base_path)) {
			Log::error("Directory not writable: " . $base_path);
			return response()->json(["error" => "Directory is not writable. Check permissions."], 500);
		}

		$file_path = $base_path . "/" . $type . ".json";

		// Attempt to write the JSON data to the file
		if (file_put_contents($file_path, $json) === false) {
			return response()->json(["error" => "Failed to write to file."], 500);
		}

		try {
			Log::info("PermaJson: " . $file_path);
		
			// Check if the type contains the word 'log'
			if (strpos($type, 'log') !== false) {
				// The type contains 'log', use uploadFolder
				$apiResponse = AppHelper::uploadFolder($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true&recursive=true&wrap-with-directory=true&quieter");
			} else {
				// The type does not contain 'log', use upload
				$apiResponse = AppHelper::upload($file_path, "http://127.0.0.1:5001/api/v0/add?pin=true");
			}

			if (is_string($apiResponse)) {
				$formattedResponse = ['Hash' => $apiResponse];
			} else {
				Log::error("Upload error: Formatting");
				return response()->json(["error"=>"formatting error"], 500);
			}
			
			return response()->json($formattedResponse, 200)->header('Content-Type', "application/json;");
		} catch (\Exception $e) {
			// Handle exceptions during the upload and pinning process
			Log::error("Upload error: " . $e->getMessage());
			return response()->json(["error" => $e->getMessage()], 500);
		}
	}

    public function getThreadsByCategory($categoryId) {
        $threads = $this->fetchThreads($categoryId);
        return response()->json(['threads' => $threads]);
    }
    
    // private function fetchThreads($categoryId) {
    //     $threads = DB::table('forum_threads')
    //         ->where('forum_threads.category_id', $categoryId)
    //         ->leftJoin('users', 'forum_threads.author_id', '=', 'users.id')
    //         ->leftJoin('profile', 'users.id', '=', 'profile.userid')
    //         ->select(
    //             'forum_threads.id',
    //             'forum_threads.title',
    //             'forum_threads.created_at',
    //             'forum_threads.reply_count',
    //             'users.fullname as author_name'
    //         )
    //         ->orderBy('forum_threads.created_at', 'desc')
    //         ->get();
    //     return $threads;
    // }

    private function fetchThreads($categoryId) {
        $userId = Auth::id();
    
        $threads = DB::table('forum_threads')
            ->where('forum_threads.category_id', $categoryId)
            ->leftJoin('users', 'forum_threads.author_id', '=', 'users.id')
            ->leftJoin('profile', 'users.id', '=', 'profile.userid')
            ->leftJoin('user_blocks as ub', function($join) use ($userId) {
                $join->on('forum_threads.author_id', '=', 'ub.blocked_user_id')
                     ->where('ub.user_id', '=', $userId);
            })
            ->select(
                'forum_threads.id',
                'forum_threads.title',
                'forum_threads.created_at',
                'forum_threads.reply_count',
                'users.fullname as author_name',
                DB::raw('IF(ub.blocked_user_id IS NOT NULL, true, false) as is_blocked')
            )
            ->orderBy('forum_threads.created_at', 'desc')
            ->get();
    
        return $threads;
    }


    public function getThreadComments($threadId) {
        // Assume $threadId is passed correctly to the function
        $comments = $this->fetchCommentsByThread($threadId);
        return response()->json(['comments' => $comments]);
    }
    
    // private function fetchCommentsByThread($threadId) {
    //     // The query as outlined above
    //     // Return the collection of comments
    //     $query = "
    //         WITH RECURSIVE CommentTree AS (
    //             SELECT 
    //                 p.id,
    //                 p.thread_id,
    //                 p.author_id,
    //                 p.content,
    //                 p.post_id as pid,
    //                 p.created_at,
    //                 CHAR_LENGTH(p.content) as char_length_sum
    //             FROM 
    //                 forum_posts p
    //             WHERE 
    //                 p.thread_id = ? AND p.post_id IS NULL

    //             UNION ALL

    //             SELECT 
    //                 p.id,
    //                 p.thread_id,
    //                 p.author_id,
    //                 p.content,
    //                 p.post_id,
    //                 p.created_at,
    //                 ct.char_length_sum + CHAR_LENGTH(p.content)
    //             FROM 
    //                 forum_posts p
    //             INNER JOIN 
    //                 CommentTree ct ON p.post_id = ct.id
    //         )
    //         SELECT 
    //             ct.id,
    //             ct.thread_id,
    //             ct.author_id,
    //             u.fullname,
    //             ct.content,
    //             ct.created_at,
    //             ct.pid,
    //             CHAR_LENGTH(ct.content) as char_length_sum
    //         FROM 
    //             CommentTree ct
    //         LEFT JOIN users u ON ct.author_id = u.id
    //         LEFT JOIN profile pr ON ct.author_id = pr.userid
    //         ORDER BY 
    //             ct.pid ASC,
    //             ct.created_at ASC;
    //     ";

    //     $comments = DB::select($query, [$threadId]);
    //     $commentsCollection = collect($comments);

    //     return response()->json(['comments' => $commentsCollection]);
    // }

    private function fetchCommentsByThread($threadId) {
        $userId = Auth::id();
        
        $query = "
            WITH RECURSIVE CommentTree AS (
                SELECT 
                    p.id,
                    p.thread_id,
                    p.author_id,
                    p.content,
                    p.post_id as pid,
                    p.created_at,
                    CHAR_LENGTH(p.content) as char_length_sum
                FROM 
                    forum_posts p
                WHERE 
                    p.thread_id = ? AND p.post_id IS NULL
    
                UNION ALL
    
                SELECT 
                    p.id,
                    p.thread_id,
                    p.author_id,
                    p.content,
                    p.post_id,
                    p.created_at,
                    ct.char_length_sum + CHAR_LENGTH(p.content)
                FROM 
                    forum_posts p
                INNER JOIN 
                    CommentTree ct ON p.post_id = ct.id
            )
            SELECT 
                ct.id,
                ct.thread_id,
                ct.author_id,
                u.fullname,
                ct.content,
                ct.created_at,
                ct.pid,
                CHAR_LENGTH(ct.content) as char_length_sum,
                IF(ub.blocked_user_id IS NOT NULL, true, false) as is_blocked
            FROM 
                CommentTree ct
            LEFT JOIN users u ON ct.author_id = u.id
            LEFT JOIN profile pr ON ct.author_id = pr.userid
            LEFT JOIN user_blocks ub ON ub.blocked_user_id = ct.author_id AND ub.user_id = ?
            ORDER BY 
                ct.pid ASC,
                ct.created_at ASC;
        ";
    
        $comments = DB::select($query, [$threadId, $userId]);
        $commentsCollection = collect($comments);
        return response()->json(['comments' => $commentsCollection]);
    }
    

    public function getAllCategoriesWithThreads() {
        $categories = DB::table('forum_categories')
            ->get();
    
        foreach ($categories as $category) {
            $category->threads = $this->fetchThreads($category->id);
        }
    
        return response()->json(['categories' => $categories]);
    }


    public function createThread(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $thread = new Threads();
        $thread->category_id = $request->category_id;
        $thread->author_id = Auth::id();
        $thread->title = $request->title;
        $thread->save();

        $post = new Posts();
        $post->thread_id = $thread->id;
        $post->author_id = Auth::id();
        $post->content = $request->content;
        $post->save();

        $thread->first_post_id = $post->id;
        $thread->last_post_id = $post->id;
        $thread->reply_count = 0;
        $thread->save();

        return response()->json([
            'message' => 'Thread created successfully',
            'thread_id' => $thread->id,
            'post_id' => $post->id,
        ], 201);
    }

    public function createComment(Request $request, $threadId)
    {
        $request->validate([
            'content' => 'required|string',
            'post_id' => 'nullable|exists:forum_posts,id',
        ]);

        $thread = Threads::findOrFail($threadId);

        $post = new Posts();
        $post->thread_id = $threadId;
        $post->author_id = Auth::id();
        $post->content = $request->content;
        $post->post_id = $request->post_id; // This will be null for top-level comments
        $post->save();

        $thread->last_post_id = $post->id;
        $thread->reply_count += 1;
        $thread->save();

        return response()->json([
            'message' => 'Comment created successfully',
            'post_id' => $post->id,
        ], 201);
    }


    public function blockUser(Request $request, $id) 
    {
        Log::debug("in function"); 
	$uid = Auth::user()->id;
	Log::debug("auth happened");
        $blockedUserId = $id; 
    
        // Insert into `user_blocks` table if not already blocked
        DB::table('user_blocks')->updateOrInsert(
            ['user_id' => $uid, 'blocked_user_id' => $blockedUserId]
        );
    
        return response()->json(['message' => 'User blocked successfully'], 201);
    }

    public function handleEula(Request $request)
    {
        $uid = Auth::user()->id;
        $profile = Profile::where('userid', $uid)->firstOrFail();
        
        return response()->json([
            'is_signed' => (bool)$profile->signed_eula
        ]); 
    }

    public function setEula(Request $request)
    {
        $uid = Auth::user()->id;
        $profile = Profile::where('userid', $uid)->firstOrFail();
        if (!$profile->signed_eula) {
            $profile->signed_eula = 1;
            $profile->save();
        }
        return response()->json([
            'message' => 'EULA signed successfully',
            'is_signed' => true
        ]);
    }


    // App/Http/Controllers/ApiController.php
    public function deleteUser(Request $request, $id)
    {
        try {
            // Find the user
            $user = User::findOrFail($id);
            
            
            Log::debug("Deleting... ". $id);
           
            $user->update([
                'status' => 'inactive'
            ]);
            Log::debug("Status updated");
            
            // Revoke all tokens
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }
            Log::debug("Token wiped...");

            DB::commit();
            
            return response()->json([
                'message' => 'User deleted successfully'
            ], 200);
            
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while deleting the user'
            ], 500);
        }
    }




}
