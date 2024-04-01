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

class ApiController extends Controller
{
    public function allPublic()
    {
        $perPage = 25;
        $feeds = Feed::with(['user', 'user.profile'])
            ->whereHas('user.profile', function ($query) {
                $query->where('tag', 'GP');
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    
        return response()->json($feeds);
    }

    public function allCitizen()
    {
        $perPage = 25;
        $feeds = Feed::with(['user.profile'])
            ->whereHas('user.profile', function ($query) {
                $query->where('tag', 'CT');
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    
        return response()->json($feeds);
    }
    

    public function allApplicants()
    {
        $perPage = 25; 
    
        $applicants = User::whereHas('profile', function ($query) {
                $query->where('has_application', 1);
            })
            ->with(['profile', 'hdWallet']) 
            ->orderByDesc('profile.userid') 
            ->paginate($perPage);
    
        return response()->json($applicants);
    }
}
