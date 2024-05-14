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


	//Get all inventory data and display table
	//
    protected function showAll()
	{
		
		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = CivicWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}
			$view = View::make('congress.dashboard');
			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->civic_wallet_open;
			return $view;


		}else{
            return redirect('/login');
        }

		
	}

	// Show Voting Page
	protected function showVoting()
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
			->get();

			$active = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->where('proposals.active', '=', 1)
			->whereIn('status', ['submitted','voting'])
			->groupBy('proposals.id')
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
				->get();


			$rejected = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->where('proposals.status', '=', 'rejected')
			->groupBy('proposals.id')
			->get();

			$closed = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->where('proposals.status', '=', 'closed')
			->where('proposals.active', '=', 0)
			->groupBy('proposals.id')
			->get();


			$expired = DB::table('proposals')
			->leftJoin('forum_posts', 'proposals.discussion', '=', 'forum_posts.thread_id')
			->select('proposals.*', DB::raw('COUNT(forum_posts.id) as post_count'))
			->whereNotIn('status', ['rejected','passed', 'closed'])
			->groupBy('proposals.id')
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
			//check if any proposals have expired... if so, archive.
			Proposals::where(DB::raw('DATE_ADD(mined, INTERVAL duration DAY)'), '<', now())
        ->update(['active' => 0]);

			$endTime = microtime(true);
    		$executionTime = $endTime - $startTime;
    		Log::info("Execution time2: {$executionTime} seconds");

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

	protected function newProposal()
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
			//check if any proposals have expired... if so, archive.
			Proposals::where(DB::raw('DATE_ADD(mined, INTERVAL duration DAY)'), '<', now())
        ->update(['active' => 0]);

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



	protected function proposal($id)
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

			return $view;
		}else{
            return redirect('/login');
        }
	}




	protected function acquireBallot($propid)
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

			return $view;
		}else{
            return redirect('/login');
        }
	}


	protected function breakdown(Request $request)
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


}
