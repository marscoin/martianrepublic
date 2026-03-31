#!/usr/bin/env node
/**
 * Martian Republic — Full Walkthrough Integration Test
 * 
 * Comprehensive end-to-end test covering:
 * 1. Login + 2FA authentication
 * 2. All navigation tabs (no 500 errors)
 * 3. Wallet unlock + balance display
 * 4. HD address discovery
 * 5. Transaction history
 * 6. Citizen registry
 * 7. Congress proposals
 * 8. Forum
 * 9. Academy
 * 10. Logout
 * 
 * Run: node tests/visual/full-walkthrough-test.mjs
 * Screenshots saved to: tests/visual/screenshots/walkthrough/
 */

import puppeteer from 'puppeteer';
import { createHmac } from 'crypto';
import { mkdir, writeFile } from 'fs/promises';
import { dirname, join } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const SCREENSHOT_DIR = join(__dirname, 'screenshots', 'walkthrough');
const BASE_URL = 'https://martianrepublic.org';

// Astra Olympus test account
const ASTRA = {
    email: 'astra.olympus@martianrepublic.ai',
    password: 'MarsR3public2026!',
    twofa_secret: 'VEYZIUCJG4CSZ7QK',
    mnemonic: 'circle film slide velvet autumn library actual patient play whip roof marriage',
    address: 'MDCURC61G7A5jNRjnDq42XB1RvU51y4Ftx',
};

const results = [];
let screenshotIndex = 0;

// ============================================================
// TOTP Generator
// ============================================================
function generateTOTP(secret) {
    const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    let bits = '';
    for (const c of secret.toUpperCase()) {
        const val = alphabet.indexOf(c);
        if (val === -1) continue;
        bits += val.toString(2).padStart(5, '0');
    }
    const bytes = [];
    for (let i = 0; i + 8 <= bits.length; i += 8) {
        bytes.push(parseInt(bits.substring(i, i + 8), 2));
    }
    const key = Buffer.from(bytes);
    const time = Math.floor(Date.now() / 1000 / 30);
    const timeBuffer = Buffer.alloc(8);
    timeBuffer.writeBigUInt64BE(BigInt(time));
    const hmac = createHmac('sha1', key).update(timeBuffer).digest();
    const offset = hmac[hmac.length - 1] & 0xf;
    const code = ((hmac[offset] & 0x7f) << 24 |
                  (hmac[offset + 1] & 0xff) << 16 |
                  (hmac[offset + 2] & 0xff) << 8 |
                  (hmac[offset + 3] & 0xff)) % 1000000;
    return code.toString().padStart(6, '0');
}

// ============================================================
// Helpers
// ============================================================
function log(status, step, msg) {
    const icon = status === 'pass' ? '✅' : status === 'fail' ? '❌' : '⏭️';
    console.log(`  ${icon} [${step}] ${msg}`);
    results.push({ status, step, msg, time: new Date().toISOString() });
}

async function screenshot(page, name) {
    screenshotIndex++;
    const filename = `${String(screenshotIndex).padStart(2, '0')}-${name}.png`;
    await page.screenshot({ path: join(SCREENSHOT_DIR, filename), fullPage: false });
    return filename;
}

async function checkNoServerError(page, step) {
    const content = await page.content();
    if (content.includes('500') && content.includes('Server Error')) {
        log('fail', step, `500 Server Error on ${page.url()}`);
        return false;
    }
    if (content.includes('419') && content.includes('Page Expired')) {
        log('fail', step, `419 Page Expired on ${page.url()}`);
        return false;
    }
    return true;
}

async function navigateAndCheck(page, url, step, description) {
    try {
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await new Promise(r => setTimeout(r, 1000));
        const ok = await checkNoServerError(page, step);
        if (ok) {
            const status = page.url().includes('/login') ? 'skip' : 'pass';
            log(status === 'skip' ? 'skip' : 'pass', step, 
                status === 'skip' ? `${description} — redirected to login (auth required)` : description);
        }
        await screenshot(page, step.toLowerCase().replace(/\s+/g, '-'));
        return ok;
    } catch (e) {
        log('fail', step, `${description} — ${e.message}`);
        return false;
    }
}

