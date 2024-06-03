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
Route::post('/marsauth', 'App\Http\Controllers\ApiController@marsAuth')->name('api_marsauth');

Route::get('/citizen/{address}', 'App\Http\Controllers\ApiController@showCitizen')->name('api_show');

Route::post('/token', 'App\Http\Controllers\ApiController@token');
Route::get('/test', 'App\Http\Controllers\ApiController@test');

Route::post('/scitizen', 'App\Http\Controllers\ApiController@scitizen')->middleware(['auth:sanctum'])->name('api_scitizen');
Route::post('/pinpic', 'App\Http\Controllers\ApiController@pinpic')->middleware(['auth:sanctum'])->name('api_pinpic');
Route::post('/pinvideo', 'App\Http\Controllers\ApiController@pinvideo')->middleware(['auth:sanctum'])->name('api_pinvideo');
Route::post('/pinjson', 'App\Http\Controllers\ApiController@pinjson')->middleware(['auth:sanctum'])->name('api_pinjson');

Route::get('/forum/thread/{threadId}/comments', 'App\Http\Controllers\ApiController@getThreadComments')->middleware(['auth:sanctum'])->name('api_forum_comments');

Route::get('/forum/category/{categoryId}/threads', 'App\Http\Controllers\ApiController@getThreadsByCategory')->middleware(['auth:sanctum'])->name('api_forum_threads');
Route::get('/forum/categories/threads', 'App\Http\Controllers\ApiController@getAllCategoriesWithThreads')->middleware(['auth:sanctum'])->name('api_forum_catthreads');