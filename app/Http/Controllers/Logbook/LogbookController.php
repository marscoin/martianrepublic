<?php

namespace App\Http\Controllers\Logbook;

use App\Http\Controllers\Controller;
use App\Includes\AppHelper;
use App\Models\CivicWallet;
use App\Models\Profile;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class LogbookController extends Controller
{
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    public function __construct() {}

    // Get all inventory data and display table
    //
    public function showAll()
    {
        $view = View::make('logbook.dashboard');
        $view->allPublications = Publication::orderBy('id', 'desc')->get();

        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', '=', $uid)->first();
            $wallet = CivicWallet::where('user_id', '=', $uid)->first();

            if (! $profile) {
                return Redirect::to('/twofa');
            } else {
                if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
                    return Redirect::to('/twofachallenge');
                }
            }

            $view->wallet_open = $profile->civic_wallet_open;
            $view->isCitizen = $profile->citizen;
            $view->isGP = $profile->general_public;
            $view->myPublications = Publication::where('userid', '=', $uid)->orderBy('created_at', 'desc')->get();
            $view->balance = 0;

            if ($wallet) {
                $view->balance = AppHelper::getMarscoinBalance($wallet['public_addr']);
                $view->public_address = $wallet['public_addr'];
            } else {
                $view->balance = 0;
                $view->public_address = '';
            }
        } else {
            $view->wallet_open = false;
            $view->isCitizen = false;
            $view->isGP = false;
            $view->myPublications = collect();
            $view->balance = 0;
            $view->public_address = '';
        }

        return $view;

    }
}
