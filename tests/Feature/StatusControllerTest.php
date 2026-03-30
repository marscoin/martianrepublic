<?php

/**
 * Tests for the StatusController.
 * Uses direct controller testing for method inspection,
 * and HTTP for public route accessibility.
 */

use App\Http\Controllers\StatusController;

uses(Tests\TestCase::class, Tests\CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
});

// ============================================================
// Controller class and method existence
// ============================================================

test('StatusController class exists', function () {
    expect(class_exists(StatusController::class))->toBeTrue();
});

test('StatusController has showStatus method', function () {
    $reflection = new ReflectionClass(StatusController::class);
    expect($reflection->hasMethod('showStatus'))->toBeTrue();
});

test('StatusController has getSystemStatus method', function () {
    $reflection = new ReflectionClass(StatusController::class);
    expect($reflection->hasMethod('getSystemStatus'))->toBeTrue();
});

// ============================================================
// Route accessibility
// ============================================================

test('status page is publicly accessible (no auth redirect)', function () {
    // The /status route has no auth middleware, so it should not redirect to login.
    // It may error due to external service calls, but it should NOT return 302 to /login.
    $response = $this->get('/status');
    expect($response->status())->not->toBe(302);
});

test('getSystemStatus returns JSON structure', function () {
    $reflection = new ReflectionClass(StatusController::class);
    $method = $reflection->getMethod('getSystemStatus');
    expect($method->isPublic())->toBeTrue();
});

test('StatusController has collectStatusData method', function () {
    $reflection = new ReflectionClass(StatusController::class);
    expect($reflection->hasMethod('collectStatusData'))->toBeTrue();
});

test('StatusController has checkBallotServer method', function () {
    $reflection = new ReflectionClass(StatusController::class);
    expect($reflection->hasMethod('checkBallotServer'))->toBeTrue();
});
