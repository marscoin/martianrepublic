<?php

use App\Http\Controllers\AiHelperController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Citizen\IdentityController;
use App\Http\Controllers\Congress\CongressController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Logbook\LogbookController;
use App\Http\Controllers\Planet\MapController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\Wallet\DashboardController;
use App\Http\Controllers\Wallet\DiscoveryController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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
Route::post('/contact', [ContactFormController::class, 'sendEmail'])->middleware('throttle:3,1')->name('contact.send');
Route::get('/status', [StatusController::class, 'showStatus']);

//
// Native Forum (The Forum) — registered before vendor routes for priority
// ==================================================================================
Route::get('/forum', [ForumController::class, 'index'])->name('forum.home');
Route::get('/forum/t/{id}-{slug?}', [ForumController::class, 'show'])->where('id', '[0-9]+')->name('forum.thread.show');
Route::get('/forum/c/{id}-{slug?}', [ForumController::class, 'categoryThreads'])->where('id', '[0-9]+')->name('forum.category');
Route::middleware(['auth'])->group(function () {
    Route::post('/forum/thread', [ForumController::class, 'storeThread'])->middleware('throttle:5,1')->name('forum.thread.store');
    Route::post('/forum/t/{id}/post', [ForumController::class, 'storePost'])->middleware('throttle:10,1')->name('forum.post.store');
});

//
// Wallet (all require authentication)
// ==================================================================================

Route::middleware(['auth'])->group(function () {
    Route::any('/wallet/dashboard', [DashboardController::class, 'showDashboard']);
    Route::get('/wallet/profile', [DashboardController::class, 'showProfile']);
    Route::get('/wallet/dashboard/buy', [DashboardController::class, 'showBuy']);
    Route::get('/wallet/dashboard/send', [DashboardController::class, 'showSend']);
    Route::get('/wallet/dashboard/receive', [DashboardController::class, 'showReceive']);
    Route::get('/wallet/reports', [DashboardController::class, 'showReports']);
    Route::get('/wallet/features', [DashboardController::class, 'showFeatures']);
    Route::get('/wallet/camera', [DashboardController::class, 'showCamera']);
    Route::get('/wallet/dashboard/transactions', [DashboardController::class, 'showTransactions']);
    Route::get('/wallet/anchor', [DashboardController::class, 'anchor']);
    Route::post('/wallet/forget', [DashboardController::class, 'forgetWallet']);

    Route::any('/wallet/dashboard/hd', [DashboardController::class, 'listHDWallet']);
    Route::get('/wallet/create', [DashboardController::class, 'showCreateWallet']);
    Route::any('/wallet/dashboard/hd-open', [DashboardController::class, 'showHDOpen']);
    Route::any('/wallet/dashboard/hd-close', [DashboardController::class, 'showHDClose']);
    Route::any('/wallet/getwallet', [DashboardController::class, 'getWallet']);
    Route::any('/wallet/failwallet', [DashboardController::class, 'failWallet']);

    Route::post('/wallet/createwallet', [DashboardController::class, 'postCreateWallet']);
    Route::post('/wallet/dashboard/buy', [DashboardController::class, 'postBuy']);

    Route::any('/check', [DashboardController::class, 'showChallenge']);
    Route::any('/twofa', [DashboardController::class, 'show2FA']);
    Route::any('/twofachallenge', [DashboardController::class, 'show2FAChallenge']);
});

//
// Citizen Routes
// ==================================================================================
Route::get('/citizen/all', [IdentityController::class, 'showAll']);
Route::get('/citizen/join', [IdentityController::class, 'showJoin']);
Route::get('/citizen/printout', [IdentityController::class, 'printout']);
Route::get('/citizen/printout2', [IdentityController::class, 'printout2']);
Route::get('/citizen/printout3', [IdentityController::class, 'printout3']);
Route::get('/citizen/id/{address?}', [IdentityController::class, 'showId']);

//
// Inventory Routes
// ==================================================================================
Route::get('/inventory/all', [InventoryController::class, 'showAll']);
Route::middleware(['auth'])->group(function () {
    Route::post('/inventory/store', [InventoryController::class, 'store']);
    Route::post('/inventory/{id}/update', [InventoryController::class, 'update']);
    Route::post('/inventory/{id}/delete', [InventoryController::class, 'destroy']);
});

//
// BADS Instrument Registry Routes
// ==================================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/inventory/instruments', [InstrumentController::class, 'index'])->name('instruments.index');
    Route::get('/inventory/instruments/create', [InstrumentController::class, 'create'])->name('instruments.create');
    Route::post('/inventory/instruments', [InstrumentController::class, 'store'])->name('instruments.store');
    Route::get('/inventory/instruments/{id}', [InstrumentController::class, 'show'])->name('instruments.show');
    Route::get('/inventory/committees', [InstrumentController::class, 'committees'])->name('committees.index');
    Route::get('/api/instruments/{id}/chain', [InstrumentController::class, 'chainOfTrust']);
});

//
// Congress Routes
// ==================================================================================
Route::post('/api/ai/chat', [AiHelperController::class, 'chat'])->middleware('throttle:20,1');

