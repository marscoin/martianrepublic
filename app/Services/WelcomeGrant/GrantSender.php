<?php

namespace App\Services\WelcomeGrant;

/**
 * Pays welcome grants. The only implementation that moves coins is
 * RpcGrantSender; tests bind a fake.
 */
interface GrantSender
{
    /**
     * Spendable balance of the grant wallet in zubrins, or null when the
     * wallet is not configured or cannot be reached.
     */
    public function availableZubrins(): ?int;

    /**
     * Send the amount to the address and return the transaction id.
     *
     * @throws GrantSendException when no payment was made or the outcome is unknown
     */
    public function send(string $address, int $zubrins): string;
}
