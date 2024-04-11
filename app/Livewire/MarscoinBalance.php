<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
    $cacheKey = 'balance_' . $this->address;
    // Attempt to retrieve the balance from cache first
    $cachedBalance = Cache::get($cacheKey);

    if ($cachedBalance !== null) {
        $this->balance = $cachedBalance;
        return;
    }

    try {
        $response = Http::retry(3, 100) // Retry up to 3 times, with 100ms delay between attempts
                     ->get('https://pebas.marscoin.org/api/mars/balance', [
                         'address' => $this->address,
                     ]);

        if ($response->successful()) {
            $this->balance = $response->json()['balance'];

            // Cache the fetched balance for 5 minutes to reduce API calls
            Cache::put($cacheKey, $this->balance, now()->addMinutes(5));

            \Log::info('Successfully fetched balance for address: ' . $this->address);
        } else {
            \Log::error('Failed to fetch balance for address: ' . $this->address, [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            $this->balance = '0';
        }
    } catch (\Throwable $exception) {
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