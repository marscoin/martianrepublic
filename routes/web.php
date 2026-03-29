<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wallet\DashboardController;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

require __DIR__.'/auth.php';

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
    
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

Route::get('/tos', function () {
    return Cache::remember('tos.page', 2, function () {
        return view('tos')->render();
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
// Native Forum (The Forum) — registered before vendor routes for priority
// ==================================================================================
Route::get("/forum", "ForumController@index")->name("forum.home");
Route::get("/forum/t/{id}-{slug?}", "ForumController@show")->where("id", "[0-9]+")->name("forum.thread.show");
Route::get("/forum/c/{id}-{slug?}", "ForumController@categoryThreads")->where("id", "[0-9]+")->name("forum.category");
Route::middleware(["auth"])->group(function () {
    Route::post("/forum/thread", "ForumController@storeThread")->name("forum.thread.store");
    Route::post("/forum/t/{id}/post", "ForumController@storePost")->name("forum.post.store");
});


//
// Wallet (all require authentication)
// ==================================================================================

Route::middleware(['auth'])->group(function () {
    Route::any('/wallet/dashboard', 'Wallet\DashboardController@showDashboard');
    Route::get('/wallet/profile', 'Wallet\DashboardController@showProfile');
    Route::get('/wallet/dashboard/buy', 'Wallet\DashboardController@showBuy');
    Route::get('/wallet/dashboard/send', 'Wallet\DashboardController@showSend');
    Route::get('/wallet/dashboard/receive', 'Wallet\DashboardController@showReceive');
    Route::get('/wallet/reports', 'Wallet\DashboardController@showReports');
    Route::get('/wallet/features', 'Wallet\DashboardController@showFeatures');
    Route::get('/wallet/camera', 'Wallet\DashboardController@showCamera');
    Route::get('/wallet/dashboard/transactions', 'Wallet\DashboardController@showTransactions');
    Route::get('/wallet/anchor', 'Wallet\DashboardController@anchor');
    Route::post('/wallet/forget', 'Wallet\DashboardController@forgetWallet');

    Route::any('/wallet/dashboard/hd', 'Wallet\DashboardController@listHDWallet');
    Route::get('/wallet/create', 'Wallet\DashboardController@showCreateWallet');
    Route::any('/wallet/dashboard/hd-open', 'Wallet\DashboardController@showHDOpen');
    Route::any('/wallet/dashboard/hd-close', 'Wallet\DashboardController@showHDClose');
    Route::any('/wallet/getwallet', 'Wallet\DashboardController@getWallet');
    Route::any('/wallet/failwallet', 'Wallet\DashboardController@failWallet');

    Route::post('/wallet/createwallet', 'Wallet\DashboardController@postCreateWallet');
    Route::post('/wallet/dashboard/buy', 'Wallet\DashboardController@postBuy');

    Route::any('/check', 'Wallet\DashboardController@showChallenge');
    Route::any('/twofa', 'Wallet\DashboardController@show2FA');
    Route::any('/twofachallenge', 'Wallet\DashboardController@show2FAChallenge');
});


// 
// Citizen Routes
// ==================================================================================
Route::get('/citizen/all', 'Citizen\IdentityController@showAll');
Route::get('/citizen/join', 'Citizen\IdentityController@showJoin');
Route::get('/citizen/printout', 'Citizen\IdentityController@printout');
Route::get('/citizen/printout2', 'Citizen\IdentityController@printout2');
Route::get('/citizen/printout3', 'Citizen\IdentityController@printout3');
Route::get('/citizen/id/{address?}', 'Citizen\IdentityController@showId');

//
// Inventory Routes
// ==================================================================================
Route::get('/inventory/all', 'Inventory\InventoryController@showAll');
Route::middleware(['auth'])->group(function () {
    Route::post('/inventory/store', 'Inventory\InventoryController@store');
    Route::post('/inventory/{id}/update', 'Inventory\InventoryController@update');
    Route::post('/inventory/{id}/delete', 'Inventory\InventoryController@destroy');
});


//
// Congress Routes
// ==================================================================================
Route::post("/api/ai/chat", [App\Http\Controllers\AiHelperController::class, "chat"]);

Route::get('/congress/all', 'Congress\CongressController@showAll');
Route::get('/congress/proposal/{id?}', 'Congress\CongressController@proposal');
Route::middleware(['auth'])->group(function () {
    Route::any('/congress/voting', 'Congress\CongressController@showVoting');
    Route::any('/congress/voting/new', 'Congress\CongressController@newProposal');
    Route::get("/congress/ballot/pending", "Congress\CongressController@pendingBallots");
    Route::get('/congress/ballot/{propid?}', 'Congress\CongressController@acquireBallot');
    Route::post('/congress/vote/breakdown', 'Congress\CongressController@breakdown');
    Route::post('/congress/proposal/diff', 'Congress\CongressController@proposalDiff');
    Route::post('/congress/proposal/withdraw', 'Congress\CongressController@withdrawProposal');
    Route::post('/congress/proposal/amend', 'Congress\CongressController@amendProposal');
    Route::post('/congress/proposal/challenge', 'Congress\CongressController@challengeTier');
    Route::post("/congress/ballot/backup-key", "Congress\CongressController@backupBallotKey");
    Route::post("/congress/ballot/restore-key", "Congress\CongressController@restoreBallotKey");
    Route::post("/congress/ballot/update-tx", "Congress\CongressController@updateBallotTx");
    Route::post("/congress/ballot/confirm", "Congress\CongressController@confirmBallot");
    Route::post("/congress/ballot/mark-used", "Congress\CongressController@markBallotUsed");
});


// 
// Logbook Routes
// ==================================================================================
Route::get('/logbook/all', 'Logbook\LogbookController@showAll');


//
// Academy Routes
// ==================================================================================
Route::get('/academy', function () {
    return view('academy.index');
});
Route::get('/academy/{slug}', function ($slug) {
    // Check for top-level academy views first (e.g., complete-guide)
    $topLevel = 'academy.' . $slug;
    if (view()->exists($topLevel) && $slug !== 'index') {
        return view($topLevel);
    }
    // Then check articles subdirectory
    $viewName = 'academy.articles.' . $slug;
    if (view()->exists($viewName)) {
        return view($viewName);
    }
    abort(404);
});


//
// Map/Geography Routes
// ==================================================================================
Route::get('/map/all', 'Planet\MapController@showAll');
Route::get('/map/embed', 'Planet\MapController@embed');



//
// Internal API (all require authentication)
// ==================================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/api/balance/{account?}', 'Wallet\ApiController@getBalance');
    // Routes below require controller methods to be implemented:
    // Route::get('/api/account/{account?}', 'Wallet\ApiController@getAccount');
    // Route::post('/api/sendFrom/{account?}', 'Wallet\ApiController@sendFrom');
    // Route::post('/api/newAddress/{account?}', 'Wallet\ApiController@newAddress');
    // Route::post('/api/importPK/{account?}', 'Wallet\ApiController@importPK');
    // Route::post('/api/redeem', 'Wallet\ApiController@redeem');
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
    Route::post('/api/link-civic', 'Wallet\ApiController@linkCivicWallet');
    Route::post('/api/discover', 'Wallet\ApiController@discoverAddresses');
    Route::get('/api/mars-price', 'Wallet\ApiController@marsPrice');
    Route::get('/api/mars-txhistory', 'Wallet\ApiController@marsTxHistory');
    Route::get('/api/mars-utxo-multi', 'Wallet\ApiController@marsUtxoMulti');
});

//
// Mobile Authenticator Login
// ================================================================================
Route::get('/api/checkauth', 'App\Http\Controllers\ApiController@checkAuth')->name('api_marsauthcheck');


//test
// Route::any('/scitizen', 'App\Http\Controllers\ApiController@scitizen');