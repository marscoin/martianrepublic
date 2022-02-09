<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wallet\DashboardController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Route::get('/', function()
{
    return view('landing');
});


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

Route::any('/wallet/dashboard/hd', 'Wallet\DashboardController@showHDWallet');
Route::any('/wallet/dashboard/hd-open', 'Wallet\DashboardController@showHDOpen');
Route::any('/wallet/getwallet', 'Wallet\DashboardController@getWallet');
Route::any('/wallet/failwallet', 'Wallet\DashboardController@failWallet');

Route::post('/wallet/createwallet', 'Wallet\DashboardController@postCreateWallet');
Route::post('/wallet/register', 'Wallet\DashboardController@postRegister');
Route::post('/wallet/loginattempt', 'Wallet\DashboardController@postLogin');
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

// 
// Inventory Routes
// ==================================================================================
Route::get('/inventory/all', 'Inventory\InventoryController@showAll');


// 
// Congress Routes
// ==================================================================================
Route::get('/congress/all', 'Congress\CongressController@showAll');
Route::any('/congress/voting', 'Congress\CongressController@showVoting');
Route::post('/congress/createproposal', 'Congress\CongressController@postCreateProposal');


// 
// Logbook Routes
// ==================================================================================
Route::get('/logbook/all', 'Logbook\LogbookController@showAll');


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
Route::post('/api/permapinjson', 'Wallet\ApiController@permapinjson');
