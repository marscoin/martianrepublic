<?php

namespace App\Includes;

use Carbon\Carbon;

/**
 * GovernanceTiers — Single source of truth for the Martian Republic's 4-tier governance system.
 *
 * Signal:         Non-binding temperature check. No CoinShuffle.
 * Operational:    Day-to-day decisions, resource allocation.
 * Legislative:    Significant policy, committees, major treasury.
 * Constitutional: Code changes, governance rules. Git-as-constitution.
 */
class GovernanceTiers
{
    // TEST MODE: Set to true to compress all timers for smoke testing
    // Screening: 3 minutes instead of 3 sols
    // Voting: scales proportionally (Signal 7min, Operational 14min, etc.)
    // MUST be false in production
    const TEST_MODE = true;

    const TEST_MODE_MINUTES_PER_SOL = 1; // 1 minute = 1 sol in test mode

    // Duration of the screening period before voting opens (in sols/days)
    const SCREENING_DURATION_SOLS = 3;

    // Tier definitions: all parameters in one place
    const TIERS = [
        'signal' => [
            'label' => 'Signal',
            'description' => 'Non-binding temperature check — a formal poll',
            'quorum_percent' => 10,   // % of active citizens
            'threshold' => 51,   // % approval to pass
            'duration_sols' => 7,    // voting window
            'timelock_sols' => 0,    // no timelock (non-binding)
            'sunset_sols' => 0,    // non-binding, no sunset
            'quiet_ending' => false,
            'coinshuffle' => false, // simple signed messages
            'binding' => false,
            'color' => '#34d399', // green
        ],
        'operational' => [
            'label' => 'Operational',
            'description' => 'Day-to-day governance, resource allocation, parameter changes',
            'quorum_percent' => 25,
            'threshold' => 60,
            'duration_sols' => 14,
            'timelock_sols' => 3,
            'sunset_sols' => 668,  // 1 Martian year
            'quiet_ending' => false,
            'coinshuffle' => true,
            'binding' => true,
            'color' => '#00e4ff', // cyan
        ],
        'legislative' => [
            'label' => 'Legislative',
            'description' => 'Significant policy, committees, major treasury decisions',
            'quorum_percent' => 40,
            'threshold' => 66,
            'duration_sols' => 30,
            'timelock_sols' => 7,
            'sunset_sols' => 2672, // ~4 Earth years
            'quiet_ending' => true,
            'coinshuffle' => true,
            'binding' => true,
            'color' => '#f59e0b', // amber
        ],
        'constitutional' => [
            'label' => 'Constitutional',
            'description' => 'Code changes, governance rules, citizenship parameters',
            'quorum_percent' => 50,
            'threshold' => 75,
            'duration_sols' => 60,
            'timelock_sols' => 30,
            'sunset_sols' => 0,    // never expires
            'quiet_ending' => true,
            'coinshuffle' => true,
            'binding' => true,
            'color' => '#c84125', // mars red
        ],
    ];

    /**
     * Get tier config or null if invalid
     */
    public static function get(string $tier): ?array
    {
        return self::TIERS[$tier] ?? null;
    }

    /**
     * Map old category names to new tier names
     */
    public static function categoryToTier(string $category): string
    {
        return match ($category) {
            'poll' => 'signal',
            'regulation' => 'operational',
            'statute', 'law' => 'legislative',
            'amendment' => 'constitutional',
            'signal', 'operational', 'legislative', 'constitutional' => $category,
            default => 'signal',
        };
    }

    /**
     * Calculate all lifecycle timestamps for a proposal based on its tier.
     * Returns [screening_ends_at, voting_ends_at, timelock_ends_at, sunset_at]
     */
    public static function calculateTimestamps(string $tier, ?\DateTimeInterface $minedAt = null): array
    {
        $config = self::get($tier);
        if (! $config) {
            $config = self::TIERS['signal'];
        }

        // If not yet mined/notarized, timestamps start from now
        $start = $minedAt ? Carbon::parse($minedAt) : now();

        if (self::TEST_MODE) {
            // Test mode: minutes instead of days
            $m = self::TEST_MODE_MINUTES_PER_SOL;
            $screeningEnds = $start->copy()->addMinutes(self::SCREENING_DURATION_SOLS * $m);
            $votingEnds = $screeningEnds->copy()->addMinutes($config['duration_sols'] * $m);

            $timelockEnds = null;
            if ($config['timelock_sols'] > 0) {
                $timelockEnds = $votingEnds->copy()->addMinutes($config['timelock_sols'] * $m);
            }

            $sunsetAt = null;
            if ($config['sunset_sols'] > 0) {
                $baseDate = $timelockEnds ?? $votingEnds;
                $sunsetAt = $baseDate->copy()->addMinutes($config['sunset_sols'] * $m);
            }
        } else {
            // Production: days (sols)
            $screeningEnds = $start->copy()->addDays(self::SCREENING_DURATION_SOLS);
            $votingEnds = $screeningEnds->copy()->addDays($config['duration_sols']);

            $timelockEnds = null;
            if ($config['timelock_sols'] > 0) {
                $timelockEnds = $votingEnds->copy()->addDays($config['timelock_sols']);
            }

            $sunsetAt = null;
            if ($config['sunset_sols'] > 0) {
                $baseDate = $timelockEnds ?? $votingEnds;
                $sunsetAt = $baseDate->copy()->addDays($config['sunset_sols']);
            }
        }

        return [
            'screening_ends_at' => $screeningEnds,
            'voting_ends_at' => $votingEnds,
            'timelock_ends_at' => $timelockEnds,
            'sunset_at' => $sunsetAt,
        ];
    }

    /**
     * Determine the current lifecycle phase of a proposal.
     * Returns: screening, voting, timelock, active, expired, sunset
     */
    public static function currentPhase(object $proposal): string
    {
        $now = now();

        // Check explicit terminal states first
        if (in_array($proposal->status, ['passed', 'rejected', 'closed', 'withdrawn', 'expired', 'sunset'])) {
            return $proposal->status;
        }

        // Screening phase
        if ($proposal->screening_ends_at && $now->lt(Carbon::parse($proposal->screening_ends_at))) {
            return 'screening';
        }

        // Voting phase
        if ($proposal->voting_ends_at && $now->lt(Carbon::parse($proposal->voting_ends_at))) {
            return 'voting';
        }

        // Timelock phase
        if ($proposal->timelock_ends_at && $now->lt(Carbon::parse($proposal->timelock_ends_at))) {
            return 'timelock';
        }

        // Active (enacted, not yet sunset)
        if ($proposal->enacted_at) {
            if ($proposal->sunset_at && $now->gte(Carbon::parse($proposal->sunset_at))) {
                return 'sunset';
            }

            return 'active';
        }

        // Fallback: if voting ended but no result set, it's expired
        if ($proposal->voting_ends_at && $now->gte(Carbon::parse($proposal->voting_ends_at))) {
            return 'expired';
        }

        return $proposal->status ?? 'screening';
    }

    /**
     * Get tier order (for challenge reclassification — upward only)
     */
    public static function tierOrder(string $tier): int
    {
        return match ($tier) {
            'signal' => 1,
            'operational' => 2,
            'legislative' => 3,
            'constitutional' => 4,
            default => 0,
        };
    }

    /**
     * Check if reclassification is valid (upward only)
     */
    public static function isValidReclassification(string $from, string $to): bool
    {
        return self::tierOrder($to) > self::tierOrder($from);
    }
}
