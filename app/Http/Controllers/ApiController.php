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
        ->with([
            'profile' => function ($query) {
                // Assuming 'userid' is needed for relation mapping
                // and you want to show 'general_public' from profiles
                $query->select('userid', 'general_public');
            },
            'hdWallet' => function ($query) {
                // Select the 'user_id' for relation mapping 
                // and any other fields you want to include from hd_wallet
                $query->select('user_id', 'public_addr');
            }
        ])
        ->select('id', 'fullname', 'created_at') // Only selecting the fields we want from User
        ->orderByDesc('id')
        ->paginate($perPage);

        return response()->json($applicants);
    }
}
