<?php

namespace App\Livewire;

use App\Models\MSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRLogin extends Component
{
    public $sid = null;

    public $qrCode = '';

    public $qrCodeImage = '';

    public function mount()
    {
        $this->generateQRCode();
    }

    public function generateQRCode()
    {
        if (! Auth::check()) {
            // Attempt to retrieve or generate a session ID and challenge
            $session = MSession::firstOrCreate(
                ['sid' => $this->sid],
                ['sid' => now()->timestamp.'_'.bin2hex(random_bytes(10)), 'v' => '']
            );

            $challenge = bin2hex(random_bytes(10)).'_'.time();
            $clist = array_filter(explode(',', $session->v));
            array_push($clist, $challenge);
            if (count($clist) > 4) {
                $clist = array_slice($clist, -4);
            }

            $session->v = implode(',', $clist);
            $session->save();

            $this->sid = $session->sid;
            $url = "https://martianrepublic.org/api/marsauth?sid={$this->sid}&c={$challenge}&t=".dechex(time());

            try {
                $qrResult = QrCode::format('png')->size(250)->generate($url);
                $this->qrCodeImage = base64_encode(is_string($qrResult) ? $qrResult : (string) $qrResult);
            } catch (\Exception $e) {
                Log::error('QR Code generation failed', ['error' => $e->getMessage()]);
                $this->qrCodeImage = '';
            }
            Log::debug('QR Code generated', ['sid' => $this->sid]);
            $this->dispatch('sidUpdated', ['sid' => $this->sid]);
        }
    }

    public function render()
    {
        return view('livewire.q-r-login');
    }
}
