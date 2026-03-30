[![Test Coverage & Analysis](https://github.com/marscoin/martianrepublic/actions/workflows/test-coverage.yaml/badge.svg)](https://github.com/marscoin/martianrepublic/actions/workflows/test-coverage.yaml)
[![Security Scan](https://github.com/marscoin/martianrepublic/actions/workflows/security-scan.yaml/badge.svg)](https://github.com/marscoin/martianrepublic/actions/workflows/security-scan.yaml)
[![Coverage Status](https://coveralls.io/repos/github/marscoin/martianrepublic/badge.svg)](https://coveralls.io/github/marscoin/martianrepublic)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

<p align="center">
  <a href="https://martianrepublic.org" target="_blank">
    <img src="https://github.com/marscoin/martianrepublic/blob/main/public/assets/landing/img/headerpic.png" width="100%">
  </a>
</p>

# The Martian Republic

A blockchain-based direct democracy platform for Mars colonists. Every citizen gets a wallet, every vote is on-chain, every decision is transparent. Built on [Marscoin](https://github.com/marscoin/marscoin), stored on [IPFS](https://ipfs.tech/), designed to run without Earth.

**Live at [martianrepublic.org](https://martianrepublic.org)**

---

## What It Does

| Module | Description |
|--------|-------------|
| **The Forge** | HD wallet creation with BIP39 mnemonic, BIP32 key derivation, civic identity binding |
| **Citizen Registry** | Decentralized identity: photo + liveness video on IPFS, endorsement-based citizenship |
| **Congress** | 4-tier governance (Signal / Operational / Legislative / Constitutional) with on-chain proposals |
| **The Ballot Box** | CoinShuffle-based anonymous voting with encrypted ballot backup |
| **The Forum** | Native governance discussion linked to proposals |
| **Instrument Registry** | BADS (Blockchain Attested Data Streams) for IoT device certification chain-of-trust |
| **The Academy** | 20+ educational articles on governance, cryptography, Mars timekeeping |
| **Inventory** | Personal asset tracking with blockchain notarization |
| **Logbook** | IPFS-anchored publications and research logs |

## Tech Stack

- **Backend:** Laravel 11, PHP 8.2, MySQL
- **Frontend:** Blade templates, custom CSS design system (Orbitron/JetBrains Mono), Livewire, Alpine.js
- **Blockchain:** Marscoin (Litecoin fork), marscoind JSON-RPC, Electrum via [Pebas](https://github.com/marscoin/pebas)
- **Storage:** IPFS for decentralized file storage
- **Testing:** Pest PHP (79 tests), PHPStan (level 1), Puppeteer visual tests
- **CI/CD:** GitHub Actions (PHPStan + Pest + Coveralls + Gitleaks + security audit)
- **Monitoring:** Sentry (backend + frontend JS), AI error triage via OpenRouter

## Quick Start

### Prerequisites

- PHP 8.2+ with extensions: `bcmath`, `gmp`, `mbstring`, `openssl`, `pdo_mysql`
- MySQL 8.0+
- Composer 2.x
- Node.js 20+ (for frontend build and Puppeteer tests)
- A running [Marscoin node](https://github.com/marscoin/marscoin) (marscoind)
- An [IPFS node](https://docs.ipfs.tech/install/) (kubo) on port 5001
- [Pebas](https://github.com/marscoin/pebas) API bridge on port 3001

### Installation

```bash
git clone https://github.com/marscoin/martianrepublic.git
cd martianrepublic
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials, Marscoin RPC settings, and IPFS endpoint.

```bash
php artisan migrate
npm install && npm run prod
```

### Running Tests

```bash
# Static analysis
./vendor/bin/phpstan analyse --memory-limit=2G

# Unit + feature tests (79 tests)
./vendor/bin/pest --exclude-group wip

# With coverage
./vendor/bin/pest --coverage --exclude-group wip

# Visual smoke tests (requires running app)
node tests/visual/visual-smoke-test.mjs
```

### Blockchain Scanner

The blockchain scanner watches for on-chain transactions (citizen applications, endorsements, proposals) and syncs them to the database:

```bash
# Add to crontab
0 1 * * * cd /path/to/martianrepublic/scripts && python3 applicant_detector.py >> /var/log/applicant_detector.log 2>&1
```

## Architecture

```
martianrepublic/
  app/
    Http/Controllers/
      Wallet/          # Dashboard, 2FA, HD wallet, transactions
      Citizen/         # Registry, onboarding, identity
      Congress/        # Proposals, voting, ballots
      Inventory/       # Asset management
      Logbook/         # Publications
      Planet/          # Mars map
    Models/
      Bads/            # BADS instrument chain-of-trust models
    Livewire/          # Real-time components (block display, feeds)
    Includes/          # Blockchain helpers, ECDSA, governance tiers
    Jobs/              # Background jobs (error triage)
  resources/views/
    academy/           # Educational content (20+ articles)
    congress/          # Voting UI, proposal detail, ballot wizard
    forum/             # Governance discussion
    wallet/            # Dashboard, vault, bridge, forge
    livewire/          # Real-time component views
  tests/
    Feature/           # Pest feature tests
    visual/            # Puppeteer smoke + integration tests
```

## Governance Model

The Martian Republic implements a 4-tier direct democracy:

1. **Signal** — Non-binding opinion polls (24h, simple majority)
2. **Operational** — Day-to-day decisions (72h, >50% of active citizens)
3. **Legislative** — Laws and policies (168h, >60% of active citizens)
4. **Constitutional** — Foundational changes (336h, >75% of active citizens)

Every proposal gets a git branch. Every amendment is a commit. Every enactment is a merge. The git history **is** the legislative record.

Voting uses CoinShuffle for cryptographic ballot anonymity — no one (not even the system) can trace a vote back to a citizen.

## Contributing

We welcome contributions! Here's how:

1. Fork the repo and create a feature branch
2. Make your changes — follow the existing code style
3. Run `./vendor/bin/phpstan analyse --memory-limit=2G` (must pass with 0 errors)
4. Run `./vendor/bin/pest --exclude-group wip` (all tests must pass)
5. Submit a pull request with a clear description


### Areas Where Help Is Needed

- Test coverage expansion (currently ~30% route coverage)
- Accessibility audit (alt text, ARIA labels, semantic HTML)
- Internationalization (i18n) — the app is English-only
- Mobile responsiveness improvements
- Documentation for API endpoints

## For Mars

Copy this project onto a USB stick along with a copy of the Marscoin ledger, a Marscoin node, and an IPFS node. Take a SpaceX Starship to Mars. Upon arrival, bootstrap an entire economic and governance system from the cargo bay.

No Earth required.

## Security

If you discover a security vulnerability, please email [info@marscoin.org](mailto:info@marscoin.org). All vulnerabilities will be addressed promptly. Do not open a public issue for security reports.

## License

The Martian Republic is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).
