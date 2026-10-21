<!DOCTYPE html>
<html lang="en">
<head>
<title>Market Makers Without Exchanges: How Mars Will Trade with Earth - The Academy</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="How atomic swaps let two planets trade without an exchange, what a market maker does when the exchange is a protocol, and the first live MARS/BTC maker at swaps.marscoin.org.">
<meta name="keywords" content="atomic swap, market maker, Marscoin, Bitcoin, HTLC, interplanetary commerce, Mars economy, swaps.marscoin.org, atomicswapd">
<meta property="og:title" content="Market Makers Without Exchanges: How Mars Will Trade with Earth">
<meta property="og:description" content="There will be no exchange both planets can reach. Atomic swaps make the exchange a protocol, and the first Marscoin market maker is already live.">
<meta property="og:image" content="https://martianrepublic.org/assets/academy/swaps-dashboard.jpg">
<meta property="og:type" content="article">
<meta property="og:url" content="https://martianrepublic.org/academy/atomic-swap-market-makers">
<meta property="og:site_name" content="The Martian Republic">
<meta property="og:locale" content="en_US">
<meta property="article:published_time" content="2026-10-21">
<meta property="article:author" content="The Martian Republic">
<meta name="robots" content="index, follow">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Market Makers Without Exchanges: How Mars Will Trade with Earth">
<meta name="twitter:description" content="There will be no exchange both planets can reach. Atomic swaps make the exchange a protocol, and the first Marscoin market maker is already live.">
<meta name="twitter:image" content="https://martianrepublic.org/assets/academy/swaps-dashboard.jpg">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://martianrepublic.org/academy/atomic-swap-market-makers">
@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Market Makers Without Exchanges: How Mars Will Trade with Earth",
  "description": "How atomic swaps let two planets trade without an exchange, what a market maker does when the exchange is a protocol, and the first live MARS/BTC maker at swaps.marscoin.org.",
  "image": "https://martianrepublic.org/assets/academy/swaps-dashboard.jpg",
  "datePublished": "2026-10-21",
  "author": { "@type": "Organization", "name": "The Martian Republic" },
  "publisher": {
    "@type": "Organization",
    "name": "The Martian Republic",
    "url": "https://martianrepublic.org",
    "logo": { "@type": "ImageObject", "url": "https://martianrepublic.org/assets/favicon.ico" }
  },
  "mainEntityOfPage": "https://martianrepublic.org/academy/atomic-swap-market-makers"
}
</script>
@endverbatim

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="/assets/landing/css/bootstrap.min.css">

<style>
:root {
  --mr-void: #06060c;
  --mr-dark: #0c0c16;
  --mr-surface: #12121e;
  --mr-surface-raised: #1a1a2a;
  --mr-border: rgba(255,255,255,0.06);
  --mr-border-bright: rgba(255,255,255,0.12);
  --mr-text: #e0dfe6;
  --mr-text-dim: #8a8998;
  --mr-text-faint: #5a5968;
  --mr-mars: #c84125;
  --mr-mars-glow: #e05535;
  --mr-cyan: #00e4ff;
  --mr-cyan-dim: rgba(0,228,255,0.15);
  --mr-green: #34d399;
  --mr-amber: #d4a44a;
  --mr-red: #ef4444;
  --mr-font-display: 'Chakra Petch', sans-serif;
  --mr-font-body: 'DM Sans', sans-serif;
  --mr-font-serif: 'DM Sans', sans-serif;
  --mr-font-mono: 'JetBrains Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
  margin: 0; padding: 0;
  background: var(--mr-void);
  color: var(--mr-text);
  font-family: var(--mr-font-body);
  font-size: 16px;
  line-height: 1.7;
  -webkit-font-smoothing: antialiased;
}
a { color: var(--mr-cyan); transition: all 0.3s ease; text-decoration: none; }
a:hover { color: var(--mr-amber); text-decoration: none; }
img { max-width: 100%; height: auto; }

