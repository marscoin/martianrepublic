<?php

/**
 * Tests for the ContactFormController.
 * Validates controller existence, form validation, and rate limiting config.
 * Uses direct controller testing to avoid RouteServiceProvider namespace issues.
 */

use App\Http\Controllers\ContactFormController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
});

// ============================================================
// Controller class and method existence
// ============================================================

test('ContactFormController class exists', function () {
    expect(class_exists(ContactFormController::class))->toBeTrue();
});

test('ContactFormController has sendEmail method', function () {
    $reflection = new ReflectionClass(ContactFormController::class);
    expect($reflection->hasMethod('sendEmail'))->toBeTrue();
    expect($reflection->getMethod('sendEmail')->isPublic())->toBeTrue();
});

// ============================================================
// Validation: missing required fields throws ValidationException
// ============================================================

test('contact form requires name field', function () {
    $controller = app()->make(ContactFormController::class);
    $request = Request::create('/contact', 'POST', [
        'email' => 'test@test.com',
        'subject' => 'Hello',
        'text' => 'Body text',
    ]);
    $request->setLaravelSession(app('session.store'));

    $controller->sendEmail($request);
})->throws(ValidationException::class);

test('contact form requires email field', function () {
    $controller = app()->make(ContactFormController::class);
    $request = Request::create('/contact', 'POST', [
        'name' => 'Test',
        'subject' => 'Hello',
        'text' => 'Body text',
    ]);
    $request->setLaravelSession(app('session.store'));

    $controller->sendEmail($request);
})->throws(ValidationException::class);

test('contact form requires subject field', function () {
    $controller = app()->make(ContactFormController::class);
    $request = Request::create('/contact', 'POST', [
        'name' => 'Test',
        'email' => 'test@test.com',
        'text' => 'Body text',
    ]);
    $request->setLaravelSession(app('session.store'));

    $controller->sendEmail($request);
})->throws(ValidationException::class);

test('contact form requires text field', function () {
    $controller = app()->make(ContactFormController::class);
    $request = Request::create('/contact', 'POST', [
        'name' => 'Test',
        'email' => 'test@test.com',
        'subject' => 'Hello',
    ]);
    $request->setLaravelSession(app('session.store'));

    $controller->sendEmail($request);
})->throws(ValidationException::class);

test('contact form rejects invalid email', function () {
    $controller = app()->make(ContactFormController::class);
    $request = Request::create('/contact', 'POST', [
        'name' => 'Test',
        'email' => 'not-an-email',
        'subject' => 'Hello',
        'text' => 'Body text',
    ]);
    $request->setLaravelSession(app('session.store'));

    $controller->sendEmail($request);
})->throws(ValidationException::class);

// ============================================================
// Rate limiting is configured
// ============================================================

test('contact route has throttle middleware configured', function () {
    // Read the routes file and verify throttle middleware is applied
    $routeFile = file_get_contents(base_path('routes/web.php'));
    expect($routeFile)->toContain("'/contact'");
    expect($routeFile)->toContain('throttle:3,1');
});
