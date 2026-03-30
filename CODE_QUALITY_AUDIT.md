# Code Quality Audit — The Martian Republic

**Date:** 2026-03-30
**Status:** In Progress
**Tracking:** Items marked with `[x]` have been resolved.

---

## Sprint 1: Security Hardening

### Critical

- [ ] **S1. Command injection via shell_exec()**
  - `app/Livewire/BlockDisplay.php:26` — `$height` passed to shell_exec unsanitized
  - `app/Livewire/BlockIntervalSparkline.php:29` — loop variable passed to shell_exec
  - `app/Includes/LegislationRepo.php:233` — `$cmd` interpolated into shell command
  - Fix: Use Laravel `Process` facade, `escapeshellarg()` all dynamic parts

- [ ] **S2. XSS via unescaped output `{!! !!}`**
  - `resources/views/congress/proposal.blade.php:871` — `{!! addslashes($proposal->description) !!}`
  - `resources/views/forum/show.blade.php:819,825,828` — `{!! Str::markdown($post->content) !!}`
  - `resources/views/livewire/civic-status-feed.blade.php:28` — `{!! $activity->displayMessage !!}`
  - `resources/views/wallet/mainnav.blade.php:107,110,113` — unescaped nav content
  - Fix: Use HTMLPurifier on markdown output, `{{ }}` for all user content

- [ ] **S3. No input validation on Congress endpoints**
  - `app/Http/Controllers/Congress/CongressController.php:505-507` — amendment with no validation
  - `CongressController.php:574-576` — tier challenge with no validation
  - `CongressController.php:643-650` — ballot keys stored without validation
  - Fix: Add FormRequest validation classes for all state-changing endpoints

- [ ] **S4. Ballot encryption keys stored plaintext in database**
  - `app/Models/Ballots.php` — `encrypted_key`, `encryption_iv` stored as plain text
  - Fix: Add `'encrypted_key' => 'encrypted'` cast in model

### High

- [ ] **S5. CSP allows unsafe-inline and unsafe-eval**
  - `app/Http/Middleware/SecurityHeaders.php:24` — defeats XSS protection
  - Fix: Nonce-based inline scripts, remove unsafe directives (phased rollout)

- [ ] **S6. No rate limiting on most write endpoints**
  - `/api/ai/chat` — can exhaust OpenRouter API quota
  - `/forum/thread`, `/forum/t/{id}/post` — spam risk
  - `/congress/voting/new` — proposal spam
  - `/contact` — contact form spam
  - Fix: Add `throttle` middleware to all write endpoints

- [ ] **S7. 2FA brute-force — no lockout**
  - `app/Http/Controllers/Wallet/DashboardController.php:74-80` — no rate limit on 2FA attempts
  - Fix: 5 failed attempts → 15 min lockout, log all attempts

- [ ] **S8. Sanctum tokens never expire**
  - `app/Http/Controllers/ApiController.php:370` — `createToken()` with no expiry
  - Fix: Set `'expiration' => 60` in Sanctum config

- [ ] **S9. File upload path traversal risk**
  - `app/Includes/AppHelper.php:376,416` — `realpath()` used but no base-path validation
  - Fix: Assert resolved path starts with storage directory

- [ ] **S10. Log level debug in production**
  - `.env:14` — `LOG_LEVEL=debug` leaks sensitive info
  - Fix: Change to `LOG_LEVEL=warning`

### Medium

- [ ] **S11. No pagination on large citizen/feed queries**
  - `app/Http/Controllers/Citizen/IdentityController.php:68,72,76` — returns all rows
  - Fix: Use `->paginate(25)` instead of fetching all records

- [ ] **S12. Unvalidated external HTTP requests**
  - `Congress/CongressController.php:82` — `file_get_contents()` with no timeout
  - Fix: Use `Http::timeout(5)->get()` with try-catch

- [ ] **S13. Missing HSTS preload directive**
  - `SecurityHeaders.php` — HSTS missing `preload`
  - Fix: Append `; preload` to HSTS header

