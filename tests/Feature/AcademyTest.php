<?php

/**
 * Tests for the Academy section — public learning content.
 */

uses(Tests\TestCase::class)->beforeEach(function () {
    $this->artisan('migrate:fresh', [
        '--path' => [
            'database/migrations/2025_01_26_202544_create_users_table.php',
            'database/migrations/2025_01_26_202544_create_mars_sessions_table.php',
        ]
    ]);
});

test('academy index page loads', function () {
    $response = $this->get('/academy');
    $response->assertStatus(200);
});

test('academy governance-and-voting article loads', function () {
    $response = $this->get('/academy/governance-and-voting');
    $response->assertStatus(200);
});

test('academy coinshuffle-secret-ballots article loads', function () {
    $response = $this->get('/academy/coinshuffle-secret-ballots');
    $response->assertStatus(200);
});

test('academy dynamic-quorum article loads', function () {
    $response = $this->get('/academy/dynamic-quorum');
    $response->assertStatus(200);
});

test('academy history-of-blockchain-governance article loads', function () {
    $response = $this->get('/academy/history-of-blockchain-governance');
    $response->assertStatus(200);
});

test('academy the-public-good article loads', function () {
    $response = $this->get('/academy/the-public-good');
    $response->assertStatus(200);
});

test('academy blockchain-attested-data-streams article loads', function () {
    $response = $this->get('/academy/blockchain-attested-data-streams');
    $response->assertStatus(200);
});

test('academy what-is-a-blockchain article loads', function () {
    $response = $this->get('/academy/what-is-a-blockchain');
    $response->assertStatus(200);
});

test('academy hd-wallets-and-civic-identity article loads', function () {
    $response = $this->get('/academy/hd-wallets-and-civic-identity');
    $response->assertStatus(200);
});

test('academy nonexistent article returns 404', function () {
    $response = $this->get('/academy/this-article-does-not-exist');
    $response->assertStatus(404);
});
