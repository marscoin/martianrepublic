# Development Guide

Get the Martian Republic running locally for development and testing.

## Prerequisites

- PHP 8.2+ with extensions: `bcmath`, `gmp`, `mbstring`, `openssl`, `pdo_mysql`
- MySQL 8.0+
- Composer 2.x
- Node.js 20+
- [Marscoin node](https://github.com/marscoin/marscoin) (marscoind) — for blockchain features
- [IPFS node](https://docs.ipfs.tech/install/) (kubo) — for decentralized storage
- [Pebas](https://github.com/marscoin/pebas) API bridge — for Electrum queries

## Setup

```bash
# Clone
git clone https://github.com/marscoin/martianrepublic.git
cd martianrepublic

# Install PHP dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mrswalletdb
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

```bash
# Run migrations
php artisan migrate

# Install frontend dependencies and build
npm install && npm run prod
```

## Running Tests

```bash
# Static analysis (must pass with 0 errors)
./vendor/bin/phpstan analyse --memory-limit=2G

# Unit + feature tests (127 tests)
./vendor/bin/pest --exclude-group wip

# With coverage report
./vendor/bin/pest --coverage --exclude-group wip

# Code style check
./vendor/bin/pint --test
```

Tests use SQLite in-memory — they never touch the production database. The test harness in `tests/TestCase.php` enforces this with runtime checks.

Shared test database setup is in `tests/CreatesTestDatabase.php`. Use the trait in new test files:
```php
uses(Tests\TestCase::class, Tests\CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    // Add more: $this->createWalletTables(), $this->createProposalTables(), etc.
});
```

## Key Configuration

### Blockchain endpoints (`config/blockchain.php`)

All RPC, IPFS, Explorer, and Pebas URLs are centralized here. Override via `.env`:
```
MARSCOIN_EXEC_PATH=/usr/local/bin/marscoin-cli
MARSCOIN_CONF_PATH=/root/.marscoin
```

### Wallet encryption

Wallet seeds are encrypted with AES using PBKDF2-derived keys. The salt and IV come from a server-side constitution file (not in the repo). Current standard: 100,000 PBKDF2 rounds. Legacy wallets (1 round) are auto-upgraded on unlock.

## Common Gotchas

- **Homepage is cached** for ~3 days. After editing `landing.blade.php`: `php artisan cache:clear`
- **Never run `php artisan config:cache`** in development — it locks APP_ENV and breaks tests
- **Controller methods must be `public`** for Laravel routing to work
- **Route definitions use array notation** — `[Controller::class, 'method']` (Laravel 9+ standard)
- **Use `config()` not `env()`** in controllers — `env()` returns null when config is cached
- **Use `Process` facade** instead of `shell_exec()` for external commands
- **Use `{{ }}` for output** — never `{!! !!}` without HTMLPurifier or `strip_tags()`

## Architecture

```
app/
  Http/Controllers/
    Wallet/             # Dashboard, 2FA, HD wallet, transactions, discovery
    Citizen/            # Registry, identity, onboarding
    Congress/           # Proposals, voting, ballots, amendments
    Inventory/          # Asset management
    Logbook/            # Publications
    Planet/             # Mars map
  Models/
    Bads/               # BADS instrument chain-of-trust
  Livewire/             # Real-time components
  Includes/             # AppHelper, ECDSA, governance tiers
  Jobs/                 # ErrorTriageJob
  Mail/                 # ErrorTriageMail, ContactFormMail
  Http/Requests/        # FormRequest validation classes
config/
  blockchain.php        # Centralized blockchain/IPFS/Explorer URLs
  services.php          # OpenRouter, error triage config
tests/
  Feature/              # 12 test suites
  CreatesTestDatabase.php  # Shared schema setup trait
```

## Design System

The frontend uses a custom dark theme with CSS variables:
```css
--mr-void: #06060c;     /* Deepest background */
--mr-dark: #0c0c16;     /* Dark background */
--mr-surface: #12121e;  /* Card/surface background */
--mr-mars: #c84125;     /* Mars red (CTAs, accents) */
--mr-cyan: #00e4ff;     /* Cyan (links, highlights) */
--mr-green: #34d399;    /* Green (success, active) */
```

Fonts: Orbitron (headings), JetBrains Mono (data/code), Open Sans (body).

## Monitoring

- **Sentry** — Backend (PHP) + frontend (JS) error tracking
- **AI Error Triage** — Catches 500 errors, sends AI diagnosis to email via OpenRouter
- **Olympus Bot** — OpenClaw bot on Telegram/Discord/Gmail for citizen support