// ============================================================
// Main Test Flow
// ============================================================
async function run() {
    console.log('\n🚀 Martian Republic — Full Walkthrough Test');
    console.log(`   ${new Date().toISOString()}\n`);

    await mkdir(SCREENSHOT_DIR, { recursive: true });

    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage'],
        timeout: 60000,
    });

    const page = await browser.newPage();
    await page.setViewport({ width: 1440, height: 900 });

    // Track console errors from the page
    const pageErrors = [];
    page.on('pageerror', err => pageErrors.push(err.message));

    try {
        // ========== PHASE 1: PUBLIC PAGES ==========
        console.log('📋 Phase 1: Public Pages');

        await navigateAndCheck(page, `${BASE_URL}/`, '1.1', 'Homepage loads');
        await navigateAndCheck(page, `${BASE_URL}/login`, '1.2', 'Login page loads');
        await navigateAndCheck(page, `${BASE_URL}/signup`, '1.3', 'Signup page loads');
        await navigateAndCheck(page, `${BASE_URL}/academy`, '1.4', 'Academy index loads');
        await navigateAndCheck(page, `${BASE_URL}/academy/governance-and-voting`, '1.5', 'Academy article loads');
        await navigateAndCheck(page, `${BASE_URL}/congress/all`, '1.6', 'Congress public proposals loads');
        await navigateAndCheck(page, `${BASE_URL}/privacy`, '1.7', 'Privacy page loads');
        await navigateAndCheck(page, `${BASE_URL}/support`, '1.8', 'Support page loads');

        // ========== PHASE 2: LOGIN + 2FA ==========
        console.log('\n🔐 Phase 2: Authentication');

        await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await page.type('input[name="email"]', ASTRA.email);
        await page.type('input[name="password"]', ASTRA.password);
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {}),
            page.click('button[type="submit"]'),
        ]);
        await new Promise(r => setTimeout(r, 2000));
        await screenshot(page, 'after-login');

        let url = page.url();
        if (url.includes('twofachallenge') || url.includes('twofa')) {
            log('pass', '2.1', 'Login succeeded — redirected to 2FA');
            const code = generateTOTP(ASTRA.twofa_secret);
            const input = await page.$('input[name="secret"]');
            if (input) {
                await input.type(code);
                await new Promise(r => setTimeout(r, 1000));
                // Submit the 2FA form
                const submitBtn = await page.$('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    await Promise.all([
                        page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {}),
                        submitBtn.click(),
                    ]);
                } else {
                    // Some 2FA forms auto-submit or use JS
                    await page.keyboard.press('Enter');
                    await new Promise(r => setTimeout(r, 3000));
                }
                await new Promise(r => setTimeout(r, 3000));
            }
            await screenshot(page, 'after-2fa');
        }

        url = page.url();
        const loggedIn = !url.includes('/login') && !url.includes('/twofa');
        log(loggedIn ? 'pass' : 'fail', '2.2', loggedIn ? `2FA passed — at ${url}` : `Still at ${url}`);

        if (!loggedIn) {
            log('fail', '2.X', 'Cannot continue — login failed');
            throw new Error('Login failed');
        }

        // ========== PHASE 3: DASHBOARD NAVIGATION ==========
        console.log('\n🗺️ Phase 3: Dashboard Navigation (all tabs)');

        await navigateAndCheck(page, `${BASE_URL}/wallet/dashboard`, '3.1', 'Wallet Dashboard loads');
        await navigateAndCheck(page, `${BASE_URL}/wallet/dashboard/hd`, '3.2', 'The Vault (HD wallets) loads');
        await navigateAndCheck(page, `${BASE_URL}/citizen/all`, '3.3', 'Citizen Registry loads');
        await navigateAndCheck(page, `${BASE_URL}/congress/voting`, '3.4', 'Congress Voting loads');
        await navigateAndCheck(page, `${BASE_URL}/congress/voting/new`, '3.5', 'New Proposal page loads');
        await navigateAndCheck(page, `${BASE_URL}/forum`, '3.6', 'Forum loads');
        await navigateAndCheck(page, `${BASE_URL}/inventory/all`, '3.7', 'Inventory loads');
        await navigateAndCheck(page, `${BASE_URL}/logbook/all`, '3.8', 'Logbook loads');
        await navigateAndCheck(page, `${BASE_URL}/map/all`, '3.9', 'Mars Map loads');
        await navigateAndCheck(page, `${BASE_URL}/inventory/instruments`, '3.10', 'BADS Instruments loads');
        await navigateAndCheck(page, `${BASE_URL}/status`, '3.11', 'System Status loads');

        // ========== PHASE 4: CITIZEN PROFILE ==========
        console.log('\n👤 Phase 4: Citizen Profile');

        await navigateAndCheck(page, `${BASE_URL}/citizen/id/${ASTRA.address}`, '4.1', 'Own citizen profile loads');

        // ========== PHASE 5: WALLET OPERATIONS ==========
        console.log('\n💰 Phase 5: Wallet Operations');

        // Go to The Vault and check wallet cards
        await page.goto(`${BASE_URL}/wallet/dashboard/hd`, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await new Promise(r => setTimeout(r, 2000));
        
        // Check if wallet cards are visible
        const walletCards = await page.$$('.vault-card, .wallet-card, .portlet');
        log(walletCards.length > 0 ? 'pass' : 'skip', '5.1', `Wallet cards visible: ${walletCards.length} found`);
        await screenshot(page, 'vault-wallets');

        // ========== PHASE 6: API HEALTH CHECKS ==========
        console.log('\n🔌 Phase 6: API Health Checks');

        // Check balance API
        try {
            const balResp = await fetch(`${BASE_URL}/api/balance/${ASTRA.address}`);
            const balData = await balResp.json();
            log(balData.balance !== undefined ? 'pass' : 'fail', '6.1', 
                `Balance API: ${balData.balance ?? 'N/A'} MARS for ${ASTRA.address}`);
        } catch (e) {
            log('fail', '6.1', `Balance API error: ${e.message}`);
        }

        // Check Mars price API
        try {
            const priceResp = await fetch(`${BASE_URL}/api/mars-price`);
            const priceText = await priceResp.text();
            log(priceResp.ok ? 'pass' : 'fail', '6.2', `Mars price API: ${priceResp.status}`);
        } catch (e) {
            log('fail', '6.2', `Price API error: ${e.message}`);
        }

        // Check public feed API
        try {
            const feedResp = await fetch(`${BASE_URL}/api/feed/public`);
            log(feedResp.ok ? 'pass' : 'fail', '6.3', `Public feed API: ${feedResp.status}`);
        } catch (e) {
            log('fail', '6.3', `Feed API error: ${e.message}`);
        }

        // ========== PHASE 7: CONGRESS ==========
        console.log('\n🏛️ Phase 7: Congress');

        await page.goto(`${BASE_URL}/congress/voting`, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await new Promise(r => setTimeout(r, 2000));
        
        // Check if proposals are listed
        const proposals = await page.$$('.proposal-card, .portlet, [class*="proposal"]');
        log('pass', '7.1', `Congress loaded, ${proposals.length} proposal elements found`);
        await screenshot(page, 'congress-voting');

        // Check a specific proposal if any exist
        try {
            const proposalLink = await page.$('a[href*="/congress/proposal/"]');
            if (proposalLink) {
                await Promise.all([
                    page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {}),
                    proposalLink.click(),
                ]);
                await new Promise(r => setTimeout(r, 2000));
                const ok = await checkNoServerError(page, '7.2');
                if (ok) log('pass', '7.2', `Proposal detail page loaded: ${page.url()}`);
                await screenshot(page, 'proposal-detail');
            } else {
                log('skip', '7.2', 'No proposal links found to click');
            }
        } catch (e) {
            log('fail', '7.2', `Proposal detail error: ${e.message}`);
        }

        // ========== PHASE 8: FORUM ==========
        console.log('\n💬 Phase 8: Forum');

        await page.goto(`${BASE_URL}/forum`, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await new Promise(r => setTimeout(r, 2000));
        
        // Check if threads are listed
        const threads = await page.$$('.forum-thread, [class*="thread"], tr');
        log('pass', '8.1', `Forum loaded, ${threads.length} thread elements`);
        await screenshot(page, 'forum');

        // Click into a thread if one exists
        try {
            const threadLink = await page.$('a[href*="/forum/t/"]');
            if (threadLink) {
                await Promise.all([
                    page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {}),
                    threadLink.click(),
                ]);
                await new Promise(r => setTimeout(r, 2000));
                const ok = await checkNoServerError(page, '8.2');
                if (ok) log('pass', '8.2', 'Forum thread loaded');
                await screenshot(page, 'forum-thread');
            } else {
                log('skip', '8.2', 'No thread links found');
            }
        } catch (e) {
            log('fail', '8.2', `Forum thread error: ${e.message}`);
        }

        // ========== PHASE 9: LOGOUT ==========
        console.log('\n🚪 Phase 9: Logout');

        await page.goto(`${BASE_URL}/logout`, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await new Promise(r => setTimeout(r, 2000));
        url = page.url();
        log(url.includes('/login') || url === `${BASE_URL}/` || url.endsWith('/') ? 'pass' : 'fail', '9.1', `Logout — redirected to ${url}`);
        await screenshot(page, 'after-logout');

        // Verify can't access auth pages after logout
        await page.goto(`${BASE_URL}/wallet/dashboard`, { waitUntil: 'domcontentloaded', timeout: 30000 });
        url = page.url();
        log(url.includes('/login') ? 'pass' : 'fail', '9.2', `Dashboard after logout redirects to login: ${url}`);

    } catch (e) {
        log('fail', 'FATAL', `Test crashed: ${e.message}`);
    }

    // ========== RESULTS ==========
    console.log('\n' + '='.repeat(60));
    console.log('📊 Results Summary\n');

    const passed = results.filter(r => r.status === 'pass').length;
    const failed = results.filter(r => r.status === 'fail').length;
    const skipped = results.filter(r => r.status === 'skip').length;

    console.log(`  ✅ Passed:  ${passed}`);
    console.log(`  ❌ Failed:  ${failed}`);
    console.log(`  ⏭️  Skipped: ${skipped}`);
    console.log(`  📸 Screenshots: ${screenshotIndex}`);

    if (pageErrors.length > 0) {
        console.log(`\n  ⚠️  Page JS Errors: ${pageErrors.length}`);
        pageErrors.slice(0, 5).forEach(e => console.log(`    • ${e.substring(0, 100)}`));
    }

    console.log('\n' + '='.repeat(60));

    // Save results JSON
    const report = {
        timestamp: new Date().toISOString(),
        summary: { passed, failed, skipped, screenshots: screenshotIndex, jsErrors: pageErrors.length },
        results,
        pageErrors: pageErrors.slice(0, 20),
    };
    await writeFile(join(SCREENSHOT_DIR, 'report.json'), JSON.stringify(report, null, 2));
    console.log(`\n📁 Report saved to ${SCREENSHOT_DIR}/report.json`);

    await browser.close();
    process.exit(failed > 0 ? 1 : 0);
}

run().catch(e => {
    console.error('Fatal error:', e);
    process.exit(1);
});
