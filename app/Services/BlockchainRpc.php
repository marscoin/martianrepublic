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

    public function getUtxosForTx(string $address, float $amount): array
    {
        $utxos = $this->getUtxos($address);
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
                'value' => $utxo['satoshis'], 'rawTx' => $rawTx, 'address' => $address,
            ];
            $total += $utxo['satoshis'];
            $estFee = (count($selected) * 180 + 68 + 10) * 10;
            if ($total >= $targetSat + $estFee) {
                break;
            }
        }

        $fee = (count($selected) * 180 + 68 + 10) * 10;
        $change = $total - $targetSat - $fee;
        $outputs = [['address' => $address, 'value' => $targetSat]];
        if ($change > 546) {
            $outputs[] = ['address' => $address, 'value' => $change];
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

    public function broadcast(string $rawTxHex): ?string
    {
        $result = $this->call('sendrawtransaction', [$rawTxHex]);
        if ($result && is_string($result) && strlen($result) === 64) {
            Log::info("TX broadcast: {$result}");

            return $result;
        }
        Log::error('Broadcast failed', ['result' => $result]);

        return null;
    }

    public function getBlockCount(): int
    {
        $r = $this->call('getblockcount');

        return is_numeric($r) ? (int) $r : 0;
    }
}
