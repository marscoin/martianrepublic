<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(function () {
        $this->artisan('migrate:fresh', [
            '--path' => [
                'database/migrations/2025_01_26_202544_create_users_table.php',
                // Only include tables we actually need for auth
            ]
        ]);
    });



test('registration screen can be rendered', function () {
    $response = $this->get('/signup');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/signup', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/wallet/dashboard');  // Remove route() helper since 'check' isn't a named route
});