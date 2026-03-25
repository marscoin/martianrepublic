# Martian Republic - Roadmap & Ideas

> Captured during development session, March 24-25, 2026

## Land Registry System (Mars Real Estate)

### Vision (credit: Matt Wise)
Citizens can register land claims on Mars via blockchain. Claims are enforced by the community of the Republic. If someone physically travels to Mars, they register (pay) for their claim. Until then, claims require annual renewal fees that go back to the Martian Republic for maintenance. This creates a community-governed property system with real economic incentives.

### Technical Architecture
- **Globe as UI**: The Three.js Mars globe serves as the visual interface for browsing/claiming land
- **Tile-based parcels**: Not pixel-based. Use a hierarchical tile system like web maps:
  - Level 0: Hemispheres
  - Level 5: ~100km districts (territory level)
  - Level 10: ~3km parcels (farm-sized claims)
  - Level 15: ~100m lots (residential)
  - Level 18: ~12m plots (building footprints)
- **Database**: MySQL table of parcels with lat/lon polygon bounds, linked to blockchain txids
- **Blockchain proof**: Each claim is an OP_RETURN transaction with parcel coordinates + IPFS metadata
- **Overlay rendering**: Color parcels on the Three.js globe based on ownership status
- **NASA integration**: Click any parcel to open NASA Mars Trek at that location for detailed terrain study

### Parcel Math (8K texture reference)
- 8K texture pixel = ~2km x 2km = 1,066 acres
- Mars total surface: 144.8 million km²
- For 1-acre parcels: would need ~36 billion entries (database, not texture)
- Practical approach: hierarchical subdivision, render only visible zoom level

### Economics: The Citizen Dividend Model

**The flywheel:**
1. Anyone can claim 1 pixel (~2km x 2km, ~1000 acres) of Mars for $99/year
2. That $99 (paid in MARS) gets distributed equally to ALL current citizens
3. Claim is recorded on-chain, valid for 1 year
4. Before the year expires, claimant must renew ($99 again) or lose the claim
5. Lapsed claims return to the unclaimed pool

**Why this creates a flywheel:**
- Citizens earn passive income from land claims → more people want to become citizens
- More citizens = larger community that cares about enforcement → claims feel more "real"
- More claims = more revenue distributed = more incentive to be a citizen
- The community grows because there's an actual economic reason to participate
- Eventually, when Mars colonization happens, this community IS the governance body

**The math (hypothetical):**
- 33.5 million pixels on Mars (8K texture)
- If 1% is claimed (335,000 parcels): $33.2M/year in renewal fees
- Split among 1,000 citizens: $33,150/citizen/year
- Split among 10,000 citizens: $3,315/citizen/year
- Split among 100,000 citizens: $331/citizen/year
- The equilibrium finds itself: more citizens dilute the dividend, but also increase the value/legitimacy

**Pricing thoughts:**
- $99/year = accessible, impulse purchase level
- Could be tiered: $99 for 1 pixel, $499 for a 3x3 block (named territory)
- First claim is discounted or free to bootstrap adoption
- Premium locations (Olympus Mons summit, Valles Marineris rim) could auction
- Transfer fee: 10% of last renewal price goes to citizens on resale

**On-chain implementation:**
- Claim tx: OP_RETURN with `LC_lat_lon_pixelid_ipfs_metadata`
- Renewal tx: OP_RETURN with `LR_pixelid_year`
- The scanner processes these like GP/CT/ED entries
- Smart distribution: MARS sent to a treasury address, then distributed pro-rata
- Or: use Marscoin's built-in multisig or a distribution smart contract

**What makes this different from every "buy a star" scam:**
- It's backed by a real governance community with skin in the game
- The blockchain makes it transparent and verifiable
- The annual renewal prevents dead speculation
- The citizen dividend creates aligned incentives
- When humans DO go to Mars, this community has already self-organized

---

## UI/UX Redesign Roadmap

### Design Language: "The Chamber" (Mission Control meets Scandinavian Civic Design)
- **Fonts**: Orbitron (display/headings), JetBrains Mono (data/labels), Open Sans (body)
- **Colors**: CSS variables (--mr-void, --mr-dark, --mr-surface, --mr-mars, --mr-cyan, --mr-green)
- **Patterns**: Status bars, pillar cards, scan-line texture, staggered animations, HUD overlays
- **Feel**: Alive, data-flowing, a sense of a living civilization

### Pages Completed
- [x] Congress dashboard (full redesign with status bar, pillars, live stats)
- [x] Mars map (Three.js globe with 8K texture, HUD overlay, NASA integration)
- [x] Navbar/mainnav (Orbitron labels, compact avatar, console tab bar)
- [x] Forum (dark theme, readable text, styled cards)

