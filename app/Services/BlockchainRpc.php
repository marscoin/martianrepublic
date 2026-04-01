<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

/**
 * Direct marscoind RPC service — eliminates Electrum/Pebas dependency.
 * Requires txindex=1 in marscoin.conf.
 */
class BlockchainRpc
{
    private string $cli;
    private string $dataDir;

    public function __construct()
    {
        $this->cli = config('blockchain.rpc.cli_path', '/usr/local/bin/marscoin-cli');
        $this->dataDir = config('blockchain.rpc.data_dir', '/root/.marscoin');
    }

    public function call(string $method, array $params = [], int $timeout = 30): mixed
    {
        $args = [$this->cli, '-datadir='.$this->dataDir, $method, ...$params];
        $result = Process::timeout($timeout)->run($args);
        if (! $result->successful()) {
            Log::warning("RPC {$method} failed", ['stderr' => trim($result->errorOutput()), 'exit' => $result->exitCode()]);
            return null;
        }
        $output = trim($result->output());
        $decoded = json_decode($output, true);

        return $decoded !== null ? $decoded : $output;
    }

    public function getBalance(string $address): array
    {
        $result = $this->call('scantxoutset', ['start', json_encode(["addr({$address})"])], 60);
        if (! $result || ! isset($result['total_amount'])) {
            return ['balance' => 0, 'utxos' => 0];
        }

        return ['balance' => $result['total_amount'], 'utxos' => count($result['unspents'] ?? [])];
    }

    public function getUtxos(string $address): array
    {
        $result = $this->call('scantxoutset', ['start', json_encode(["addr({$address})"])], 60);
        if (! $result || ! isset($result['unspents'])) {
            return [];
        }

        return array_map(fn ($u) => [
            'txid' => $u['txid'], 'vout' => $u['vout'],
            'amount' => $u['amount'], 'satoshis' => (int) round($u['amount'] * 1e8),
            'height' => $u['height'] ?? null, 'confirmations' => $u['confirmations'] ?? 0,
        ], $result['unspents']);
    }

    /**
     * Get UTXOs across multiple addresses, filtering out mempool-spent ones.
     */
    public function getUtxosMulti(array $addresses): array
    {
        // Build descriptors for all addresses in one scantxoutset call
        $descriptors = array_map(fn ($a) => "addr({$a})", $addresses);
        $result = $this->call('scantxoutset', ['start', json_encode($descriptors)], 60);
        if (! $result || ! isset($result['unspents'])) {
            return [];
        }

        // Get mempool txids to filter out already-spent UTXOs
        $mempoolSpent = $this->getMempoolSpentOutputs();

        $utxos = [];
        foreach ($result['unspents'] as $u) {
            $outpoint = $u['txid'].':'.$u['vout'];
            if (isset($mempoolSpent[$outpoint])) {
                Log::debug("Skipping mempool-spent UTXO: {$outpoint}");
                continue;
            }
            // Find which address owns this UTXO
            $addr = $u['desc'] ?? null;
            if ($addr && preg_match('/addr\(([A-Za-z0-9]+)\)/', $addr, $m)) {
                $addr = $m[1];
            } else {
                // Fallback: decode the raw tx to find the address
                $addr = $addresses[0];
                foreach ($addresses as $a) {
                    if (str_contains($u['desc'] ?? '', $a)) {
                        $addr = $a;
                        break;
                    }
                }
            }
            $utxos[] = [
                'txid' => $u['txid'], 'vout' => $u['vout'],
                'amount' => $u['amount'], 'satoshis' => (int) round($u['amount'] * 1e8),
                'height' => $u['height'] ?? null, 'confirmations' => $u['confirmations'] ?? 0,
                'address' => $addr,
            ];
        }

        return $utxos;
    }