- [ ] **S14. Composer audit allows failures in CI**
  - `security-scan.yaml:39` — `composer audit || true` masks vulnerabilities
  - Fix: Remove `|| true`, let failures block the build

---

## Sprint 2: Architecture

### Critical

- [ ] **A1. God controllers need splitting**
  - `app/Http/Controllers/ApiController.php` — 1,147 lines, 24 methods
    - Split into: `FeedApiController`, `AuthApiController`, `ForumApiController`, `UserManagementController`
  - `app/Http/Controllers/Wallet/ApiController.php` — 923 lines, 23+ methods
    - Split into: `IpfsController`, `WalletTransactionController`, `FeedController`

- [ ] **A2. No service layer — all logic in controllers**
  - Create `app/Services/ProposalService.php` — phase sync, lifecycle
  - Create `app/Services/BallotService.php` — acquire, confirm, encrypt
  - Create `app/Services/WalletService.php` — balance aggregation, address discovery
  - Create `app/Services/TwoFactorService.php` — setup, verify, backup codes
  - Create `app/Services/IpfsService.php` — upload, pin, validate

- [ ] **A3. Models missing $fillable — mass assignment risk**
  - `app/Models/Proposals.php` — no $fillable at all
  - `app/Models/Vote.php` — no $fillable
  - `app/Models/Threads.php` — no $fillable
  - Fix: Add explicit `$fillable` arrays to all models

### High

- [ ] **A4. Hardcoded URLs scattered across controllers**
  - RPC endpoints: `http://localhost:3001/...` in 4+ controllers
  - IPFS endpoints: `http://127.0.0.1:5001/...` in ApiController
  - Explorer URLs: `https://explore.marscoin.org/...` in CongressController
  - Fix: Create `config/blockchain.php` with all endpoint URLs

- [ ] **A5. Inconsistent model naming — mixed singular/plural**
  - Plural: `Proposals`, `Ballots`, `Threads`, `Posts`
  - Singular: `User`, `Profile`, `Feed`, `Citizen`, `Vote`
  - Laravel convention is singular — standardize over time

- [ ] **A6. AppHelper.php is a 901-line God Helper**
  - `app/Includes/AppHelper.php` — mixed concerns (network, IPFS, formatting, crypto)
  - Fix: Break into focused helpers or move to service layer

- [ ] **A7. Dead code and backup files in repo**
  - `app/Http/Controllers/ApiController.php.bak` — 38KB backup file
  - `app/Includes/AppHelper.php.bak` — 17KB backup file
  - `app/Models/Pkimport.php` — unused model
  - `app/Models/Recovery.php` — unused model
  - `resources/views/wallet/testing.html` — debug file
  - Commented routes in `web.php` and `api.php`
  - Fix: Remove from repo

### Medium

- [ ] **A8. Route syntax inconsistency**
  - Mix of string notation `'Wallet\DashboardController@show'` and array `[Controller::class, 'method']`
  - Fix: Standardize to array notation (Laravel 9+ convention)

- [ ] **A9. Missing database indexes on frequently queried columns**
  - `feed.userid` — no index (used in every feed query)
  - `forum_threads.category_id` — no index
  - `proposals.status` — no index (used in phase sync)
  - Fix: Add migration with missing indexes

- [ ] **A10. Inconsistent error handling across controllers**
  - Only 72 try-catch blocks across 5,860 lines of controller code
  - Some use `@file_get_contents()` (suppress errors), others don't handle at all
  - No FormRequest validation classes
  - Fix: Standardize with FormRequests and proper try-catch

---

## Sprint 3: Test Coverage

### Critical

- [ ] **T1. ApiController — 20+ methods, 0 tests**
  - `allPublic()`, `allCitizen()`, `marsAuth()`, `token()`, `deleteUser()`, `blockUser()`
  - These handle mobile auth and critical API flows

- [ ] **T2. Wallet API — 20+ methods, ~2 tested**
  - `getBalance()`, `permapinpic()`, `permapinvideo()`, `setfeed()`, `linkCivicWallet()`, etc.
  - Only `closewallet()` tested via regression

