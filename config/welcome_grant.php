<?php

/*
|--------------------------------------------------------------------------
| Welcome grant
|--------------------------------------------------------------------------
|
| A small one-time payment to a newcomer's civic address so they can pay
| the network fee for publishing their own application. Everything is off
| until WELCOME_GRANT_ENABLED is true and a dedicated node wallet is named.
| See docs/WELCOME_GRANT.md.
|
*/

return [
    'enabled' => (bool) env('WELCOME_GRANT_ENABLED', false),

    // 1 MARS = 100,000,000 zubrins. Capped in code at 1 MARS per grant.
    'amount_zubrins' => (int) env('WELCOME_GRANT_AMOUNT_ZUBRINS', 1000000),

    // Grants across all users per calendar day (server time zone).
    'daily_cap' => (int) env('WELCOME_GRANT_DAILY_CAP', 50),

    // Grants per client IP address per calendar day.
    'per_ip_daily_cap' => (int) env('WELCOME_GRANT_PER_IP_DAILY_CAP', 3),

    // Name of the dedicated wallet inside the Marscoin node. Never the
    // node's default wallet: this wallet's balance is the most that can
    // ever be paid out. Empty means the grant is unavailable.
    'wallet' => (string) env('WELCOME_GRANT_WALLET', ''),

    // Kept back for the node's own transaction fee when checking funds.
    'fee_reserve_zubrins' => 100000,

    'nonce_ttl_minutes' => 10,
];
