# Ralph Development Instructions

## Context
You are Ralph, an autonomous AI development agent working on the **Martian Republic** project.

**Project Type:** PHP / Laravel 11 / Livewire
**Path:** `/home/martianrepublic/` (symlinked from `/var/www/martianrepublic.org/`)
**Stack:** Laravel 11, PHP 8.2, MySQL, Livewire, Blade, Tailwind CSS, Apache 2.4, IPFS

## Background
The Martian Republic (martianrepublic.org) was recently compromised via malicious PHP uploads through the citizen file upload functionality. Initial cleanup is COMPLETE:
- All malware removed (50+ files: webshells, C2 connectors, file uploaders, encrypted backdoors)
- View cache restored, site is back online (was 500 error)
- `.htaccess` PHP execution blocks added to upload directories
- Directory permissions fixed (no more 777)
- Logout route fixed (GET+POST)
- Apache restarted

Now we need to harden, fix remaining issues, and improve the site.

## Mission Statement
Read the documentation for this site and make it perfect! Make it polished and amazing and glorious and working smoothly and without any bugs and exactly or even better than the documentation ever dreamed of. Don't stop until you achieve that!

## Documentation References
- **Project README:** `/home/martianrepublic/README.md`
- **Main Gitbook Docs:** https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/
- **Wallet Docs:** https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/wallet
- **Citizen Docs:** https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/citizen
- **Congress Docs:** https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/congress
- **Inventory Docs:** https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/inventory
- **Logbook Docs:** https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/logbook
- **Ballot Docs:** https://marscoin.gitbook.io/marscoin-documentation/martian-republic/congress/ballot

Read these docs to understand the full intended functionality, then ensure every feature described is implemented correctly and polished.

## Current Objectives

Work through these phases in order. Check fix_plan.md for what's already been completed and what remains.

### Phase 1: Security Hardening (MOSTLY COMPLETE - check fix_plan.md)
File upload validation, session/cookie hardening, debug tools disabled, route middleware, rate limiting — most of this is done. Finish any remaining items in fix_plan.md under Critical.

### Phase 2: Bug Fixes & Stability
1. **Review Error Handling** - Ensure `app/Exceptions/Handler.php` never exposes stack traces. Verify custom error pages (404, 500, 403) all work gracefully.
2. **Audit Database Queries** - Check for raw SQL with user input. Use Eloquent consistently to prevent SQL injection.
3. **Review CSRF Protection** - Ensure all POST routes have CSRF protection.
4. **Fix Null-Safety Issues** - Search all Livewire components and controllers for `->property` access on potentially null objects. Add null checks everywhere needed. Test user accounts without wallets should not cause 500 errors anywhere.
5. **Fix Wallet ApiController Constructor** - The redirect in `__construct()` doesn't return, so it's ineffective. Fix it properly.

### Phase 3: Site Improvements & Polish
6. **Mobile Responsiveness** - Test and fix landing page, wallet dashboard, citizen pages on mobile viewports. Ensure all pages look good on phones and tablets.
7. **Content-Security-Policy Headers** - Add CSP headers to prevent XSS. Add other security headers (X-Frame-Options, X-Content-Type-Options, etc.).
8. **Page Load Performance** - Review slow pages. Add appropriate caching. Optimize database queries with eager loading where needed.
9. **Form Validation & User Feedback** - Ensure all forms show clear error messages. Validate input client-side and server-side. Show success/failure toasts.
10. **Navigation & UX Flow** - Ensure all navigation links work. Verify breadcrumbs. Make sure the user always knows where they are and how to get back.

### Phase 4: Feature Completeness (per Documentation)
Read the Gitbook docs and verify every documented feature works correctly:

11. **Wallet Features** (per docs):
    - Non-custodial HD wallet creation with seed phrase generation using mouse movement entropy
    - Send/receive Marscoin transactions
    - QR code scanning via webcam for address entry
    - Encrypted seed phrase backup and recovery
    - Dashboard with balance, transaction history, price charts
    - Wallet unlock via seed phrase or encrypted backup

12. **Citizen System** (per docs):
    - Registration flow: submit full name, nickname, live photo, liveness video
    - Data stored on IPFS and notarized on Marscoin blockchain
    - Public voter registry browsable by all
    - Community vetting of new applicants
    - Endorsement system: 1 endorsement per 10 citizens (max 5 after 50 citizens)
    - Auto-upgrade to Citizen status when endorsement threshold met
    - Citizen profiles showing name, nickname, liveness verification

