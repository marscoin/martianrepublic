# Martian Republic - Technical Deep Dive

> Written after an intensive development session (March 24-25, 2026) that touched nearly every part of the system. This document captures architectural knowledge, gotchas, and lessons learned.

## Architecture Overview

The Martian Republic is a blockchain-based governance platform where citizens propose legislation, vote on measures, and manage identity - all anchored on the Marscoin blockchain (a Litecoin fork). Think of it as "on-chain democracy for Mars."

### Stack
- **Backend**: Laravel 11 (PHP 8.x), MySQL, Apache
- **Frontend**: Blade templates, Bootstrap 3, Livewire, jQuery, inline JS (significant amounts)
- **Blockchain**: Marscoin daemon (marscoind v28.1), Electrum server
- **APIs**: Pebas (custom Node.js API for Electrum/blockchain queries), IPFS (local node)
- **Infra**: DigitalOcean (159.203.79.101), Cloudflare CDN, PM2 process manager, GitHub CI/CD

### Key Services Running on the Server
| Service | Port | Process | Purpose |
|---------|------|---------|---------|
| Apache | 443 | apache2 | Web server (www-data user) |
| marscoind | 8332 | marscoind -daemon | Marscoin full node |
| Pebas | 3001 | PM2 -> node index.js | Blockchain API (Electrum-based) |
| IPFS | 5001 | ipfs daemon | Decentralized file storage |
| Blockchain Scanner | - | python3 track_blockchain.py | Processes new blocks, caches feed entries |
| Electrum Server | 50002 | External (147.182.177.23) | Address/UTXO queries |

---

## The Wallet System

### Overview
The wallet is the heart of the entire platform. Every governance action (citizen application, endorsement, proposal, vote) requires a signed blockchain transaction. The wallet manages:

1. **Key generation** - BIP39 mnemonic -> BIP32 HD key derivation
2. **Key storage** - Encrypted seed in DB, mnemonic in browser localStorage
3. **Transaction signing** - Client-side PSBT construction and signing
4. **Balance queries** - Via Pebas (Electrum) or explorer API

### Critical Architectural Decision: Client-Side Signing
Private keys NEVER touch the server. The mnemonic lives in localStorage and all transaction signing happens in the browser using my_bundle.js (a browserified bundle of bitcoinjs-lib + bip32 + bip39).

### The Two-Bundle Problem
There are TWO JS bundles with overlapping but INCOMPATIBLE crypto libraries:
- **bundle.js** - Contains an older bitcoinjs-lib with its own bip32
- **my_bundle.js** - Contains bip32, bip39, bitcoinjs-lib, IPFS client, Electrum client

**CRITICAL**: Loading bundle.js alongside my_bundle.js causes bundle.js's bip32 to interfere with my_bundle.js's bip32, producing different key derivations. Pages that need signing should ONLY load my_bundle.js.

### HD Wallet Derivation

The standard derivation path is m/44'/2'/0'/chain/index where:
- 44' = BIP44 purpose
- 2' = Coin type (Litecoin-legacy; official Marscoin is 107 but 2 is used everywhere)
- 0' = Account 0
- chain = 0 for receiving, 1 for change
- index = Address index (0, 1, 2, ...)

### The Trailing Space Bug
The wallet unlock compiles the mnemonic from 12 input fields:
```javascript
for (var i = 1; i < 13; i++) {
    input_mnemonic += `${mnem} `  // trailing space after EVERY word including last
}
```
BIP39's mnemonicToSeedSync uses PBKDF2 with the raw mnemonic as the password. A trailing space produces a COMPLETELY different seed, which derives different keys. The fix: `mnemonic = mnemonic.trim()` before any seed derivation.

### Network Parameters
```javascript
const Marscoin = {
    mainnet: {
        messagePrefix: "\x19Marscoin Signed Message:\n",
        bech32: "M",
        bip44: 2,  // Legacy; official SLIP-0044 is 107
        bip32: { public: 0x043587cf, private: 0x04358394 },
        pubKeyHash: 0x32,
        scriptHash: 0x32,
        wif: 0x80,
    }
};
```

