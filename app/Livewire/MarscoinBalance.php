<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class MarscoinBalance extends Component
{
    public $address;
    public $balance = 0;

    public function mount($address)
    {
        $this->address = $address;
        $this->fetchBalance();
    }

    public function fetchBalance()
    {
        try {
            $response = Http::get('https://pebas.marscoin.org/api/mars/balance', [
                'address' => $this->address,
            ]);

            if ($response->successful()) {
                $this->balance = $response->json()['balance'];
                // Log success message
                \Log::info('Successfully fetched balance for address: ' . $this->address);
            } else {
                // Log error details
                \Log::error('Failed to fetch balance for address: ' . $this->address, [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                $this->balance = '0';
            }
        } catch (\Throwable $exception) {
            // Log exception details
            \Log::error('Exception occurred while fetching balance: ' . $exception->getMessage(), [
                'address' => $this->address,
                'exception' => $exception,
            ]);
            $this->balance = '0';
        }
    }

    public function render()
    {
        return view('livewire.marscoin-balance');
    }
}