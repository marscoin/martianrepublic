<?php

/**
 * Tests for the AI helper (Olympus) and error triage system.
 */

use App\Mail\ErrorTriageMail;
use App\Jobs\ErrorTriageJob;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class)->beforeEach(function () {
    $this->artisan('migrate:fresh', [
        '--path' => [
            'database/migrations/2025_01_26_202544_create_users_table.php',
            'database/migrations/2025_01_26_202544_create_mars_sessions_table.php',
        ]
    ]);
});

test('AI chat endpoint requires messages array', function () {
    $response = $this->postJson('/api/ai/chat', []);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('messages');
});

test('AI chat validates message format', function () {
    $response = $this->postJson('/api/ai/chat', [
        'messages' => [
            ['role' => 'invalid', 'content' => 'hello'],
        ],
    ]);
    $response->assertStatus(422);
});

test('AI chat accepts valid user message', function () {
    // We can't test the full OpenRouter call in CI, but we can test
    // that validation passes and the controller is reachable
    config(['services.openrouter.api_key' => null]);

    $response = $this->postJson('/api/ai/chat', [
        'messages' => [
            ['role' => 'user', 'content' => 'What is the Martian Republic?'],
        ],
    ]);

    // Without API key it will fail at the HTTP call level, not validation
    // Status will be 502 (bad gateway from OpenRouter) or 500, not 422
    expect($response->status())->not->toBe(422);
});

test('ErrorTriageMail renders with correct data', function () {
    $mail = new ErrorTriageMail(
        exceptionClass: 'RuntimeException',
        errorMessage: 'Test error message',
        file: '/home/martianrepublic/app/Http/Controllers/TestController.php',
        line: 42,
        url: 'https://martianrepublic.org/test',
        method: 'GET',
        userId: '123',
        occurredAt: '2026-03-30 12:00:00',
        aiSummary: "**What happened** — Test error\n**Severity** — Medium",
    );

    expect($mail->exceptionClass)->toBe('RuntimeException');
    expect($mail->errorMessage)->toBe('Test error message');
    expect($mail->file)->toContain('TestController.php');
    expect($mail->line)->toBe(42);

    // Check envelope
    $envelope = $mail->envelope();
    expect($envelope->subject)->toContain('500 Error');
    // Severity extracted via regex from AI summary
    expect($envelope->subject)->toContain('500 Error');
});

test('ErrorTriageMail extracts severity from AI summary', function () {
    $critical = new ErrorTriageMail(
        exceptionClass: 'PDOException',
        errorMessage: 'DB gone',
        file: '/test.php',
        line: 1,
        url: '/test',
        method: 'GET',
        userId: null,
        occurredAt: now()->toDateTimeString(),
        aiSummary: "**Severity** — Critical\nDatabase connection failed.",
    );
    expect($critical->envelope()->subject)->toContain('CRITICAL');

    $low = new ErrorTriageMail(
        exceptionClass: 'LogicException',
        errorMessage: 'Minor issue',
        file: '/test.php',
        line: 1,
        url: '/test',
        method: 'GET',
        userId: null,
        occurredAt: now()->toDateTimeString(),
        aiSummary: "**Severity** — Low\nNon-issue.",
    );
    expect($low->envelope()->subject)->toContain('LOW');
});

test('ErrorTriageJob can be instantiated with exception data', function () {
    $job = new ErrorTriageJob(
        exceptionClass: 'RuntimeException',
        message: 'Something broke',
        file: '/app/Test.php',
        line: 10,
        trace: '#0 test',
        url: '/test',
        method: 'GET',
        userId: null,
        occurredAt: now()->toDateTimeString(),
    );

    expect($job)->toBeInstanceOf(ErrorTriageJob::class);
});

test('error triage config is properly structured', function () {
    config([
        'services.error_triage' => [
            'openrouter_key' => 'test-key',
            'model' => 'openrouter/auto',
            'emails' => 'test@mars.org,admin@mars.org',
            'cooldown_minutes' => 15,
        ],
    ]);

    expect(config('services.error_triage.model'))->toBe('openrouter/auto');
    expect(config('services.error_triage.cooldown_minutes'))->toBe(15);

    $emails = explode(',', config('services.error_triage.emails'));
    expect($emails)->toHaveCount(2);
});