    /**
     * Get set of outpoints (txid:vout) that are spent by mempool transactions.
     */
    private function getMempoolSpentOutputs(): array
    {
        $rawMempool = $this->call('getrawmempool', ['true'], 10);
        $spent = [];
        if (is_array($rawMempool)) {
            foreach ($rawMempool as $txid => $info) {
                // Get the full tx to find its inputs
                $tx = $this->call('getrawtransaction', [$txid, '1'], 10);
                if ($tx && isset($tx['vin'])) {
                    foreach ($tx['vin'] as $vin) {
                        if (isset($vin['txid'])) {
                            $spent[$vin['txid'].':'.$vin['vout']] = true;
                        }
                    }
                }
            }
        }

        return $spent;
    }

    public function getUtxosForTx(array|string $addresses, float $amount, string $receiver = null, string $changeAddress = null): array
    {
        // Accept single address or array
        if (is_string($addresses)) {
            $addresses = [$addresses];
        }

        $utxos = $this->getUtxosMulti($addresses);
        if (empty($utxos)) {
            return ['error' => 'No UTXOs found'];
        }
        usort($utxos, fn ($a, $b) => $b['amount'] <=> $a['amount']);

        $selected = [];
        $total = 0;
        $targetSat = (int) round($amount * 1e8);

        foreach ($utxos as $utxo) {
            $rawTx = $this->call('getrawtransaction', [$utxo['txid']]);
            $selected[] = [
                'txId' => $utxo['txid'], 'vout' => $utxo['vout'],
                'value' => $utxo['satoshis'], 'rawTx' => $rawTx, 'address' => $utxo['address'],
            ];
            $total += $utxo['satoshis'];
            $estFee = (count($selected) * 180 + 68 + 10) * 10;
            if ($total >= $targetSat + $estFee) {
                break;
            }
        }

        if ($total < $targetSat) {
            return ['error' => 'Insufficient funds'];
        }

        $fee = (count($selected) * 180 + 68 + 10) * 10;
        $change = $total - $targetSat - $fee;
        $toAddr = $receiver ?: $addresses[0];
        $chgAddr = $changeAddress ?: $addresses[0];
        $outputs = [['address' => $toAddr, 'value' => $targetSat]];
        if ($change > 546) {
            $outputs[] = ['address' => $chgAddr, 'value' => $change];
        }

        return ['inputs' => $selected, 'outputs' => $outputs, 'fee' => $fee];
    }

    public function getTransactionHistory(string $address, int $limit = 50): array
    {
        $result = $this->call('scantxoutset', ['start', json_encode(["addr({$address})"])], 60);
        if (! $result || ! isset($result['unspents'])) {
            return [];
        }
        $txs = [];
        foreach (array_slice($result['unspents'], 0, $limit) as $u) {
            $tx = $this->call('getrawtransaction', [$u['txid'], '1']);
            if ($tx) {
                $txs[] = [
                    'txid' => $u['txid'], 'amount' => $u['amount'],
                    'confirmations' => $u['confirmations'] ?? 0,
                    'height' => $u['height'] ?? null, 'time' => $tx['time'] ?? null,
                ];
            }
        }

        return $txs;
    }

    public function broadcast(string $rawTxHex): array
    {
        // sendrawtransaction returns txid on success, or CLI exits non-zero with error on stderr
        $args = [$this->cli, '-datadir='.$this->dataDir, 'sendrawtransaction', $rawTxHex];
        $result = Process::timeout(30)->run($args);

        $output = trim($result->output());
        $error = trim($result->errorOutput());

        if ($result->successful() && strlen($output) === 64) {
            Log::info("TX broadcast: {$output}");
            return ['txid' => $output];
        }

        Log::error('Broadcast failed', ['output' => $output, 'error' => $error, 'exit' => $result->exitCode()]);
        return ['error' => $error ?: 'Broadcast failed', 'txid' => null];
    }

    public function getBlockCount(): int
    {
        $r = $this->call('getblockcount');

        return is_numeric($r) ? (int) $r : 0;
    }
}
