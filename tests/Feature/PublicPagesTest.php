<?php

/**
 * Tests for pages using class-based route definitions (auth routes).
 * For string-based controller routes (citizen, congress, etc.), see WalletFlowTest
 * which tests controllers directly to avoid RouteServiceProvider namespace issues.
 */

uses(Tests\TestCase::class)->beforeEach(function () {
    $this->artisan('migrate:fresh', [
        '--path' => [
            'database/migrations/2025_01_26_202544_create_users_table.php',
            'database/migrations/2025_01_26_202544_create_mars_sessions_table.php',
            'database/migrations/2025_01_26_202544_create_password_resets_table.php',
        ]
    ]);
});

test('homepage loads', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('login page loads', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('signup page loads', function () {
    $response = $this->get('/signup');
    $response->assertStatus(200);
});

test('privacy page loads', function () {
    $response = $this->get('/privacy');
    $response->assertStatus(200);
});

test('tos page loads', function () {
    $response = $this->get('/tos');
    $response->assertStatus(200);
});

test('support page loads', function () {
    $response = $this->get('/support');
    $response->assertStatus(200);
});

test('wallet dashboard requires auth', function () {
    $response = $this->get('/wallet/dashboard');
    $response->assertRedirect('/login');
});
