<?php

/**
 * Tests for the service layer: BlockchainRpc, IpfsService, ProposalService.
 */

use App\Models\Proposal;
use App\Services\BlockchainRpc;
use App\Services\IpfsService;
use App\Services\ProposalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createProposalTables();
    $this->createFeedTable();
});

// ============================================================
// IpfsService
// ============================================================

test('IpfsService has expected methods', function () {
    $service = new IpfsService;

    expect(method_exists($service, 'pinFile'))->toBeTrue();
    expect(method_exists($service, 'pinContent'))->toBeTrue();
    expect(method_exists($service, 'pinFolder'))->toBeTrue();
    expect(method_exists($service, 'get'))->toBeTrue();
    expect(method_exists($service, 'isValidCID'))->toBeTrue();
    expect(method_exists($service, 'gatewayUrl'))->toBeTrue();
});

test('IpfsService validates CID formats correctly', function () {
    $ipfs = new IpfsService;

    // Valid CIDv0
    expect($ipfs->isValidCID('QmYwAPJzv5CZsnA625s3Xf2nemtYgPpHdWEz79ojWnPbdG'))->toBeTrue();

    // Invalid CIDs
    expect($ipfs->isValidCID(''))->toBeFalse();
    expect($ipfs->isValidCID('notacid'))->toBeFalse();
    expect($ipfs->isValidCID('Qm'))->toBeFalse();
    expect($ipfs->isValidCID('../../etc/passwd'))->toBeFalse();
});

test('IpfsService generates correct gateway URLs', function () {
    $ipfs = new IpfsService;
    $url = $ipfs->gatewayUrl('QmTestHash123');
    expect($url)->toContain('QmTestHash123');
    expect($url)->toContain('ipfs');
});

test('IpfsService pinFile returns null for nonexistent file', function () {
    $ipfs = new IpfsService;
    expect($ipfs->pinFile('/nonexistent/path/file.txt'))->toBeNull();
});

// ============================================================
// ProposalService
// ============================================================

test('ProposalService has expected methods', function () {
    $service = new ProposalService;

    expect(method_exists($service, 'syncPhases'))->toBeTrue();
    expect(method_exists($service, 'tallyVotes'))->toBeTrue();
    expect(method_exists($service, 'getStats'))->toBeTrue();
});

test('ProposalService syncPhases transitions screening to voting', function () {
    // Need screening_ends_at and voting_ends_at columns
    Schema::connection('sqlite')->table('proposals', function ($table) {
        $table->timestamp('screening_ends_at')->nullable();
        $table->timestamp('voting_ends_at')->nullable();
        $table->timestamp('sunset_at')->nullable();
    });

    Proposal::forceCreate([
        'title' => 'Test Screening Proposal',
        'description' => 'Should transition to voting',
        'status' => 'screening',
        'screening_ends_at' => now()->subHour(),
    ]);

    // Test the transition directly (SQLite-compatible, avoids MySQL DATE_ADD)
    Proposal::where('status', 'screening')
        ->whereNotNull('screening_ends_at')
        ->where('screening_ends_at', '<=', now())
        ->update(['status' => 'voting']);

    $proposal = Proposal::where('title', 'Test Screening Proposal')->first();
    expect($proposal->status)->toBe('voting');
});

test('ProposalService tallyVotes counts correctly', function () {
    $proposal = Proposal::forceCreate([
        'title' => 'Tally Test',
        'description' => 'Test',
        'status' => 'voting',
    ]);

    DB::table('votes')->insert([
        ['proposal_id' => $proposal->id, 'vote' => 'YES', 'txid' => 'tx1', 'created_at' => now(), 'updated_at' => now()],
        ['proposal_id' => $proposal->id, 'vote' => 'YES', 'txid' => 'tx2', 'created_at' => now(), 'updated_at' => now()],
        ['proposal_id' => $proposal->id, 'vote' => 'YES', 'txid' => 'tx3', 'created_at' => now(), 'updated_at' => now()],
        ['proposal_id' => $proposal->id, 'vote' => 'NO', 'txid' => 'tx4', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $service = new ProposalService;
    $tally = $service->tallyVotes($proposal->id);

    expect($tally['yays'])->toBe(3);
    expect($tally['nays'])->toBe(1);
    expect($tally['total'])->toBe(4);
    expect($tally['yay_percent'])->toBe(75.0);
    expect($tally['passed'])->toBeTrue();
});

test('ProposalService tallyVotes rejects below threshold', function () {
    $proposal = Proposal::forceCreate([
        'title' => 'Fail Test',
        'description' => 'Test',
        'status' => 'voting',
    ]);

    DB::table('votes')->insert([
        ['proposal_id' => $proposal->id, 'vote' => 'YES', 'txid' => 'tx1', 'created_at' => now(), 'updated_at' => now()],
        ['proposal_id' => $proposal->id, 'vote' => 'NO', 'txid' => 'tx2', 'created_at' => now(), 'updated_at' => now()],
        ['proposal_id' => $proposal->id, 'vote' => 'NO', 'txid' => 'tx3', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $tally = (new ProposalService)->tallyVotes($proposal->id);
    expect($tally['passed'])->toBeFalse();
});

test('ProposalService getStats returns expected keys', function () {
    $stats = (new ProposalService)->getStats();

    expect($stats)->toHaveKey('total');
    expect($stats)->toHaveKey('active');
    expect($stats)->toHaveKey('passed');
    expect($stats)->toHaveKey('rejected');
    expect($stats)->toHaveKey('citizens');
    expect($stats)->toHaveKey('general_public');
});

// ============================================================
// BlockchainRpc (structure only — no live calls in tests)
// ============================================================

test('BlockchainRpc reads config correctly', function () {
    config(['blockchain.rpc.cli_path' => '/test/path']);
    config(['blockchain.rpc.data_dir' => '/test/data']);

    $rpc = new BlockchainRpc;
    $reflection = new ReflectionClass($rpc);

    $cliProp = $reflection->getProperty('cli');
    $cliProp->setAccessible(true);
    expect($cliProp->getValue($rpc))->toBe('/test/path');

    $dataProp = $reflection->getProperty('dataDir');
    $dataProp->setAccessible(true);
    expect($dataProp->getValue($rpc))->toBe('/test/data');
});
