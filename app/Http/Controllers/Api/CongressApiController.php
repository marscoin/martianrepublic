<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Includes\GovernanceTiers;
use App\Models\Proposal;
use App\Models\Vote;
use App\Services\ProposalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CongressApiController extends Controller
{
    /**
     * List proposals with vote tallies, filterable by status and tier.
     *
     * GET /api/congress/proposals?status=voting&tier=signal&page=1&per_page=20
     */
    public function index(Request $request): JsonResponse
    {
        // Sync phases before listing
        app(ProposalService::class)->syncPhases();

        $query = DB::table('proposals as p')
            ->leftJoinSub(
                'SELECT proposal_id, COUNT(*) AS yays FROM votes WHERE vote = "Y" GROUP BY proposal_id',
                'yay_votes',
                fn ($join) => $join->on('p.id', '=', 'yay_votes.proposal_id')
            )
            ->leftJoinSub(
                'SELECT proposal_id, COUNT(*) AS nays FROM votes WHERE vote = "N" GROUP BY proposal_id',
                'nay_votes',
                fn ($join) => $join->on('p.id', '=', 'nay_votes.proposal_id')
            )
            ->leftJoinSub(
                'SELECT thread_id, COUNT(*) AS post_count FROM forum_posts GROUP BY thread_id',
                'fp',
                fn ($join) => $join->on('p.discussion', '=', 'fp.thread_id')
            )
            ->selectRaw('
                p.id, p.title, p.tier, p.status, p.author, p.active,
                p.created_at, p.updated_at, p.amended_at,
                p.screening_ends_at, p.voting_ends_at, p.timelock_ends_at, p.enacted_at, p.sunset_at,
                p.voting_extended, p.txid, p.ipfs_hash, p.discussion,
                COALESCE(yay_votes.yays, 0) as yays,
                COALESCE(nay_votes.nays, 0) as nays,
                COALESCE(yay_votes.yays, 0) + COALESCE(nay_votes.nays, 0) as total_votes,
                COALESCE(100 * yay_votes.yays / NULLIF(yay_votes.yays + nay_votes.nays, 0), 0) as yay_percent,
                COALESCE(100 * nay_votes.nays / NULLIF(yay_votes.yays + nay_votes.nays, 0), 0) as nay_percent,
                COALESCE(fp.post_count, 0) as post_count
            ');

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('p.status', $status);
        }

        // Filter by tier
        if ($tier = $request->input('tier')) {
            $query->where('p.tier', $tier);
        }

        // Filter active only
        if ($request->boolean('active')) {
            $query->where('p.active', 1);
        }

        $perPage = min((int) $request->input('per_page', 20), 50);

        $proposals = $query
            ->groupBy('p.id', 'yay_votes.yays', 'nay_votes.nays', 'fp.post_count')
            ->orderBy('p.id', 'desc')
            ->paginate($perPage);

        // Enrich with tier config and lifecycle phase
        $proposals->getCollection()->transform(function ($proposal) {
            $tierConfig = GovernanceTiers::get($proposal->tier ?? 'signal');
            $proposal->tier_label = $tierConfig['label'];
            $proposal->tier_color = $tierConfig['color'];
            $proposal->threshold = $tierConfig['threshold'];
            $proposal->phase = GovernanceTiers::currentPhase($proposal);

            return $proposal;
        });

        return response()->json($proposals);
    }

    /**
     * Get a single proposal with full detail, vote tally, and tier config.
     *
     * GET /api/congress/proposals/{id}
     */
    public function show(int $id): JsonResponse
    {
        app(ProposalService::class)->syncPhases();

        $proposal = DB::table('proposals as p')
            ->leftJoinSub(
                'SELECT proposal_id, COUNT(*) AS yays FROM votes WHERE vote = "Y" GROUP BY proposal_id',
                'yay_votes',
                fn ($join) => $join->on('p.id', '=', 'yay_votes.proposal_id')
            )
            ->leftJoinSub(
                'SELECT proposal_id, COUNT(*) AS nays FROM votes WHERE vote = "N" GROUP BY proposal_id',
                'nay_votes',
                fn ($join) => $join->on('p.id', '=', 'nay_votes.proposal_id')
            )
            ->leftJoinSub(
                'SELECT thread_id, COUNT(*) AS post_count FROM forum_posts GROUP BY thread_id',
                'fp',
                fn ($join) => $join->on('p.discussion', '=', 'fp.thread_id')
            )
            ->selectRaw('
                p.*,
                COALESCE(yay_votes.yays, 0) as yays,
                COALESCE(nay_votes.nays, 0) as nays,
                COALESCE(yay_votes.yays, 0) + COALESCE(nay_votes.nays, 0) as total_votes,
                COALESCE(100 * yay_votes.yays / NULLIF(yay_votes.yays + nay_votes.nays, 0), 0) as yay_percent,
                COALESCE(100 * nay_votes.nays / NULLIF(yay_votes.yays + nay_votes.nays, 0), 0) as nay_percent,
                COALESCE(fp.post_count, 0) as post_count
            ')
            ->where('p.id', $id)
            ->first();

        if (! $proposal) {
            return response()->json(['message' => 'Proposal not found.'], 404);
        }

        $tierConfig = GovernanceTiers::get($proposal->tier ?? 'signal');
        $proposal->tier_config = $tierConfig;
        $proposal->phase = GovernanceTiers::currentPhase($proposal);

        // Include any open tier challenges
        $challenges = DB::table('proposal_challenges')
            ->where('proposal_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Include proposer info
        $proposer = DB::table('citizen')
            ->where('userid', $proposal->user_id)
            ->select('firstname', 'lastname', 'displayname', 'avatar_link')
            ->first();

        return response()->json([
            'proposal' => $proposal,
            'challenges' => $challenges,
            'proposer' => $proposer,
        ]);
    }

    /**
     * Get vote breakdown for a proposal.
     *
     * GET /api/congress/proposals/{id}/votes
     */
    public function votes(int $id): JsonResponse
    {
        $proposal = Proposal::find($id);
        if (! $proposal) {
            return response()->json(['message' => 'Proposal not found.'], 404);
        }

        $yays = Vote::where('proposal_id', $id)->where('vote', 'Y')->count();
        $nays = Vote::where('proposal_id', $id)->where('vote', 'N')->count();
        $totalVotes = $yays + $nays;
        $yayPercent = $totalVotes > 0 ? round(($yays / $totalVotes) * 100, 2) : 0;
        $nayPercent = $totalVotes > 0 ? round(($nays / $totalVotes) * 100, 2) : 0;

        $tierConfig = GovernanceTiers::get($proposal->tier ?? 'signal');
        $threshold = $tierConfig['threshold'];
        $passed = $yayPercent >= $threshold && $totalVotes > 0;

        return response()->json([
            'proposal_id' => $id,
            'yays' => $yays,
            'nays' => $nays,
            'total_votes' => $totalVotes,
            'yay_percent' => $yayPercent,
            'nay_percent' => $nayPercent,
            'threshold' => $threshold,
            'passing' => $passed,
        ]);
    }

    /**
     * Governance dashboard stats.
     *
     * GET /api/congress/stats
     */
    public function stats(): JsonResponse
    {
        $proposalCount = Proposal::count();
        $citizenCount = DB::table('feed')->where('tag', 'CT')->distinct('userid')->count('userid');
        $publicCount = DB::table('feed')->where('tag', 'GP')->distinct('userid')->count('userid');

        $activeCount = Proposal::where('active', 1)
            ->whereIn('status', ['screening', 'challenged', 'voting', 'voting_extended'])
            ->count();

        $passedCount = Proposal::where('status', 'passed')->count();
        $rejectedCount = Proposal::where('status', 'rejected')->count();

        // Block height from explorer
        $blockHeight = '---';
        try {
            $resp = \Illuminate\Support\Facades\Http::timeout(5)
                ->get(config('blockchain.explorer.primary_url') . '/api/status?q=getInfo');
            if ($resp->successful()) {
                $blockHeight = $resp->json()['info']['blocks'] ?? '---';
            }
        } catch (\Exception $e) {
            // Explorer unavailable
        }

        // Tier summary
        $tiers = [];
        foreach (GovernanceTiers::TIERS as $key => $tier) {
            $tiers[$key] = [
                'label' => $tier['label'],
                'color' => $tier['color'],
                'threshold' => $tier['threshold'],
                'binding' => $tier['binding'],
            ];
        }

        return response()->json([
            'proposals' => $proposalCount,
            'active_proposals' => $activeCount,
            'passed' => $passedCount,
            'rejected' => $rejectedCount,
            'citizens' => $citizenCount,
            'general_public' => $publicCount,
            'block_height' => $blockHeight,
            'tiers' => $tiers,
        ]);
    }
}