### Wallet States (Profile Flags)
- profile.wallet_open = HD wallet ID (0 = locked)
- profile.civic_wallet_open = Civic wallet ID (0 = locked)
- Both must be set for full platform access
- Logout resets both to 0

### PBKDF2 Rounds
- **New wallets**: 100,000 rounds (secure)
- **Legacy wallets**: 1 round (auto-upgraded on successful unlock)
- The unlock code tries 100k rounds first, falls back to 1 round

---

## Pebas - Blockchain API

### Location
/var/www/pebas/index.js - Managed by PM2, runs on Node 17.4.0 (via nvm)

### Endpoints
| Endpoint | Method | Purpose |
|----------|--------|---------|
| /api/mars/utxo | GET | Get UTXOs for address (for tx construction) |
| /api/mars/broadcast | ALL | Broadcast signed transaction |
| /api/mars/balance | GET | Get address balance |
| /api/mars/txdetails | GET | Get transaction details |
| /api/mars/discover | GET | HD wallet address discovery (scans BIP44 tree) |
| /api/mars/utxo-multi | GET | Multi-address UTXO selection |

### HD Discovery
The discover endpoint takes an xpub and scans both receiving (chain 0) and change (chain 1) addresses up to a configurable gap limit. Uses pure JS crypto (@noble/hashes) to avoid OpenSSL compatibility issues with Node 17.

### OpenSSL Issue
Node 17+ deprecated certain OpenSSL algorithms. Pebas's HD discovery uses @noble/hashes (sha256, ripemd160) and bs58check for pure-JS address derivation, bypassing bitcoinjs-lib's OpenSSL-dependent crypto.hash160.

### MR Integration
The Laravel app proxies pebas calls through /api/discover to bypass Cloudflare CSP restrictions. AppHelper::getMarscoinBalance uses pebas (localhost:3001) as primary source, falling back to the explorer API.

---

## Blockchain Scanner

### Location
/var/www/martianrepublic.org/scripts/track_blockchain.py

### What It Does
Continuously polls marscoind for new blocks, processes transactions, and caches relevant data:
- **GP** (General Public) - Citizen application submitted
- **CT** (Citizen) - Citizenship endorsement
- **ED** (Endorsement) - Citizen endorsing another
- **PR** (Proposal) - Voting proposal launched
- **PRY/PRN/PRA** - Vote Yes/No/Abstain
- **LB** (Logbook) - Logbook entry
- **SP** (Signed Post) - Forum post signed on-chain

### The v28 Address Format Bug
Marscoin v28.1 changed the transaction output format from `addresses` (array) to `address` (string) in scriptPubKey. The scanner was checking `script.get('addresses', [])` which always returned empty on v28+, causing ALL OP_RETURN transactions to be classified as "Unknown operation" and silently dropped. Fixed by checking both fields.

### OP_RETURN Format
Transactions embed data as: TAG_IPFS_CID
- Example: GP_QmXyz... = General Public application with IPFS data hash
- The scanner decodes the OP_RETURN, splits on _, and routes to the appropriate handler

---

## Citizen Registration Flow

1. **Signup** (/signup) - Create account (name, email, password)
2. **2FA Setup** (/twofa) - Google Authenticator QR code
3. **2FA Verify** (/twofachallenge) - Enter 6-digit TOTP code
4. **Wallet Creation** (/wallet/dashboard/hd) - Mouse entropy -> mnemonic -> password -> HD wallet
5. **Wallet Unlock** - Connect with mnemonic or password
6. **Citizen Application** (/citizen/all) - Profile photo (IPFS), liveness video (IPFS), bio, then publish on-chain
7. **Endorsements** - Existing citizens endorse the applicant (3-5 needed)
8. **Citizen Status** - Promoted automatically when endorsement threshold met

---

## Common Bugs and Their Root Causes

