<?php

namespace App\Http\Controllers;

/**
 * ApiController - formerly the monolithic API controller.
 *
 * Methods have been split into focused controllers:
 *   - FeedApiController       (allPublic, allCitizen, allApplicants, allFeed, showCitizen, scitizen)
 *   - AuthApiController       (marsAuth, checkAuth, wauth, token, test)
 *   - ForumApiController      (getThreadsByCategory, getThreadComments, getAllCategoriesWithThreads, createThread, createComment)
 *   - ContentApiController    (pinpic, pinvideo, pinjson)
 *   - UserManagementController (blockUser, deleteUser, handleEula, setEula)
 */
class ApiController extends Controller
{
    //
}
