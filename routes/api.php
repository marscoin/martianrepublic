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

//mobile
Route::get('/feed/public', 'App\Http\Controllers\ApiController@allPublic')->name('api_allpublic');
Route::get('/feed/citizen', 'App\Http\Controllers\ApiController@allCitizen')->name('api_allcitizen');
Route::get('/feed/applicant', 'App\Http\Controllers\ApiController@allApplicants')->name('api_allapplicants');

Route::get('/citizen/{address}', 'App\Http\Controllers\ApiController@showCitizen')->name('api_show');

Route::get('/test', 'App\Http\Controllers\ApiController@test');