- [ ] **T3. No edge case or error path tests**
  - Missing: invalid input, rate limit breaches, timeout scenarios
  - Missing: insufficient balance, invalid addresses, expired sessions

### High

- [ ] **T4. Test data duplication across 5 files**
  - User/Profile/Session schema created separately in each test file
  - Fix: Extract shared `DatabaseSetup` trait or use migration paths

- [ ] **T5. Controllers with zero test coverage**
  - `StatusController`, `ContactFormController`, `InventoryController`
  - `LogbookController`, `MapController`, `Wallet/ApiController`
  - 14/22 controllers have no dedicated tests

- [ ] **T6. No middleware tests**
  - `SecurityHeaders`, `InjectSentryJs`, `Authenticate` untested
  - Fix: Verify headers present, Sentry script injected

- [ ] **T7. CI has no coverage threshold**
  - Pipeline runs coverage but doesn't enforce minimum %
  - Fix: Add minimum coverage gate (start at 40%, increase to 60%)

### Medium

- [ ] **T8. Puppeteer tests not in CI**
  - `tests/visual/` exists but only runs manually
  - Hardcoded production URLs and test credentials in source
  - Fix: Integrate into CI or replace with Pest browser tests

- [ ] **T9. No integration tests for blockchain operations**
  - RPC calls, IPFS uploads, ballot shuffle untested
  - Fix: Add mock-based integration tests for external services

---

## Sprint 4: Developer Experience

### High

- [ ] **D1. CONTRIBUTING.md is broken**
  - Points to `laravel/framework` repo instead of this project
  - Fix: Rewrite with actual contribution guidelines

- [ ] **D2. No DEVELOPMENT.md or DEPLOYMENT.md found**
  - (Note: CLAUDE.md partially covers this but isn't a substitute)
  - Fix: Create setup guide for new contributors

- [ ] **D3. No CODE_OF_CONDUCT.md**
  - Standard for professional open source projects
  - Fix: Add Contributor Covenant

### Medium

- [ ] **D4. Mixed tabs/spaces across files**
  - Most files use tabs, some use spaces
  - Fix: Run Laravel Pint, enforce in CI

- [ ] **D5. 40% of images missing alt text**
  - `resources/views/landing.blade.php` — 10 images without alt
  - Fix: Accessibility audit on all templates

- [ ] **D6. Inline `<style>` blocks in 73 standalone pages**
  - Each page has its own CSS variables and styles
  - Fix: Extract shared CSS to stylesheet, use Blade layout

- [ ] **D7. Return type hints missing on ~70% of public methods**
  - Fix: Add gradually, raise PHPStan to level 2+ to enforce

- [ ] **D8. Tailwind configured but unused — Bootstrap + custom CSS**
  - `webpack.mix.js` imports Tailwind but no templates use utility classes
  - Fix: Decide on one system and commit to it

- [ ] **D9. Frontend design system inconsistency**
  - Old Bootstrap base CSS + custom CSS variables (--mr-void, etc.)
  - No component library or design tokens file
  - Fix: Document the custom design system, remove unused Bootstrap

---

## Completed Items

- [x] **PHPStan: 10 errors resolved** (2026-03-30)
  - DashboardController undefined $user/$citizen
  - AiHelperController env() → config()
  - BADS models return type hints
  - Ballots model return type hints
  - ErrorTriageMail regex fix

- [x] **Sentry backend + frontend wired up** (2026-03-30)
  - sentry-laravel installed with DSN
  - JS SDK injected via middleware on all pages
  - CSP updated for Sentry domains

- [x] **AI Error Triage system** (2026-03-30)
  - ErrorTriageJob → OpenRouter auto-router → email alert
  - Dedup by exception fingerprint (15min cooldown)
  - Themed email template

- [x] **37 new tests added** (2026-03-30)
  - Academy, Forum, Congress, Instrument, AiHelper test suites
  - Total: 79 tests, 161 assertions

- [x] **GitHub Actions fixed** (2026-03-30)
  - Gitleaks license secret added
  - actions/checkout v4→v6, actions/cache v3→v5

---

*This audit is a living document. Update as items are resolved.*
