# Welcome grant

A newcomer registers in three steps. Steps 1 (name, photo) and 2 (video) are
free. Step 3 is a Marscoin transaction from the newcomer's own civic address,
which costs a small network fee. Someone with no Marscoin at all cannot pay it.

The welcome grant sends a small one-time amount (default 0.01 MARS =
1,000,000 zubrins) to the newcomer's civic address once steps 1 and 2 are
complete, so they can publish their own application.

It is **off by default**. Merging this code changes nothing until the steps
under "Setup" are done.

## API (bearer token, as for the rest of the mobile API)

`POST /api/grant/challenge`

```json
{ "eligible": true, "reason": null, "status": "available",
  "address": "<civic address>", "amount_zubrins": 1000000,
  "nonce": "<64 hex characters>", "message": "<text to sign>",
  "expires_at": "<ISO 8601>", "txid": null }
```

`status` is `available`, `already_granted`, `not_eligible` or `unavailable`.
`nonce`, `message` and `expires_at` are null unless the status is `available`.
The message is exactly three lines joined by `\n`:

```
Martian Republic welcome grant
address: <civic address>
nonce: <nonce>
```

`POST /api/grant/claim` with `{ "address", "nonce", "signature" }`, where the
signature is the message signed by the civic key, in the same format as the
sign-in at `/api/token` (base64, 65 bytes).

```json
{ "status": "sent", "txid": "<txid>", "amount_zubrins": 1000000, "reason": null }
```

`status` is `sent`, `already_granted`, `not_eligible`, `unavailable`,
`invalid_signature` or `expired`. `expired` also covers a nonce that is
unknown, already used, replaced by a newer challenge, or issued to someone
else.

`GET /api/grant/status`

```json
{ "granted": true, "txid": "<txid>", "amount_zubrins": 1000000, "granted_at": "<ISO 8601>" }
```

All three always answer 200 with one of these shapes (401 without a token,
429 when called more than 10 times a minute).

## Rules enforced by the server

- The claim must carry a valid signature by the civic address over the
  server's message. Verification is `MarscoinECDSA::checkSignatureForMessage`,
  the same call the sign-in uses.
- The grant is paid only to the civic address stored for the signed-in user
  (`civic_wallet.public_addr`). The address in the request must equal it.
- Nonces are 256 random bits, stored with the user id and address, valid for
  10 minutes, usable once. A new challenge cancels the previous one.
- Eligibility is checked at claim time: `citizen.firstname`, `lastname`,
  `avatar_link` and `liveness_link` are filled; `profile.general_public` and
  `profile.citizen` are not set; the account is active; no earlier grant.
- One grant per user and per address, ever. The `welcome_grants` table has a
  unique key on `user_id` and another on `address`. The row is written
  before the payment is requested, so two simultaneous claims cannot both
  pay.
- Limits: 50 grants a day in total, 3 a day per client IP address, 1 MARS
  at most per grant whatever the configuration says.
- If the feature is off, a limit is reached, or the grant wallet is short or
  unreachable, the answer is `unavailable` with a reason.

## Setup

1. Run the migration: `php artisan migrate` (creates `welcome_grants` and
   `welcome_grant_nonces`).
2. Create a dedicated wallet inside the Marscoin node the server already
   uses. Do not use the node's default wallet.

   ```
   marscoin-cli -datadir=<data dir> createwallet welcome-grant
   marscoin-cli -datadir=<data dir> -rpcwallet=welcome-grant getnewaddress
   ```

   Make sure the node loads this wallet after a restart (current node
   versions do so for wallets made with `createwallet`; check with
   `listwallets` after the next restart). Back the wallet up like any other.
3. Send to that address only what you are prepared to give away, for
   example 5 MARS for about 500 grants. This balance is the most that can
   ever be paid out.
4. Set in `.env` (names only; choose your own values):

   ```
   WELCOME_GRANT_ENABLED=
   WELCOME_GRANT_WALLET=
   WELCOME_GRANT_AMOUNT_ZUBRINS=
   WELCOME_GRANT_DAILY_CAP=
   WELCOME_GRANT_PER_IP_DAILY_CAP=
   ```

   `WELCOME_GRANT_ENABLED=true` and `WELCOME_GRANT_WALLET=welcome-grant`
   are required. The other three are optional (defaults 1000000, 50, 3).
   Then `php artisan config:clear` (or `config:cache` if the site caches
   its configuration).
5. Check: `marscoin-cli -datadir=<data dir> -rpcwallet=welcome-grant getbalance`
   shows the funds, run as the same system user as the web server.

The server stores no key and no password for this. It runs the same
`marscoin-cli` (`MARSCOIN_EXEC_PATH`, `MARSCOIN_CONF_PATH`) as the rest of
the site, with `-rpcwallet=<WELCOME_GRANT_WALLET>`, and calls only
`getbalance` and `sendtoaddress`.

The per-IP limit uses the client address Laravel sees. If the site sits
behind a proxy that is not listed in `App\Http\Middleware\TrustProxies`,
every request appears to come from the proxy, and the per-IP limit then
acts as a second global limit of 3 a day. Raise
`WELCOME_GRANT_PER_IP_DAILY_CAP` or configure the trusted proxy.

## Kill switch

Set `WELCOME_GRANT_ENABLED=false` (or remove the line) and clear the
configuration cache. Challenges and claims then answer `unavailable` and
nothing is paid. Emptying the grant wallet or unsetting
`WELCOME_GRANT_WALLET` has the same effect.

## When a payment fails

Each grant row has a `status`:

- `sent`: paid, `txid` recorded.
- `pending`: reserved, payment in progress. A row that stays `pending` means
  the request died mid-payment.
- `failed`: the node refused (for example not enough funds) or could not be
  reached, so nothing was sent. The user's next claim retries; only one
  retry can take the row.
- `uncertain`: the node did not give a clear answer (for example a
  timeout). The payment may or may not have gone out, so it is never retried
  automatically.

For a `pending` or `uncertain` row, look in the grant wallet
(`marscoin-cli -rpcwallet=welcome-grant listtransactions "*" 100`) for a
payment to the row's `address`. If there is one, set `status = 'sent'` and
fill `txid` and `sent_at`. If there is none, set `status = 'failed'` and the
user can claim again. Failures are written to the Laravel log with the grant
id; no keys are logged.

## Threat model

What an attacker can gain: the grant amount, once per account and address,
for each identity they register with a name, a photo and a video. Keys are
free to make, so one person can register several identities; the uploads
are not checked for being a real, distinct person at this stage. The loss is
bounded three ways: per day by the global cap (50 x 0.01 = 0.5 MARS by
default), per network by the per-IP cap, and in total by the balance of the
dedicated wallet. A grant that is paid can be spent on anything; it is not
tied to publishing the application.

What an attacker cannot do: receive a grant for an address they do not
control (the signature must come from that address, and it must be the
address on the account); redirect someone else's grant (the server pays the
address on record, not an address from the request); reuse a captured
signature (the nonce is single-use, expires in 10 minutes, and is bound to
user and address); obtain two grants for one account or address by sending
claims at the same time (unique keys, row written before paying); make the
server pay more than the wallet holds, or pay from any other wallet (the
node is always called with the named wallet, and the call is refused if no
name is set). A stolen bearer token alone is not enough to claim, and would
only send the grant to its owner's address anyway.

What is trusted: the server itself and the node. Anyone who can change the
server's environment or run `marscoin-cli` on the host can spend the grant
wallet, which is why it is separate from every other wallet and holds only
what may be given away.
