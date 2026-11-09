<?php

namespace App\Services;

use App\Includes\GovernanceTiers;
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

        // Voting closed: decide the result
        Proposal::where('status', 'voting')
            ->whereNotNull('voting_ends_at')
            ->where('voting_ends_at', '<=', $now)
            ->get()
            ->each(fn (Proposal $proposal) => $this->decide($proposal));

        // Legacy expiration (pre-tier proposals)
        Proposal::whereNull('voting_ends_at')
            ->where('active', 1)
            ->whereNotNull('mined')
            ->whereNotNull('duration')
            ->get(['id', 'mined', 'duration'])
            ->filter(fn (Proposal $proposal) => \Illuminate\Support\Carbon::parse($proposal->mined)->addDays((int) $proposal->duration)->lt($now))
            ->each(fn (Proposal $proposal) => Proposal::where('id', $proposal->id)->update(['active' => 0]));

        // In force → sunset
        Proposal::whereIn('status', ['active', 'passed'])
            ->whereNotNull('sunset_at')
            ->where('sunset_at', '<=', $now)
            ->update(['status' => 'sunset', 'active' => 0]);
    }

    /**
     * Record the result of a proposal whose voting window has closed.
     *
     * It passes when enough citizens took part (quorum, counting abstentions)
     * and the share of yes among yes and no votes reaches the tier threshold.
     * A passed proposal takes effect when its timelock ends.
     */
    public function decide(Proposal $proposal): string
    {
        $outcome = $this->tallyVotes($proposal->id);
        $status = $outcome['passed'] ? 'passed' : 'rejected';

        $proposal->status = $status;
        $proposal->active = 0;
        if ($outcome['passed']) {
            $proposal->enacted_at = $proposal->timelock_ends_at ?? $proposal->voting_ends_at;
        }
        $proposal->save();

        Log::info('Proposal decided', ['id' => $proposal->id, 'result' => $status] + $outcome);

        return $status;
    }

    /**
     * Number of citizens entitled to vote: the base for quorum.
     */
    public function citizenCount(): int
    {
        return DB::table('feed')->where('tag', 'CT')->distinct()->count('userid');
    }

    /**
     * Tally votes for a proposal against its tier's quorum and threshold.
     *
     * Percentages are of yes and no votes; abstentions count toward turnout
     * (and so toward quorum) only.
     */
    public function tallyVotes(int $proposalId): array
    {
        $counts = DB::table('votes')
            ->where('proposal_id', $proposalId)
            ->selectRaw('vote, COUNT(*) as n')
            ->groupBy('vote')
            ->pluck('n', 'vote');

        $yays = (int) ($counts['Y'] ?? 0);
        $nays = (int) ($counts['N'] ?? 0);
        $abstains = (int) ($counts['A'] ?? 0);
        $total = $yays + $nays;
        $turnout = $total + $abstains;
        $yayPct = $total > 0 ? round(100 * $yays / $total, 2) : 0;
        $nayPct = $total > 0 ? round(100 * $nays / $total, 2) : 0;

        $tier = GovernanceTiers::get(Proposal::where('id', $proposalId)->value('tier') ?? 'signal') ?? GovernanceTiers::TIERS['signal'];
        $citizens = $this->citizenCount();
        $quorumRequired = max(1, (int) ceil($citizens * $tier['quorum_percent'] / 100));
        $quorumMet = $turnout >= $quorumRequired;

        return [
            'yays' => $yays,
            'nays' => $nays,
            'abstains' => $abstains,
            'total' => $total,
            'turnout' => $turnout,
            'yay_percent' => $yayPct,
            'nay_percent' => $nayPct,
            'threshold' => $tier['threshold'],
            'citizens' => $citizens,
            'quorum_percent' => $tier['quorum_percent'],
            'quorum_required' => $quorumRequired,
            'quorum_met' => $quorumMet,
            'passed' => $quorumMet && $total > 0 && $yayPct >= $tier['threshold'],
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
