<?php

namespace App\Livewire;

use App\Includes\AppHelper;
use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CitizenIdCard extends Component
{
    public string $fullname = '';

    public string $address = '';

    public string $status = 'GP';

    public string $statusLabel = 'Applicant';

    public string $citizenSince = '';

    public bool $hasCivicWallet = false;

    public function mount()
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $this->fullname = $user->fullname ?: 'Unknown';
        $profile = Profile::where('userid', $user->id)->first();
        $civic = CivicWallet::where('user_id', $user->id)->first();

        if ($civic) {
            $this->hasCivicWallet = true;
            $this->address = $civic->public_addr;
        }

        if ($profile) {
            $citizenStatus = AppHelper::getCitizenStatus($user->id);
            if ($citizenStatus) {
                $this->status = $citizenStatus->type;
            }

            if ($profile->citizen) {
                $this->statusLabel = 'Citizen';
                $citizen = Citizen::where('userid', $user->id)->first();
                if ($citizen && $citizen->created_at) {
                    $this->citizenSince = $citizen->created_at->format('M Y');
                }
            } elseif ($profile->general_public) {
                $this->statusLabel = 'General Public';
            } else {
                $this->statusLabel = 'Applicant';
            }
        }
    }

    public function render()
    {
        return view('livewire.citizen-id-card');
    }
}
