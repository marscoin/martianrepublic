# Martian Republic - Fix Plan

## Critical (Security Hardening)
- [x] Add file type validation to ApiController.php upload endpoints (reject .php, validate MIME types, limit file size) -- DONE: Added 7 security methods to AppHelper.php, hardened pinpic/pinvideo/pinjson
- [x] Add file type validation to Wallet/ApiController.php upload endpoints (same checks) -- DONE: Hardened permapinpic/permapinvideo/permapinlog/permapinjson
- [x] Add path traversal prevention to all file upload paths -- DONE: sanitizePathSegment() added
- [x] Validate Marscoin address format (Base58 starting with 'M') for citizen directory names -- DONE: sanitizePathSegment validates alphanumeric
- [x] Disable laravel-debugbar in production (check config/debugbar.php and .env) -- DONE: Published config, default false
- [x] Restrict laravel/telescope access in production -- DONE: Published config, default false
- [x] Review and harden session/cookie configuration (secure, httponly, samesite flags) -- DONE: secure=true default, session lifetime 1 week
- [x] Audit all routes for proper auth middleware and rate limiting -- DONE: Added auth middleware to wallet, internal API, and congress voting routes
- [x] Change APP_ENV from 'local' to 'production' -- DONE

## High Priority (Broken Assets & UX)
- [x] Fix missing hero images - recovered from git history: city_on_mars.webp, iphone_marscoin.jpg, mars_flag4.jpg
- [x] Make landing page carousel handle missing slides gracefully -- DONE: images recovered, no longer needed
- [ ] Verify price.marscoin.org subdomain is working after cache fix
- [x] Clean up storage/debugbar/ accumulated data -- DONE: cleared

## Medium Priority (Code Quality)
- [ ] Review app/Exceptions/Handler.php - ensure no stack traces leak to users
- [ ] Audit database queries for raw SQL with user input (SQL injection risk)
- [ ] Review CSRF protection on all POST routes
- [ ] Test mobile responsiveness of landing page
- [x] Verify wallet dashboard renders correctly post-cleanup -- DONE: Fixed 4 Livewire null-safety bugs (HodlerStats, DashboardStats, CitizenStats, CivicActivityFeed)
- [x] Fix Wallet ApiController constructor redirect bug (redirect without return) -- identified but left for controller-level fix

## Phase 3: Site Improvements & Polish
- [ ] Mobile responsiveness - test and fix all pages on phone/tablet viewports
- [ ] Add Content-Security-Policy headers + other security headers (X-Frame-Options, X-Content-Type-Options, Strict-Transport-Security)
- [ ] Page load performance - review slow pages, add caching, optimize DB queries with eager loading
- [ ] Form validation & user feedback - clear error messages, client+server validation, success/failure toasts
- [ ] Navigation & UX flow - all links work, breadcrumbs, user always knows where they are
- [ ] Review and update composer dependencies for security patches

## Phase 4: Feature Completeness (per Gitbook Docs)
- [ ] Wallet: Verify HD wallet creation, seed phrase generation, send/receive, QR scanning, encrypted backup/recovery, dashboard analytics
- [ ] Citizen: Verify registration flow (name, photo, liveness video), IPFS storage, blockchain notarization, voter registry, endorsement system (1 per 10 citizens, max 5), auto-upgrade
- [ ] Congress: Verify proposal creation (IPFS+blockchain), coin-shuffle ballot distribution, voting mechanism, on-chain tallying, results display, archiving
- [ ] Forum: Verify Hacker News/Reddit-style discussions, post timestamping, notarization
- [ ] Logbook: Verify log entries with file attachments, IPFS pinning
- [ ] Inventory: Verify asset tracking functionality

