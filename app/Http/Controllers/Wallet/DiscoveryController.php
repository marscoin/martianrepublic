<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class DiscoveryController extends Controller
{
    /**
     * Discover HD wallet addresses with funds across multiple derivation paths.
     * Uses marscoind scantxoutset — no Electrum dependency.
     *
     * Client sends an array of addresses to check (derived client-side from all
     * possible BIP44 paths). Server checks each against the UTXO set.
     */
    public function discover(Request $request)
    {
        $request->validate([
            'addresses' => 'required|array|min:1|max:200',
            'addresses.*.address' => 'required|string|max:50',
            'addresses.*.path' => 'required|string|max:50',
            'addresses.*.chain' => 'required|string|in:receiving,change',
            'addresses.*.index' => 'required|integer|min:0',
            'addresses.*.scheme' => 'required|string|max:30',
        ]);

        $addresses = $request->input('addresses');
        $discovered = [];
        $totalBalance = 0;
        $totalUnconfirmed = 0;

        // Batch check via scantxoutset — single RPC call for all addresses
        $descriptors = array_map(function ($a) {
            return 'addr('.$a['address'].')';
        }, $addresses);

        $cli = config('blockchain.rpc.cli_path');
        $dataDir = config('blockchain.rpc.data_dir');
        $descriptorJson = json_encode($descriptors);

        try {
            $result = Process::timeout(60)->run([
                $cli, '-datadir='.$dataDir, 'scantxoutset', 'start',
                $descriptorJson,
            ]);

            if (! $result->successful()) {
                Log::warning('Discovery: scantxoutset failed', ['error' => $result->errorOutput()]);

                return response()->json(['error' => 'UTXO scan failed'], 500);
            }

            $scan = json_decode($result->output(), true);
            if (! $scan || ! isset($scan['unspents'])) {
                return response()->json(['error' => 'Invalid scan result'], 500);
            }

            // Build a map: address → UTXO totals
            $utxoMap = [];
            foreach ($scan['unspents'] as $utxo) {
                // Extract address from the descriptor
                $addr = null;
                if (preg_match('/addr\(([A-Za-z0-9]+)\)/', $utxo['desc'] ?? '', $m)) {
                    $addr = $m[1];
                }
                if (! $addr) {
                    continue;
                }

                if (! isset($utxoMap[$addr])) {
                    $utxoMap[$addr] = ['balance' => 0, 'utxoCount' => 0];
                }
                $utxoMap[$addr]['balance'] += $utxo['amount'];
                $utxoMap[$addr]['utxoCount']++;
            }

            // Match back to the input addresses
            foreach ($addresses as $a) {
                $addr = $a['address'];
                if (isset($utxoMap[$addr])) {
                    $discovered[] = [
                        'address' => $addr,
                        'balance' => round($utxoMap[$addr]['balance'], 8),
                        'unconfirmed' => 0,
                        'chain' => $a['chain'],
                        'index' => $a['index'],
                        'path' => $a['path'],
                        'scheme' => $a['scheme'],
                        'utxoCount' => $utxoMap[$addr]['utxoCount'],
                    ];
                    $totalBalance += $utxoMap[$addr]['balance'];
                }
            }

            // Also check addresses with zero balance but historical transactions
            // via getrawtransaction if txindex=1 (future enhancement)

        } catch (\Exception $e) {
            Log::error('Discovery: exception', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Discovery failed: '.$e->getMessage()], 500);
        }

        // Cache discovered derivation schemes for this user
        if (Auth::check() && count($discovered) > 0) {
            $schemes = array_unique(array_column($discovered, 'scheme'));
            Cache::put('wallet_schemes:'.Auth::id(), $schemes, now()->addDays(30));
        }

        return response()->json([
            'totalBalance' => round($totalBalance, 8),
            'totalUnconfirmed' => $totalUnconfirmed,
            'addressCount' => count($discovered),
            'addresses' => $discovered,
        ]);
    }

    /**
     * Get transaction history for a specific address via marscoind.
     * Uses getrawtransaction with txindex=1.
     */
    public function addressTransactions(Request $request, string $address)
    {
        if (! preg_match('/^M[A-Za-z0-9]{25,34}$/', $address)) {
            return response()->json(['error' => 'Invalid address'], 400);
        }

        $cli = config('blockchain.rpc.cli_path');
        $dataDir = config('blockchain.rpc.data_dir');

        // Get UTXOs for this address
        try {
            $result = Process::timeout(30)->run([
                $cli, '-datadir='.$dataDir, 'scantxoutset', 'start',
                json_encode(['addr('.$address.')']),
            ]);

            if (! $result->successful()) {
                return response()->json(['error' => 'Scan failed'], 500);
            }

            $scan = json_decode($result->output(), true);
            $utxos = $scan['unspents'] ?? [];

            // Get full transaction details for each UTXO
            $transactions = [];
            foreach (array_slice($utxos, 0, 50) as $utxo) {
                try {
                    $txResult = Process::timeout(10)->run([
                        $cli, '-datadir='.$dataDir, 'getrawtransaction',
                        $utxo['txid'], '1',
                    ]);

                    if ($txResult->successful()) {
                        $tx = json_decode($txResult->output(), true);
                        $transactions[] = [
                            'txid' => $utxo['txid'],
                            'amount' => $utxo['amount'],
                            'confirmations' => $utxo['confirmations'] ?? 0,
                            'height' => $utxo['height'] ?? null,
                            'time' => $tx['time'] ?? null,
                        ];
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            return response()->json([
                'address' => $address,
                'balance' => $scan['total_amount'] ?? 0,
                'txCount' => count($transactions),
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
