<?php
namespace App\Http\Controllers\Congress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Feed;
use App\Models\IPFSRoot;
use App\Models\User;
use App\Models\HDWallet;
use App\Models\CivicWallet;
use App\Models\Proposals;
use App\Models\Vote;
use App\Models\Posts;
use App\Models\Ballots;
use App\Notifications\BallotReadyNotification;
use Illuminate\Support\Facades\View;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CongressController extends Controller
{

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public function __construct()
	{
	}

	/**
	 * Sync proposal lifecycle phases based on timestamps.
	 * Called before displaying proposals to ensure states are current.
	 */
	private function syncProposalPhases(): void
	{
		$now = now();

		// Proposals in screening that should move to voting
		Proposals::where('status', 'screening')
			->whereNotNull('screening_ends_at')
			->where('screening_ends_at', '<=', $now)
			->update(['status' => 'voting']);

		// Proposals in voting that have expired (voting period ended)
		// Note: vote tallying and pass/fail determination happens separately
		Proposals::where('status', 'voting')
			->whereNotNull('voting_ends_at')
			->where('voting_ends_at', '<=', $now)
			->update(['active' => 0]);

		// Legacy: also handle old-style expiration for pre-tier proposals
		Proposals::where(DB::raw('DATE_ADD(mined, INTERVAL duration DAY)'), '<', $now)
			->whereNull('voting_ends_at')
			->update(['active' => 0]);

		// Active proposals that have reached sunset
		Proposals::where('status', 'active')
			->whereNotNull('sunset_at')
			->where('sunset_at', '<=', $now)
			->update(['status' => 'sunset', 'active' => 0]);
	}


	//Get all inventory data and display table
	//
    public function showAll()
	{
		$view = View::make('congress.dashboard');

		// Live stats for the dashboard
		$view->proposalCount = Proposals::count();
		$view->citizenCount = DB::table('feed')->where('tag', 'CT')->distinct('userid')->count('userid');
		$view->publicCount = DB::table('feed')->where('tag', 'GP')->distinct('userid')->count('userid');
		try {
			$view->blockHeight = @file_get_contents('http://localhost:3001/api/mars/balance?address=MDCURC61G7A5jNRjnDq42XB1RvU51y4Ftx') ? json_decode(file_get_contents('https://explore.marscoin.org/api/status?q=getInfo'))->info->blocks ?? '---' : '---';
		} catch (\Exception $e) {
			$view->blockHeight = '---';
		}

		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->civic_wallet_open;
		} else {
			$view->isCitizen = false;
			$view->isGP = false;
			$view->wallet_open = false;
		}

		return $view;

		
	}

	// Show Voting Page
	public function showVoting()
	{
		if (Auth::check()) {
			$startTime = microtime(true);
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();

			$proposals = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->groupBy('proposals.id')
			->orderBy('proposals.id', 'desc')
			->get();

			$active = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->where('proposals.active', '=', 1)
			->whereIn('status', ['submitted','voting','screening','challenged'])
			->groupBy('proposals.id')
			->orderBy('proposals.id', 'desc')
			->get();

			$passed = DB::table('proposals as p')
				->leftJoinSub(
					'SELECT proposal_id, COUNT(*) AS yays FROM votes WHERE vote = "Y" GROUP BY proposal_id', 
					'yay_votes', 
					function($join) {
						$join->on('p.id', '=', 'yay_votes.proposal_id');
					}
				)
				->leftJoinSub(
					'SELECT proposal_id, COUNT(*) AS nays FROM votes WHERE vote = "N" GROUP BY proposal_id', 
					'nay_votes', 
					function($join) {
						$join->on('p.id', '=', 'nay_votes.proposal_id');
					}
				)
				->leftJoinSub(
					'SELECT thread_id, COUNT(*) AS post_count FROM forum_posts GROUP BY thread_id', 
					'fp', 
					function($join) {
						$join->on('p.discussion', '=', 'fp.thread_id');
					}
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
				->where('p.status', '=', 'passed')
				->groupBy('p.id', 'yay_votes.yays', 'nay_votes.nays', 'fp.post_count')
				->orderBy('p.id', 'desc')
				->get();


			$rejected = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->where('proposals.status', '=', 'rejected')
			->groupBy('proposals.id')
			->orderBy('proposals.id', 'desc')
			->get();

			$closed = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->where('proposals.status', '=', 'closed')
			->where('proposals.active', '=', 0)
			->groupBy('proposals.id')
			->orderBy('proposals.id', 'desc')
			->get();


			$expired = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->whereNotIn('status', ['rejected','passed', 'closed'])
			->groupBy('proposals.id')
			->orderBy('proposals.id', 'desc')
			->get();
			
			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time: {$executionTime} seconds");
			
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			// Sync all proposal lifecycle phases
			$this->syncProposalPhases();

			if(!$profile->citizen){
				$view = View::make('congress.noteligableyet');
			}else{
				$view = View::make('congress.voting');
			}

			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time4: {$executionTime} seconds");

			$view->proposals = $proposals;
			$view->active = $active;
			$view->expired = $expired;
			$view->closed = $closed;
			$view->passed = $passed;
			$view->rejected = $rejected;
			$view->fullname = Auth::user()->fullname;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->civic_wallet_open;
			$view->public_address = $civic_wallet->public_addr;
			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time4a: {$executionTime} seconds");


			return $view;
		}else{
            return redirect('/login');
        }
	}

	public function newProposal()
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$proposals = DB::table('proposals')->select('proposals.*')->where('active', '=', '1')->get();
			$oldproposals = DB::table('proposals')->select('proposals.*')->where('active', '=', '0')->get();
			
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$this->syncProposalPhases();

			if(!$profile->citizen){
				$view = View::make('congress.noteligableyet');
			}else{
				$view = View::make('congress.newproposal');
			}

			$view->proposals = $proposals;
			$view->oldproposals = $oldproposals;
			$view->fullname = Auth::user()->fullname;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->civic_wallet_open;
			$view->public_address = $civic_wallet->public_addr;

			return $view;
		}else{
            return redirect('/login');
        }
	}



	public function proposal($id)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$proposal = DB::table('proposals as p')
			->leftJoinSub(
				'SELECT proposal_id, COUNT(*) AS yays FROM votes WHERE vote = "Y" GROUP BY proposal_id', 
				'yay_votes', 
				function($join) {
					$join->on('p.id', '=', 'yay_votes.proposal_id');
				}
			)
			->leftJoinSub(
				'SELECT proposal_id, COUNT(*) AS nays FROM votes WHERE vote = "N" GROUP BY proposal_id', 
				'nay_votes', 
				function($join) {
					$join->on('p.id', '=', 'nay_votes.proposal_id');
				}
			)
			->leftJoinSub(
				'SELECT thread_id, COUNT(*) AS post_count FROM forum_posts GROUP BY thread_id', 
				'fp', 
				function($join) {
					$join->on('p.discussion', '=', 'fp.thread_id');
				}
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
			->where('p.id', '=', $id)  // Here you filter by proposal ID
			->first();
		
			
			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}

			if(!$profile->citizen){
				$view = View::make('congress.noteligableyet');
			}else{
				$view = View::make('congress.proposal');
			}
			$view->activities = DB::table('proposals')
			->join('feed', function ($join) {
				$join->on('proposals.txid', '=', 'feed.txid')
					 ->where('feed.tag', '=', 'PR');
			})
			->join('citizen', 'proposals.user_id', '=', 'citizen.userid')
			->where('proposals.id', $id)
			->select('proposals.*', 'feed.*', 'citizen.firstname', 'citizen.lastname', 'citizen.displayname', 'citizen.shortbio', 'citizen.avatar_link')
			->take(10)
			->get();

			$posts = Posts::with('allRepliesWithCitizen', 'citizen')
			->where('thread_id', $proposal->discussion)
			->whereNull('post_id') // Top-level posts
			->get();

			// print_r($posts);
			// die;

			// dd($posts);

			$view->posts = $posts;
			$view->proposal = $proposal;
			$view->fullname = Auth::user()->fullname;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->civic_wallet_open;
			$view->public_address = $civic_wallet->public_addr;

			// Compute current lifecycle phase
			$view->lifecyclePhase = \App\Includes\GovernanceTiers::currentPhase($proposal);
			$view->tierConfig = \App\Includes\GovernanceTiers::get($proposal->tier ?? 'signal');
			$view->isProposer = ($proposal->user_id === $uid);

			return $view;
		}else{
            return redirect('/login');
        }
	}




	public function acquireBallot($propid)
	{
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
			$proposal = Proposals::where('id', '=', $propid)->first();

			$view = View::make('congress.ballot');
			
			$view->proposal = $proposal;
			$view->fullname = Auth::user()->fullname;
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $civic_wallet->id;
			$view->propid = $propid;
			$view->random_bytes = bin2hex(random_bytes(16));
			$view->public_address = $civic_wallet->public_addr;
			$view->slug = AppHelper::createSlug($proposal->discussion, $proposal->title);

			return $view;
		}else{
            return redirect('/login');
        }
	}


	public function breakdown(Request $request)
    {
		$propid = $request->input('proposalId');
		Log::info("Proposal: " . $propid);

        $yays = Vote::where('proposal_id', $propid)
                    ->where('vote', 'Y')
                    ->count();
        
        $nays = Vote::where('proposal_id', $propid)
                    ->where('vote', 'N')
                    ->count();

        $totalVotes = $yays + $nays;
		Log::info("Total: " . $totalVotes);
        $yayPercent = $totalVotes > 0 ? round(($yays / $totalVotes) * 100, 2) : 0;
        $nayPercent = $totalVotes > 0 ? round(($nays / $totalVotes) * 100, 2) : 0;
        Log::info("Percentage: " . $yayPercent);

		return response()->json(["yayPercent" => $yayPercent, "nayPercent" => $nayPercent, "totalVotes" => $totalVotes]);
    }


	/**
	 * Get the amendment diff and history for a proposal
	 */
	public function proposalDiff(Request $request)
	{
		$proposalId = $request->input('proposalId');

		try {
			$repo = new \App\Includes\LegislationRepo();

			$diff = $repo->getAmendmentDiff((int) $proposalId);
			$history = $repo->getProposalHistory((int) $proposalId);
			$isAmended = $repo->isAmended((int) $proposalId);
			$amendmentCount = $repo->getAmendmentCount((int) $proposalId);

			return response()->json([
				'success' => true,
				'isAmended' => $isAmended,
				'amendmentCount' => $amendmentCount,
				'diff' => $diff,
				'history' => $history,
			]);
		} catch (\Exception $e) {
			Log::error("LegislationRepo error: " . $e->getMessage());
			return response()->json([
				'success' => false,
				'isAmended' => false,
				'diff' => null,
				'history' => [],
			]);
		}
	}


	/**
	 * Withdraw a proposal during screening (proposer only)
	 */
	public function withdrawProposal(Request $request)
	{
		$proposalId = $request->input('proposalId');
		$uid = Auth::user()->id;

		$proposal = Proposals::find($proposalId);
		if (!$proposal) {
			return response()->json(['success' => false, 'error' => 'Proposal not found'], 404);
		}

		// Must be the proposer
		if ($proposal->user_id !== $uid) {
			return response()->json(['success' => false, 'error' => 'Only the proposer can withdraw'], 403);
		}

		// Must be in screening phase
		$phase = \App\Includes\GovernanceTiers::currentPhase($proposal);
		if ($phase !== 'screening') {
			return response()->json(['success' => false, 'error' => 'Can only withdraw during screening period'], 400);
		}

		$proposal->status = 'withdrawn';
		$proposal->active = 0;
		$proposal->save();

		Log::info("Proposal MR-{$proposalId} withdrawn by user {$uid}");
		return response()->json(['success' => true, 'message' => 'Proposal withdrawn']);
	}


	/**
	 * Amend a proposal during screening (proposer only, max 1 amendment)
	 */
	public function amendProposal(Request $request)
	{
		$proposalId = $request->input('proposalId');
		$newDescription = $request->input('description');
		$newTitle = $request->input('title');
		$uid = Auth::user()->id;

		$proposal = Proposals::find($proposalId);
		if (!$proposal) {
			return response()->json(['success' => false, 'error' => 'Proposal not found'], 404);
		}

		if ($proposal->user_id !== $uid) {
			return response()->json(['success' => false, 'error' => 'Only the proposer can amend'], 403);
		}

		$phase = \App\Includes\GovernanceTiers::currentPhase($proposal);
		if ($phase !== 'screening') {
			return response()->json(['success' => false, 'error' => 'Can only amend during screening'], 400);
		}

		if ($proposal->amended_at) {
			return response()->json(['success' => false, 'error' => 'Already amended once (maximum 1 amendment allowed)'], 400);
		}

		// Store original
		$proposal->original_description = $proposal->description;
		$proposal->original_ipfs_hash = $proposal->ipfs_hash;

		// Update
		$proposal->title = $newTitle ?: $proposal->title;
		$proposal->description = $newDescription ?: $proposal->description;
		$proposal->amended_at = now();

		// Reset screening period (new 3-sol window)
		$timestamps = \App\Includes\GovernanceTiers::calculateTimestamps($proposal->tier);
		$proposal->screening_ends_at = $timestamps['screening_ends_at'];
		$proposal->voting_ends_at = $timestamps['voting_ends_at'];
		$proposal->timelock_ends_at = $timestamps['timelock_ends_at'];
		$proposal->sunset_at = $timestamps['sunset_at'];

		$proposal->save();

		// Commit amendment to LegislationRepo
		try {
			$repo = new \App\Includes\LegislationRepo();
			$gitHash = $repo->amendProposal(
				$proposal->id,
				$proposal->title,
				$proposal->description,
				$proposal->author,
				$proposal->tier,
				[],
				$request->input('note', 'Amended during screening')
			);
			$proposal->git_hash = $gitHash;
			$proposal->save();
		} catch (\Exception $e) {
			Log::warning('LegislationRepo amend failed: ' . $e->getMessage());
		}

		Log::info("Proposal MR-{$proposalId} amended by user {$uid}");
		return response()->json(['success' => true, 'message' => 'Proposal amended. Screening period reset.']);
	}


	/**
	 * Challenge a proposal's tier classification during screening
	 */
	public function challengeTier(Request $request)
	{
		$proposalId = $request->input('proposalId');
		$proposedTier = $request->input('proposedTier');
		$reason = $request->input('reason', '');
		$uid = Auth::user()->id;

		$proposal = Proposals::find($proposalId);
		if (!$proposal) {
			return response()->json(['success' => false, 'error' => 'Proposal not found'], 404);
		}

		// Can't challenge your own proposal
		if ($proposal->user_id === $uid) {
			return response()->json(['success' => false, 'error' => 'Cannot challenge your own proposal'], 400);
		}

		$phase = \App\Includes\GovernanceTiers::currentPhase($proposal);
		if ($phase !== 'screening') {
			return response()->json(['success' => false, 'error' => 'Can only challenge during screening'], 400);
		}

		// Validate reclassification (upward only)
		if (!\App\Includes\GovernanceTiers::isValidReclassification($proposal->tier, $proposedTier)) {
			return response()->json(['success' => false, 'error' => 'Reclassification must be to a higher tier'], 400);
		}

		// Check no existing open challenge
		$existing = DB::table('proposal_challenges')
			->where('proposal_id', $proposalId)
			->where('status', 'open')
			->first();
		if ($existing) {
			return response()->json(['success' => false, 'error' => 'An open challenge already exists'], 400);
		}

		DB::table('proposal_challenges')->insert([
			'proposal_id' => $proposalId,
			'challenger_user_id' => $uid,
			'current_tier' => $proposal->tier,
			'proposed_tier' => $proposedTier,
			'reason' => $reason,
			'status' => 'open',
			'expires_at' => now()->addHours(48),
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$proposal->status = 'challenged';
		$proposal->save();

		Log::info("Proposal MR-{$proposalId} tier challenged: {$proposal->tier} -> {$proposedTier}");
		return response()->json(['success' => true, 'message' => "Challenge submitted: reclassify to {$proposedTier}"]);
	}


	/**
	 * Store encrypted ballot key backup (client-side encrypted)
	 */
	public function backupBallotKey(Request $request)
	{
		if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

		$request->validate([
			'proposal_id' => 'required|integer',
			'encrypted_key' => 'required|string',
			'encryption_iv' => 'required|string|max:64',
			'hidden_target' => 'required|string|max:64',
		]);

		$uid = Auth::user()->id;
		$proposalId = $request->input('proposal_id');

		$ballot = Ballots::updateOrCreate(
			['userid' => $uid, 'proposalid' => $proposalId],
			[
				'encrypted_key' => $request->input('encrypted_key'),
				'encryption_iv' => $request->input('encryption_iv'),
				'hidden_target' => $request->input('hidden_target'),
				'status' => 'in_shuffle',
			]
		);

		Log::info("Ballot key backed up for user {$uid}, proposal {$proposalId}");
		return response()->json(['success' => true, 'ballot_id' => $ballot->id]);
	}

	/**
	 * Restore encrypted ballot key (client decrypts with mnemonic)
	 */
	public function restoreBallotKey(Request $request)
	{
		if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

		$proposalId = $request->input('proposal_id');
		$uid = Auth::user()->id;

		$ballot = Ballots::where('userid', $uid)
			->where('proposalid', $proposalId)
			->whereNotNull('encrypted_key')
			->first();

		if (!$ballot) {
			return response()->json(['found' => false]);
		}

		return response()->json([
			'found' => true,
			'encrypted_key' => $ballot->encrypted_key,
			'encryption_iv' => $ballot->encryption_iv,
			'hidden_target' => $ballot->hidden_target,
			'status' => $ballot->status,
			'ballot_txid' => $ballot->ballot_txid,
			'confirmed' => $ballot->confirmed_at !== null,
		]);
	}

	/**
	 * Update ballot with shuffle transaction ID
	 */
	public function updateBallotTx(Request $request)
	{
		if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

		$request->validate([
			'proposal_id' => 'required|integer',
			'ballot_txid' => 'required|string|max:128',
		]);

		$uid = Auth::user()->id;
		$ballot = Ballots::where('userid', $uid)
			->where('proposalid', $request->input('proposal_id'))
			->first();

		if (!$ballot) {
			return response()->json(['error' => 'Ballot not found'], 404);
		}

		$ballot->ballot_txid = $request->input('ballot_txid');
		$ballot->status = 'received';
		$ballot->save();

		return response()->json(['success' => true]);
	}

	/**
	 * Confirm ballot transaction and notify user via email
	 */
	public function confirmBallot(Request $request)
	{
		if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

		$proposalId = $request->input('proposal_id');
		$uid = Auth::user()->id;

		$ballot = Ballots::where('userid', $uid)
			->where('proposalid', $proposalId)
			->whereNotNull('ballot_txid')
			->first();

		if (!$ballot) {
			return response()->json(['confirmed' => false]);
		}

		if ($ballot->confirmed_at) {
			return response()->json(['confirmed' => true, 'already' => true]);
		}

		// Mark as confirmed
		$ballot->confirmed_at = now();
		$ballot->save();

		// Send email notification if not already sent
		if (!$ballot->notified) {
			try {
				$proposal = Proposals::find($proposalId);
				$user = User::find($uid);
				if ($user && $proposal) {
					$user->notify(new BallotReadyNotification(
						$proposalId,
						$proposal->title,
						$ballot->ballot_txid
					));
					$ballot->notified = true;
					$ballot->save();
					Log::info("Ballot ready email sent to user {$uid} for proposal {$proposalId}");
				}
			} catch (\Exception $e) {
				Log::error("Failed to send ballot notification: " . $e->getMessage());
			}
		}

		return response()->json(['confirmed' => true, 'notified' => $ballot->notified]);
	}

	/**
	 * Mark ballot as used (after voting)
	 */
	public function markBallotUsed(Request $request)
	{
		if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

		$proposalId = $request->input('proposal_id');
		$uid = Auth::user()->id;

		Ballots::where('userid', $uid)
			->where('proposalid', $proposalId)
			->update([
				'status' => 'used',
				'encrypted_key' => null,
				'encryption_iv' => null,
			]);

		return response()->json(['success' => true]);
	}

	/**
	 * Get pending ballots for current user (for site-wide banner)
	 */
	public function pendingBallots()
	{
		if (!Auth::check()) return response()->json([]);

		$uid = Auth::user()->id;
		$pending = Ballots::where('userid', $uid)
			->where('status', 'received')
			->whereNotNull('confirmed_at')
			->whereNotNull('encrypted_key')
			->with('proposal:id,title')
			->get(['id', 'proposalid', 'confirmed_at']);

		return response()->json($pending);
	}

}
