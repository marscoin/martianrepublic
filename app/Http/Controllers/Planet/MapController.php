<?php

namespace App\Http\Controllers\Planet;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MapController extends Controller
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
        $view = View::make('planet.map');

        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', '=', $uid)->first();

            if (! $profile) {
                return redirect('/twofa');
            } else {
                if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
                    return redirect('/twofachallenge');
                }
            }
            $view->isCitizen = $profile->citizen;
            $view->isGP = $profile->general_public;
            $view->wallet_open = $profile->civic_wallet_open;
        } else {
            $view->isCitizen = false;
            $view->isGP = false;
            $view->wallet_open = false;
        }

        return $view;

    }

    public function embed()
    {
        $view = View::make('planet.embed');

        return $view;

    }
}
