<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;


uses(TestCase::class)
    ->beforeEach(function () {
        $this->artisan('migrate:fresh', [
            '--path' => [
                'database/migrations/2025_01_26_202544_create_users_table.php',
                // Only include tables we actually need for auth
            ]
        ]);
        $this->withSession([]); // Initialize empty session
    });


test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response->assertStatus(200);
});


test('password can be confirmed', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post('/confirm-password', [
            'password' => 'password',
        ]);
    
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

/** @group wip */
test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/confirm-password', [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});
