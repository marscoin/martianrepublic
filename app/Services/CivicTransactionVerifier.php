<?php

namespace App\Services;

/**
 * Asks the node whether a transaction a browser reports is real: known to
 * the node (mempool or chain), carrying the stated civic record, and signed
 * by one of the user's own addresses.
 */
class CivicTransactionVerifier
{
    public function __construct(private BlockchainRpc $rpc) {}

    /**
     * @param  string  $record  the full OP_RETURN text, for example "GP_<cid>"
     * @param  string[]  $addresses  addresses that belong to the user
     */
    public function carries(mixed $txid, string $record, array $addresses): bool
    {
        if (! is_string($txid) || ! preg_match('/^[0-9a-f]{64}$/', $txid) || $addresses === []) {
            return false;
        }
        try {
            $tx = $this->rpc->call('getrawtransaction', [$txid, '1'], 10);
            if (! is_array($tx) || ! $this->hasRecord($tx, $record)) {
                return false;
            }
            // Spending an output of an address proves its key signed this
            // transaction. Outputs prove nothing: anyone can pay anyone.
            foreach (array_slice($tx['vin'] ?? [], 0, 10) as $input) {
                if (! isset($input['txid'], $input['vout'])) {
                    continue;
                }
                $funding = $this->rpc->call('getrawtransaction', [$input['txid'], '1'], 10);
                $script = is_array($funding) ? ($funding['vout'][$input['vout']]['scriptPubKey'] ?? []) : [];
                // Newer nodes answer 'address', older ones 'addresses'.
                $owners = array_filter(array_merge([$script['address'] ?? null], $script['addresses'] ?? []));
                if (array_intersect($owners, $addresses) !== []) {
                    return true;
                }
            }
        } catch (\Throwable $error) {
            report($error);
        }

        return false;
    }

    private function hasRecord(array $tx, string $record): bool
    {
        foreach ($tx['vout'] ?? [] as $output) {
            $parts = explode(' ', (string) ($output['scriptPubKey']['asm'] ?? ''));
            if (($parts[0] ?? '') === 'OP_RETURN' && isset($parts[1]) && ctype_xdigit($parts[1])
                && strlen($parts[1]) % 2 === 0 && hash_equals($record, (string) hex2bin($parts[1]))) {
                return true;
            }
        }

        return false;
    }
}