| Bug | Root Cause | Fix |
|-----|-----------|-----|
| Signing key mismatch | Trailing space in mnemonic from wallet unlock | mnemonic.trim() |
| Public pages redirect to login | Controller methods were protected | Changed to public |
| Nav balance shows 0 | WalletStatus only checked one wallet type | Aggregate civic + HD |
| Endorsement modal hangs | Missing return after error alert | Added return |
| getWallet 500 crash | HDWallet::get() returns collection | Changed to first() |
| $public_addr always null | Hardcoded null for civic wallets | Use civic_wallet->public_addr |
| Scanner drops all OP_RETURN | v28 changed addresses to address | Check both fields |
| Wallet lock doesn't clear keys | Server redirect before JS runs | Render view first |
| bundle.js interferes with signing | Two incompatible bip32 implementations | Only load my_bundle.js |
| Balance shows 0 on citizen page | Hardcoded $balance = 0 in controller | Call getMarscoinBalance() |
| Explorer 503 breaks everything | AppHelper used explorer as only source | Primary: pebas, fallback: explorer |

---

## Security Improvements Made

1. **PBKDF2 100k rounds** - Password hashing increased from 1 to 100,000 rounds
2. **Encrypted keyfile export** - Backup files are password-protected (v2 format)
3. **Mnemonic confirmation** - Users must type back 3 random words during wallet creation
4. **Inline error messages** - Wallet unlock errors shown in modal, not bare JS alerts
5. **Gitleaks pre-commit hook** - Prevents committing secrets
6. **Citizen registry auth** - PII protected behind login requirement
7. **Wallet lock on logout** - Profile flags reset when user logs out
8. **Blockchain scanner v28 compat** - Processes both address formats

---

## Key Files Reference

### Controllers
- app/Http/Controllers/Wallet/DashboardController.php - Wallet CRUD, lock/unlock
- app/Http/Controllers/Wallet/ApiController.php - Balance, IPFS, endorsement, feed APIs
- app/Http/Controllers/Citizen/IdentityController.php - Citizen registry
- app/Http/Controllers/Congress/CongressController.php - Proposals, voting
- app/Http/Controllers/Auth/AuthenticatedSessionController.php - Login/logout

### Views (Blade Templates)
- resources/views/wallet/hd.blade.php (~1600 lines) - Wallet creation, unlock
- resources/views/wallet/hd-open.blade.php (~1300 lines) - Open wallet, send/receive, HD discovery
- resources/views/citizen/registry.blade.php (~900 lines) - Citizen application, endorsement, signing
- resources/views/wallet/mainnav.blade.php - Navigation, WalletKey manager, toastr

### JavaScript Bundles
- public/assets/wallet/js/dist/my_bundle.js - bip32, bip39, bitcoinjs-lib (PRIMARY - use this one)
- public/assets/wallet/js/dist/bundle.js - Older bitcoinjs-lib (DO NOT load alongside my_bundle.js)
- public/assets/wallet/js/sha256.js - Standalone SHA256 for application data hashing

### External Services
- /var/www/pebas/index.js - Pebas API server
- /var/www/martianrepublic.org/scripts/track_blockchain.py - Blockchain scanner
- /home/mars/constitution/marswallet.json - SALT and IV for wallet encryption

---

## Lessons Learned

1. **A trailing space can waste hours.** Always .trim() user input before cryptographic operations.
2. **Don't load two versions of the same crypto library.** bundle.js + my_bundle.js = different keys.
3. **CSRF tokens rotate.** Never use them as encryption keys for persistent data.
4. **Test in the actual browser.** Node.js doesn't faithfully reproduce browser crypto polyfill behavior.
5. **Cloudflare caches aggressively.** Always purge after deploying JS/CSS changes.
6. **The blockchain scanner is critical infrastructure.** When it breaks, transactions silently disappear.
7. **Ship the fix, not the refactor.** mnemonic.trim() solved the problem. Rebuilding the bundle was unnecessary.

---

*Written after a session that resulted in 30+ commits, fixing dozens of bugs, submitting the first AI citizen application to the Marscoin blockchain, and successfully endorsing that citizen - all blocked by a single trailing space.*
