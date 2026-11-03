<?php

use App\Http\Controllers\Api\CongressApiController;
use App\Http\Controllers\Api\OrganizationApiController;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ContentApiController;
use App\Http\Controllers\FeedApiController;
use App\Http\Controllers\ForumApiController;
use App\Http\Controllers\UserManagementController;
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

// mobile
Route::get('/feed/public', [FeedApiController::class, 'allPublic'])->name('api_allpublic');
Route::get('/feed/citizen', [FeedApiController::class, 'allCitizen'])->name('api_allcitizen');
Route::get('/feed/applicant', [FeedApiController::class, 'allApplicants'])->name('api_allapplicants');
Route::get('/feed', [FeedApiController::class, 'allFeed'])->name('api_allfeed');
Route::post('/marsauth', [AuthApiController::class, 'marsAuth'])->middleware('throttle:10,1')->name('api_marsauth');

Route::get('/citizen/{address}', [FeedApiController::class, 'showCitizen'])->name('api_show');

Route::post('/token', [AuthApiController::class, 'token'])->middleware('throttle:10,1');
Route::post('/scitizen', [FeedApiController::class, 'scitizen'])->middleware(['auth:sanctum'])->name('api_scitizen');
Route::post('/pinpic', [ContentApiController::class, 'pinpic'])->middleware(['auth:sanctum'])->name('api_pinpic');
Route::post('/pinvideo', [ContentApiController::class, 'pinvideo'])->middleware(['auth:sanctum'])->name('api_pinvideo');
Route::post('/pinjson', [ContentApiController::class, 'pinjson'])->middleware(['auth:sanctum'])->name('api_pinjson');

Route::post('/user/delete/{id}', [UserManagementController::class, 'deleteUser'])->middleware(['auth:sanctum', 'throttle:3,1'])->name('api_delete_user');
Route::post('/user/block/{id}', [UserManagementController::class, 'blockUser'])->middleware(['auth:sanctum'])->name('api_block_user');

Route::get('/eula', [UserManagementController::class, 'handleEula'])->middleware(['auth:sanctum'])->name('api_handle_eula');
Route::get('/set/eula', [UserManagementController::class, 'setEula'])->middleware(['auth:sanctum'])->name('api_set_eula');
Route::get('/forum/thread/{threadId}/comments', [ForumApiController::class, 'getThreadComments'])->middleware(['auth:sanctum'])->name('api_forum_comments');

Route::get('/forum/category/{categoryId}/threads', [ForumApiController::class, 'getThreadsByCategory'])->middleware(['auth:sanctum'])->name('api_forum_threads');
Route::get('/forum/categories/threads', [ForumApiController::class, 'getAllCategoriesWithThreads'])->middleware(['auth:sanctum'])->name('api_forum_catthreads');

Route::post('/forum/thread', [ForumApiController::class, 'createThread'])->middleware(['auth:sanctum'])->name('api_create_thread');
Route::post('/forum/thread/{threadId}/comment', [ForumApiController::class, 'createComment'])->middleware(['auth:sanctum'])->name('api_create_comment');
Route::get('/mstatus', 'App\Http\Controllers\StatusController@getSystemStatus')->middleware(['auth:sanctum'])->name('api_mobile_status');

// Congress / Governance API (mobile)
Route::get('/congress/proposals', [CongressApiController::class, 'index'])->name('api_congress_proposals');
Route::get('/congress/proposals/{id}', [CongressApiController::class, 'show'])->name('api_congress_proposal');
Route::get('/congress/proposals/{id}/votes', [CongressApiController::class, 'votes'])->name('api_congress_votes');
Route::get('/congress/stats', [CongressApiController::class, 'stats'])->name('api_congress_stats');

// Private organization workspaces. All writes have durable idempotency receipts.
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/workspace', [OrganizationApiController::class, 'home']);
    Route::get('/organizations', [OrganizationApiController::class, 'index']);
    Route::post('/organizations', [OrganizationApiController::class, 'store'])->middleware('throttle:20,1');
    Route::get('/organizations/{organization}', [OrganizationApiController::class, 'show'])->whereUuid('organization');
    Route::post('/organizations/{organization}/invitations', [OrganizationApiController::class, 'invite'])->whereUuid('organization')->middleware('throttle:20,1');
    Route::post('/organizations/{organization}/invitations/respond', [OrganizationApiController::class, 'respond'])->whereUuid('organization');
    Route::post('/organizations/{organization}/members/{member}/remove', [OrganizationApiController::class, 'removeMember'])->whereUuid('organization')->whereNumber('member');
    Route::post('/organizations/{organization}/archive', [OrganizationApiController::class, 'archive'])->whereUuid('organization');
    Route::get('/organizations/{organization}/tasks', [OrganizationApiController::class, 'tasks'])->whereUuid('organization');
    Route::post('/organizations/{organization}/tasks', [OrganizationApiController::class, 'createTask'])->whereUuid('organization');
    Route::get('/organizations/{organization}/tasks/{task}', [OrganizationApiController::class, 'showTask'])->whereUuid(['organization', 'task']);
    Route::patch('/organizations/{organization}/tasks/{task}', [OrganizationApiController::class, 'updateTask'])->whereUuid(['organization', 'task']);
    Route::get('/organizations/{organization}/activity', [OrganizationApiController::class, 'activity'])->whereUuid('organization');
    Route::post('/organizations/{organization}/records', [OrganizationApiController::class, 'createRecord'])->whereUuid('organization');
    Route::post('/organizations/{organization}/attachments', [OrganizationApiController::class, 'upload'])->whereUuid('organization')->middleware('throttle:12,1');
    Route::get('/organizations/{organization}/attachments/{attachment}', [OrganizationApiController::class, 'photo'])->whereUuid(['organization', 'attachment']);
});
