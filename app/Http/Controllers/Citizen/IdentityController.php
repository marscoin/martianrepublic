<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Includes\AppHelper;
use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Models\Feed;
use App\Models\Profile;
use App\Models\Proposal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class IdentityController extends Controller
{
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    public function __construct() {}

    // The Gateway: citizen onboarding wizard
    public function showJoin()
    {
        if (! Auth::check()) {
            return redirect('/login');
        }
        $uid = Auth::user()->id;
        $profile = Profile::where('userid', $uid)->first();
        if (! $profile) {
            return redirect('/twofa');
        }

        $civic_wallet = CivicWallet::where('user_id', $uid)->first();
        if (! $civic_wallet) {
            return redirect('/wallet/dashboard/hd');
        }

        $citcache = Citizen::where('userid', $uid)->first();
        $view = View::make('citizen.join');
        $view->citcache = $citcache ? $citcache->toArray() : [];
        $view->public_address = $civic_wallet->public_addr;

        return $view;
    }

    // Get all public registrations and display table
    //
    public function showAll()
    {
        if (! Auth::check()) {
            return redirect('/login');
        }

        $view = View::make('citizen.registry');

        // Shared data - cached registry lists
        $view->everyPublic = Cache::remember('registry_every_public', 300, function () {
            return DB::select('SELECT u.*, p.*, c.*, ( SELECT f.txid FROM feed f WHERE f.userid = u.id AND f.tag = ? ORDER BY f.id DESC LIMIT 1) AS txid, ( SELECT f.mined FROM feed f WHERE f.userid = u.id AND f.tag = ? ORDER BY f.id DESC LIMIT 1) AS mined FROM users u JOIN profile p ON u.id = p.userid JOIN citizen c ON u.id = c.userid WHERE EXISTS ( SELECT 1 FROM feed f WHERE f.userid = u.id AND f.tag = ?) AND u.id NOT IN (?, ?) ORDER BY mined DESC LIMIT 100', ['GP', 'GP', 'GP', 6462, 7601]);
        });

        $view->everyCitizen = Cache::remember('registry_every_citizen', 300, function () {
            return DB::select('SELECT u.*, p.*, c.*, ( SELECT f.txid FROM feed f WHERE f.userid = u.id AND f.tag = ? ORDER BY f.id DESC LIMIT 1 ) AS txid, ( SELECT f.mined FROM feed f WHERE f.userid = u.id AND f.tag = ? ORDER BY f.id DESC LIMIT 1 ) AS mined FROM users u JOIN profile p ON u.id = p.userid JOIN citizen c ON u.id = c.userid WHERE EXISTS ( SELECT 1 FROM feed f WHERE f.userid = u.id AND f.tag = ? ) AND u.id NOT IN (?) ORDER BY mined DESC LIMIT 100', ['CT', 'CT', 'CT', 6462]);
        });

        $view->everyApplicant = Cache::remember('registry_every_applicant', 300, function () {
            return DB::select('SELECT profile.userid, users.fullname, citizen.*, civic_wallet.public_addr AS address FROM users, profile, civic_wallet, citizen WHERE profile.userid = users.id AND users.id = civic_wallet.user_id AND citizen.userid = users.id AND profile.has_application = ? AND users.id NOT IN (?) ORDER BY citizen.created_at DESC LIMIT 100', [1, 6462]);
        });

        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', '=', $uid)->first();
            $wallet = CivicWallet::where('user_id', '=', $uid)->first();
            $citcache = Citizen::where('userid', '=', $uid)->first();
            if (! $profile) {
                return redirect('/twofa');
            } else {
                if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
                    return redirect('/twofachallenge');
                }
            }
            $view->wallet_open = $profile->civic_wallet_open;
            $view->isCitizen = $profile->citizen;
            $view->citcache = $citcache;
            $view->isGP = $profile->general_public;
            $view->mePublic = Feed::where('userid', '=', $uid)->where('tag', '=', 'GP')->first();
            $view->meCitizen = Feed::where('userid', '=', $uid)->where('tag', '=', 'CT')->first();
            $view->feed = Feed::where('userid', '=', $uid)->whereNotNull('mined')->whereNotIn('tag', ['GP', 'CT'])->orderBy('created_at', 'desc')->get();
            $view->endorsed = [];
            $view->recentActivityCount = Feed::where('userid', $uid)->where('mined', '>=', Carbon::now()->subDay())->count();

            if ($wallet) {
                $view->balance = AppHelper::getMarscoinBalance($wallet['public_addr']);
                $view->public_address = $wallet['public_addr'];
                $view->endorsed = Feed::where('message', '=', $wallet['public_addr'])->where('tag', '=', 'ED')->get();
            } else {
                $view->balance = 0;
                $view->public_address = '';
            }
        }
        // Auth check at top of function ensures we never reach here unauthenticated

        return $view;

    }

    public function printout()
    {
        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', '=', $uid)->first();
            $civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
            $view = View::make('citizen.printout');
            $citcache = Citizen::where('userid', '=', $uid)->first();
            $view->fullname = $citcache->firstname.' '.$citcache->lastname;

            if ($civic_wallet) {
                $view->public_address = $civic_wallet['public_addr'];
            } else {
                $view->balance = 0;
                $view->public_address = '';
            }

            return $view;
        } else {
            return redirect('/login');
        }
    }

    public function printout2()
    {
        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', '=', $uid)->first();
            $civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
            $view = View::make('citizen.themes.printout2');
            $citcache = Citizen::where('userid', '=', $uid)->first();
            $view->fullname = $citcache->firstname.' '.$citcache->lastname;

            if ($civic_wallet) {
                $view->public_address = $civic_wallet['public_addr'];
            } else {
                $view->balance = 0;
                $view->public_address = '';
            }

            return $view;
        } else {
            return redirect('/login');
        }
    }

    public function printout3()
    {
        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', '=', $uid)->first();
            $civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
            $view = View::make('citizen.themes.printout3');
            $citcache = Citizen::where('userid', '=', $uid)->first();
            $view->fullname = $citcache->firstname.' '.$citcache->lastname;

            if ($civic_wallet) {
                $view->public_address = $civic_wallet['public_addr'];
            } else {
                $view->balance = 0;
                $view->public_address = '';
            }

            return $view;
        } else {
            return redirect('/login');
        }
    }

    // Get profile view of another Martian Citizen
    //
    public function showId($address)
    {

        if (Auth::check()) {
            $uid = Auth::user()->id;
            // Martian user we are looking at
            $martian = Citizen::where('public_address', '=', $address)->first();
            if (! $martian) {
                abort(404, 'Citizen not found');
            }
            $martian_profile = Profile::where('userid', '=', $martian->userid)->first();
            $martian_proposals = Proposal::where('user_id', '=', $martian->userid)->count();
            $myprofile = Profile::where('userid', '=', $uid)->first();

            if (! $myprofile) {
                return redirect('/twofa');
            } else {
                if ($myprofile->openchallenge == 1 || is_null($myprofile->openchallenge)) {
                    return redirect('/twofachallenge');
                }
            }
            $view = View::make('citizen.martian');
            $view->wallet_open = $myprofile->civic_wallet_open;

            $view->isCitizen = $martian_profile->citizen;
            $view->endorsements = $martian_profile->endorse_cnt;
            $view->proposals = $martian_proposals;
            $view->isGP = $martian_profile->general_public;
            $view->mePublic = Feed::where('userid', '=', $martian->userid)->where('tag', '=', 'GP')->first();
            $view->meCitizen = Feed::where('userid', '=', $martian->userid)->where('tag', '=', 'CT')->first();
            $view->endorsed = Feed::where('userid', '=', $martian->userid)->where('tag', '=', 'ED')->count();

            $view->citcache = $martian;

            return $view;

        } else {
            return redirect('/login');
        }

    }
}
