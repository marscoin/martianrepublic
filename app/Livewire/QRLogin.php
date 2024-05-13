<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\MSession;
    
class QRLogin extends Component
{
    public $sid = null;
    public $qrCode = '';
    public $qrCodeImage;

    public function mount()
    {
        $this->generateQRCode();
    }

    public function generateQRCode()
    {
        if (!Auth::check()) {
            // Attempt to retrieve or generate a session ID and challenge
            $session = MSession::firstOrCreate(
                ['sid' => $this->sid],
                ['sid' => now()->timestamp . '_' . bin2hex(random_bytes(10)), 'v' => '']
            );

            $challenge = bin2hex(random_bytes(10)) . '_' . time();
            $clist = array_filter(explode(",", $session->v));
            array_push($clist, $challenge);
            if (count($clist) > 4) {
                $clist = array_slice($clist, -4);
            }

            $session->v = implode(",", $clist);
            $session->save();

            $this->sid = $session->sid;
            $url = "https://martianrepublic.org/api/marsauth?sid={$this->sid}&c={$challenge}&t=" . dechex(time());

            $this->qrCodeImage = base64_encode(QrCode::format('png')->size(250)->generate($url));
            Log::debug('QR Code generated', ['sid' => $this->sid]);
        }
    }

    public function render()
    {
        return view('livewire.q-r-login');        
    }
}

