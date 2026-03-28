<!DOCTYPE html>
<html lang="en">
<head>
<title>Scrypt vs RandomX: The Mining Algorithm Debate for Mars - The Academy</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="A deep technical comparison of Scrypt and RandomX mining algorithms and what they mean for Marscoin's future. Why the choice of proof-of-work algorithm determines whether Mars can run its own cryptocurrency.">
<meta name="keywords" content="Scrypt, RandomX, mining algorithm, ASIC resistance, proof of work, Marscoin mining, Litecoin, Monero, CPU mining, GPU mining, Mars colony">
<meta property="og:title" content="Scrypt vs RandomX: The Mining Algorithm Debate for Mars">
<meta property="og:description" content="A deep technical comparison of Scrypt and RandomX mining algorithms and what they mean for Marscoin's future. Why the choice of proof-of-work algorithm determines whether Mars can run its own cryptocurrency.">
<meta property="og:image" content="https://martianrepublic.org/assets/academy/congress-chamber-3.jpg">
<meta property="og:type" content="article">
<meta property="og:url" content="https://martianrepublic.org/academy/scrypt-vs-randomx">
<meta property="og:site_name" content="The Martian Republic">
<meta property="og:locale" content="en_US">
<meta property="article:published_time" content="2026-03-27">
<meta property="article:author" content="The Martian Republic">
<meta name="robots" content="index, follow">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Scrypt vs RandomX: The Mining Algorithm Debate for Mars">
<meta name="twitter:description" content="A deep technical comparison of Scrypt and RandomX mining algorithms and what they mean for Marscoin's future. Why the choice of proof-of-work algorithm determines whether Mars can run its own cryptocurrency.">
<meta name="twitter:image" content="https://martianrepublic.org/assets/academy/congress-chamber-3.jpg">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://martianrepublic.org/academy/scrypt-vs-randomx">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Scrypt vs RandomX: The Mining Algorithm Debate for Mars",
  "description": "A deep technical comparison of Scrypt and RandomX mining algorithms and what they mean for Marscoin's future. Why the choice of proof-of-work algorithm determines whether Mars can run its own cryptocurrency.",
  "image": "https://martianrepublic.org/assets/academy/congress-chamber-3.jpg",
  "datePublished": "2026-03-27",
  "author": { "@type": "Organization", "name": "The Martian Republic" },
  "publisher": {
    "@type": "Organization",
    "name": "The Martian Republic",
    "url": "https://martianrepublic.org",
    "logo": { "@type": "ImageObject", "url": "https://martianrepublic.org/assets/favicon.ico" }
  },
  "mainEntityOfPage": "https://martianrepublic.org/academy/scrypt-vs-randomx"
}
</script>

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
  background: rgba(0,228,255,0.15);
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

/* ---- FULL-BLEED HERO IMAGE ---- */
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

