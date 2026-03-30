<?php

/**
 * Cross-cutting security tests.
 * Validates config integrity, token expiry, model safety, and .env protection.
 */

uses(Tests\TestCase::class, Tests\CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
});

// ============================================================
// Error triage config is structured correctly
// ============================================================

test('error triage config has expected keys', function () {
    $triage = config('services.error_triage');
    expect($triage)->toBeArray();
    expect($triage)->toHaveKey('model');
    expect($triage)->toHaveKey('emails');
    expect($triage)->toHaveKey('cooldown_minutes');
});

// ============================================================
// Sanctum token expiry
// ============================================================

test('Sanctum token expiration is set to 1440 minutes', function () {
    $expiration = config('sanctum.expiration');
    expect($expiration)->toBe(1440);
});

// ============================================================
// Blockchain config exists and has expected keys
// ============================================================

test('blockchain config exists and has rpc section', function () {
    $blockchain = config('blockchain');
    expect($blockchain)->toBeArray();
    expect($blockchain)->toHaveKey('rpc');
    expect($blockchain['rpc'])->toHaveKey('cli_path');
    expect($blockchain['rpc'])->toHaveKey('data_dir');
});

test('blockchain config has pebas section', function () {
    expect(config('blockchain.pebas'))->toBeArray();
    expect(config('blockchain.pebas'))->toHaveKey('url');
    expect(config('blockchain.pebas'))->toHaveKey('public_url');
});

test('blockchain config has ipfs section', function () {
    expect(config('blockchain.ipfs'))->toBeArray();
    expect(config('blockchain.ipfs'))->toHaveKey('api_url');
    expect(config('blockchain.ipfs'))->toHaveKey('gateway_url');
});

test('blockchain config has explorer section', function () {
    expect(config('blockchain.explorer'))->toBeArray();
    expect(config('blockchain.explorer'))->toHaveKey('primary_url');
    expect(config('blockchain.explorer'))->toHaveKey('fallback_url');
});

test('blockchain config has ballot section', function () {
    expect(config('blockchain.ballot'))->toBeArray();
    expect(config('blockchain.ballot'))->toHaveKey('host');
    expect(config('blockchain.ballot'))->toHaveKey('port');
});

test('blockchain config has price section', function () {
    expect(config('blockchain.price'))->toBeArray();
    expect(config('blockchain.price'))->toHaveKey('marscoin_url');
});

// ============================================================
// .env not accessible via HTTP
// ============================================================

test('.env file is not accessible via HTTP', function () {
    $response = $this->get('/.env');
    expect($response->status())->not->toBe(200);
});

// ============================================================
// All models define $fillable or $guarded
// ============================================================

test('all models have $fillable or $guarded defined', function () {
    $modelDirs = [
        app_path('Models'),
        app_path('Models/Bads'),
    ];

    $unprotectedModels = [];

    foreach ($modelDirs as $dir) {
        if (!is_dir($dir)) {
            continue;
        }

        $files = glob($dir . '/*.php');
        foreach ($files as $file) {
            $content = file_get_contents($file);

            // Extract the fully qualified class name
            $namespace = '';
            if (preg_match('/namespace\s+([\w\\\\]+)/', $content, $nsMatch)) {
                $namespace = $nsMatch[1];
            }
            if (preg_match('/class\s+(\w+)/', $content, $classMatch)) {
                $fqcn = $namespace . '\\' . $classMatch[1];

                // Only check Eloquent models
                if (!class_exists($fqcn)) {
                    continue;
                }
                $reflection = new ReflectionClass($fqcn);
                if (!$reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class)) {
                    continue;
                }

                $hasFillable = $reflection->hasProperty('fillable') &&
                    $reflection->getProperty('fillable')->getDeclaringClass()->getName() === $fqcn;
                $hasGuarded = $reflection->hasProperty('guarded') &&
                    $reflection->getProperty('guarded')->getDeclaringClass()->getName() === $fqcn;

                if (!$hasFillable && !$hasGuarded) {
                    $unprotectedModels[] = $fqcn;
                }
            }
        }
    }

    expect($unprotectedModels)->toBeEmpty(
        'These models lack $fillable or $guarded: ' . implode(', ', $unprotectedModels)
    );
});

// ============================================================
// Security headers on error pages
// ============================================================

test('404 pages still return security headers', function () {
    $response = $this->get('/nonexistent-page-' . uniqid());
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});