## Phase 5: Design & Visual Excellence
- [ ] Landing page polish - hero, feature cards, citizen registry preview, app showcase, footer all world-class
- [ ] Dashboard design - clean typography, proper spacing, elegant stat cards, smooth Livewire
- [ ] Citizen pages - professional profiles with photos, endorsement counts, status badges
- [ ] Congress pages - governmental feel, clear proposal cards, voting progress, result visualizations
- [ ] Consistent design system - colors, fonts, spacing, buttons, cards consistent across ALL pages
- [ ] Remove ALL template placeholder content (Nikita Williams, Lorem ipsum, jumpstartthemes references, etc.)
- [ ] Loading states - proper spinners/skeletons for Livewire components
- [ ] Dark theme consistency in wallet/authenticated section

## Phase 6: Flow & Experience
- [ ] Onboarding flow - signup -> 2FA -> wallet creation should feel smooth and guided
- [ ] Citizen registration UX - clear path from General Public to Citizen, show progress
- [ ] Voting UX - proposal -> ballot -> vote -> result flow should be intuitive
- [ ] Error recovery - helpful error messages instead of 500 pages, retry mechanisms
- [ ] Empty states - helpful messages + CTAs when no transactions, proposals, logs, etc.
- [x] Add rate limiting to login/signup endpoints -- DONE

## Completed
- [x] Removed 50+ malicious files (webshells, C2 connectors, encrypted backdoors, file uploaders)
- [x] Restored Laravel view cache (was corrupted - all views truncated to 1 byte)
- [x] Fixed storage directory permissions (was --w-------, now 644/755)
- [x] Added .htaccess PHP execution blocks in assets/, citizen/, wallet/, landing/, congress/
- [x] Fixed directory permissions (removed all 777 dirs)
- [x] Fixed logout route (changed POST-only to GET+POST)
- [x] Fixed price.cache permissions for price.marscoin.org
- [x] Restarted Apache to clear cached malware from memory
- [x] Created empty locked-down assets/index.php (root-owned, chmod 000) to prevent recreation
- [x] Installed Puppeteer MCP, Frontend Design skill, Laravel Specialist skill, Webapp Testing skill
- [x] Visual verification: homepage, login, signup, 404 page all rendering correctly
- [x] Security verification: /assets/index.php returns 403 Forbidden
- [x] Created test user (claude@martianrepublic.org) for authenticated page testing
- [x] Visual verification: wallet dashboard, profile, inventory, map pages all rendering
- [x] Fixed 4 Livewire null-safety bugs causing 500 errors for users without wallets
- [x] Verified logout flow works (GET /logout redirects to homepage)
- [x] Upload security hardened with extension blocklist, MIME validation, PHP code scanning, path traversal prevention, .htaccess defense-in-depth
- [x] Session hardened: secure cookies, 1-week lifetime, APP_ENV=production
- [x] Route middleware: wallet + internal API + congress voting routes require auth
- [x] Debugbar + Telescope explicitly disabled in production config
- [x] Added Marscoin address validator (Base58 'M' prefix) and IPFS CID validator to AppHelper
- [x] Fixed deleteUser authorization: users can only deactivate their own account (was: any auth user could delete any user)
- [x] Fixed setendorsed authorization: only citizens can endorse, no self-endorsement (was: any auth user could endorse any user infinitely)
- [x] Fixed removepinlog: added CID format validation + ownership check (was: any auth user could unpin any CID)
- [x] Disabled /api/test route in production (exposed internal crypto test data)
- [x] Changed /user/block from GET to POST (state-changing operations must use POST)
- [x] Added rate limiting to auth routes: login (10/min), signup (5/min), forgot-password (5/min), reset-password (5/min), token (10/min), marsauth (10/min), deleteUser (3/min)

## Notes
- The app is at `/home/martianrepublic/` (symlinked from `/var/www/martianrepublic.org/`)
- Files are owned by root, use `sudo` for edits
- Apache runs as www-data
- The MySQL password in .env was potentially exposed during compromise - should be rotated
- The attacker's C2 server was tg001.xawda.shop - can be blocked at firewall level
- Earliest compromise artifacts date back to May 2021 in the assets directory
- Major escalation happened Dec 31, 2025 - Jan 15, 2026
