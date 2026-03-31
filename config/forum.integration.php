<?php

use App\Models\User;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Policies\CategoryPolicy;
use TeamTeaTime\Forum\Policies\ForumPolicy;
use TeamTeaTime\Forum\Policies\PostPolicy;
use TeamTeaTime\Forum\Policies\ThreadPolicy;

return [

    /*
    |--------------------------------------------------------------------------
    | Policies
    |--------------------------------------------------------------------------
    |
    | Here we specify the policy classes to use. Change these if you want to
    | extend the provided classes and use your own instead.
    |
    */

    'policies' => [
        'forum' => ForumPolicy::class,
        'model' => [
            Category::class => CategoryPolicy::class,
            Thread::class => ThreadPolicy::class,
            Post::class => PostPolicy::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Application user model
    |--------------------------------------------------------------------------
    |
    | Your application's user model.
    |
    */

    'user_model' => User::class,

    /*
    |--------------------------------------------------------------------------
    | Application user name
    |--------------------------------------------------------------------------
    |
    | The user model attribute to use for displaying usernames.
    |
    */

    'user_name' => 'fullname',

];
