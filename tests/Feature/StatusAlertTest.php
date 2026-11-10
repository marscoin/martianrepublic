<?php

/**
 * When a failing subsystem is worth an email, and how the status page judges
 * the chain tracker.
 */

use App\Http\Controllers\StatusController;
use App\Services\StatusAlertPolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class);

const T0 = 1794300000;

test('a blip is not reported, an outage is, once', function () {
    $r = StatusAlertPolicy::evaluate(['Node' => false, 'Pebas' => true], [], T0);
    expect($r['down'])->toBe([]);

    $r = StatusAlertPolicy::evaluate(['Node' => false, 'Pebas' => true], $r['state'], T0 + 300);
    expect($r['down'])->toBe([]);

    $r = StatusAlertPolicy::evaluate(['Node' => false, 'Pebas' => true], $r['state'], T0 + 600);
    expect($r['down'])->toBe(['Node']);

    $r = StatusAlertPolicy::evaluate(['Node' => false, 'Pebas' => true], $r['state'], T0 + 900);
    expect($r['down'])->toBe([]);
});

test('a short failure that clears is never mentioned', function () {
    $r = StatusAlertPolicy::evaluate(['Node' => false], [], T0);
    $r = StatusAlertPolicy::evaluate(['Node' => true], $r['state'], T0 + 300);
    expect($r['down'])->toBe([]);
    expect($r['recovered'])->toBe([]);
    // A later failure starts its own clock.
    $r = StatusAlertPolicy::evaluate(['Node' => false], $r['state'], T0 + 5000);
    $r = StatusAlertPolicy::evaluate(['Node' => false], $r['state'], T0 + 5300);
    expect($r['down'])->toBe([]);
});

test('a long outage is repeated every six hours and its recovery is announced once', function () {
    $state = [];
    $reported = [];
    for ($t = 0; $t <= 13 * 3600; $t += 300) {
        $r = StatusAlertPolicy::evaluate(['Tracker' => false], $state, T0 + $t);
        $state = $r['state'];
        if ($r['down']) {
            $reported[] = $t;
        }
    }
    expect($reported)->toBe([600, 600 + 21600, 600 + 43200]);

    $r = StatusAlertPolicy::evaluate(['Tracker' => true], $state, T0 + 14 * 3600);
    expect($r['recovered'])->toBe(['Tracker']);
    $r = StatusAlertPolicy::evaluate(['Tracker' => true], $r['state'], T0 + 14 * 3600 + 300);
    expect($r['recovered'])->toBe([]);
});

test('several failures are reported together as each crosses the threshold', function () {
    $r = StatusAlertPolicy::evaluate(['A' => false, 'B' => true, 'C' => false], [], T0);
    $r = StatusAlertPolicy::evaluate(['A' => false, 'B' => false, 'C' => false], $r['state'], T0 + 600);
    expect($r['down'])->toBe(['A', 'C']);
    $r = StatusAlertPolicy::evaluate(['A' => false, 'B' => false, 'C' => true], $r['state'], T0 + 1200);
    expect($r['down'])->toBe(['B']);
    expect($r['recovered'])->toBe(['C']);
});

test('the tracker is judged by how far behind the chain it is, not by how long since the last block', function () {
    $schema = Schema::connection('sqlite');
    $schema->dropIfExists('feed_log');
    $schema->create('feed_log', function ($table) {
        $table->integer('block');
        $table->string('hash')->nullable();
        $table->timestamp('mined')->nullable();
        $table->timestamp('processed_at')->nullable();
    });
    // The last block was processed two hours ago: Marscoin simply had none since.
    DB::table('feed_log')->insert(['block' => 3598363, 'processed_at' => now('America/New_York')->subHours(2)->format('Y-m-d H:i:s')]);

    $controller = new StatusController;
    $current = new ReflectionMethod($controller, 'trackerIsCurrent');
    $current->setAccessible(true);

    expect($current->invoke($controller, 3598363))->toBeTrue();
    expect($current->invoke($controller, 3598366))->toBeTrue();
    // Really behind: the node is far ahead of what was processed.
    expect($current->invoke($controller, 3598367))->toBeFalse();
    expect($current->invoke($controller, 3700000))->toBeFalse();
    // Node height unknown: fall back to the time-based guess.
    expect($current->invoke($controller, null))->toBeFalse();
    DB::table('feed_log')->insert(['block' => 3598364, 'processed_at' => now('America/New_York')->subMinutes(2)->format('Y-m-d H:i:s')]);
    expect($current->invoke($controller, 0))->toBeTrue();
});