Route::get('/congress/all', [CongressController::class, 'showAll']);
Route::get('/congress/proposal/{id?}', [CongressController::class, 'proposal']);
Route::middleware(['auth'])->group(function () {
    Route::any('/congress/voting', [CongressController::class, 'showVoting']);
    Route::any('/congress/voting/new', [CongressController::class, 'newProposal']);
    Route::get('/congress/ballot/pending', [CongressController::class, 'pendingBallots']);
    Route::get('/congress/ballot/{propid?}', [CongressController::class, 'acquireBallot']);
    Route::post('/congress/vote/breakdown', [CongressController::class, 'breakdown']);
    Route::post('/congress/proposal/diff', [CongressController::class, 'proposalDiff']);
    Route::post('/congress/proposal/withdraw', [CongressController::class, 'withdrawProposal']);
    Route::post('/congress/proposal/amend', [CongressController::class, 'amendProposal']);
    Route::post('/congress/proposal/challenge', [CongressController::class, 'challengeTier']);
    Route::post('/congress/ballot/backup-key', [CongressController::class, 'backupBallotKey']);
    Route::post('/congress/ballot/restore-key', [CongressController::class, 'restoreBallotKey']);
    Route::post('/congress/ballot/update-tx', [CongressController::class, 'updateBallotTx']);
    Route::post('/congress/ballot/confirm', [CongressController::class, 'confirmBallot']);
    Route::post('/congress/ballot/mark-used', [CongressController::class, 'markBallotUsed']);
});

//
// Logbook Routes
// ==================================================================================
Route::get('/logbook/all', [LogbookController::class, 'showAll']);

//
// Academy Routes
// ==================================================================================
Route::get('/academy', function () {
    return view('academy.index');
});
Route::get('/academy/{slug}', function ($slug) {
    // Check for top-level academy views first (e.g., complete-guide)
    $topLevel = 'academy.'.$slug;
    if (view()->exists($topLevel) && $slug !== 'index') {
        return view($topLevel);
    }
    // Then check articles subdirectory
    $viewName = 'academy.articles.'.$slug;
    if (view()->exists($viewName)) {
        return view($viewName);
    }
    abort(404);
});

//
// Map/Geography Routes
// ==================================================================================
Route::get('/map/all', [MapController::class, 'showAll']);
Route::get('/map/embed', [MapController::class, 'embed']);

//
// Internal API (all require authentication)
// ==================================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/api/balance/{account?}', [App\Http\Controllers\Wallet\ApiController::class, 'getBalance']);
    Route::post('/api/permapinpic', [App\Http\Controllers\Wallet\ApiController::class, 'permapinpic']);
    Route::post('/api/permapinvideo', [App\Http\Controllers\Wallet\ApiController::class, 'permapinvideo']);
    Route::post('/api/permapinlog', [App\Http\Controllers\Wallet\ApiController::class, 'permapinlog']);
    Route::post('/api/permapinjson', [App\Http\Controllers\Wallet\ApiController::class, 'permapinjson']);
    Route::post('/api/setfeed', [App\Http\Controllers\Wallet\ApiController::class, 'setfeed']);
    Route::post('/api/getTransactions', [App\Http\Controllers\Wallet\ApiController::class, 'getTransactions']);
    Route::post('/api/setfullname', [App\Http\Controllers\Wallet\ApiController::class, 'setfullname']);
    Route::post('/api/closewallet', [App\Http\Controllers\Wallet\ApiController::class, 'closewallet']);
    Route::post('/api/cacheproposal', [App\Http\Controllers\Wallet\ApiController::class, 'cacheproposal']);
    Route::post('/api/cacheonboarding', [App\Http\Controllers\Wallet\ApiController::class, 'cacheonboarding']);
    Route::post('/api/removelog', [App\Http\Controllers\Wallet\ApiController::class, 'removepinlog']);
    Route::post('/api/rejection', [App\Http\Controllers\Wallet\ApiController::class, 'rejectApplication']);
    Route::post('/api/balance', [App\Http\Controllers\Wallet\ApiController::class, 'getBalance']);
    Route::post('/api/price', [App\Http\Controllers\Wallet\ApiController::class, 'getPrice']);
    Route::post('/api/dismiss', [App\Http\Controllers\Wallet\ApiController::class, 'dismissAlert']);
    Route::post('/api/rename', [App\Http\Controllers\Wallet\ApiController::class, 'renameWallet']);
    Route::post('/api/link-civic', [App\Http\Controllers\Wallet\ApiController::class, 'linkCivicWallet']);
    Route::post('/api/discover', [DiscoveryController::class, 'discover']);
    Route::get('/api/address/{address}/transactions', [DiscoveryController::class, 'addressTransactions']);
    Route::get('/api/mars-price', [App\Http\Controllers\Wallet\ApiController::class, 'marsPrice']);
    Route::get('/api/mars-txhistory', [App\Http\Controllers\Wallet\ApiController::class, 'marsTxHistory']);
    Route::get('/api/mars-utxo-multi', [App\Http\Controllers\Wallet\ApiController::class, 'marsUtxoMulti']);

    // Civic wallet migration
    Route::get('/wallet/migrate', [App\Http\Controllers\Wallet\MigrationController::class, 'show'])->name('wallet.migrate');
    Route::post('/api/wallet/migrate/initiate', [App\Http\Controllers\Wallet\MigrationController::class, 'initiate']);
    Route::post('/api/wallet/migrate/confirm', [App\Http\Controllers\Wallet\MigrationController::class, 'confirm']);
    Route::get('/api/wallet/migrate/history', [App\Http\Controllers\Wallet\MigrationController::class, 'history']);
    Route::post('/api/broadcast', [App\Http\Controllers\Wallet\ApiController::class, 'broadcastTx']);
});

//
// Mobile Authenticator Login
// ================================================================================
Route::get('/api/checkauth', [ApiController::class, 'checkAuth'])->name('api_marsauthcheck');
