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
            $query->select('id', 'fullname', 'email', 'created_at');
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
        $feeds = Feed::with(['user.profile'])
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
            ->with(['profile', 'hdWallet']) 
            ->orderByDesc('id')
            ->paginate($perPage);
    
        return response()->json($applicants);
    }
}
