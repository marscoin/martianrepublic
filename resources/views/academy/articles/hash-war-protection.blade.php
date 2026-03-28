<!DOCTYPE html>
<html lang="en">
<head>
<title>Hash-War Protection: How Mars Defends Its Blockchain From Earth - The Academy</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="How the Martian Republic protects its blockchain from Earth's vastly superior mining power using decentralized licensing — a new consensus model where citizenship, not hashrate, secures the chain.">
<meta name="keywords" content="hash war, 51% attack, blockchain security, mining protection, decentralized licensing, Mars blockchain, Marscoin, interplanetary security">
<meta property="og:title" content="Hash-War Protection: How Mars Defends Its Blockchain From Earth">
<meta property="og:description" content="How the Martian Republic protects its blockchain from Earth's vastly superior mining power using decentralized licensing — a new consensus model where citizenship, not hashrate, secures the chain.">
<meta property="og:image" content="https://martianrepublic.org/assets/landing/img/logomarscoinwallet.png">
<meta property="og:type" content="article">
<meta property="og:url" content="https://martianrepublic.org/academy/hash-war-protection">
<meta property="og:site_name" content="The Martian Republic">
<meta property="og:locale" content="en_US">
<meta property="article:published_time" content="2026-03-27">
<meta property="article:author" content="The Martian Republic">
<meta name="robots" content="index, follow">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Hash-War Protection: How Mars Defends Its Blockchain From Earth">
<meta name="twitter:description" content="How the Martian Republic protects its blockchain from Earth's vastly superior mining power using decentralized licensing — a new consensus model where citizenship, not hashrate, secures the chain.">
<meta name="twitter:image" content="https://martianrepublic.org/assets/landing/img/logomarscoinwallet.png">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://martianrepublic.org/academy/hash-war-protection">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Hash-War Protection: How Mars Defends Its Blockchain From Earth",
  "description": "How the Martian Republic protects its blockchain from Earth's vastly superior mining power using decentralized licensing — a new consensus model where citizenship, not hashrate, secures the chain.",
  "image": "https://martianrepublic.org/assets/landing/img/logomarscoinwallet.png",
  "datePublished": "2026-03-27",
  "author": { "@type": "Organization", "name": "The Martian Republic" },
  "publisher": {
    "@type": "Organization",
    "name": "The Martian Republic",
    "url": "https://martianrepublic.org",
    "logo": { "@type": "ImageObject", "url": "https://martianrepublic.org/assets/favicon.ico" }
  },
  "mainEntityOfPage": "https://martianrepublic.org/academy/hash-war-protection"
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
  background: rgba(200,65,37,0.15);
  color: var(--mr-mars);
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

