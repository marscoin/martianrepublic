<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/feed/public', 'App\Http\Controllers\ApiController@allPublic')->middleware(['auth:sanctum'])->name('api_allpublic');
Route::get('/feed/citizen', 'App\Http\Controllers\ApiController@allCitizen')->middleware(['auth:sanctum'])->name('api_allcitizen');
Route::get('/feed/applicant', 'App\Http\Controllers\ApiController@allApplicants')->middleware(['auth:sanctum'])->name('api_allapplicants');

