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
    // Method for 'allPublic' endpoint
    public function allPublic()
    {
        $data = DB::select("select * from feed, users, profile where feed.userid = profile.userid and profile.userid = users.id and feed.tag = 'GP' ORDER BY feed.id desc");
        return response()->json($data);
    }

    // Method for 'allCitizen' endpoint
    public function allCitizen()
    {
        $data = DB::select("select * from feed, users, profile where feed.userid = profile.userid and profile.userid = users.id and feed.tag = 'CT' ORDER BY feed.id desc");
        return response()->json($data);
    }

    // Method for 'allApplicants' endpoint
    public function allApplicants()
    {
        $data = DB::select("select profile.userid, users.fullname, hd_wallet.public_addr as address from users, profile, hd_wallet where profile.userid = users.id and users.id = hd_wallet.user_id and profile.has_application = 1 ORDER BY profile.userid DESC");
        return response()->json($data);
    }
}
