<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wallet\DashboardController;
use Illuminate\Support\Facades\Cache;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Route::get('/', function () {
    return Cache::remember('home.page', 260000, function () {
        return view('landing')->render();
    });
});
Route::get('/privacy', function () {
    return Cache::remember('privacy.page', 2, function () {
        return view('privacy')->render();
    });
});
Route::get('/support', function () {
    return Cache::remember('support.page', 2, function () {
        return view('support')->render();
    });
});
Route::post('/contact', 'ContactFormController@sendEmail')->name('contact.send');
Route::get('/status', 'StatusController@showStatus');

// 
// Wallet
// ==================================================================================

Route::any('/wallet/dashboard', 'Wallet\DashboardController@showDashboard');
Route::get('/wallet/profile', 'Wallet\DashboardController@showProfile');
Route::get('/wallet/dashboard/buy', 'Wallet\DashboardController@showBuy');
Route::get('/wallet/dashboard/send', 'Wallet\DashboardController@showSend');
Route::get('/wallet/dashboard/receive', 'Wallet\DashboardController@showReceive');
Route::get('/wallet/reports', 'Wallet\DashboardController@showReports');
Route::get('/wallet/features', 'Wallet\DashboardController@showFeatures');
Route::get('/wallet/camera', 'Wallet\DashboardController@showCamera');
Route::get('/wallet/dashboard/transactions', 'Wallet\DashboardController@showTransactions');
Route::get('/wallet/chart', 'Wallet\DashboardController@showChart');
Route::get('/wallet/anchor', 'Wallet\DashboardController@anchor');
Route::post('/wallet/forget', 'Wallet\DashboardController@forgetWallet');


Route::any('/wallet/dashboard/hd', 'Wallet\DashboardController@listHDWallet');
Route::any('/wallet/dashboard/hd-open', 'Wallet\DashboardController@showHDOpen');
Route::any('/wallet/dashboard/hd-close', 'Wallet\DashboardController@showHDClose');
Route::any('/wallet/getwallet', 'Wallet\DashboardController@getWallet');
Route::any('/wallet/failwallet', 'Wallet\DashboardController@failWallet');

Route::post('/wallet/createwallet', 'Wallet\DashboardController@postCreateWallet');
Route::post('/wallet/dashboard/buy', 'Wallet\DashboardController@postBuy');

Route::any('/check', 'Wallet\DashboardController@showChallenge');
Route::any('/twofa', 'Wallet\DashboardController@show2FA');
Route::any('/twofachallenge', 'Wallet\DashboardController@show2FAChallenge');
Route::any('/logout', 'Wallet\DashboardController@showLogout');


// 
// Citizen Routes
// ==================================================================================
Route::get('/citizen/all', 'Citizen\IdentityController@showAll');
Route::get('/citizen/printout', 'Citizen\IdentityController@printout');
Route::get('/citizen/printout2', 'Citizen\IdentityController@printout2');
Route::get('/citizen/printout3', 'Citizen\IdentityController@printout3');
Route::get('/citizen/id/{address?}', 'Citizen\IdentityController@showId');

// 
// Inventory Routes
// ==================================================================================
Route::get('/inventory/all', 'Inventory\InventoryController@showAll');


// 
// Congress Routes
// ==================================================================================
Route::get('/congress/all', 'Congress\CongressController@showAll');
Route::any('/congress/voting', 'Congress\CongressController@showVoting');
Route::any('/congress/voting/new', 'Congress\CongressController@newProposal');
Route::post('/congress/createproposal', 'Congress\CongressController@postCreateProposal');
Route::get('/congress/ballot/{propid?}', 'Congress\CongressController@acquireBallot');
Route::get('/congress/proposal/{id?}', 'Congress\CongressController@proposal');
Route::post('/congress/vote/breakdown', 'Congress\CongressController@breakdown');



// 
// Logbook Routes
// ==================================================================================
Route::get('/logbook/all', 'Logbook\LogbookController@showAll');


// 
// Map/Geography Routes
// ==================================================================================
Route::get('/map/all', 'Planet\MapController@showAll');
Route::get('/map/embed', 'Planet\MapController@embed');



//
//Internal API
// ==================================================================================
Route::get('/api/balance/{account?}', 'Wallet\ApiController@getBalance');
Route::get('/api/account/{account?}', 'Wallet\ApiController@getAccount');
Route::post('/api/sendFrom/{account?}', 'Wallet\ApiController@sendFrom');
Route::post('/api/newAddress/{account?}', 'Wallet\ApiController@newAddress');
Route::post('/api/importPK/{account?}', 'Wallet\ApiController@importPK');
Route::post('/api/redeem', 'Wallet\ApiController@redeem');
Route::post('/api/permapinpic', 'Wallet\ApiController@permapinpic');
Route::post('/api/permapinvideo', 'Wallet\ApiController@permapinvideo');
Route::post('/api/permapinlog', 'Wallet\ApiController@permapinlog');
Route::post('/api/permapinjson', 'Wallet\ApiController@permapinjson');
Route::post('/api/setfeed', 'Wallet\ApiController@setfeed');
Route::post('/api/getTransactions', 'Wallet\ApiController@getTransactions');
Route::post('/api/setfullname', 'Wallet\ApiController@setfullname');
Route::post('/api/closewallet', 'Wallet\ApiController@closewallet');
Route::post('/api/cacheproposal', 'Wallet\ApiController@cacheproposal');
Route::post('/api/cacheonboarding', 'Wallet\ApiController@cacheonboarding');
Route::post('/api/removelog', 'Wallet\ApiController@removepinlog');
Route::post('/api/rejection', 'Wallet\ApiController@rejectApplication');
Route::post('/api/balance', 'Wallet\ApiController@getBalance');
Route::post('/api/price', 'Wallet\ApiController@getPrice');
Route::post('/api/dismiss', 'Wallet\ApiController@dismissAlert');
Route::post('/api/rename', 'Wallet\ApiController@renameWallet');

//
// Mobile Authenticator Login
// ================================================================================
Route::get('/api/checkauth', 'App\Http\Controllers\ApiController@checkAuth')->name('api_marsauthcheck');


//test
// Route::any('/scitizen', 'App\Http\Controllers\ApiController@scitizen');