<?php

namespace App\Services\WelcomeGrant;

use Illuminate\Support\Facades\Process;

/**
 * Pays from a dedicated, separately named wallet inside the Marscoin node,
 * through the same marscoin-cli the rest of the server uses (BlockchainRpc).
 * The server holds no key: the node signs. The wallet's balance is the
 * most that can ever be paid out.
 */
class RpcGrantSender implements GrantSender
{
    public function availableZubrins(): ?int
    {
        $command = $this->command('getbalance');
        if ($command === null) {
            return null;
        }
        try {
            $result = Process::timeout(15)->run($command);
        } catch (\Throwable) {
            return null;
        }
        $output = trim($result->output());
        if (! $result->successful() || ! is_numeric($output)) {
            return null;
        }

        return (int) round(((float) $output) * 1e8);
    }

    public function send(string $address, int $zubrins): string
    {
        if (! preg_match('/^M[1-9A-HJ-NP-Za-km-z]{24,34}$/', $address) || $zubrins < 1) {
            throw new GrantSendException('Invalid payment request.', true);
        }
        $command = $this->command('sendtoaddress', [$address, number_format($zubrins / 1e8, 8, '.', ''), 'welcome grant']);
        if ($command === null) {
            throw new GrantSendException('The grant wallet is not configured.', true);
        }
        try {
            $result = Process::timeout(60)->run($command);
        } catch (\Throwable $error) {
            // A timeout says nothing about whether the node sent the coins.
            throw new GrantSendException('No answer from the node: '.class_basename($error), false);
        }
        $output = trim($result->output());
        if ($result->successful() && preg_match('/^[0-9a-f]{64}$/', $output)) {
            return $output;
        }
        $error = trim($result->errorOutput());
        // The node answered with an RPC error, or was never reached: in both
        // cases no transaction was created. Anything else is unknown.
        $nothingSent = ! $result->successful()
            && (preg_match('/error code: -\d+/', $error) === 1 || stripos($error, 'not connect to') !== false);

        throw new GrantSendException(substr($error !== '' ? $error : 'Unexpected answer from the node.', 0, 200), $nothingSent);
    }

    private function command(string $method, array $params = []): ?array
    {
        $wallet = (string) config('welcome_grant.wallet');
        // Without a named wallet the call would spend from the node's
        // default wallet, so refuse.
        if (! preg_match('/^[A-Za-z0-9_-]{1,64}$/', $wallet)) {
            return null;
        }

        return [
            config('blockchain.rpc.cli_path', '/usr/local/bin/marscoin-cli'),
            '-datadir='.config('blockchain.rpc.data_dir', '/root/.marscoin'),
            '-rpcwallet='.$wallet,
            $method, ...$params,
        ];
    }
}