/* ---- Code Block ---- */
.article-content code {
  font-family: var(--mr-font-mono);
  font-size: 14px;
  background: var(--mr-surface);
  padding: 2px 8px;
  border-radius: 4px;
  color: var(--mr-cyan);
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
    <a href="/">Home</a><span>/</span><a href="/academy">Academy</a><span>/</span><a href="/academy">Governance &amp; Congress</a><span>/</span><span style="color:var(--mr-text);">Hash-War Protection</span>
  </div>
  <span class="article-tag-hero">Governance &amp; Congress</span>
  <h1>Hash-War Protection: How Mars Defends Its Blockchain From Earth</h1>
  <div class="article-hero-meta">
    <span><i class="fa-solid fa-clock"></i> 22 min read</span>
    <span><i class="fa-solid fa-calendar"></i> March 27, 2026</span>
    <span><i class="fa-solid fa-user"></i> The Marscoin Foundation</span>
    <span><i class="fa-solid fa-signal"></i> Advanced</span>
  </div>
</header>

<!-- FULL-BLEED HERO IMAGE -->
<div class="article-hero-image">
  <img src="/assets/academy/hash-war.jpg" alt="Earth and Mars locked in a blockchain hash-war, red attack beams deflected by cyan shield grid">
</div>

<!-- ARTICLE CONTENT -->
<article class="article-content">

<p>The moment Mars runs its own blockchain, it faces a problem no cryptocurrency on Earth has ever encountered: a hostile blockchain on another planet with a million times more mining power. Earth miners could, in theory, attack the Martian blockchain from 140 million miles away &mdash; and there is nothing Martians could do about it under standard proof-of-work rules. The longest chain wins. Earth's chain will always be longer. Game over.</p>

<p>Except it doesn't have to be. The Marscoin Foundation first presented this problem &mdash; and a proposed solution &mdash; at Mars Society conventions, where the intersection of settlement planning and blockchain governance has been a recurring theme since Marscoin's launch in 2014. The problem is real, it is unsolved by any existing cryptocurrency protocol, and it will become urgent the day a Martian settlement begins running its own financial and governance infrastructure.</p>

<p>This article describes the attack, explains why every standard defense fails in the interplanetary context, and proposes a solution that turns the Martian Republic's governance system into a security layer for the blockchain itself. The concept is called <strong>decentralized licensing</strong>, and it introduces a new consensus modifier: <strong>Proof of Citizenship</strong>.</p>

<h2>The Problem: The Vulnerable Martian Blockchain</h2>

<p>To understand the threat, you need to understand three things: how proof-of-work consensus operates, how much computational power exists on Earth, and how the physics of interplanetary communication turns a theoretical vulnerability into a practical one.</p>

<h3>How Proof-of-Work Consensus Works</h3>

<p>In a proof-of-work blockchain like Bitcoin, Litecoin, or Marscoin, miners compete to solve a cryptographic puzzle &mdash; finding a hash value below a target threshold by varying a nonce in the block header. The miner who finds a valid hash first gets to add the next block to the chain, earning a block reward and transaction fees. When two miners find valid blocks at roughly the same time (a fork), the network resolves the ambiguity with a simple rule: <strong>the longest chain wins</strong>. The chain with the most cumulative proof of work is accepted as canonical, and the shorter fork is discarded.</p>

<p>This rule is elegant and effective on Earth. It means no central authority decides which chain is "real." The math decides. The chain with the most work behind it represents the most computational investment, and attacking it requires outspending all honest miners combined. For Bitcoin in 2026, that means overpowering a network consuming roughly 150 terawatt-hours per year &mdash; more electricity than many countries. The cost of a sustained 51% attack on Bitcoin is estimated at over $20 billion annually, making it economically irrational for any single actor.</p>

<p>Now transport this system to Mars.</p>

<h3>Mars's Mining Infrastructure</h3>

<p>A Martian settlement in its first decade will have severe constraints on computing hardware. Every kilogram launched from Earth to Mars costs between $500 and $2,000, depending on the launch architecture. A modern Bitcoin ASIC miner weighs 10&ndash;15 kg and consumes 3,000&ndash;3,500 watts. Shipping a single unit to Mars costs $5,000&ndash;$30,000 in launch mass alone, and then it demands power that the colony's RTG reactors and solar arrays can barely spare for life support.</p>

<p>Realistically, the first Martian Marscoin network will consist of <strong>10 to 100 mining nodes</strong> running on general-purpose computers &mdash; the same machines used for habitat control, communications, scientific analysis, and governance. These are not dedicated mining rigs. They are multi-purpose systems contributing spare cycles to blockchain security. Total hashrate: a rounding error compared to Earth.</p>

<div class="callout mars-red">
<p><strong>The hashrate disparity:</strong> Earth's combined cryptocurrency mining infrastructure in 2026 produces roughly 750 exahashes per second for Bitcoin alone. A Martian network of 100 general-purpose computers might produce a few hundred megahashes per second for a Scrypt-based coin like Marscoin. That is a ratio of approximately <strong>one trillion to one</strong>. An attacker would not need a mining pool. A single hobbyist with a used ASIC could overpower the entire Martian network.</p>
</div>

<div class="callout green">
<p><strong>But why have a separate blockchain at all?</strong> Because the physics demands it. Bitcoin's 10-minute block confirmations assume sub-second network propagation. With a 4-to-24-minute speed-of-light delay between Earth and Mars, a Martian node can never participate in Bitcoin mining in real time &mdash; it would receive block headers minutes late, submit work that is already stale, and could never confirm a local transaction without waiting for a round-trip to Earth. A space suit rental on Mars cannot wait 48 minutes for a Bitcoin confirmation round-trip. Mars needs a blockchain that runs locally, confirms locally, and operates independently of Earth's network &mdash; its own ledger, its own consensus, its own finality. That blockchain is Marscoin. The vulnerability described here is the <em>cost</em> of sovereignty. This article describes how the Republic turns that cost into a strength.</p>
</div>

<h3>The Attack Scenario</h3>

<p>Here is how a hash-war attack on the Martian blockchain would work in practice:</p>

<ol>
<li><strong>The attacker mines in secret.</strong> On Earth, a miner (or mining pool, or state actor, or anyone with sufficient hardware) begins mining Marscoin blocks privately. They do not broadcast these blocks to the network. They simply accumulate a longer chain in isolation.</li>
<li><strong>The attacker waits for a communication window.</strong> Earth and Mars can communicate when the Sun is not directly between them &mdash; roughly 24 out of every 26 months. One-way signal time ranges from 3.03 minutes (at closest approach, roughly 55 million km) to 22.3 minutes (at maximum distance, roughly 401 million km).</li>
<li><strong>The attacker transmits the longer chain.</strong> During a communication window, the attacker sends their secretly mined chain to a Marscoin node on Mars. The chain is longer than the one Mars has been building honestly. Under standard proof-of-work rules, every Marscoin node on Mars is now obligated to accept the attacker's chain as canonical and discard its own.</li>
<li><strong>The damage is done.</strong> Every transaction on the discarded Martian chain is reversed. Double-spends become trivial. Governance votes recorded on-chain can be undone. Financial records are rewritten. The attacker controls what the Martian blockchain says happened.</li>
</ol>

<p>The light-speed delay actually makes this attack <em>easier</em> to execute, not harder. On Earth, miners can monitor the network in real time and detect suspicious hashrate accumulation. Mars cannot see what Earth miners are doing until their blocks arrive &mdash; minutes to tens of minutes after the fact. By the time the attack chain reaches Mars, it is already too late. The damage is baked into the mathematics.</p>

<h3>Why This Matters Beyond Finance</h3>

<p>If Marscoin were merely a financial instrument &mdash; a way to buy and sell goods &mdash; a chain rewrite would be disruptive but survivable. The colony could switch to a different payment method. But Marscoin is not just currency. Under the Martian Republic's architecture, the blockchain records <strong>citizenship endorsements</strong>, <strong>governance votes</strong>, <strong>congressional proposals</strong>, <strong>committee appointments</strong>, and <strong>property registrations</strong>. A successful chain rewrite does not just steal money. It rewrites the political record. It reverses elections. It deletes citizens. It is an existential attack on the Republic itself.</p>

<h2>Why Standard Solutions Fail</h2>

<p>Every major defense mechanism proposed for proof-of-work blockchains has been analyzed in the context of interplanetary deployment. None of them work without modification. Here is why.</p>

<h3>Checkpointing</h3>

<p>Checkpointing is the simplest defense: a trusted authority periodically marks certain blocks as "final," and the network refuses to accept any chain that contradicts a checkpoint. Bitcoin Core does this informally &mdash; certain block hashes are hardcoded into the software as known-good checkpoints.</p>

<p>The problem on Mars is <strong>who issues the checkpoints</strong>. On Earth, Bitcoin's checkpoints are embedded in the software by the Core development team &mdash; a loose but identifiable group. On Mars, any single checkpoint authority becomes a single point of failure and a single point of political control. If the checkpoint authority is corrupted, compromised, or simply makes an error, the entire chain is at risk. Checkpointing trades one vulnerability (hashrate attacks) for another (centralization of trust). It does not solve the problem; it moves it.</p>

<h3>Proof of Stake</h3>

<p>In proof-of-stake systems like Ethereum (post-Merge, September 15, 2022), validators are selected proportionally to the amount of cryptocurrency they have staked as collateral. No mining hardware is needed. The security model shifts from "who has the most computing power" to "who has the most coins locked up."</p>

<p>The problem for Mars is the <strong>initial distribution of stake</strong>. Marscoin has been trading on Earth since 2014. Early adopters, speculators, and exchanges on Earth hold significant quantities. If the Martian blockchain transitions to proof of stake, the validators are determined by coin holdings &mdash; and Earth-based holders may control the majority of coins. The colony has replaced one form of Earth dominance (hashrate) with another (token wealth). Plutocracy from 225 million kilometers away is still plutocracy.</p>

<h3>Merge Mining (AuxPoW)</h3>

<p>Marscoin already uses Auxiliary Proof of Work (AuxPoW), which allows miners of a larger chain (Litecoin, and by extension Dogecoin) to simultaneously mine Marscoin blocks. This is a brilliant solution on Earth: it gives a small coin like Marscoin access to the security of a much larger mining ecosystem without requiring dedicated miners. Litecoin's hashrate protects Marscoin as a side effect of Litecoin mining.</p>

<p>But merge mining works because the parent chain (Litecoin) and the child chain (Marscoin) coexist in the same communication environment. Miners can monitor both chains, nodes can validate both chains, and the whole system operates within Earth's low-latency network. When the Martian blockchain physically relocates to Mars &mdash; running on Martian nodes, serving Martian citizens, recording Martian governance &mdash; it needs to be <strong>independent</strong>. It cannot depend on Litecoin blocks arriving from Earth to secure its own consensus. The communication delay, the conjunction blackouts, and the fundamental requirement for self-sovereignty all preclude continued merge-mining dependence.</p>

<h3>Ignoring Longer Chains from Earth</h3>

<p>The most intuitive defense: simply program Martian nodes to reject any chain that arrives from Earth. If Mars builds its own chain and refuses to accept external replacements, the problem disappears.</p>

<p>It is also the most dangerous approach. It breaks the fundamental consensus rule that makes proof-of-work systems function. If Martian nodes ignore longer chains, they are no longer running proof-of-work consensus &mdash; they are running a trust-based system where "Martian" nodes are assumed honest by fiat. But how does a node determine whether a block was mined "on Mars" or "on Earth"? Proof-of-work hashes carry no geographic information. You cannot look at a block header and determine which planet produced it. Any rule that says "ignore blocks from Earth" requires some mechanism to <em>identify</em> those blocks &mdash; and that mechanism is precisely what the decentralized licensing solution provides.</p>

<div class="callout">
<p><strong>The core dilemma:</strong> Mars needs to reject blocks from unauthorized miners (defense) without introducing a central authority that decides who is authorized (centralization). The solution must be decentralized, transparent, revocable, and grounded in the consent of the governed. This is not a cryptographic problem. It is a governance problem.</p>
</div>

<h2>The Decentralized Licensing Solution</h2>

<p>The Marscoin Foundation's proposed solution, first presented at the 26th Annual International Mars Society Convention in Arizona in 2023, uses the governance infrastructure that the Martian Republic has already built &mdash; the same system used for citizenship verification, congressional voting, and proposal management &mdash; as the authorization layer for mining.</p>

<p>The mechanism has five steps.</p>

<h3>Step 1: Miner Registration</h3>

<p>A Martian citizen who wishes to operate a mining node publishes their node's public key on-chain. This is an ordinary Marscoin transaction &mdash; a special transaction type (analogous to the GP_ prefix used for governance proposals or the CT_ prefix for citizenship transactions) that registers a cryptographic identity for a mining node. The transaction is signed by the citizen's civic address, linking the mining node to a verified human identity.</p>

<p>The citizen must already be verified through the Republic's endorsement system: endorsed by existing citizens, their identity confirmed through the same process that grants voting rights. You cannot register a mining node anonymously. The miner has a name, a civic address, and a reputation within the community.</p>

<h3>Step 2: Community Approval</h3>

<p>After registration, the mining node enters a review period during which fellow citizens can endorse or challenge the registration. This mirrors the citizenship endorsement process &mdash; the same social verification mechanism that prevents Sybil attacks on the citizen registry also prevents unauthorized mining registrations.</p>

<p>Citizens might endorse a miner because they trust the operator, because they have inspected the hardware, or because the colony needs additional mining capacity. Citizens might challenge a registration if the applicant is unknown, if the hardware is suspect, or if the colony already has sufficient mining nodes and adding another would waste power.</p>

<h3>Step 3: Block Signing</h3>

<p>Once approved, the miner operates normally &mdash; solving proof-of-work puzzles, assembling transactions into blocks, competing with other authorized miners to find valid hashes. The critical addition: when an authorized miner finds a new block, they <strong>sign the block hash with their registered public key</strong>. This signature is included as an additional field in the block structure.</p>

<p>The signature is compact (64&ndash;72 bytes for an ECDSA signature) and adds negligible overhead to block size. But it carries enormous semantic weight: it is cryptographic proof that this block was produced by a specific, identifiable, community-approved miner.</p>

<h3>Step 4: Network Validation</h3>

<p>Marscoin nodes on Mars enforce an additional validation rule beyond standard proof-of-work checks: <strong>is this block signed by a registered, community-approved miner?</strong> The node checks the block signature against the on-chain registry of authorized mining keys. If the signature is valid and the key is in the registry, the block is accepted. If the signature is missing, invalid, or from an unregistered key, the block is rejected &mdash; regardless of whether it has a valid proof-of-work hash, regardless of whether it extends the longest chain.</p>

<p>This is the decisive rule change. The longest chain no longer wins unconditionally. The longest <em>validly signed</em> chain wins. An attacker on Earth can mine a chain a million blocks longer than the Martian chain, but if none of those blocks carry valid signatures from authorized Martian miners, every Martian node will reject them. The attack chain is mathematically valid but politically unauthorized &mdash; and on the Martian network, authorization is part of consensus.</p>

<h3>Step 5: Revocation</h3>

<p>If a miner acts maliciously &mdash; attempting double-spends, mining empty blocks to disrupt the network, or colluding with off-world attackers &mdash; the community can revoke their authorization through a governance vote. Revocation is itself an on-chain transaction, recorded permanently. Once revoked, the miner's key is removed from the valid set, and any future blocks signed by that key are rejected.</p>

<p>Revocation can also handle non-malicious scenarios: hardware failure, a miner leaving the colony, or a reallocation of computing resources to higher-priority tasks. The authorization system is <strong>dynamic</strong> &mdash; miners can be added and removed as the colony's needs evolve, all through the same governance process that manages every other aspect of the Republic.</p>

<div class="callout green">
<p><strong>The key insight:</strong> An Earth attacker can generate unlimited proof of work, but they cannot generate a signature from a key they do not possess. They have not been endorsed by Martian citizens. They do not hold a registered mining key. Their blocks are unsigned &mdash; and unsigned blocks are invalid on the Martian network. The attack surface has shifted from computational power (where Earth dominates) to social trust (where Mars is sovereign).</p>
</div>

<h2>Why This Works: The Security Analysis</h2>

<p>The decentralized licensing system derives its security from several properties that are worth examining individually.</p>

<h3>Decentralized Authorization</h3>

<p>No single entity issues mining licenses. The citizen body as a whole decides who can mine, through the same endorsement and voting mechanisms used for all governance decisions. There is no Martian Mining Authority, no Foundation committee, no appointed regulator. The authorization is distributed across the entire citizen body and recorded immutably on the blockchain. Corrupting the system requires corrupting the majority of citizens &mdash; the same threshold required to corrupt any democratic process.</p>

<h3>Preservation of Proof-of-Work Security</h3>

<p>Within the authorized miner set, proof-of-work operates normally. Authorized miners still compete to find valid hashes. The difficulty adjusts based on the collective hashrate of authorized miners. An attacker who manages to get authorized (more on this below) still needs to outcompute the other authorized miners to execute a 51% attack within the authorized set. The licensing system adds a layer of security; it does not replace the existing one.</p>

<h3>Transparency and Auditability</h3>

<p>Every miner registration, every endorsement, every revocation is on-chain. Any citizen can audit the current set of authorized miners at any time. There are no secret authorizations, no backdoor mining keys, no hidden validators. The mining registry is as transparent as the citizen registry, and for the same reason: in a self-governing society, the governed must be able to verify who governs.</p>

<h3>Fork Compatibility</h3>

<p>If a faction within the colony disagrees with the authorized miner set &mdash; perhaps they believe a miner was unjustly revoked, or that the authorization process has been captured by a political faction &mdash; they can fork. They can run a version of the Marscoin software with a different authorized set and build their own chain. This is no different from any governance disagreement in any blockchain: the ultimate resolution mechanism is the right to exit. Decentralized licensing preserves this right fully.</p>

<h2>Attack Scenarios and Defenses</h2>

<p>Theory is useful but insufficient. The real test of any security system is whether it survives contact with specific, realistic attack scenarios. Here are four.</p>

<h3>Scenario 1: Earth Mining Pool Attacks with Superior Hashrate</h3>

<p><strong>The attack:</strong> A major Earth mining pool &mdash; perhaps one that mines Litecoin and is already familiar with the Scrypt algorithm &mdash; decides to attack the Martian blockchain. They secretly mine a chain thousands of blocks longer than the Martian chain and transmit it during a communication window.</p>

<p><strong>The defense:</strong> Every block in the attack chain lacks a valid signature from an authorized Martian miner. Martian nodes check each block's signature against the authorization registry. None match. The entire attack chain is rejected. The Martian chain continues uninterrupted. The attacker has wasted electricity mining blocks that no node on Mars will ever accept.</p>

<h3>Scenario 2: Rogue Martian Miner Colludes with Earth Attackers</h3>

<p><strong>The attack:</strong> An authorized Martian miner secretly transmits their signing key to an Earth-based accomplice. The accomplice mines blocks on Earth using superior hardware, signs them with the stolen key, and transmits the resulting chain to Mars.</p>

<p><strong>The defense:</strong> This is the most dangerous scenario because the attack blocks carry valid signatures. However, several layers of defense apply. First, blocks signed by a single miner when multiple miners are authorized will appear anomalous &mdash; why is one miner producing 100% of blocks when seven are authorized? Second, block timestamps and difficulty adjustments will be inconsistent with the known hashrate of the authorized set. Third, once detected (and detection is straightforward given the transparency of the system), the community initiates a revocation vote. The rogue miner's key is revoked, and all future blocks signed by that key are rejected. The rogue miner has also destroyed their standing in the community &mdash; their civic address, their citizenship reputation, their social capital. On Mars, that is not an abstract cost.</p>

<h3>Scenario 3: Majority of Authorized Miners Go Offline</h3>

<p><strong>The attack:</strong> Not a deliberate attack, but a failure scenario. A dust storm damages solar arrays. Power rationing forces mining nodes offline. Only one or two authorized miners remain operational. The network is vulnerable to any attacker who can outcompute the reduced set.</p>

<p><strong>The defense:</strong> Emergency governance. The Republic's proposal system includes operational-tier proposals that can be resolved in 24&ndash;48 hours. Citizens vote to authorize backup miners &mdash; perhaps redirecting computational resources from lower-priority tasks, or authorizing hardware that was previously reserved for other functions. The dynamic nature of the licensing system means the authorized set can be expanded quickly in response to emergencies. This is the advantage of coupling mining authorization to governance: the same system that handles emergency decisions in every other domain handles mining emergencies too.</p>

<h3>Scenario 4: Sybil Attack on the Authorization Process</h3>

<p><strong>The attack:</strong> An adversary attempts to register multiple fraudulent mining nodes by creating fake citizen identities and endorsing each other. If they can get enough fake miners authorized, they control the block-signing process.</p>

<p><strong>The defense:</strong> The same Sybil resistance that protects the citizenship registry. Creating a fake citizen requires endorsements from existing citizens &mdash; real people who stake their own reputation on the endorsement. In a small colony where everyone knows everyone, fabricating identities is extraordinarily difficult. This is the same defense that protects governance votes, congressional proposals, and every other civic function. The mining authorization system inherits the full Sybil resistance of the citizenship system, because it <em>is</em> the citizenship system applied to a new domain.</p>

<table class="tier-table">
<thead>
<tr>
  <th>Attack Scenario</th>
  <th>Attack Vector</th>
  <th>Defense Mechanism</th>
  <th>Outcome</th>
</tr>
</thead>
<tbody>
<tr>
  <td><strong>Earth hashrate attack</strong></td>
  <td class="mono">Superior mining power</td>
  <td>Unsigned blocks rejected</td>
  <td class="mono">Attack fails completely</td>
</tr>
<tr>
  <td><strong>Rogue miner collusion</strong></td>
  <td class="mono">Stolen signing key</td>
  <td>Anomaly detection + revocation vote</td>
  <td class="mono">Key revoked, damage limited</td>
</tr>
<tr>
  <td><strong>Mass miner offline</strong></td>
  <td class="mono">Power failure / disaster</td>
  <td>Emergency governance to authorize backups</td>
  <td class="mono">Network recovers within hours</td>
</tr>
<tr>
  <td><strong>Sybil authorization</strong></td>
  <td class="mono">Fake citizen identities</td>
  <td>Citizenship endorsement requirements</td>
  <td class="mono">Same resistance as civic registry</td>
</tr>
</tbody>
</table>

<h2>The Communication Window Attack</h2>

<p>The most subtle attack vector deserves its own section. The plan for Marscoin's transfer to Mars is a clean fork &mdash; similar to the Bitcoin / Bitcoin Cash split of 2017. At the moment of departure, the blockchain is copied to Mars. From that point forward, the Earth chain and the Mars chain diverge permanently. Earth-based Marscoin continues as a legacy chain; the Martian chain becomes the sovereign ledger of the Republic. There is no ongoing synchronization, no interoperability, no shared consensus. Two chains, two worlds, two histories.</p>

<p>But communication windows between Earth and Mars will still exist &mdash; and data will flow between the planets. Could an attacker exploit these windows to inject manipulated chain data? The scenario: during a communication window, a Martian node receives what appears to be legitimate data from Earth &mdash; perhaps relayed through a compromised communication relay or disguised as software updates. The data includes a longer chain that, under standard rules, would force a reorganization.</p>

<p>The signed-block requirement makes this impossible. Every block in the incoming chain must carry a valid signature from an authorized Martian miner. Forged chain data, regardless of how it arrives or how it is disguised, will not carry valid signatures. Martian nodes apply the same validation rules to incoming data regardless of its source. A block from an Earth relay is treated identically to a block from a Martian peer: if it is signed by an authorized miner, accept it. If not, reject it.</p>

<p>The communication window is a data pipe, not a trust boundary. The trust boundary is the authorization registry &mdash; and that registry lives on the Martian chain itself, controlled by Martian citizens.</p>

<h2>The Generalized Framework: Decentralized Licensing</h2>

<p>Mining authorization is the most urgent application of decentralized licensing, but the pattern generalizes. The same mechanism &mdash; citizens vote to authorize actors, actors register public keys, actors sign their actions, the network validates signatures against the authorization registry &mdash; can secure any activity where you need to answer the question: <strong>"Is this actor authorized?"</strong> without relying on a central authority.</p>

<h3>IPFS Node Authorization</h3>

<p>The Republic stores documents, proposals, and attachments on IPFS (the InterPlanetary File System). On Mars, not every IPFS node is equally trustworthy. Which nodes are authorized to pin and serve Republic data? Decentralized licensing: citizens vote to authorize IPFS nodes. Authorized nodes register their peer IDs on-chain. Clients verify that the node serving them data is in the authorized set. Rogue nodes that serve manipulated content can be revoked.</p>

<h3>API Endpoint Certification</h3>

<p>The Republic exposes a REST API for querying citizen data, proposal status, vote tallies, and blockchain state. Which servers are authorized to serve this API? On Earth, we trust HTTPS certificates issued by certificate authorities &mdash; a hierarchical trust model. On Mars, decentralized licensing provides an alternative: citizens authorize API servers, servers register their TLS public keys on-chain, and clients can verify that the server they are talking to is authorized by the Republic.</p>

<h3>Equipment Certification</h3>

<p>Which instruments are authorized to write data to the blockchain? A temperature sensor, an RTG power monitor, a structural integrity scanner &mdash; any device that produces data the Republic relies upon needs a verifiable identity and a chain of authorization back to the citizen body. This application is explored in depth in the companion article on <a href="/academy/blockchain-attested-data-streams">blockchain-attested data streams</a>.</p>

<div class="callout">
<p><strong>The universal pattern:</strong> Citizens &rarr; Vote &rarr; Authorize &rarr; Actor registers public key &rarr; Actor signs their actions &rarr; Network validates signature against authorization registry. This pattern is the same whether the actor is a miner, an IPFS node, an API server, or a temperature sensor. Decentralized licensing is a general-purpose authorization framework powered by democratic governance.</p>
</div>

<h2>Historical Precedents and Comparisons</h2>

<p>The decentralized licensing model is new, but it is not without historical context. Every major blockchain consensus mechanism has made a specific trade-off between openness and security. Understanding those trade-offs clarifies what the Republic's model achieves.</p>

<h3>Bitcoin: Permissionless Mining</h3>

<p>Bitcoin's design, as specified by Satoshi Nakamoto in the 2008 whitepaper, is explicitly permissionless. Anyone can mine. No registration, no authorization, no identity required. This was a radical design choice &mdash; one that works because Earth's mining ecosystem is massive and distributed. Attacking Bitcoin requires overpowering millions of miners across dozens of countries. The permissionless model's security scales with network size.</p>

<p>For a 100-person colony with 10 mining nodes, the permissionless model provides no security at all. It is an open invitation to any Earth-based attacker with a used ASIC and a communication relay.</p>

<h3>Proof of Authority (PoA)</h3>

<p>Proof-of-authority chains like VeChain and some Ethereum testnets use a fixed set of pre-approved validators. Blocks are produced only by validators on a whitelist. This is structurally similar to decentralized licensing &mdash; but with a critical difference: in PoA systems, the whitelist is typically controlled by a foundation, a consortium, or a fixed set of organizations. The authority is centralized. If the foundation is compromised, the validator set is compromised.</p>

<p>The Republic's model replaces the foundation with the citizen body. No single entity controls the authorized set. Adding or removing miners requires a governance vote with transparent, on-chain records. It is proof of authority where the authority is democratic, not institutional.</p>

<h3>Delegated Proof of Stake (DPoS)</h3>

<p>DPoS systems like EOS (launched June 2018) allow token holders to vote for block producers. This introduces democracy into consensus &mdash; but it is <strong>plutocratic democracy</strong>. Votes are weighted by token holdings. A whale holding 10% of the supply has 10% of the voting power. In EOS's history, this led to well-documented cartel behavior: the top 21 block producers colluded to maintain their positions, and small token holders had no meaningful influence.</p>

<p>The Republic's model is <strong>one citizen, one vote</strong>. Mining authorization is not weighted by coin holdings. A citizen who holds 100,000 Marscoin has exactly the same vote as a citizen who holds 10. This is a deliberate design choice: on Mars, the right to participate in security decisions should be based on membership in the community, not on wealth.</p>

<table class="tier-table">
<thead>
<tr>
  <th>Consensus Model</th>
  <th>Who Decides?</th>
  <th>Authorization</th>
  <th>Key Weakness</th>
</tr>
</thead>
<tbody>
<tr>
  <td><strong>Bitcoin PoW</strong></td>
  <td class="mono">Anyone with hardware</td>
  <td class="mono">Permissionless</td>
  <td>Fails with small miner count</td>
</tr>
<tr>
  <td><strong>Proof of Stake</strong></td>
  <td class="mono">Token holders</td>
  <td class="mono">Wealth-based</td>
  <td>Plutocratic; Earth holders dominate</td>
</tr>
<tr>
  <td><strong>Proof of Authority</strong></td>
  <td class="mono">Foundation / consortium</td>
  <td class="mono">Centralized whitelist</td>
  <td>Single point of trust failure</td>
</tr>
<tr>
  <td><strong>Delegated PoS</strong></td>
  <td class="mono">Token-weighted voting</td>
  <td class="mono">Plutocratic election</td>
  <td>Cartel formation; wealth = power</td>
</tr>
<tr>
  <td><strong>Proof of Citizenship</strong></td>
  <td class="mono">Verified citizens (1:1 vote)</td>
  <td class="mono">Democratic licensing</td>
  <td>Requires robust identity system</td>
</tr>
</tbody>
</table>

<h3>Proof of Citizenship: A New Consensus Modifier</h3>

<p>The Republic's approach introduces what we call <strong>Proof of Citizenship</strong> &mdash; not a standalone consensus mechanism, but a <em>modifier</em> layered on top of proof of work. PoW still secures the chain against attacks within the authorized set. Proof of Citizenship secures the chain against attacks from outside the authorized set. Together, they provide a defense that neither mechanism could achieve alone.</p>

<p>This is a genuinely novel contribution to blockchain consensus theory. Proof of Work answers: "Did someone expend computational effort to produce this block?" Proof of Citizenship answers: "Was that someone authorized by the democratic process to produce blocks?" Both questions must be answered affirmatively for a block to be accepted.</p>

<h2>Implementation Considerations</h2>

<p>Moving from concept to code requires addressing several practical questions.</p>

<h3>Soft Fork vs. Hard Fork</h3>

<p>The block-signing requirement can be implemented as a <strong>soft fork</strong>: a tightening of existing validation rules rather than a change in block structure. Nodes running the updated software enforce the signing requirement; nodes running older software see signed blocks as valid (the signature is stored in a backward-compatible field). This is the preferred deployment strategy because it does not require every node to upgrade simultaneously.</p>

<h3>The Transition Point</h3>

<p>The signing requirement should activate at the point of <strong>blockchain transfer to Mars</strong>, not before. While Marscoin operates primarily on Earth (as it does today), the permissionless mining model and merge-mining via AuxPoW work well. The transition to Proof of Citizenship happens when the chain moves to Mars and Earth-based mining becomes a threat rather than an asset. This could be implemented as a flag-day activation: at a specific block height, the signing requirement activates. Before that height, standard rules apply. After it, only signed blocks from authorized miners are accepted.</p>

<h3>Key Management</h3>

<p>Authorized miners must protect their signing keys with extreme care. A stolen key enables the rogue-miner collusion attack described above. On Mars, key storage in hardware security modules (HSMs) &mdash; or even air-gapped signing devices &mdash; is essential. The Republic's governance system can mandate minimum key security standards as a condition of mining authorization.</p>

<h3>Key Rotation</h3>

<p>Signing keys should be rotated periodically to limit the damage window if a key is compromised. A governance proposal can mandate rotation intervals &mdash; perhaps every 10,000 blocks or every Martian month (roughly 28 Martian sols). The rotation process: the miner generates a new key pair, registers the new public key on-chain (signed by the old key to prove continuity), and the community confirms. Seamless, auditable, and decentralized.</p>

<h3>Emergency Key Revocation</h3>

<p>For urgent situations &mdash; a confirmed key compromise, a miner caught colluding &mdash; the Republic's operational-tier governance process allows rapid response. An emergency revocation proposal can be raised and resolved within 24 hours, removing the compromised key from the authorized set before significant damage occurs.</p>

<h2>The Broader Implication: Governance Is Security</h2>

<p>The hash-war protection system reveals a deeper truth about blockchain security in the interplanetary context: <strong>governance and security are not separate concerns</strong>. They are the same concern.</p>

<p>On Earth, blockchain security is primarily a function of economics and computation. You secure a chain by making it expensive to attack &mdash; either through proof of work (expensive hardware and electricity) or proof of stake (expensive capital lockup). Governance is an afterthought, handled off-chain through informal coordination among developers, miners, and exchanges.</p>

<p>On Mars, this model inverts. The colony cannot compete on computational or economic terms with Earth. Its security advantage is <strong>social</strong>: a small, cohesive community where citizens know each other, where identity is verified, where trust is earned through participation in civic life. The governance system that enables citizens to vote on proposals and endorse newcomers also protects the financial and administrative backbone of the colony from external attack.</p>

<blockquote>
<p>"These 'licenses' are just one example how the 'hive mind' of the Martian Republic can add and revoke licenses granted to individuals."</p>
<p style="font-size:13px; color:var(--mr-text-faint); font-style:normal; margin-top:8px;">&mdash; Marscoin Foundation, Mars Society Convention presentation</p>
</blockquote>

<p>The blockchain protects the Republic &mdash; by recording votes, endorsements, and proposals immutably. And the Republic protects the blockchain &mdash; by authorizing the miners who produce its blocks. This is a closed loop of mutual defense: <strong>democracy secures the chain, and the chain secures democracy</strong>.</p>

<p>No proof-of-work chain on Earth has ever needed this. But no proof-of-work chain on Earth has ever been a million miles from the nearest attacker with a trillion-to-one hashrate advantage. The interplanetary context demands a new security model &mdash; one that combines the mathematical rigor of cryptographic proof with the social legitimacy of democratic governance.</p>

<div class="callout mars-red">
<p><strong>The synthesis:</strong> Proof of Work proves that computational effort was expended. Proof of Citizenship proves that the community authorized the effort. Neither is sufficient alone. Together, they create a consensus mechanism that is both mathematically secure and democratically legitimate &mdash; a blockchain that cannot be overwritten by anyone the citizens have not authorized. On Mars, governance IS security. Democracy IS defense.</p>
</div>

<p>The first Martian settlement will inherit a blockchain that has been tested on Earth, hardened by a decade of operation, and secured by a governance system built from first principles for the constraints of another world. When the hash-war comes &mdash; and it will come, because the incentives for attacking a rival chain are as old as competition itself &mdash; the Republic will be ready. Not because it can out-compute Earth. But because its citizens have decided who can mine, and that decision, recorded on-chain and enforced by every node, is the one thing an attacker on Earth cannot forge.</p>

</article>

<!-- CONTINUE READING -->
<div class="continue-reading">
  <h3>Continue Learning</h3>
  <a href="/academy/blockchain-attested-data-streams" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-microchip" style="margin-right:8px; color:var(--mr-cyan);"></i> Blockchain-Attested Data Streams: When Machines Report to the Republic</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy/governance-and-voting" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-landmark" style="margin-right:8px; color:var(--mr-mars);"></i> How Mars Governs Itself: Governance &amp; Voting</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-arrow-left" style="margin-right:8px; color:var(--mr-cyan);"></i> Back to The Academy</span>
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