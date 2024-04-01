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

class ApiController extends Controller
{
    public function allPublic()
    {
        $perPage = 25;
        $feeds = Feed::with(['user' => function ($query) {
            $query->select('id', 'fullname', 'created_at');
        }, 'user.profile' => function ($query) {
            $query->select('userid', 'general_public'); // only select general_public and the foreign key
        }])
        ->whereHas('user.profile', function ($query) {
            $query->where('tag', 'GP');
        })
        ->orderByDesc('id')
        ->paginate($perPage);

        return response()->json($feeds);
    }


    public function allCitizen()
    {
        $perPage = 25;
        $feeds = Feed::with(['user' => function ($query) {
            $query->select('id', 'fullname', 'created_at');
        }, 'user.profile' => function ($query) {
            $query->select('userid', 'general_public'); // only select general_public and the foreign key
        }, 'user.citizen' => function ($query) {
            $query->select('userid', 'avatar_link'); // Select the userid and avatar_link
        }])
        ->whereHas('user.profile', function ($query) {
            $query->where('tag', 'CT');
        })
        ->orderByDesc('id')
        ->paginate($perPage);

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
        ->orderByDesc('profile.userid') // Assuming you want to sort by the profile's userid
        ->paginate($perPage, ['id', 'fullname']); // Specify the columns you want from the users table
    
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


}