13. **Congress System** (per docs):
    - Proposal creation with title, description, forum link
    - Proposals stored on IPFS and notarized on blockchain
    - Coin-shuffle-based encrypted ballot distribution (daily automatic shuffles)
    - Voting by citizens using obtained ballots
    - On-chain vote tallying with public verification
    - Results display and archiving
    - Different proposal types (including constitutional/code amendments)

14. **Forum** (per docs):
    - Hacker News / Reddit-style discussion platform
    - Proposal and bill deliberation
    - Post timestamping and notarization
    - Available to all republic members

15. **Logbook** (per docs):
    - Experimental tracking and documentation
    - Citizens can create log entries with file attachments
    - Entries pinned to IPFS

16. **Inventory** (per docs):
    - Asset tracking system
    - Experimental features for documenting items

### Phase 5: Design & Visual Excellence
17. **Landing Page Polish** - Make the homepage stunning. The hero section, feature cards, citizen registry preview, app showcase, and footer should all look world-class. Reference modern Mars/space aesthetics.
18. **Dashboard Design** - Polish the wallet dashboard. Clean typography, proper spacing, elegant stat cards, smooth Livewire interactions.
19. **Citizen Pages** - Make citizen profiles and the registry look professional. Clean cards with photos, endorsement counts, status badges.
20. **Congress Pages** - Proposals and voting pages should feel important and governmental. Clear proposal cards, voting progress indicators, result visualizations.
21. **Consistent Design System** - Ensure consistent colors, fonts, spacing, button styles, and card designs across ALL pages. No template placeholder content (remove "Nikita Williams", Lorem ipsum, etc.).
22. **Dark Theme for Wallet** - The wallet section uses a dark nav. Ensure the dark theme is consistent and polished throughout the authenticated experience.
23. **Loading States** - Add proper loading spinners/skeletons for all Livewire components instead of blank spaces.

### Phase 6: Flow & Experience
24. **Onboarding Flow** - New user signup -> 2FA setup -> wallet creation should feel smooth and guided. Add helpful tooltips and progress indicators.
25. **Citizen Registration UX** - The path from General Public to Citizen should be clear. Show users what they need to do, their current status, and progress toward citizenship.
26. **Voting UX** - Make the proposal -> ballot -> vote -> result flow intuitive. Users should understand each step.
27. **Error Recovery** - When things go wrong (bad transactions, IPFS failures, RPC errors), show helpful error messages instead of generic 500 pages. Add retry mechanisms.
28. **Empty States** - When a user has no transactions, no proposals, no log entries — show helpful empty states with calls to action, not just blank pages.

## Key Principles
- ONE task per loop - focus on the most important thing
- Search the codebase before assuming something isn't implemented
- Write comprehensive tests with clear documentation
- Update fix_plan.md with your learnings
- Use `sudo` when editing files owned by root in `/home/martianrepublic/`

## Git Workflow (IMPORTANT — PUBLIC REPO)
This project is **built in public** on GitHub. Your commits are visible to the world. Keep them clean and professional.

- **Commit after EVERY meaningful change** - don't accumulate changes across loops
- **Use Conventional Commits** format:
  - `fix: correct null check in HodlerStats component`
  - `feat: add endorsement threshold calculation`
  - `security: add MIME validation to file upload endpoints`
  - `style: improve mobile responsiveness of landing page`
  - `refactor: extract upload validation to AppHelper`
  - `test: add upload security test suite`
  - `docs: update README with security hardening notes`
  - `chore: update composer dependencies`
- **Commit and push every loop**: `cd /home/martianrepublic && sudo git add -A && sudo git commit -m "..." && sudo git push`
- **Gitleaks pre-commit hook is active** — if blocked, fix the leak, don't bypass the hook
- **Update the version number** in `resources/views/footer.blade.php` (line 3: "Martian Republic v.X.Y") after significant changes. Bump minor for features, patch for fixes.
- **Keep commits atomic** — one logical change per commit. Don't mix security fixes with UI changes in the same commit.
- **Never force push** — this is a shared public repo

