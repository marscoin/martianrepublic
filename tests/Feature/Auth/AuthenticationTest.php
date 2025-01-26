<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(function () {
        $this->artisan('migrate:fresh', [
            '--path' => [
                'database/migrations/2025_01_26_202544_create_users_table.php',
                'database/migrations/2025_01_26_202544_create_mars_sessions_table.php',
                'database/migrations/2025_01_26_202544_create_password_resets_table.php'
                // Only include tables we actually need for auth
            ]
        ]);
    });

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    // Add debugging
    $debugData = [
        'status' => $response->status(),
        'content' => substr($response->getContent(), 0, 500),
    ];

    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        $debugData['url'] = $response->getTargetUrl();
    } else {
        $debugData['url'] = 'no redirect';
    }

    $response->assertStatus(200);
});

test('users can login with correct credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
    $response->assertRedirect('/check');
});


test('users cannot login with incorrect password', function () {
    $user = User::factory()->create();

    $response = $this->withSession([])
        ->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    // Add debugging
    $debugData = [
        'status' => $response->status(),
        'content' => substr($response->getContent(), 0, 500),
    ];

    if ($response instanceof RedirectResponse) {
        $debugData['url'] = $response->getTargetUrl();
    }

    // dd($debugData);

    $this->assertGuest();
});

test('users can logout successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->post('/logout');
    Auth::forgetGuards();
    
    $this->assertGuest();
    $response->assertRedirect('/');
});