/* ---- Tier Table ---- */
.tier-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin: 32px 0;
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  overflow: hidden;
}
.tier-table thead th {
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
.tier-table tbody td {
  padding: 14px 16px;
  font-size: 14px;
  color: var(--mr-text);
  border-bottom: 1px solid var(--mr-border);
  vertical-align: top;
}
.tier-table tbody tr:last-child td { border-bottom: none; }
.tier-table .mono {
  font-family: var(--mr-font-mono);
  font-size: 13px;
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
    <a href="/">Home</a><span>/</span><a href="/academy">Academy</a><span>/</span><a href="/academy">Blockchain &amp; Marscoin</a><span>/</span><span style="color:var(--mr-text);">Scrypt vs RandomX</span>
  </div>
  <span class="article-tag-hero">Blockchain &amp; Marscoin</span>
  <h1>Scrypt vs RandomX: The Mining Algorithm Debate for Mars</h1>
  <div class="article-hero-meta">
    <span><i class="fa-solid fa-clock"></i> 22 min read</span>
    <span><i class="fa-solid fa-calendar"></i> March 27, 2026</span>
    <span><i class="fa-solid fa-user"></i> The Marscoin Foundation</span>
    <span><i class="fa-solid fa-signal"></i> Advanced</span>
  </div>
</header>

<!-- FULL-BLEED HERO IMAGE -->
<div class="article-hero-image">
  <img src="/assets/academy/scrypt-randomx.jpg" alt="ASIC mining facility contrasted with Mars colony computers">
</div>

<!-- ARTICLE CONTENT -->
<article class="article-content">

<p>On Earth, cryptocurrency mining is an arms race. Bitcoin mining alone consumes an estimated 150 terawatt-hours of electricity per year &mdash; more than the entire annual energy consumption of Argentina, a nation of 46 million people. Specialized hardware called ASICs (Application-Specific Integrated Circuits), each costing thousands of dollars, sit in warehouse-scale facilities in Texas, Kazakhstan, and rural China, in regions selected for one reason: cheap electricity. The machines do nothing except compute SHA-256 hashes. They cannot browse the web, run a spreadsheet, or play a video. They hash. When they become obsolete &mdash; typically within 18 to 24 months &mdash; they become electronic waste.</p>

<p>This model is absurd for Mars. A colony of 100 people does not have spare gigawatts. It does not have ASIC fabrication plants. It does not have warehouse space for single-purpose hardware that becomes trash in two years. What it does have is general-purpose computers &mdash; laptops, servers, embedded systems &mdash; that need to do real work: run life support, manage communications, process scientific data, coordinate logistics, and, ideally, also secure the blockchain that governs their economy and civic life.</p>

<p>The choice of mining algorithm is not academic. It determines whether a Mars colony can actually run its own cryptocurrency, or whether Marscoin remains permanently dependent on Earth's mining infrastructure. This is the Scrypt vs RandomX debate, and its resolution will shape the Republic's technical future.</p>

<h2>The Fundamentals: What Mining Actually Does</h2>

<p>Before comparing algorithms, it is important to dispel a persistent misconception. Mining is <strong>not</strong> primarily about creating new coins. The block reward &mdash; the new coins minted with each block &mdash; is an incentive mechanism, not the purpose of mining. Mining serves three functions that are essential to the existence of any proof-of-work blockchain:</p>

<ol>
<li><strong>Ordering transactions (consensus).</strong> When two people spend the same coin simultaneously (a double-spend attempt), the network needs a definitive way to decide which transaction counts. Mining provides this: the transaction that appears in a mined block first is the valid one. The computational work required to mine a block makes it economically irrational to attempt to reorder transactions after the fact.</li>
<li><strong>Securing the chain (immutability).</strong> To rewrite history &mdash; to change a past transaction &mdash; an attacker would need to redo all the computational work from that point forward, faster than the honest network is extending the chain. The more hashrate (computational power) the network has, the more expensive this attack becomes. Mining turns energy into security.</li>
<li><strong>Distributing new coins (incentive).</strong> The block reward compensates miners for providing functions #1 and #2. It also provides a fair initial distribution mechanism: anyone willing to contribute computational work receives coins in proportion to their contribution. No pre-sale. No allocation by committee. Work in, coins out.</li>
</ol>

<p>The mining puzzle itself is conceptually simple: find a number (called a <strong>nonce</strong>) such that when combined with the block header and hashed, the result is below a target value. The target is adjusted periodically to maintain consistent block times regardless of how much computing power joins or leaves the network. If more miners join, the target decreases (making the puzzle harder). If miners leave, it increases (making it easier). This is the <strong>difficulty adjustment</strong>, and its design varies significantly across blockchains.</p>

<div class="callout">
<p><strong>The essence of proof of work:</strong> Mining forces the creation of each block to cost real resources (electricity, hardware depreciation). This cost is what makes the blockchain's history trustworthy. To rewrite the last 10 blocks, you would need to redo 10 blocks' worth of work while the honest network continues extending the chain. The deeper a transaction is buried, the more expensive it becomes to reverse. After 6 confirmations (roughly 12 minutes for Marscoin), reversal is economically impractical for all but the most powerful attackers.</p>
</div>

<h2>SHA-256: Where It Started, and Why Decentralization Failed</h2>

<p>Bitcoin uses SHA-256 (Secure Hash Algorithm, 256-bit) as its proof-of-work function. SHA-256 is purely computational: it performs a fixed sequence of bitwise operations, additions, and rotations on 32-bit words. It uses no significant memory. It requires no complex branching logic. It is, from a hardware perspective, a dream to optimize.</p>

<p>This simplicity invited an arms race that unfolded with predictable inevitability:</p>

<ul>
<li><strong>2009&ndash;2010: CPU mining.</strong> Satoshi Nakamoto mined the first Bitcoin blocks on a standard desktop PC. Anyone with a laptop could mine Bitcoin profitably. One CPU, one vote &mdash; Satoshi's original vision of decentralized mining.</li>
<li><strong>2010&ndash;2012: GPU mining.</strong> Graphics cards, designed for the parallel floating-point operations of 3D rendering, turned out to be roughly 100 times faster at SHA-256 than CPUs. Mining shifted from laptops to gaming rigs. Individual CPU miners became unprofitable overnight.</li>
<li><strong>2013: FPGA mining.</strong> Field-Programmable Gate Arrays &mdash; reprogrammable logic chips &mdash; offered another 5&ndash;10x improvement over GPUs while consuming less power. A brief transitional phase.</li>
<li><strong>2013 onward: ASIC mining.</strong> Purpose-built chips designed to do nothing except compute SHA-256 hashes. The first Bitcoin ASICs (from Avalon, Butterfly Labs, and later Bitmain) were 1,000 times faster than GPUs per watt. Mining immediately consolidated into facilities with hundreds or thousands of ASICs, located wherever electricity was cheapest.</li>
</ul>

<p>By 2026, Bitcoin mining is dominated by approximately five large mining pools (Foundry USA, AntPool, F2Pool, ViaBTC, Binance Pool) that collectively control over 80% of hashrate. The hardware comes from two or three manufacturers: Bitmain (Antminer S21, ~200 TH/s, ~$5,400), MicroBT (WhatsMiner M60S, ~186 TH/s, ~$4,800), and Canaan (Avalon A1566, ~185 TH/s, ~$4,200). These machines consume 3,000&ndash;3,500 watts each and do nothing except hash SHA-256, 24 hours a day, until a newer model makes them obsolete.</p>

<div class="callout mars-red">
<p><strong>Satoshi's vision, abandoned:</strong> "One CPU, one vote" was the founding principle of Bitcoin mining. By 2014, that principle was dead. You cannot mine Bitcoin profitably with a laptop in 2026, or in 2020, or even in 2015. The SHA-256 algorithm, by being so simple to optimize in hardware, created the exact centralization that proof of work was designed to prevent. The question for every cryptocurrency that followed was: can we design an algorithm that resists this outcome?</p>
</div>

<h2>Scrypt: Litecoin's Answer, and Marscoin's Current Algorithm</h2>

<p>In 2009, Colin Percival &mdash; a FreeBSD Security Officer and founder of Tarsnap, an encrypted backup service &mdash; published a key derivation function called <strong>Scrypt</strong>. Its original purpose was not cryptocurrency mining but password hashing: it was designed to make brute-force password cracking expensive by requiring large amounts of memory, not just CPU cycles. The idea was that while ASICs and GPUs could perform billions of simple hash computations per second, memory was expensive and hard to parallelize on custom hardware.</p>

<p>Charlie Lee adopted Scrypt as Litecoin's proof-of-work algorithm when he launched Litecoin on October 7, 2011. The thesis was explicit: Scrypt's memory-hardness would keep mining accessible to regular computers and prevent the ASIC centralization that was already beginning in Bitcoin. Dogecoin, launched in December 2013, followed Litecoin's lead. So did Marscoin, launched on January 1, 2014.</p>

<h3>How Scrypt Differs from SHA-256</h3>

<p>The core difference is memory. SHA-256 processes data through a fixed pipeline of logical operations using a few hundred bytes of working memory. Scrypt, by contrast, generates a large pseudorandom dataset in memory (controlled by the parameter N, which determines the memory requirement) and then reads from it repeatedly in a pattern that depends on the data itself. This means:</p>

<ul>
<li>You cannot compute the result without storing the dataset. There is no shortcut that trades memory for computation (this property is called "memory-hard").</li>
<li>The dataset must be accessed in a pattern that is not known in advance, which defeats simple caching strategies.</li>
<li>Parallelizing Scrypt requires duplicating the memory for each parallel instance, making it expensive to scale on custom hardware where memory is the bottleneck.</li>
</ul>

<p>The theory was sound. The practice was more complicated.</p>

<h3>What Actually Happened: Scrypt ASICs Arrive</h3>

<p>In March 2014 &mdash; less than three years after Litecoin's launch &mdash; the first Scrypt ASICs shipped. Gridseed, a Chinese manufacturer, released a dual-mining chip capable of both SHA-256 and Scrypt. Zeus Miner followed. KnCMiner's Titan, announced in May 2014, offered 300 MH/s of Scrypt hashing. By late 2014, Scrypt ASICs from multiple manufacturers were widely available.</p>

<p>The memory-hardness turned out to be insufficient. Scrypt as used in Litecoin (and Marscoin) uses parameters that require only 128 KB of memory per instance. This is small enough to embed directly on an ASIC die. A custom chip with dedicated SRAM blocks can hold the full Scrypt dataset on-chip without accessing external memory, eliminating the memory-bandwidth bottleneck that was supposed to be the ASIC deterrent. Percival himself had warned that the memory parameters used by Litecoin were too low to provide meaningful ASIC resistance, but the parameters were set at launch and changing them would have required a hard fork.</p>

<p>The arms race repeated, delayed by approximately two years compared to Bitcoin:</p>

<ul>
<li>2011&ndash;2013: CPU and GPU mining of Litecoin (Scrypt) was profitable.</li>
<li>2014: Scrypt ASICs arrived. GPU miners became unprofitable within months.</li>
<li>2014&ndash;2026: Litecoin and Dogecoin mining became increasingly dominated by ASIC farms, mirroring Bitcoin's trajectory.</li>
</ul>

<h3>Marscoin's Current Situation: Merged Mining</h3>

<p>Since February 2025, at block height 3,145,555, Marscoin has used <strong>Auxiliary Proof of Work (AuxPoW)</strong> &mdash; merged mining with the Litecoin/Dogecoin mining ecosystem. This means that Scrypt ASICs mining Litecoin or Dogecoin can simultaneously mine Marscoin at essentially zero additional cost. The Marscoin block headers are embedded within Litecoin/Dogecoin blocks, inheriting their proof of work.</p>

<p>The result has been transformative for network security. Marscoin's effective hashrate reached 2 TH/s &mdash; a level that the project's standalone mining community could never have achieved. The difficulty adjustment algorithm, upgraded to <strong>ASERT</strong> (Absolutely Scheduled Exponentially Rising Targets) in July 2024, reduced block time variance by 47%, producing more consistent 123-second block intervals.</p>

<div class="callout">
<p><strong>The merged mining bargain:</strong> Merged mining gives Marscoin access to Litecoin's massive ASIC infrastructure for free. This provides security that would cost millions of dollars in dedicated hardware. But it comes with a dependency: Marscoin's security is now tied to the health of the Litecoin/Dogecoin mining ecosystem. If Litecoin miners shut down (due to profitability collapse, regulatory action, or technological shift), Marscoin's hashrate drops with them. The chain borrows strength from a host; it does not generate its own.</p>
</div>

<h2>RandomX: Monero's Revolutionary Approach</h2>

<p>In November 2019, at block height 1,978,433, the Monero network activated a proof-of-work algorithm called <strong>RandomX</strong>. It was developed over two years by a team including tevador (Howard Chu), hyc, SChernykh, and other contributors, and it represented a fundamentally different philosophy of ASIC resistance.</p>

<p>Every previous ASIC-resistant algorithm had tried to find an operation that was hard for custom hardware: memory-hard (Scrypt, Ethash), memory-bandwidth-hard (CryptoNight), or IO-hard (Cuckoo Cycle). RandomX inverted the approach entirely. Instead of finding an operation that ASICs are bad at, it <strong>generates random programs that require a general-purpose CPU to execute</strong>. An ASIC that could run RandomX efficiently would, by definition, need to implement a general-purpose CPU &mdash; at which point it <em>is</em> a general-purpose CPU, and the ASIC advantage disappears.</p>

<h3>Technical Architecture</h3>

<p>RandomX is, at its core, a virtual machine that executes randomly generated programs. Each mining attempt works as follows:</p>

<ol>
<li><strong>Program generation:</strong> The block header (which changes with each mining attempt because the nonce changes) is used as a seed to generate a random program in RandomX's custom instruction set. The instruction set contains 32 instructions covering integer arithmetic (add, subtract, multiply, XOR, rotate, shift), floating-point arithmetic (add, subtract, multiply, divide, square root), memory operations (load, store), and conditional branches.</li>
<li><strong>Scratchpad initialization:</strong> A 2 MB scratchpad is allocated and filled with pseudorandom data derived from the block header. This scratchpad is designed to fit exactly within the L3 cache of modern CPUs. Accessing it from DRAM (as a naive ASIC implementation might) would be 10&ndash;50x slower.</li>
<li><strong>Program execution:</strong> The random program executes using the scratchpad for data. The program includes conditional branches, meaning the execution path depends on intermediate results &mdash; defeating pipelining optimizations that ASICs use to process multiple operations simultaneously.</li>
<li><strong>Floating-point compliance:</strong> The random program includes IEEE 754 floating-point operations with specific rounding modes. Every modern CPU has a hardware floating-point unit (FPU) that implements IEEE 754 natively. Building a custom ASIC with a fully compliant FPU adds significant die area and complexity, eroding any ASIC advantage.</li>
<li><strong>Hash output:</strong> After program execution, the scratchpad contents and register state are hashed (using Blake2b) to produce the final proof-of-work hash, which is checked against the difficulty target.</li>
</ol>

<p>Critically, the random program changes with every nonce &mdash; meaning every mining attempt executes a <strong>different</strong> program. An ASIC optimized for one specific program would gain no advantage because the next attempt uses a completely different program. The only way to execute arbitrary random programs efficiently is with a general-purpose processor.</p>

<div class="callout green">
<p><strong>The key insight:</strong> Previous ASIC-resistant algorithms asked "what operation is hard for ASICs?" RandomX asks "what <em>device</em> is already optimized for running arbitrary programs?" The answer is: a CPU. RandomX is designed so that the optimal hardware for mining is the hardware that already exists in every computer on the planet. You cannot beat a CPU at being a CPU.</p>
</div>

<h3>Results After Five Years</h3>

<p>RandomX has been live on the Monero network since November 2019. The results, as of early 2026, are striking:</p>

<ul>
<li><strong>No RandomX ASICs exist.</strong> After more than six years, no manufacturer has produced a RandomX ASIC. This is unprecedented. Every other "ASIC-resistant" algorithm (Scrypt, Ethash, Equihash, CryptoNight) was broken by ASICs within 1&ndash;4 years.</li>
<li><strong>CPU mining is competitive.</strong> A modern AMD Ryzen 7 7700X achieves roughly 15,000&ndash;18,000 H/s. An Intel Core i7-13700K achieves roughly 12,000&ndash;15,000 H/s. These are consumer desktop processors costing $200&ndash;400.</li>
<li><strong>GPUs offer minimal advantage.</strong> A high-end GPU achieves roughly 2&ndash;3x the hashrate of a comparable-generation CPU, compared to 100x+ advantages for SHA-256 or 10x+ for Ethash. The economic advantage of GPU mining over CPU mining is marginal after accounting for GPU power consumption and cost.</li>
<li><strong>Decentralization achieved.</strong> Monero's hashrate comes from hundreds of thousands of individual computers worldwide. There are no RandomX mining farms in the traditional sense. The largest Monero mining pools each represent 20&ndash;30% of hashrate (compared to single Bitcoin pools sometimes exceeding 30%), and the barrier to solo mining is low enough that many individuals mine directly.</li>
</ul>

<h3>The Downsides</h3>

<p>RandomX is not without costs:</p>

<ul>
<li><strong>Complexity.</strong> The RandomX specification is over 50 pages of technical documentation. The reference implementation is thousands of lines of C++. Auditing and formally verifying such a complex system is significantly harder than for SHA-256 or Scrypt. TrailOfBits, Quarkslab, Kudelski Security, and X41 D-Sec all performed security audits before Monero's deployment. Four independent audits &mdash; an expensive and time-consuming process.</li>
<li><strong>Power per hash.</strong> Because RandomX exercises the full CPU (ALU, FPU, cache, memory controller), power consumption per hash is higher than simpler algorithms. A CPU mining RandomX runs at or near full thermal load. However, the total network power consumption is orders of magnitude lower than Bitcoin because the total hashrate is proportionally lower &mdash; security comes from the algorithm's resistance to optimization, not from raw energy expenditure.</li>
<li><strong>Botnets.</strong> Because any computer can mine RandomX, compromised computers are used for illicit mining. Monero has a well-documented cryptojacking problem: malware that silently mines Monero using victims' CPUs. The Coinhive browser-based miner (shut down in 2019) and numerous malware families have exploited RandomX's CPU-friendliness. This is a social and legal problem, not a technical one, but it is a real consequence of making mining accessible to all hardware.</li>
<li><strong>Verification cost.</strong> Verifying a RandomX proof of work requires re-executing the random program, which is more computationally expensive than verifying a SHA-256 or Scrypt hash. For full nodes on resource-constrained hardware, this adds meaningful overhead. The RandomX "light mode" reduces verification memory requirements from 2 GB (full dataset) to 256 MB at the cost of slower verification.</li>
</ul>

<h2>The Mars Colony Argument</h2>

<p>The Scrypt vs RandomX debate becomes consequential &mdash; not merely theoretical &mdash; when you apply it to the specific constraints of a Martian settlement. Mars is not Earth. The assumptions that drive mining algorithm choices on Earth (abundant cheap energy, global hardware supply chains, warehouse-scale facilities) do not apply. Different constraints produce different optimal solutions.</p>

<h3>The Case for RandomX on Mars</h3>

<p><strong>1. Hardware availability.</strong> Mars colonists will bring general-purpose computers. They will not bring ASICs. Every kilogram launched from Earth to Mars costs approximately $500&ndash;2,000 (at projected Starship economics). A 15 kg Antminer S21 that can only hash Scrypt is 15 kg of dead weight for any purpose except mining one specific algorithm. A 2 kg laptop or a 5 kg server that runs life support software, processes scientific data, handles communications, <em>and</em> mines the blockchain is 2&ndash;5 kg of multi-purpose equipment. On Mars, every gram must justify itself.</p>

<p><strong>2. No ASIC supply chain.</strong> When a Scrypt ASIC fails on Mars (mean time between failure for ASICs under thermal stress is 2&ndash;5 years), it cannot be replaced. There is no semiconductor fabrication on Mars. There will not be for decades, possibly centuries. General-purpose computers can be repaired with interchangeable components, repurposed from other systems, or eventually manufactured with less specialized equipment. ASICs are dead ends.</p>

<p><strong>3. True decentralization.</strong> In a colony of 100 people, every computer that can mine is a node that participates in consensus. If mining requires ASICs, only those colonists with ASICs have a voice in block production. If mining works on any CPU, every colonist with a computer participates. For a democratic society that runs its governance on the blockchain, this is not a nice-to-have. It is a structural requirement.</p>

<p><strong>4. Anti-concentration.</strong> Marscoin's whitepaper explicitly states the project's commitment to "continuous upgrades to prevent mining centralization." RandomX is the most effective algorithm ever deployed for achieving this goal. After six years, it remains unbroken by ASIC manufacturers.</p>

<div class="callout">
<p><strong>The dual-use argument:</strong> On Mars, every watt of energy comes from solar panels or nuclear reactors (like NASA's Kilopower). No watt can be wasted on single-purpose computation. A RandomX miner is simultaneously a general-purpose computer that can run habitat control systems during the day and mine blocks during low-demand periods at night. A Scrypt ASIC mines blocks and does nothing else, ever. In a resource-constrained colony, dual-use is not optional &mdash; it is survival.</p>
</div>

<h3>The Case for Keeping Scrypt</h3>

<p><strong>1. Twelve years of stability.</strong> Marscoin has used Scrypt since January 2014. Twelve years of uninterrupted operation with no algorithm-related vulnerabilities. Switching to RandomX is a migration with risk &mdash; potential bugs, consensus failures, and ecosystem disruption. In engineering, "if it isn't broken, don't fix it" is a valid heuristic, especially for critical infrastructure.</p>

<p><strong>2. Merged mining provides unmatched security.</strong> Through AuxPoW with Litecoin/Dogecoin, Marscoin benefits from the combined hashrate of the entire Litecoin mining ecosystem. This is security that Marscoin could never generate independently &mdash; the project's market capitalization and mining rewards are too small to attract 2 TH/s of dedicated hardware. Switching to RandomX would abandon merged mining entirely, since RandomX is incompatible with Scrypt AuxPoW. The hashrate would drop to whatever Marscoin's standalone community can generate, likely orders of magnitude less.</p>

<p><strong>3. Hard fork consensus risk.</strong> Changing the proof-of-work algorithm requires a hard fork &mdash; a mandatory upgrade where every node on the network must update its software. Any node that does not upgrade follows the old chain and is incompatible with the new one. Hard forks carry risk of chain splits (as seen with Ethereum/Ethereum Classic in 2016 and Bitcoin/Bitcoin Cash in 2017). For a smaller project like Marscoin, a contentious hard fork could fracture the community.</p>

<p><strong>4. Implementation complexity.</strong> Scrypt is well-understood, widely implemented, and simple to audit. RandomX is none of these things. The Marscoin development team would need to integrate a complex new proof-of-work system, test it extensively, and maintain it indefinitely. For a volunteer-driven open-source project, this is a significant ongoing commitment.</p>

<p><strong>5. Colony hardware constraints.</strong> RandomX's 2 MB scratchpad per mining thread, while designed to fit in L3 cache, still consumes non-trivial memory on resource-constrained colony computers. If a habitat management system is already using 90% of available RAM, mining with RandomX may not be practical without impacting critical systems. Scrypt's 128 KB per instance is less demanding.</p>

<h3>The Hybrid Proposal: Two-Phase Migration</h3>

<p>A pragmatic approach has emerged from community discussion that avoids the false binary of Scrypt-forever vs. switch-now:</p>

<p><strong>Phase 1 (Earth era):</strong> Keep Scrypt + merged mining. While Marscoin operates primarily on Earth, with its community and infrastructure Earth-based, merged mining with Litecoin provides security that the project cannot generate alone. The ASIC question is moot because the ASICs mining Litecoin are on Earth, where ASIC supply chains exist and energy is relatively cheap. Use this phase to develop, test, and audit a RandomX implementation for Marscoin.</p>

<p><strong>Phase 2 (Mars transition):</strong> At the point of blockchain transfer to Mars &mdash; when the colony's computing infrastructure becomes the primary network and the interplanetary communication delay makes Earth-based mining impractical for consensus &mdash; execute the hard fork to RandomX. On Mars, merged mining with Litecoin is irrelevant anyway (Litecoin miners are on Earth; the signal delay makes real-time merged mining impossible). On Mars, you need an algorithm that works on the hardware the colony actually has: general-purpose CPUs.</p>

<div class="callout green">
<p><strong>The best of both worlds:</strong> Phase 1 uses Scrypt's strength (access to Litecoin's massive ASIC hashrate through merged mining) during the period when it matters (Earth-based operations). Phase 2 uses RandomX's strength (CPU mining on general-purpose hardware) during the period when it matters (Mars-based operations). The transition point is not arbitrary &mdash; it is defined by a physical reality: when the Mars network becomes self-sustaining and Earth mining becomes impractical due to signal delay.</p>
</div>

<h2>Other Algorithms in the Discussion</h2>

<p>Scrypt and RandomX are not the only options. A brief survey of other ASIC-resistant approaches illustrates the difficulty of the problem and why RandomX's track record is exceptional.</p>

<table class="tier-table">
<thead>
<tr>
  <th>Algorithm</th>
  <th>ASIC-Resistant Duration</th>
  <th>Memory Requirement</th>
  <th>CPU Competitive?</th>
  <th>Adopted By</th>
</tr>
</thead>
<tbody>
<tr>
  <td><strong>SHA-256</strong></td>
  <td class="mono">~3 years (2009&ndash;2012)</td>
  <td class="mono">Negligible</td>
  <td class="mono">No (since 2012)</td>
  <td class="mono">Bitcoin, BCH</td>
</tr>
<tr>
  <td><strong>Scrypt</strong></td>
  <td class="mono">~3 years (2011&ndash;2014)</td>
  <td class="mono">128 KB per instance</td>
  <td class="mono">No (since 2014)</td>
  <td class="mono">Litecoin, Dogecoin, Marscoin</td>
</tr>
<tr>
  <td><strong>Ethash</strong></td>
  <td class="mono">~4 years (2015&ndash;2018)</td>
  <td class="mono">1&ndash;4 GB DAG (growing)</td>
  <td class="mono">No (GPU-dominated)</td>
  <td class="mono">Ethereum (pre-merge), ETC</td>
</tr>
<tr>
  <td><strong>Equihash</strong></td>
  <td class="mono">~3 years (2016&ndash;2018)</td>
  <td class="mono">144 MB</td>
  <td class="mono">No (since 2018)</td>
  <td class="mono">Zcash, Horizen</td>
</tr>
<tr>
  <td><strong>CryptoNight</strong></td>
  <td class="mono">~5 years (2014&ndash;2019)</td>
  <td class="mono">2 MB scratchpad</td>
  <td class="mono">Partially (until ASICs)</td>
  <td class="mono">Monero (pre-2019)</td>
</tr>
<tr>
  <td><strong>ProgPoW</strong></td>
  <td class="mono">Never deployed at scale</td>
  <td class="mono">Variable</td>
  <td class="mono">GPU-optimized by design</td>
  <td class="mono">Proposed for Ethereum</td>
</tr>
<tr>
  <td><strong>RandomX</strong></td>
  <td class="mono">6+ years and counting (2019&ndash;)</td>
  <td class="mono">2 MB scratchpad + 2 GB dataset</td>
  <td class="mono">Yes</td>
  <td class="mono">Monero</td>
</tr>
</tbody>
</table>

<p>The pattern is stark. Every algorithm except RandomX has been defeated by ASICs within three to five years of deployment. The memory-hardness approach (Scrypt, Ethash, Equihash) delays ASICs but does not prevent them. ASIC manufacturers have consistently demonstrated that they can integrate significant memory onto custom chips when the economic incentive is sufficient. CryptoNight survived longer by requiring 2 MB of cache-resident memory (matching CPU L3 cache sizes), but even it was eventually targeted by ASICs from Bitmain (the Antminer X3, shipped in 2018) and others, which forced Monero to hard-fork repeatedly before adopting RandomX.</p>

<p><strong>Ethash</strong> deserves special mention because its approach &mdash; a growing DAG (Directed Acyclic Graph) that requires multiple gigabytes of memory &mdash; was specifically designed to outpace ASIC memory capacity. By 2018, Bitmain's Antminer E3 shipped with sufficient memory. Ethereum ultimately abandoned proof of work entirely in September 2022 (The Merge), transitioning to proof of stake. Ethereum Classic continues to use Etchash (a variant) and remains GPU/ASIC mined.</p>

<p><strong>ProgPoW</strong> (Programmatic Proof of Work) took a different approach: instead of resisting all hardware optimization, it was designed to match the architecture of commodity GPUs, making a GPU the most efficient possible miner. The logic was that GPUs are mass-produced consumer hardware and therefore "ASIC-resistant enough." ProgPoW was proposed for Ethereum but became politically contentious &mdash; GPU manufacturers would benefit disproportionately, and the relationship between ProgPoW developers and GPU makers was questioned. It was never deployed on a major network.</p>

<h2>The Economics of Mining on Mars</h2>

<p>The algorithm debate cannot be separated from the economic context. Mining on Mars operates under constraints that have no terrestrial precedent.</p>

<h3>Energy Budget</h3>

<p>Mars receives approximately 43% of the solar energy per square meter that Earth does (due to its greater distance from the Sun). Dust storms can reduce solar output by 90% for weeks. The leading supplementary power source for early colonies is nuclear: NASA's <strong>Kilopower</strong> reactor produces 10 kW of electrical power from a uranium-235 core. Ten Kilopower units &mdash; 100 kW total &mdash; might power a small colony's essential systems: life support, heating, communications, food production, and lighting.</p>

<p>In this energy budget, how much can be allocated to mining? If the colony allocates 5% of its power to blockchain security (5 kW from a 100 kW supply), that is enough to run approximately 25&ndash;50 modern CPUs mining RandomX, or 1&ndash;2 Scrypt ASICs. With RandomX, those 25&ndash;50 CPUs are also the colony's general-purpose computing infrastructure &mdash; they run everything else when not mining, and many can mine at reduced priority while performing other tasks simultaneously. With Scrypt ASICs, those 1&ndash;2 machines do nothing but hash, and you still need separate computers for everything else.</p>

<h3>The Block Reward Endgame</h3>

<p>Marscoin's emission schedule means that the vast majority of MARS coins have already been mined. The block reward decreases over time following a halving schedule. As block rewards diminish toward zero, transaction fees become the primary incentive for miners. This changes the algorithm calculation in a subtle but important way.</p>

<p>When block rewards are large, mining is primarily about coin distribution: who gets the new coins, and in what proportion to their contributed hashrate. When transaction fees dominate, mining is primarily about transaction ordering: the service that miners provide is including transactions in blocks and maintaining consensus. The economic question shifts from "how much hashrate can you buy?" to "how cheaply can you provide consensus?" RandomX, which allows mining on existing multi-purpose hardware with minimal marginal cost, is better suited to a fee-based economy than Scrypt, which requires dedicated ASIC hardware with significant capital and operating costs.</p>

<h3>Hashrate and Security on a Small Network</h3>

<p>A Mars colony's blockchain does not need Earth-scale hashrate. It needs enough hashrate to make a 51% attack impractical given the realistic threat model. On Earth, Bitcoin faces adversaries with nation-state resources. On Mars, the threat model is different: the most likely adversaries are compromised colony computers, rogue colonists, or (in the far future) competing settlements.</p>

<p>For a colony of 100&ndash;1,000 people where all computing hardware is known and physical access to infrastructure is controlled, the hashrate requirements for security are orders of magnitude lower than on Earth. Even a modest number of CPUs mining RandomX can provide sufficient security for a colony-scale network. The security margin does not come from absolute hashrate but from the percentage of total network hashrate controlled by honest participants. If 70% of colony computers mine honestly, the chain is secure regardless of the absolute hashrate number.</p>

<div class="callout amber">
<p><strong>The "useful proof of work" dream:</strong> Could mining computation be directed at something useful &mdash; protein folding, climate simulation, materials science? The idea surfaces regularly in cryptocurrency discussions. The fundamental problem: useful computation, by definition, produces different results for different inputs. This makes it impossible to generate a predictable, uniform difficulty target. Two miners working on different protein structures would produce proofs of different "hardness," breaking the fair difficulty adjustment that proof of work requires. Some projects (Primecoin, Gridcoin) have attempted variations, but none has achieved both useful computation and robust consensus security simultaneously.</p>
</div>

<h2>The Decision Framework</h2>

<p>Stripping away the technical details, the Scrypt vs RandomX debate reduces to a question about time horizons and dependencies.</p>

<p><strong>If your time horizon is the next 5&ndash;10 years</strong> and your priority is maximum security at minimum cost for an Earth-based project, Scrypt with merged mining is the clear winner. It provides borrowed security from one of the largest mining networks in existence, at zero cost to Marscoin's community. The ASIC centralization problem is irrelevant because the project does not need or want independent mining infrastructure at this stage.</p>

<p><strong>If your time horizon is the next 50&ndash;100 years</strong> and your priority is building a blockchain that a Mars colony can operate independently with local hardware, RandomX is the only proven algorithm that achieves genuine CPU-mining accessibility. The six-year track record against ASIC manufacturers provides evidence (not proof, but the strongest evidence available) that the approach is durable. The dual-use nature of CPU mining aligns perfectly with a colony where every kilogram and every watt must serve multiple purposes.</p>

<p>The hybrid approach &mdash; Scrypt now, RandomX later &mdash; is an engineering compromise, and like most engineering compromises, it is probably the right answer. It avoids premature optimization (switching before the switch is needed), avoids permanent lock-in (committing to Scrypt when the Mars use case demands something different), and defines a clear trigger for the transition (the point at which colony computing becomes the primary network).</p>

<blockquote>
<p>"The best is the enemy of the good." Scrypt is good enough for Earth. RandomX is designed for Mars. The question is not which is better in the abstract &mdash; it is which is better for each phase of the Republic's evolution.</p>
<p style="font-size:13px; color:var(--mr-text-faint); font-style:normal; margin-top:8px;">&mdash; Community discussion, Marscoin governance forum, 2025</p>
</blockquote>

<h2>Looking Forward</h2>

<p>The mining algorithm question for Marscoin is not about which algorithm produces more hashes per second. It is about which algorithm aligns with the values and constraints of a Mars colony: decentralization, resource efficiency, hardware independence, and security proportional to the actual threat model.</p>

<p>Scrypt served Marscoin well for twelve years. Through merged mining with Litecoin and Dogecoin, it provides security today that the project could never achieve alone &mdash; 2 terahashes per second of borrowed hashrate, protecting every transaction, every vote, every citizenship record on the chain. That security is real, and abandoning it prematurely would be reckless.</p>

<p>RandomX represents the future that the project was designed for &mdash; a future where general-purpose computers, owned by ordinary citizens, run the economy and the government of a new world. Where mining is not an industrial operation conducted by specialized facilities, but a civic function performed by every computer in the colony. Where the blockchain's security comes not from the concentration of capital in hardware, but from the distribution of computation across a community.</p>

<p>The question is not if. It is when and how. And the answer, like most good engineering answers, is: at the right time, with thorough preparation, and with the consensus of the citizens who depend on the system.</p>

<p>On Mars, 225 million kilometers from the nearest ASIC factory, the CPU you brought with you is the only miner you will ever have. The algorithm had better work with it.</p>

</article>

<!-- CONTINUE READING -->
<div class="continue-reading">
  <h3>Continue Learning</h3>
  <a href="/academy/public-key-cryptography" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-key" style="margin-right:8px; color:var(--mr-cyan);"></i> Public-Key Cryptography: The Mathematics of Trust</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-arrow-left" style="margin-right:8px; color:var(--mr-cyan);"></i> Back to The Academy</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy/governance-and-voting" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-landmark" style="margin-right:8px; color:var(--mr-mars);"></i> How Mars Governs Itself</span>
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