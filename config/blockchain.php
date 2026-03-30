<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Marscoin RPC / CLI
    |--------------------------------------------------------------------------
    |
    | Path to the marscoin-cli binary and its data directory.
    | Used by Livewire components and AppHelper for direct node queries.
    |
    */
    'rpc' => [
        'cli_path' => env('MARSCOIN_EXEC_PATH', '/usr/local/bin/marscoin-cli'),
        'data_dir' => env('MARSCOIN_CONF_PATH', '/root/.marscoin'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pebas Electrum API
    |--------------------------------------------------------------------------
    |
    | Local pebas service (Electrum-based UTXO / balance / discovery).
    | The public URL is used for external health-checks on the status page.
    |
    */
    'pebas' => [
        'url' => env('PEBAS_URL', 'http://localhost:3001'),
        'public_url' => env('PEBAS_PUBLIC_URL', 'https://pebas.marscoin.org'),
    ],

    /*
    |--------------------------------------------------------------------------
    | IPFS
    |--------------------------------------------------------------------------
    |
    | Local IPFS API for pinning / uploading, and the public gateway
    | used to construct user-facing IPFS links (avatars, liveness, etc.).
    |
    */
    'ipfs' => [
        'api_url' => env('IPFS_API_URL', 'http://127.0.0.1:5001'),
        'gateway_url' => env('IPFS_GATEWAY_URL', 'https://ipfs.marscoin.org/ipfs/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Block Explorer(s)
    |--------------------------------------------------------------------------
    |
    | Public explorer endpoints used for balance lookups, tx history,
    | and network status when the local node is unavailable.
    |
    */
    'explorer' => [
        'primary_url' => env('EXPLORER_PRIMARY_URL', 'https://explore.marscoin.org'),
        'fallback_url' => env('EXPLORER_FALLBACK_URL', 'http://explore1.marscoin.org'),
        'secondary_url' => env('EXPLORER_SECONDARY_URL', 'http://explore2.marscoin.org'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Ballot Shuffle Server
    |--------------------------------------------------------------------------
    |
    | SSL socket endpoint for the ballot shuffle server health check.
    |
    */
    'ballot' => [
        'host' => env('BALLOT_SERVER_HOST', '127.0.0.1'),
        'port' => env('BALLOT_SERVER_PORT', 3678),
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Feeds
    |--------------------------------------------------------------------------
    |
    | External price APIs for Marscoin valuation.
    |
    */
    'price' => [
        'marscoin_url' => env('MARSCOIN_PRICE_URL', 'https://price.marscoin.org/json/'),
        'coingecko_url' => env('COINGECKO_PRICE_URL', 'https://api.coingecko.com/api/v3/simple/price?ids=marscoin&vs_currencies=usd'),
    ],

];