/* ---- NAV ---- */
.mr-nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  background: rgba(6,6,12,0.85);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--mr-border);
  padding: 16px 0;
}
.mr-nav .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; }
.mr-nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
.mr-nav-brand img { width: 42px; height: 42px; border-radius: 50%; }
.mr-nav-brand span { font-family: var(--mr-font-display); font-weight: 700; font-size: 15px; color: #fff; letter-spacing: 0.5px; text-transform: uppercase; }
.mr-nav-links { display: flex; align-items: center; gap: 28px; }
.mr-nav-links a { color: var(--mr-text-dim); font-size: 14px; font-weight: 500; }
.mr-nav-links a:hover { color: #fff; }
.mr-nav-cta {
  background: var(--mr-mars); color: #fff !important;
  padding: 8px 20px; border-radius: 6px;
  font-family: var(--mr-font-display); font-weight: 600; font-size: 13px; letter-spacing: 0.5px;
}
.mr-nav-cta:hover { background: var(--mr-mars-glow); color: #fff !important; }

/* ---- READING PROGRESS BAR ---- */
.reading-progress {
  position: fixed;
  top: 64px; left: 0; right: 0;
  height: 3px;
  background: var(--mr-dark);
  z-index: 999;
}
.reading-progress-bar {
  height: 100%;
  background: linear-gradient(90deg, var(--mr-mars), var(--mr-cyan));
  width: 0%;
  transition: width 0.1s ease;
}

/* ---- ARTICLE HERO ---- */
.article-hero {
  padding: 120px 0 48px;
  max-width: 820px;
  margin: 0 auto;
  padding-left: 24px;
  padding-right: 24px;
}
.article-breadcrumb {
  font-family: var(--mr-font-mono);
  font-size: 12px;
  letter-spacing: 1px;
  margin-bottom: 32px;
}
.article-breadcrumb a { color: var(--mr-text-dim); }
.article-breadcrumb a:hover { color: var(--mr-cyan); }
.article-breadcrumb span { color: var(--mr-text-faint); margin: 0 8px; }
.article-tag-hero {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  padding: 4px 14px;
  border-radius: 4px;
  display: inline-block;
  margin-bottom: 20px;
  background: var(--mr-cyan-dim);
  color: var(--mr-cyan);
}
.article-hero h1 {
  font-size: clamp(28px, 4vw, 44px);
  font-weight: 700;
  line-height: 1.15;
  margin: 0 0 20px;
  letter-spacing: -0.5px;
}
.article-hero-meta {
  display: flex;
  align-items: center;
  gap: 20px;
  font-family: var(--mr-font-mono);
  font-size: 12px;
  color: var(--mr-text-faint);
  flex-wrap: wrap;
}
.article-hero-meta i { margin-right: 4px; }

/* ---- FULL-BLEED HERO IMAGE (BF-style) ---- */
.article-hero-image {
  width: 100vw;
  margin-left: calc(-50vw + 50%);
  margin-top: 40px;
  margin-bottom: 0;
  background: var(--mr-dark);
  display: flex;
  align-items: center;
  justify-content: center;
}
.article-hero-image img {
  width: 100%;
  max-width: 1100px;
  height: auto;
  display: block;
}

/* ---- ARTICLE CONTENT ---- */
.article-content {
  max-width: 720px;
  margin: 0 auto;
  padding: 48px 24px 80px;
}
.article-content p {
  font-family: var(--mr-font-body);
  font-size: 18px;
  line-height: 1.7;
  color: var(--mr-text-dim);
  margin-bottom: 24px;
}
.article-content h2 {
  font-family: var(--mr-font-display);
  font-size: 28px;
  font-weight: 700;
  color: #fff;
  margin: 56px 0 20px;
  padding-top: 32px;
  border-top: 1px solid var(--mr-border);
}
.article-content h3 {
  font-family: var(--mr-font-display);
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  margin: 40px 0 16px;
}
.article-content strong { color: #fff; }
.article-content em { color: var(--mr-text-dim); font-family: var(--mr-font-body); }

/* ---- Callout Box ---- */
.callout {
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-left: 3px solid var(--mr-cyan);
  border-radius: 0 10px 10px 0;
  padding: 24px 28px;
  margin: 32px 0;
}
.callout p { font-family: var(--mr-font-body); font-size: 15px; margin-bottom: 8px; }
.callout p:last-child { margin-bottom: 0; }
.callout.mars-red { border-left-color: var(--mr-mars); }
.callout.green { border-left-color: var(--mr-green); }
.callout.amber { border-left-color: var(--mr-amber); }

/* ---- Step Cards ---- */
.step-card {
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  padding: 28px;
  margin: 24px 0;
  position: relative;
  overflow: hidden;
}
.step-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; bottom: 0;
  width: 4px;
  background: var(--mr-cyan);
}
.step-card.mars::before { background: var(--mr-mars); }
.step-card.green::before { background: var(--mr-green); }
.step-card.amber::before { background: var(--mr-amber); }
.step-card .step-number {
  font-family: var(--mr-font-display);
  font-size: 36px;
  font-weight: 700;
  color: rgba(0,228,255,0.15);
  line-height: 1;
  margin-bottom: 8px;
}
.step-card.mars .step-number { color: rgba(200,65,37,0.15); }
.step-card h4 {
  font-family: var(--mr-font-display);
  font-size: 18px;
  font-weight: 700;
  margin: 0 0 8px;
  color: var(--mr-cyan);
}
.step-card.mars h4 { color: var(--mr-mars); }
.step-card.green h4 { color: var(--mr-green); }
.step-card.amber h4 { color: var(--mr-amber); }
.step-card p {
  font-family: var(--mr-font-body);
  font-size: 15px;
  color: var(--mr-text-dim);
  line-height: 1.7;
  margin-bottom: 0;
}
.step-card .step-detail {
  margin-top: 12px;
  padding: 12px 16px;
  background: var(--mr-dark);
  border-radius: 6px;
  font-family: var(--mr-font-mono);
  font-size: 12px;
  color: var(--mr-text-dim);
  line-height: 1.7;
}

/* ---- Comparison Table ---- */
.compare-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin: 32px 0;
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  overflow: hidden;
}
.compare-table thead th {
  font-family: var(--mr-font-mono);
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mr-text-dim);
  background: var(--mr-dark);
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid var(--mr-border);
}
.compare-table tbody td {
  padding: 14px 16px;
  font-size: 14px;
  color: var(--mr-text);
  border-bottom: 1px solid var(--mr-border);
  vertical-align: top;
}
.compare-table tbody tr:last-child td { border-bottom: none; }
.compare-table .mono {
  font-family: var(--mr-font-mono);
  font-size: 13px;
}
.compare-table .system-name {
  font-family: var(--mr-font-display);
  font-weight: 700;
  font-size: 14px;
}
.compare-table .mr-highlight {
  background: rgba(0,228,255,0.04);
}

/* ---- Protocol Flow Diagram ---- */
.protocol-flow {
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  padding: 28px;
  margin: 32px 0;
  overflow-x: auto;
}
.protocol-flow .flow-title {
  font-family: var(--mr-font-display);
  font-size: 14px;
  font-weight: 700;
  color: var(--mr-cyan);
  text-transform: uppercase;
  letter-spacing: 1.5px;
  margin-bottom: 20px;
}
.flow-stack {
  font-family: var(--mr-font-mono);
  font-size: 13px;
  line-height: 2.2;
  color: var(--mr-text-dim);
}
.flow-stack .flow-layer {
  display: flex;
  align-items: center;
  gap: 12px;
}
.flow-stack .flow-arrow {
  color: var(--mr-cyan);
  font-size: 11px;
  margin: 4px 0 4px 16px;
}
.flow-stack .flow-label {
  color: var(--mr-text-faint);
  font-size: 10px;
  letter-spacing: 1px;
  text-transform: uppercase;
  min-width: 100px;
}
.flow-stack .flow-component {
  color: #fff;
}
.flow-stack .flow-protocol {
  color: var(--mr-cyan);
  font-size: 11px;
}

/* ---- Article Image (inline) ---- */
.article-image {
  margin: 40px 0;
  border-radius: 10px;
  overflow: hidden;
  border: 1px solid var(--mr-border);
}
.article-image img {
  width: 100%;
  display: block;
}
.article-image-caption {
  padding: 12px 16px;
  background: var(--mr-surface);
  font-family: var(--mr-font-mono);
  font-size: 12px;
  color: var(--mr-text-faint);
  line-height: 1.5;
}

/* ---- Blockquote ---- */
.article-content blockquote {
  border-left: 3px solid var(--mr-mars);
  margin: 32px 0;
  padding: 16px 24px;
  background: var(--mr-surface);
  border-radius: 0 8px 8px 0;
}
.article-content blockquote p {
  font-family: var(--mr-font-body);
  font-style: italic;
  color: var(--mr-text-dim);
  font-size: 16px;
  margin-bottom: 0;
}

/* ---- Lists ---- */
.article-content ul, .article-content ol {
  padding-left: 24px;
  margin-bottom: 24px;
}
.article-content li {
  font-family: var(--mr-font-body);
  font-size: 16px;
  line-height: 1.8;
  color: var(--mr-text);
  margin-bottom: 8px;
}

/* ---- Continue Reading ---- */
.continue-reading {
  max-width: 720px;
  margin: 0 auto;
  padding: 0 24px 80px;
  border-top: 1px solid var(--mr-border);
  padding-top: 48px;
}
.continue-reading h3 {
  font-family: var(--mr-font-display);
  font-size: 16px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mr-text-dim);
  margin-bottom: 24px;
}
.continue-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 8px;
  margin-bottom: 12px;
  transition: all 0.3s ease;
}
.continue-card:hover { border-color: var(--mr-border-bright); transform: translateX(4px); }
.continue-card-title { font-family: var(--mr-font-display); font-size: 15px; font-weight: 600; color: #fff; }
.continue-card-arrow { color: var(--mr-cyan); font-size: 14px; }

/* ---- FOOTER ---- */
.mr-footer {
  background: var(--mr-dark);
  padding: 60px 0 30px;
  border-top: 1px solid var(--mr-border);
}
.mr-footer-inner {
  max-width: 1200px; margin: 0 auto; padding: 0 24px;
  display: flex; justify-content: space-between; align-items: center;
}
.mr-footer-copy { font-size: 13px; color: var(--mr-text-faint); }
.mr-footer-links { display: flex; gap: 24px; }
.mr-footer-links a { color: var(--mr-text-faint); font-size: 13px; }
.mr-footer-links a:hover { color: #fff; }

@media (max-width: 768px) {
  .article-hero-meta { flex-direction: column; gap: 8px; }
  .compare-table { font-size: 12px; }
  .compare-table tbody td, .compare-table thead th { padding: 10px 8px; }
  .flow-stack .flow-label { min-width: 70px; font-size: 9px; }
}
</style>
</head>

<body>

<!-- NAV -->
<nav class="mr-nav">
  <div class="nav-inner">
    <a href="/" class="mr-nav-brand">
      <img src="/assets/landing/img/logomarscoinwallet.png" alt="Martian Republic">
      <span>Martian Republic</span>
    </a>
    <div class="mr-nav-links">
      <a href="/">Home</a>
      <a href="/academy" style="color:#fff;">Academy</a>
      <a href="/congress/all">Congress</a>
      <a href="/login">Login</a>
      <a href="/signup" class="mr-nav-cta">Become a Citizen</a>
    </div>
  </div>
</nav>

<!-- READING PROGRESS -->
<div class="reading-progress">
  <div class="reading-progress-bar" id="progress-bar"></div>
</div>

<!-- ARTICLE HERO -->
<header class="article-hero">
  <div class="article-breadcrumb">
    <a href="/">Home</a><span>/</span><a href="/academy">Academy</a><span>/</span><a href="/academy">Economy</a><span>/</span><span style="color:var(--mr-text);">Market Makers Without Exchanges</span>
  </div>
  <span class="article-tag-hero">Economy</span>
  <h1>Market Makers Without Exchanges: How Mars Will Trade with Earth</h1>
  <div class="article-hero-meta">
    <span><i class="fa-solid fa-clock"></i> 12 min read</span>
    <span><i class="fa-solid fa-calendar"></i> October 21, 2026</span>
    <span><i class="fa-solid fa-user"></i> The Marscoin Foundation</span>
    <span><i class="fa-solid fa-signal"></i> Intermediate</span>
  </div>
</header>

<!-- FULL-BLEED HERO IMAGE -->
<div class="article-hero-image">
  <img src="/assets/academy/arrival-hall.jpg" alt="A habitat concourse on Mars lined with terminals">
</div>

<!-- ARTICLE CONTENT -->
<article class="article-content">

<p>Every market needs someone who is always there. On Earth, that someone is a market maker: a trader who keeps buy and sell quotes standing, so that anyone who wants to trade finds a counterparty right away. Market makers earn the spread between their prices and carry the risk of holding inventory. Without them, markets go quiet.</p>

<p>A Martian settlement will need the same thing. A colonist who earns bitcoin from an Earth employer will want marscoin to spend at the habitat. A supplier on Earth will want to be paid. And here is the problem: the market maker can't sit on an exchange, because there is no exchange both planets can use.</p>

<p>This article explains how a market maker works when the exchange is not a company but a protocol. It also introduces the first one, which went live on Marscoin this week.</p>

<h2>Why an Exchange Can't Span Two Planets</h2>

<p>An exchange is a place where both sides send their money first. The exchange holds it, matches orders, and pays out. That works on Earth because the round trip to the exchange takes milliseconds and the exchange is, mostly, trustworthy.</p>

<p>Between Earth and Mars, light takes 3 to 22 minutes each way. About every 26 months the Sun sits between the planets and blocks radio contact for roughly two weeks. An exchange on Earth would be holding a Martian's money with no way for the Martian to reach it, check on it, or get it back quickly. An exchange on Mars would have the same problem in reverse. As the <a href="/academy/defi-and-finance-on-mars">DeFi on Mars</a> article argues, every custodial design breaks at the physical layer.</p>

<div class="callout mars-red">
<p><strong>The requirement:</strong> two people on two worlds must be able to trade without either of them, or anyone in between, ever holding the other's money.</p>
</div>

<h2>The Exchange Is the Protocol</h2>

<p>The answer has existed since 2013. It is called an <strong>atomic swap</strong>, and it is built from hash time-locked contracts: lockboxes on each blockchain that open with the same secret.</p>

<p>Here is a swap of marscoin for bitcoin, step by step:</p>

<ol>
<li><strong>The seller locks the MARS</strong> in a lockbox on the Marscoin blockchain. Only the buyer can open it, and only with a secret the seller knows. If nothing happens, the seller can take it back after about eight hours.</li>
<li><strong>The buyer checks that lock</strong> on the Marscoin blockchain, then locks the bitcoin in a matching lockbox on the Bitcoin blockchain. The seller can open it with the same secret. If nothing happens, the buyer can take it back after about four hours.</li>
<li><strong>The seller opens the bitcoin lockbox</strong>, and to do so must publish the secret on the Bitcoin blockchain.</li>
<li><strong>The buyer reads the secret</strong> from the Bitcoin blockchain and opens the MARS lockbox.</li>
</ol>

<p>Either both lockboxes open or neither does. Nobody holds anyone's coins in between. The buyer's refund opens before the seller's, because the buyer pays second. That way the seller can never take back the MARS while also holding the bitcoin.</p>

<p>What is left of "the exchange" is just a bulletin board: an order book where sellers post offers. In Marscoin's case it is relayed by the network's ElectrumX servers. Anyone can post an offer, anyone can take one, and the board itself never touches money.</p>

<h2>The First Maker Is Live</h2>

<p>An open order book still needs someone to fill it. This week the Marscoin Foundation started an always-on market maker that sells MARS for bitcoin through atomic swaps, with a public dashboard at <a href="https://swaps.marscoin.org">swaps.marscoin.org</a>.</p>

<div class="article-image">
<img src="/assets/academy/swaps-dashboard.jpg" alt="The swaps.marscoin.org dashboard: the MARS/BTC price chart, the order book with three live offers, a swap quote, the settlement timeline and the maker's exposure gauge">
<div class="article-image-caption">
swaps.marscoin.org: the live order book, a quote for the cheapest offer, the settlement timeline with real block heights, and the maker's inventory. Every number comes from a public source.
</div>
</div>

<p>The dashboard shows every offer from every relay server. For each one it checks the same thing a buyer's wallet checks before paying: whether the seller's refund opens safely after the buyer's. Pick an offer and it shows exactly what you would pay and receive, and the guarantees behind the trade:</p>

<div class="article-image" style="max-width:420px; margin-left:auto; margin-right:auto;">
<img src="/assets/academy/swaps-quote.jpg" alt="A swap quote: about 0.000066 BTC for 70.5 MARS, with three green safety checks">
<div class="article-image-caption">
A quote for one offer. The trade itself happens in the Electrum-Mars wallet, where the buyer's keys stay.
</div>
</div>

<h2>The Rules a Maker Lives By</h2>

<p>An honest maker can't be cheated out of its coins by an atomic swap. It can, however, be harassed. Someone can accept an offer and never pay. The maker's MARS then sits locked until the refund opens, eight hours later. Do that to every offer and the maker is frozen. The first maker follows four rules, and each is worth understanding:</p>

<ul>
<li><strong>Exposure cap.</strong> MARS locked in swaps plus MARS on offer never exceeds 30% of inventory. Even if every offer were taken by a griefer at once, most of the inventory stays free.</li>
<li><strong>One swap per buyer.</strong> Relay servers with the new abuse controls tag each connection with an anonymous fingerprint. A buyer with a swap in progress can't take a second one. The maker reports buyers who abandon swaps, and those relays block them for a day.</li>
<li><strong>No guessed prices.</strong> Offers are priced at a spread over the live market. If the price feed is stale, the maker posts nothing rather than risk a wrong price.</li>
<li><strong>No late claims.</strong> The maker won't open the bitcoin lockbox when the buyer's refund is minutes away. Publishing the secret that late could let a buyer refund the bitcoin and claim the MARS too. Too close to the deadline, the maker lets the swap unwind instead.</li>
</ul>

<h2>From Earth Hours to Martian Days</h2>

<p>Today's swaps use refund windows of four and eight hours. That's generous for two parties on Earth, where a swap normally finishes in about half an hour. The dashboard's timeline shows both clocks with real block heights. Even in the worst case, the seller can't reclaim the MARS until well after the buyer could recover the bitcoin.</p>

<div class="article-image">
<img src="/assets/academy/swaps-settlement.jpg" alt="The settlement timeline: four steps, the buyer's refund at about four hours, the seller's at about eight, and the safety margin between them">
<div class="article-image-caption">
How a swap settles. The thin blue bar at the start is the light travel time from Earth to Mars that day: about 12.5 minutes.
</div>
</div>

<p>Nothing in this protocol depends on the parties being close. The secret is just as valid on Mars as on Earth. The only thing that changes across planets is how long the lockboxes must stay open. The <a href="https://marscoin.org/academy/ipas">InterPlanetary Atomic Swaps</a> paper sizes them:</p>

<ul>
<li><strong>At average distance</strong> (about 13 light-minutes): 72 hours for bitcoin and 144 hours for MARS.</li>
<li><strong>At greatest distance</strong> (about 22 light-minutes): 96 and 192 hours.</li>
<li><strong>Across a solar conjunction:</strong> 21 days and 28 days, so a swap can survive two weeks without radio contact.</li>
</ul>

<p>That changes the economics of market making. A Martian maker's coins may be locked for days, or weeks around conjunction, instead of hours. Spreads will price that time, the way interest prices a loan. It's the same lesson as the bills of exchange that financed trade across oceans centuries ago, with light instead of sailing ships.</p>

<h2>Anyone Can Be a Maker</h2>

<p>The first maker has no special status. The program it runs, <a href="https://github.com/marscoin/atomicswapd">atomicswapd</a>, is open source. It uses the same swap engine as the Electrum-Mars wallet and runs on any small server. A few commands on fly.io get one running for about six dollars a month. The repository even includes instructions an AI assistant can follow to set one up, without ever seeing the wallet's secret seed. More makers mean tighter spreads and a market that doesn't depend on anyone.</p>

<p>On Mars, the first maker might be a small computer in a habitat's server rack, running the same software from a bootable image brought on the first ship, quoting prices to the colony and settling with Earth across the light delay.</p>

<blockquote>
<p>Markets were never really about exchanges. They are about trust: knowing that if you hand over what you owe, you will get what you are owed. For most of history that trust came from institutions close enough to hold you to account. Between planets nothing is close enough. Atomic swaps replace that trust with arithmetic, and a market maker is just the first participant willing to stand behind a price.</p>
</blockquote>

<p>See the market for yourself at <a href="https://swaps.marscoin.org">swaps.marscoin.org</a>. To take an offer, install <a href="https://github.com/marscoin/electrum-mars/releases/latest">Electrum-Mars 4.4.3 or later</a> and follow the <a href="https://marscoin.org/academy/buying-mars-with-btc">step-by-step guide</a>.</p>

</article>

<!-- CONTINUE READING -->
<div class="continue-reading">
  <h3>Continue Learning</h3>
  <a href="/academy" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-arrow-left" style="margin-right:8px; color:var(--mr-cyan);"></i> Back to The Academy</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy/defi-and-finance-on-mars" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-coins" style="margin-right:8px; color:var(--mr-mars);"></i> DeFi on Mars: Finance Without Banks</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy/public-key-cryptography" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-key" style="margin-right:8px; color:var(--mr-green);"></i> Public Key Cryptography</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
</div>

<!-- FOOTER -->
<footer class="mr-footer">
  <div class="mr-footer-inner">
    <div class="mr-footer-copy">
      &copy; 2014&ndash;{{ date('Y') }} The Marscoin Foundation, Inc.
    </div>
    <div class="mr-footer-links">
      <a href="/">Home</a>
      <a href="/academy">Academy</a>
      <a href="/congress/all">Congress</a>
      <a href="/privacy">Privacy</a>
    </div>
  </div>
</footer>

<script>
// Reading progress bar
window.addEventListener('scroll', function() {
  const article = document.querySelector('.article-content');
  if (!article) return;
  const rect = article.getBoundingClientRect();
  const articleTop = rect.top + window.scrollY;
  const articleHeight = article.offsetHeight;
  const scrolled = window.scrollY - articleTop + window.innerHeight * 0.3;
  const progress = Math.min(Math.max(scrolled / articleHeight * 100, 0), 100);
  document.getElementById('progress-bar').style.width = progress + '%';
});
</script>

</body>
</html>