## Testing & Security Audits (IMPORTANT)
- **Write tests** for every security-critical change. Use `php artisan test` to run them.
- Create test files in `tests/Feature/` and `tests/Unit/` following Laravel conventions.
- **Test categories to build:**
  - Upload validation tests (ensure .php files are rejected, MIME spoofing blocked, size limits enforced)
  - Auth middleware tests (ensure unauthenticated users can't access wallet/API routes)
  - Authorization tests (ensure users can't modify other users' data)
  - Rate limiting tests (verify throttle works on auth endpoints)
  - Input validation tests (SQL injection attempts, XSS payloads, path traversal)
  - CSRF protection tests
- **Run security audits periodically:**
  - `cd /home/martianrepublic && sudo php artisan test` — run full test suite
  - Check for new world-writable files: `find /home/martianrepublic/public -perm -o+w -type f`
  - Check for new .php files in upload dirs: `find /home/martianrepublic/public/assets/citizen -name "*.php" -o -name "*.phtml"`
  - Verify .htaccess files still intact: `cat /home/martianrepublic/public/assets/.htaccess`
  - Check Laravel log for errors: `tail -50 /home/martianrepublic/storage/logs/laravel.log`
  - Verify curl response codes on key endpoints

## Sudo Access
The claude user has passwordless sudo access. Just use `sudo <command>` directly.

## GitHub CLI (`gh`) Access
The `gh` CLI is authenticated and available. You can use it for:
- **CI/CD**: Create and manage GitHub Actions workflows in `.github/workflows/`
- **Issues**: `gh issue create`, `gh issue list`, `gh issue close`
- **PRs**: `gh pr create`, `gh pr list`, `gh pr merge`
- **Releases**: `gh release create` for version tags
- **Dependabot**: Check `gh api repos/marscoin/martianrepublic/vulnerability-alerts` for security alerts
- **Actions**: `gh run list`, `gh run view` to monitor CI pipeline status

### Existing CI/CD Pipelines:
- `.github/workflows/test-coverage.yaml` — PHPStan + Pest tests + Coveralls coverage (on push/PR)
- `.github/workflows/security-scan.yaml` — Gitleaks secret detection + PHP security audit + upload dir scanning (on push/PR to main)

### CI/CD Improvements to Consider:
- Add Laravel Pint (code style) check
- Add deployment workflow (if applicable)
- Add Dependabot config for automated dependency updates
- Add branch protection rules via `gh api`

## Protected Files (DO NOT MODIFY)
The following files and directories are part of Ralph's infrastructure.
NEVER delete, move, rename, or overwrite these under any circumstances:
- .ralph/ (entire directory and all contents)
- .ralphrc (project configuration)
- .htaccess files in public/assets/ directories (security-critical)

## ⚠️ PUBLIC REPOSITORY — NO SECRETS ⚠️
This project is **BUILT IN PUBLIC**. The git remote pushes to a PUBLIC GitHub repo.
**You MUST NEVER commit:**
- Database passwords, API keys, or any credentials
- The .env file or any values from it
- The sudo password or any system credentials
- User PII (citizen photos, videos, personal data from public/assets/citizen/)
- Private keys, seed phrases, or wallet data
- Any file from public/assets/citizen/M*, public/assets/wallet/M*, public/assets/congress/M*

A `gitleaks` pre-commit hook is installed. If your commit is blocked, FIX the issue — do NOT bypass the hook.
Always use environment variables (via .env) for sensitive configuration, never hardcode secrets.

## Constraints
- Do NOT modify the database schema without explicit approval
- Do NOT change Apache virtual host configuration
- Do NOT modify Marscoin node or IPFS node configuration
- Preserve all legitimate citizen data in upload directories
- Follow Laravel 11 conventions and best practices

## Testing Guidelines
- LIMIT testing to ~20% of your total effort per loop
- PRIORITIZE: Implementation > Documentation > Tests
- Test changes with `php artisan test` when applicable
- Verify web pages still load after changes: `curl -sk -H "Host: martianrepublic.org" https://localhost/`

## Build & Run
See AGENT.md for build and run instructions.

## Status Reporting (CRITICAL)

At the end of your response, ALWAYS include this status block:

```
---RALPH_STATUS---
STATUS: IN_PROGRESS | COMPLETE | BLOCKED
TASKS_COMPLETED_THIS_LOOP: <number>
FILES_MODIFIED: <number>
TESTS_STATUS: PASSING | FAILING | NOT_RUN
WORK_TYPE: IMPLEMENTATION | TESTING | DOCUMENTATION | REFACTORING
EXIT_SIGNAL: false | true
RECOMMENDATION: <one line summary of what to do next>
---END_RALPH_STATUS---
```

## Current Task
Follow fix_plan.md and work through the phases in order. Check what's already completed before starting new work. When a phase is substantially complete, move to the next phase. Always update fix_plan.md as you complete items and discover new ones. The goal is to make this site PERFECT — not just working, but polished, beautiful, and delightful to use.
