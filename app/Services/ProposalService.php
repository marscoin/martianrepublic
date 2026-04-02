<?php

namespace App\Services;

use App\Models\Proposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Proposal lifecycle management — phase transitions, tallying, sunset.
 * Extracted from CongressController to enable reuse by scanner, cron, and tests.
 */
class ProposalService
{
    /**
     * Synchronize proposal phases based on timestamps.
     * Should be called on every Congress page load and by the scheduler.
     */
    public function syncPhases(): void
    {
        $now = now();

        // Screening → voting
        Proposal::where('status', 'screening')
            ->whereNotNull('screening_ends_at')
            ->where('screening_ends_at', '<=', $now)
            ->update(['status' => 'voting']);

        // Voting expired
        Proposal::where('status', 'voting')
            ->whereNotNull('voting_ends_at')
            ->where('voting_ends_at', '<=', $now)
            ->update(['active' => 0]);

        // Legacy expiration (pre-tier proposals)
        Proposal::where(DB::raw('DATE_ADD(mined, INTERVAL duration DAY)'), '<', $now)
            ->whereNull('voting_ends_at')
            ->update(['active' => 0]);

        // Active → sunset
        Proposal::where('status', 'active')
            ->whereNotNull('sunset_at')
            ->where('sunset_at', '<=', $now)
            ->update(['status' => 'sunset', 'active' => 0]);
    }

    /**
     * Tally votes for a proposal.
     * Returns [yays, nays, total, yay_percent, nay_percent, passed].
     */
    public function tallyVotes(int $proposalId): array
    {
        $yays = DB::table('votes')
            ->where('proposal_id', $proposalId)
            ->where('vote', 'YES')
            ->count();

        $nays = DB::table('votes')
            ->where('proposal_id', $proposalId)
            ->where('vote', 'NO')
            ->count();

        $total = $yays + $nays;
        $yayPct = $total > 0 ? round(100 * $yays / $total, 1) : 0;
        $nayPct = $total > 0 ? round(100 * $nays / $total, 1) : 0;

        return [
            'yays' => $yays,
            'nays' => $nays,
            'total' => $total,
            'yay_percent' => $yayPct,
            'nay_percent' => $nayPct,
            'passed' => $yayPct >= 65 && $total > 0,
        ];
    }

    /**
     * Get proposal stats for the dashboard.
     */
    public function getStats(): array
    {
        return [
            'total' => Proposal::count(),
            'active' => Proposal::where('active', 1)->count(),
            'passed' => Proposal::where('status', 'passed')->count(),
            'rejected' => Proposal::where('status', 'rejected')->count(),
            'citizens' => DB::table('feed')->where('tag', 'CT')->distinct('userid')->count('userid'),
            'general_public' => DB::table('feed')->where('tag', 'GP')->distinct('userid')->count('userid'),
        ];
    }
}
