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
- [x] Review app/Exceptions/Handler.php -- DONE: APP_DEBUG=false, custom 500/403/404 pages all in place
- [x] Audit database queries -- DONE: All queries use parameterized bindings or are hardcoded. No injection risks.
- [x] Review CSRF protection -- DONE: All web POST routes have CSRF via web middleware group, API routes use Sanctum
- [ ] Test mobile responsiveness of landing page
- [x] Verify wallet dashboard renders correctly post-cleanup -- DONE: Fixed 4 Livewire null-safety bugs (HodlerStats, DashboardStats, CitizenStats, CivicActivityFeed)
- [x] Fix Wallet ApiController constructor -- DONE: Removed redundant middleware('auth') + removed all 12 redundant Auth::check() blocks (route group handles auth)
- [x] Fix cacheproposal crash -- DONE: `$post->thread->id = 2` (null property access) → `$post->thread_id = 2`. Also added payload validation, null-safety on citcache, used AppHelper::isValidCID
- [x] Fix null-safety bugs -- DONE: setfeed, closewallet, rejectApplication, setfullname - all now check for null Profile/Citizen/User before accessing properties
- [x] Fix getTransactions URL injection -- DONE: Added Marscoin address validation + urlencode() before passing to external API
- [x] Fix dismissAlert session injection -- DONE: Whitelist of allowed alert type keys prevents arbitrary session key manipulation
- [x] Remove dead routes -- DONE: Commented out sendFrom, newAddress, importPK, redeem, getAccount (routes existed but no controller methods)
- [x] Remove duplicate isValidCID -- DONE: Deleted protected isValidCID() from Wallet\ApiController, now uses AppHelper::isValidCID

## Phase 3: Site Improvements & Polish
- [x] Mobile responsiveness -- DONE: Landing page (masthead, buttons, clients list, iframe, footer), wallet dashboard (table-responsive, portlets), congress voting (voting-nav, price-box, posts, comments). Removed duplicate Voter Registry section. Fixed stray `<style>` tag in voting.css.
- [x] Security headers -- DONE: SecurityHeaders middleware with full CSP, X-Frame-Options, X-Content-Type-Options, Strict-Transport-Security, Referrer-Policy, Permissions-Policy
- [x] Page load performance -- DONE: Increased balance/received/sent API cache TTL 5s→300s, cached dashboard stats (forum count, proposal count), cached citizen registry queries (everyPublic/everyCitizen/everyApplicant), replaced 3 uncached network API calls with cached AppHelper::getMarscoinNetworkInfo(), fixed N+1 in getAllCategoriesWithThreads (single joined query), removed excessive microtime debug logging, removed dead showChart endpoint with hardcoded API key
- [x] Form validation & user feedback -- DONE: Added server-side validation to ContactFormController, added validation error display + old() persistence to support page, added global toastr flash handler to mainnav (success/error/warning), fixed wallet failWallet() using error flash instead of success
- [x] Navigation & UX flow -- DONE: Fixed hardcoded martianrepublic.local URL in newproposal, fixed external URL in navbarright to relative path, replaced all placeholder content (Lorem ipsum, Nikita Williams, jumpstartthemes, MVP Ready), rewrote profile page with actual user data, fixed social links in footers
- [ ] Review and update composer dependencies for security patches

## Phase 4: Feature Completeness (per Gitbook Docs)
- [x] Wallet: Dashboard analytics (balance, transactions, price charts) WORKING. QR scanning WORKING. HD wallet creation partial (seed generation/derivation/send not implemented - needs external crypto service). Encrypted backup/recovery minimal.
- [x] Citizen: Registration flow WORKING (name, photo, liveness video → IPFS → blockchain). Voter registry WORKING (cached queries). Endorsement system NOW COMPLETE: duplicate prevention, limit enforcement (1 per 10 citizens, max 5), auto-upgrade to citizen when endorsement threshold met.
- [x] Congress: Proposal creation WORKING (IPFS+blockchain via cacheproposal API, auto-creates forum thread). Voting UI WORKING. Vote tallying WORKING. Fixed voting API using test URL instead of production (was pointing to localhost:3001). Removed dead createproposal route.
- [x] Forum: API endpoints WORKING (thread listing, nested comments, post creation). Web UI exists via vendor/forum package. Post notarization not implemented.
- [x] Logbook: Entry creation WORKING (IPFS pinning + file attachments via permapinlog API). Notarization WORKING (blockchain signing via client-side JS). Delete WORKING. Added empty states. Fixed uncached balance API call (was hitting explore.marscoin.org directly, now uses cached AppHelper::getMarscoinBalance).
- [ ] Inventory: Only scaffolding exists (route + empty controller + "under construction" view). Needs full implementation.
- [ ] Wallet HD: Seed phrase generation, BIP32/BIP44 key derivation, address generation, send transactions, backup/recovery - all missing. Crypto libraries bundled but not called. Would need significant crypto engineering.

## Phase 5: Design & Visual Excellence
- [x] Landing page polish -- DONE: Complete dark space-tech redesign with star canvas, scroll reveals, feature grid, mission principles, ticker, partner logos, CTA, responsive
- [x] ALL public pages redesigned -- DONE (by human + claude): All 11 standalone public-facing pages converted to dark space-tech theme:
  - Landing (/), Login, Signup, Forgot Password, Reset Password, Privacy, TOS, Support, Status, 2FA Challenge, 2FA Setup
  - Shared architecture: mr-theme.css + partials/public-head.blade.php + partials/public-nav.blade.php + partials/public-footer.blade.php
  - Fonts: Chakra Petch (display), DM Sans (body), JetBrains Mono (mono). Colors: --mr-void, --mr-mars, --mr-cyan, --mr-amber
  - Cache-busted CSS via {{ time() }} query param in public-head partial
- [x] Consistent design system -- DONE: mr-theme.css with CSS custom properties, shared partials (public-head, public-nav, public-footer), dark theme across all public pages
- [x] Remove ALL template placeholder content -- DONE (completed in Phase 3)
- [ ] Dashboard design - clean typography, proper spacing, elegant stat cards, smooth Livewire (INTERNAL/AUTHENTICATED PAGES - the wallet/* section)
- [ ] Citizen pages - professional profiles with photos, endorsement counts, status badges (INTERNAL)
- [ ] Congress pages - governmental feel, clear proposal cards, voting progress, result visualizations (INTERNAL)
- [ ] Loading states - proper spinners/skeletons for Livewire components

## Phase 6: Flow & Experience
- [ ] Onboarding flow - signup -> 2FA -> wallet creation should feel smooth and guided
- [ ] Citizen registration UX - clear path from General Public to Citizen, show progress
- [ ] Voting UX - proposal -> ballot -> vote -> result flow should be intuitive
- [ ] Error recovery - helpful error messages instead of 500 pages, retry mechanisms
- [x] Empty states - PARTIAL: Added empty states to logbook (my entries + all entries). Congress active proposals already has empty state. Remaining: wallet transactions, citizen registry, forum.
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
