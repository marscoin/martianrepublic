<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Auth;
use App\Models\CivicWallet;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Feed;

class CitizenStats extends Component
{

    public $userId;
    public $userAddress; 

    public function mount()
    {
        $uid = Auth::user()->id;
		$civic = CivicWallet::where('user_id', '=', $uid)->first();
        $this->userId = $uid;
        $this->userAddress = $civic->public_addr;
    }

    public function render()
    {
        $endorsedIssuedCount = DB::table('feed')
            ->where('address', $this->userAddress)
            ->where('tag', 'ED') // Assuming 'ED' tag is for endorsements
            ->count();

        $endorsedReceivedCount = DB::table('feed')
            ->where('message', 'like', '%' . $this->userAddress . '%')
            ->where('tag', 'ED') // Assuming this is how you determine endorsements received
            ->count();

        $proposalsInitiatedCount = DB::table('proposals')
            ->where('user_id', $this->userId)
            ->count();

        $forumPostsCount = DB::table('forum_posts')
            ->where('author_id', $this->userId)
            ->count();

        return view('livewire.citizen-stats', compact('endorsedIssuedCount', 'endorsedReceivedCount', 'proposalsInitiatedCount', 'forumPostsCount'));
    }
}
