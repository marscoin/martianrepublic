<?php

/**
 * Tests for SecurityHeaders and InjectSentryJs middleware.
 * Uses HTTP route testing since middleware runs on every request.
 */

uses(Tests\TestCase::class, Tests\CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
});

// ============================================================
// SecurityHeaders middleware
// ============================================================

test('response includes X-Content-Type-Options header', function () {
    $response = $this->get('/');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
});

test('response includes X-Frame-Options header', function () {
    $response = $this->get('/');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});

test('response includes Strict-Transport-Security with preload', function () {
    $response = $this->get('/');
    $hsts = $response->headers->get('Strict-Transport-Security');
    expect($hsts)->toContain('max-age=31536000');
    expect($hsts)->toContain('includeSubDomains');
    expect($hsts)->toContain('preload');
});

test('response includes Content-Security-Policy header', function () {
    $response = $this->get('/');
    $csp = $response->headers->get('Content-Security-Policy');
    expect($csp)->not->toBeNull();
    expect($csp)->toContain("default-src 'self'");
});

test('CSP header contains sentry-cdn.com', function () {
    $response = $this->get('/');
    $csp = $response->headers->get('Content-Security-Policy');
    expect($csp)->toContain('sentry-cdn.com');
});

test('CSP header contains sentry ingest endpoint', function () {
    $response = $this->get('/');
    $csp = $response->headers->get('Content-Security-Policy');
    expect($csp)->toContain('ingest.us.sentry.io');
});

// ============================================================
// InjectSentryJs middleware
// ============================================================

test('HTML response includes Sentry JS script tag when DSN is set', function () {
    config(['sentry.dsn' => 'https://test@sentry.io/123']);

    $response = $this->get('/');
    $content = $response->getContent();

    expect($content)->toContain('sentry-cdn.com');
    expect($content)->toContain('Sentry.init');
});

test('Sentry snippet is not injected when DSN is empty', function () {
    config(['sentry.dsn' => null]);

    $response = $this->get('/');
    $content = $response->getContent();

    expect($content)->not->toContain('Sentry.init');
});

// ============================================================
// Additional security header checks
// ============================================================

test('response includes X-XSS-Protection header', function () {
    $response = $this->get('/');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
});

test('response includes Referrer-Policy header', function () {
    $response = $this->get('/');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('response includes Permissions-Policy header', function () {
    $response = $this->get('/');
    $response->assertHeader('Permissions-Policy');
});