### Pages To Redesign
- [ ] Dashboard (main page after login - stats, onboarding, recent activity)
- [ ] Citizen registry (card-based layout instead of tables)
- [ ] Wallet open (hd-open - send/receive/balance with better layout)
- [ ] Wallet select (hd - card design for wallet list)
- [ ] Congress voting (proposal list with status badges, vote buttons)
- [ ] Congress ballot (voting interface)
- [ ] Inventory (item cards instead of tables)
- [ ] Logbook (publication cards)
- [ ] Landing page (public homepage - could use the Mars globe!)

### Component Library Needed
- Citizen card (avatar, name, address, status badge)
- Proposal card (title, status, vote count, blockchain anchor)
- Transaction item (amount, from/to, timestamp, tx link)
- Status badge (citizen/applicant/GP with color coding)
- Data stat cell (label + value, used in status bars)

---

## Governance Loop Testing Plan

### End-to-End Flow to Verify
1. **Send/Receive MARS** - Transfer between wallets, verify balance updates
2. **Blockchain messages** - Post signed messages, verify scanner picks them up
3. **Endorsement** - Endorse a citizen (DONE - trailing space bug fixed)
4. **Proposal creation** - Draft legislation, publish on-chain
5. **Voting** - Cast votes (Y/N/A) as different citizens
6. **Proposal resolution** - Verify threshold logic, passed/rejected status
7. **Multi-agent voting** - Use Astra, Valles, Olympus accounts for quorum testing

### Known Issues to Fix During Testing
- [ ] `/api/setendorsed` endpoint returns 404 (endorsement count not tracked)
- [ ] `showSend`, `showReceive`, `showTransactions` methods not implemented
- [ ] Trailing space bug exists in OTHER pages too (not just citizen registry)
- [ ] Multi-address send needs pebas `utxo-multi` integration
- [ ] Wallet unlock mnemonic input adds trailing spaces (root cause fix needed)

---

## Security Hardening Remaining

- [ ] Fix mnemonic input to NOT add trailing spaces (root cause in hd.blade.php)
- [ ] Implement proper localStorage encryption (stable key, not CSRF-based)
- [ ] Consolidate 3 wallet unlock paths into one robust flow
- [ ] Add wallet activity audit log
- [ ] Implement password attempt limiting
- [ ] Remove plaintext key from localStorage (transition period for WalletKey)

---

## Infrastructure

- [ ] Address 55 Dependabot vulnerabilities
- [ ] Update Livewire assets (showing "out of date" warning)
- [ ] Rebuild my_bundle.js with modern build pipeline
- [ ] Push pebas changes to GitHub (deploy key needs push access)
- [ ] Add Cloudflare Insights to CSP whitelist
- [ ] Update Node.js on server (currently v17.4.0 for PM2/pebas)

---

## Mars Globe Future Features

- [ ] Click globe to pin a location marker
- [ ] Overlay registered land claims as colored polygons
- [ ] Search by feature name (Olympus Mons, Valles Marineris, etc.)
- [ ] Named locations database with descriptions
- [ ] Distance measurement tool
- [ ] Day/night terminator line
- [ ] Phobos and Deimos orbiting
- [ ] Landing sites of real Mars missions marked
- [ ] Citizen locations (claimed territories) shown on globe

---

---

## Self-Governing Software (The Ultimate Vision)

### Proposals as Real Governance
The proposal system shouldn't just be for abstract policy. It should govern REAL things:

1. **Land registry rules** - Propose and vote on pricing, claim limits, renewal terms
2. **Marscoin protocol changes** - Propose features like seed phrase support in the wallet software, then vote on implementation
3. **Martian Republic code changes** - Propose UI/UX improvements, new features, bug fixes as formal proposals
4. **Auto-deploying governance** - The system restarts every 24 hours implementing changes that were voted upon

### The Self-Modifying Republic
Imagine: a citizen proposes "Add dark mode to the voting page." It goes through the standard proposal flow (discussion, voting period, threshold). If passed, the corresponding PR gets auto-merged and deployed. The software literally evolves based on the will of its citizens.

This is:
- **Direct democracy applied to software** - not just policy
- **A DAO that governs its own infrastructure** - not just a treasury
- **Dogfooding at its finest** - the governance tool governs itself

### Implementation Path
1. First: proposals are discussion-only (current state)
2. Next: proposals can reference GitHub PRs
3. Then: passed proposals trigger webhook to merge PR
4. Finally: CI/CD auto-deploys after successful vote + merge
5. Safety: 24-hour cool-down period, rollback vote mechanism

### Near-term Proposal Ideas for Testing
- "Add BIP39 seed phrase import to Marscoin Core wallet"
- "Set land claim pricing at $99/year per pixel"
- "Require 3 endorsements for citizenship (currently 5)"
- "Add Olympus Mons as a named landmark on the Mars globe"

---

*This is a living document. Ideas captured here should be turned into GitHub issues when ready for implementation.*
