<!DOCTYPE html>
<html lang="en">
<head>
<title>Dynamic Quorum: Why Fixed Thresholds Kill DAOs - The Academy</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Why fixed quorum requirements killed Yam Finance and forced Compound to cut its threshold by 60%. The math of dynamic quorum, Tezos's formula, and the Martian Republic's active-citizen model.">
<meta name="keywords" content="dynamic quorum, DAO governance, Tezos, Compound, Yam Finance, exponential moving average, active citizen, Martian Republic, Arbitrum">
<meta property="og:title" content="Dynamic Quorum: Why Fixed Thresholds Kill DAOs">
<meta property="og:description" content="The math of governance participation. How Compound's 10%-to-4% migration, Yam's collapse, and Tezos's elegant formula shaped the Martian Republic's quorum system.">
<meta property="og:image" content="https://martianrepublic.org/assets/citizen/mars_flag5.png">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://www.martianrepublic.org/academy/dynamic-quorum">

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
.mr-nav-brand img { width: 32px; height: 32px; border-radius: 50%; }
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

/* ---- Formula Box ---- */
.formula-box {
  background: var(--mr-dark);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  padding: 32px;
  margin: 32px 0;
  text-align: center;
}
.formula-box .formula {
  font-family: var(--mr-font-mono);
  font-size: 20px;
  color: var(--mr-cyan);
  margin-bottom: 16px;
  line-height: 1.6;
}
.formula-box .formula-label {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mr-text-faint);
  margin-bottom: 8px;
}
.formula-box .formula-desc {
  font-family: var(--mr-font-body);
  font-size: 14px;
  color: var(--mr-text-dim);
  max-width: 560px;
  margin: 0 auto;
  line-height: 1.7;
}

/* ---- Scenario Cards ---- */
.scenario-card {
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  padding: 28px;
  margin: 24px 0;
  position: relative;
  overflow: hidden;
}
.scenario-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; bottom: 0;
  width: 4px;
}
.scenario-card.disaster::before { background: var(--mr-red); }
.scenario-card.success::before { background: var(--mr-green); }
.scenario-card.warning::before { background: var(--mr-amber); }
.scenario-card.neutral::before { background: var(--mr-cyan); }

.scenario-card h4 {
  font-family: var(--mr-font-display);
  font-size: 18px;
  font-weight: 700;
  margin: 0 0 6px;
  color: #fff;
}
.scenario-card .scenario-meta {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mr-text-faint);
  margin-bottom: 12px;
}
.scenario-card p {
  font-family: var(--mr-font-body);
  font-size: 15px;
  color: var(--mr-text-dim);
  line-height: 1.7;
  margin-bottom: 0;
}

/* ---- Data Table ---- */
.data-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin: 32px 0;
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  overflow: hidden;
}
.data-table thead th {
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
.data-table tbody td {
  padding: 14px 16px;
  font-size: 14px;
  color: var(--mr-text);
  border-bottom: 1px solid var(--mr-border);
  vertical-align: top;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table .mono {
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
  .formula-box .formula { font-size: 14px; }
}
</style>
</head>

<body>

<!-- NAV -->
<nav class="mr-nav">
  <div class="nav-inner">
    <a href="/" class="mr-nav-brand">
      <img src="/assets/citizen/mars_flag5.png" alt="Martian Republic">
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
    <a href="/">Home</a><span>/</span><a href="/academy">Academy</a><span>/</span><a href="/academy">Governance</a><span>/</span><span style="color:var(--mr-text);">Dynamic Quorum</span>
  </div>
  <span class="article-tag-hero">Governance &amp; Congress</span>
  <h1>Dynamic Quorum: Why Fixed Thresholds Kill DAOs</h1>
  <div class="article-hero-meta">
    <span><i class="fa-solid fa-clock"></i> 12 min read</span>
    <span><i class="fa-solid fa-calendar"></i> March 27, 2026</span>
    <span><i class="fa-solid fa-user"></i> The Marscoin Foundation</span>
    <span><i class="fa-solid fa-signal"></i> Essential Reading</span>
  </div>
</header>

<!-- FULL-BLEED HERO IMAGE -->
<div class="article-hero-image">
  <img src="/assets/academy/congress-chamber-2.jpg" alt="The Martian Congressional Chamber">
</div>

<!-- ARTICLE CONTENT -->
<article class="article-content">

<p>Quorum is the minimum number of participants required for a vote to be valid. It is, in theory, the simplest parameter in any governance system &mdash; just a number. In practice, it has killed more DAOs than any hacker, exploit, or hostile takeover.</p>

<p>Set quorum too high, and governance freezes. Proposals cannot pass because not enough people vote, even when those who do vote overwhelmingly agree. Set it too low, and a small minority can push through decisions that the wider community would reject if they were paying attention. The history of on-chain governance is littered with projects that got this number wrong and paid for it with their existence.</p>

<p>This article tells the story of that failure mode &mdash; from Compound's painful 10%-to-4% migration, to Yam Finance's mathematical death spiral, to Tezos's elegant solution &mdash; and explains how the Martian Republic uses dynamic quorum to ensure governance works at every scale, from 30 citizens to 30,000.</p>

<h2>The Fixed Quorum Trap</h2>

<p>Most governance systems set quorum as a fixed percentage of total eligible voters. It seems intuitive: "20% of all token holders must vote for a proposal to be valid." The problem is that the denominator &mdash; "all token holders" or "all citizens" &mdash; grows in ways that have nothing to do with governance participation.</p>

<p>Tokens accumulate in exchange cold wallets, lost wallets, and the accounts of holders who bought the token purely as an investment and have no intention of ever voting. Citizens move away, lose interest, or simply forget their accounts exist. The denominator grows. The numerator (actual voters) stays roughly the same. The effective difficulty of reaching quorum increases with every passing month.</p>

<div class="callout mars-red">
<p><strong>The empirical evidence is devastating:</strong> Across 200+ DAOs, the median voter participation rate sits below <strong>5%</strong> of eligible voters. A study of 50 DAOs found average turnout of just <strong>1.77%</strong>. Even well-governed protocols like Compound and Uniswap rarely exceed 10% participation on routine proposals. Any quorum requirement that assumes more than single-digit participation is, statistically, a governance death sentence.</p>
</div>

<h2>Case Study: Compound's 10% to 4% Migration</h2>

<div class="scenario-card warning">
<h4>Compound Finance: The Quorum Reduction</h4>
<div class="scenario-meta">2020 &ndash; 2022 &bull; Governance Gridlock</div>
<p>Compound started with a 10% quorum requirement. Proposals stalled for months. The protocol was forced to cut quorum by 60% to restore governance functionality.</p>
</div>

<p>Compound Finance launched its governance system with a <strong>10% quorum</strong> &mdash; meaning 10% of all COMP tokens in existence needed to participate in a vote for it to be valid. This seemed conservative. Surely one in ten token holders would care about the governance of a protocol managing billions in user deposits.</p>

<p>The reality was brutal. COMP tokens were distributed across thousands of wallets, many belonging to:</p>

<ul>
<li>Exchange cold wallets (Coinbase alone held millions of COMP)</li>
<li>Early investors who held tokens purely for price appreciation</li>
<li>Lost or abandoned wallets</li>
<li>Investors who didn't even know they had governance rights</li>
</ul>

<p>The result: proposals that received <strong>overwhelming support from everyone who voted</strong> still failed to reach quorum. The community wanted to act. The math said no.</p>

<p>Compound's solution was to deploy Governor Bravo &mdash; an upgraded governance contract that allowed parameter changes &mdash; and lower the quorum to <strong>4%</strong>. This 60% reduction restarted governance, but it came at a cost: the lower quorum eventually enabled the "Golden Boys" capture attempt in July 2024, where a voting bloc with 228,000 borrowed COMP tokens controlled 81% of the 400,000 COMP quorum and passed a $24 million self-enrichment proposal.</p>

<p>Compound's dilemma illustrates the fundamental problem with fixed quorum: every choice is bad. Too high and governance freezes. Too low and governance is captured. There is no fixed number that is correct at every scale, at every level of community engagement, at every point in a protocol's lifecycle.</p>

<h2>Case Study: Yam Finance's Mathematical Death (2020)</h2>

<div class="scenario-card disaster">
<h4>Yam Finance: Governance Impossible</h4>
<div class="scenario-meta">August 11&ndash;13, 2020 &bull; Total Collapse</div>
<p>A rebase bug minted tokens owned by the governance contract itself. Because these tokens existed but couldn't vote, quorum became mathematically impossible. The protocol died in 48 hours.</p>
</div>

<p>Yam Finance is the purest case study of fixed quorum killing a protocol. Launched on August 11, 2020, Yam attracted <strong>$400 million in staked assets within 24 hours</strong>. Two days later, the team discovered a critical bug.</p>

<p>The rebase contract &mdash; designed to automatically adjust the YAM token supply &mdash; had a flaw that minted far more tokens than intended. Crucially, these excess tokens were sent to the governance contract itself. The governance contract owned the tokens but could not vote with them. They existed in the total supply (the denominator) but could never participate in governance (the numerator).</p>

<p>The quorum requirement was <strong>160,000 votes</strong> to approve a proposal. After the bug, the inflated total supply made this threshold unreachable. The team rallied the community to delegate their votes toward a rescue proposal, believing they had enough. They were wrong. The bug's interaction with the governance module made quorum mathematically impossible.</p>

<table class="data-table">
<thead>
<tr>
  <th>Metric</th>
  <th>Before Bug</th>
  <th>After Bug</th>
</tr>
</thead>
<tbody>
<tr>
  <td>Quorum requirement</td>
  <td class="mono">160,000 votes</td>
  <td class="mono">160,000 votes (unchanged)</td>
</tr>
<tr>
  <td>Votable tokens</td>
  <td class="mono">Sufficient</td>
  <td class="mono" style="color:var(--mr-red);">Insufficient (governance contract held excess)</td>
</tr>
<tr>
  <td>Treasury status</td>
  <td class="mono" style="color:var(--mr-green);">Accessible</td>
  <td class="mono" style="color:var(--mr-red);">Permanently locked</td>
</tr>
<tr>
  <td>Governance status</td>
  <td class="mono" style="color:var(--mr-green);">Functional</td>
  <td class="mono" style="color:var(--mr-red);">Permanently frozen</td>
</tr>
</tbody>
</table>

<p>The treasury was locked. Governance was permanently frozen. A protocol that held $400 million collapsed in <strong>48 hours</strong> &mdash; not because of an attack, not because of a market crash, but because a fixed quorum number became mathematically unreachable.</p>

<h2>Case Study: Jupiter and the Governance Surrender (2025)</h2>

<p>Yam's collapse was dramatic but brief. A slower, more common version of quorum failure played out across dozens of DAOs over the following years. Jupiter, the leading DEX aggregator on Solana, <strong>paused all DAO governance in mid-2025</strong>. Yuga Labs scrapped its ApeCoin DAO entirely. Neither was attacked. Both simply acknowledged that their governance structures had failed &mdash; chronic low participation made quorum unreachable for routine operations, and the accumulated weight of unactionable proposals drove the remaining active participants away.</p>

<p>The pattern is universal: participation declines over time, fixed quorum stays the same, the gap widens, governance dies.</p>

<h2>Tezos: The Elegant Solution</h2>

<p>Tezos, the only blockchain to achieve 18+ consecutive protocol upgrades through on-chain governance without a contentious fork, solved the quorum problem with a formula so simple it fits in a single line.</p>

<div class="formula-box">
<div class="formula-label">Tezos Participation EMA</div>
<div class="formula">participation_ema = 0.8 &times; previous_ema + 0.2 &times; last_vote_participation</div>
<div class="formula-desc" style="margin-top: 16px;">The participation estimate is an exponential moving average that weights the most recent vote at 20% and the accumulated history at 80%. It naturally adapts to real participation trends.</div>
</div>

<p>This is an <strong>exponential moving average (EMA)</strong> &mdash; a mathematical function that smoothly tracks a changing value while resisting sudden spikes. The factor 0.8/0.2 means each new vote's participation rate contributes 20% to the running average, while the previous history retains 80% weight. The average adjusts gradually, never jumping or crashing.</p>

<p>Tezos then uses this participation EMA to calculate the expected quorum for the next vote:</p>

<div class="formula-box">
<div class="formula-label">Tezos Expected Quorum</div>
<div class="formula">expected_quorum = 0.2 + (0.7 &minus; 0.2) &times; participation_ema</div>
<div class="formula-desc" style="margin-top: 16px;">The expected quorum is bounded between 20% and 70%. When participation is high, quorum rises. When participation drops, quorum falls &mdash; but never below 20%, ensuring a meaningful minimum threshold always exists.</div>
</div>

<p>The genius of this design is in what it prevents:</p>

<ul>
<li><strong>It prevents governance freeze.</strong> If participation trends downward over multiple votes, the quorum tracks it down. The system never becomes unreachable.</li>
<li><strong>It prevents governance capture.</strong> The 20% floor means you always need at least one-fifth of eligible voters, even if participation has been declining.</li>
<li><strong>It prevents sudden manipulation.</strong> Because the EMA weights history at 80%, a single high-turnout or low-turnout vote cannot dramatically shift the quorum. An attacker would need to manipulate participation across many consecutive votes to move the threshold significantly.</li>
<li><strong>It is self-correcting.</strong> If a contentious proposal drives high turnout, the quorum ratchets up naturally, reflecting the community's demonstrated willingness to engage.</li>
</ul>

<h3>Tezos in Practice</h3>

<p>The results over seven years of operation speak for themselves:</p>

<table class="data-table">
<thead>
<tr>
  <th>Metric</th>
  <th>Value</th>
</tr>
</thead>
<tbody>
<tr>
  <td>Consecutive successful on-chain upgrades</td>
  <td class="mono" style="color:var(--mr-green);">18+</td>
</tr>
<tr>
  <td>Contentious hard forks</td>
  <td class="mono" style="color:var(--mr-green);">0</td>
</tr>
<tr>
  <td>Block time reduction via governance</td>
  <td class="mono">60s &rarr; 30s &rarr; 15s &rarr; 10s &rarr; 8s</td>
</tr>
<tr>
  <td>Upgrade frequency</td>
  <td class="mono">3&ndash;4 per year</td>
</tr>
<tr>
  <td>Supermajority requirement</td>
  <td class="mono">80%</td>
</tr>
<tr>
  <td>Quorum range</td>
  <td class="mono">20% &ndash; 70% (dynamic)</td>
</tr>
</tbody>
</table>

<p>Tezos maintains an <strong>80% supermajority</strong> requirement for protocol upgrades &mdash; far higher than most DAOs. Yet governance never freezes, because the dynamic quorum adapts to the actual voting population. The supermajority ensures that changes have broad support. The dynamic quorum ensures that "broad support" is measured against who actually shows up, not against an abstract total that includes lost wallets and disengaged holders.</p>

<h2>Arbitrum's Approach: The Exclude Address</h2>

<p>Arbitrum, an Ethereum Layer 2 network, took a different but complementary approach to the quorum denominator problem. Instead of dynamically adjusting quorum, Arbitrum <strong>removes known non-participants from the denominator</strong>.</p>

<p>Any ARB token holder can delegate their tokens to a special "exclude address" (<span style="font-family:var(--mr-font-mono); font-size:13px; color:var(--mr-cyan);">0x...0a4b86</span>) &mdash; a read-only address with no private key that cannot vote. Tokens delegated to the exclude address are subtracted from the "votable tokens" denominator when calculating quorum.</p>

<div class="callout">
<p><strong>Primary use case:</strong> Arbitrum's DAO treasury is the single largest holder of ARB tokens. Because these treasury tokens will never vote, they are permanently delegated to the exclude address, preventing them from inflating the quorum denominator. But any address can opt into exclusion &mdash; exchanges, long-term holders who don't governance-participate, anyone who wants to remove themselves from the quorum calculation without selling their tokens.</p>
</div>

<p>Arbitrum's two-tier system uses this adjusted denominator:</p>

<table class="data-table">
<thead>
<tr>
  <th>Proposal Type</th>
  <th>Quorum (of votable tokens)</th>
  <th>Timeline</th>
</tr>
</thead>
<tbody>
<tr>
  <td><strong>Constitutional AIPs</strong><br><span style="font-size:12px; color:var(--mr-text-dim);">Governance text, software, new chains</span></td>
  <td class="mono">5%</td>
  <td class="mono">34&ndash;37 days</td>
</tr>
<tr>
  <td><strong>Non-constitutional AIPs</strong><br><span style="font-size:12px; color:var(--mr-text-dim);">Funding, grants, operations</span></td>
  <td class="mono">3%</td>
  <td class="mono">21&ndash;27 days</td>
</tr>
</tbody>
</table>

<p>The exclude address approach solves the "dead weight" problem &mdash; tokens that inflate quorum but will never vote. It does not solve the "declining engagement" problem that Tezos's EMA addresses. Ideally, a governance system uses both: exclude known non-participants <em>and</em> dynamically adjust quorum based on actual engagement trends.</p>

<h2>How the Martian Republic Calibrates Quorum</h2>

<p>The Martian Republic synthesizes the best lessons from Compound, Yam, Tezos, and Arbitrum into a quorum system designed specifically for a growing colony.</p>

<h3>The Core Innovation: Active Citizen Quorum</h3>

<p>The Republic's quorum is not measured against total citizens. It is measured against <strong>active citizens</strong> &mdash; defined as citizens who have voted or endorsed within the trailing <strong>180 sols</strong> (approximately 185 Earth days). This single change eliminates the denominator inflation that killed Yam Finance and paralyzed Compound.</p>

<div class="formula-box">
<div class="formula-label">Martian Republic Active Citizen EMA</div>
<div class="formula">Active<sub>N</sub> = 0.8 &times; Active<sub>N-1</sub> + 0.2 &times; Actual_Participation<sub>last vote</sub></div>
<div class="formula-desc" style="margin-top: 16px;">Adapted from Tezos. The active citizen count is an exponential moving average that naturally adjusts to real participation trends. If engagement drops, the effective quorum drops. If a contentious proposal drives high turnout, the quorum ratchets up.</div>
</div>

<p>The Republic then applies tier-specific quorum percentages against this active-citizen denominator:</p>

<table class="data-table">
<thead>
<tr>
  <th>Tier</th>
  <th>Quorum (of Active Citizens)</th>
  <th>Approval Threshold</th>
  <th>Timelock</th>
</tr>
</thead>
<tbody>
<tr>
  <td><strong style="color:var(--mr-green);">Signal</strong></td>
  <td class="mono">10%</td>
  <td class="mono">50%+1</td>
  <td class="mono">&mdash;</td>
</tr>
<tr>
  <td><strong style="color:var(--mr-cyan);">Operational</strong></td>
  <td class="mono">25%</td>
  <td class="mono">60%</td>
  <td class="mono">3 sols</td>
</tr>
<tr>
  <td><strong style="color:var(--mr-amber);">Legislative</strong></td>
  <td class="mono">40%</td>
  <td class="mono">66%</td>
  <td class="mono">7 sols</td>
</tr>
<tr>
  <td><strong style="color:var(--mr-mars);">Constitutional</strong></td>
  <td class="mono">50%</td>
  <td class="mono">75%</td>
  <td class="mono">30 sols</td>
</tr>
</tbody>
</table>

<h3>Why This Works at Every Scale</h3>

<p>Let's walk through a concrete scenario. The Martian Republic starts with <strong>50 citizens</strong>. All 50 are active early adopters. The active citizen count is 50.</p>

<div class="scenario-card neutral">
<h4>Year 1: 50 Citizens, All Active</h4>
<div class="scenario-meta">Active Citizens: 50</div>
<p>A Legislative proposal needs 40% of 50 = <strong>20 voters</strong>. With an engaged early community, this is easily achievable. The 66% supermajority requires 14 of 20 to vote YES.</p>
</div>

<p>Now the colony grows to <strong>500 citizens</strong>. But only 200 have been active in the last 180 sols. The rest signed up, participated briefly, and drifted away.</p>

<div class="scenario-card neutral">
<h4>Year 3: 500 Citizens, 200 Active</h4>
<div class="scenario-meta">Active Citizens: 200 (EMA-adjusted)</div>
<p>The same Legislative proposal now needs 40% of 200 = <strong>80 voters</strong>. Not 40% of 500 (which would be 200 &mdash; likely impossible). The quorum grows with engagement, not with total signups.</p>
</div>

<p>Compare this to a fixed quorum of 40% of total citizens. At 500 citizens, that would require <strong>200 voters</strong> &mdash; the same as the <em>entire</em> active population. A proposal with 199 YES votes and 0 NO votes would fail. That is not democratic governance. That is mathematical paralysis.</p>

<div class="scenario-card warning">
<h4>The Fixed Quorum Counterfactual</h4>
<div class="scenario-meta">500 Citizens &bull; Fixed 40% Quorum</div>
<p>Required: 200 voters. Active population: 200. A single citizen staying home means quorum is unreachable. <strong>Governance freezes.</strong> This is exactly what happened to Compound, Yam, Jupiter, and dozens of other DAOs.</p>
</div>

<h3>The EMA in Action</h3>

<p>Suppose the Martian Republic's active citizen EMA is currently <strong>180</strong>. Here is how it evolves across three votes with different participation levels:</p>

<table class="data-table">
<thead>
<tr>
  <th>Vote</th>
  <th>Actual Participants</th>
  <th>EMA Calculation</th>
  <th>New Active EMA</th>
</tr>
</thead>
<tbody>
<tr>
  <td>Vote N</td>
  <td class="mono">180 (starting point)</td>
  <td class="mono">&mdash;</td>
  <td class="mono">180</td>
</tr>
<tr>
  <td>Vote N+1 (routine)</td>
  <td class="mono">120</td>
  <td class="mono">0.8 &times; 180 + 0.2 &times; 120</td>
  <td class="mono">168</td>
</tr>
<tr>
  <td>Vote N+2 (low interest)</td>
  <td class="mono">90</td>
  <td class="mono">0.8 &times; 168 + 0.2 &times; 90</td>
  <td class="mono">152</td>
</tr>
<tr>
  <td>Vote N+3 (contentious!)</td>
  <td class="mono">250</td>
  <td class="mono">0.8 &times; 152 + 0.2 &times; 250</td>
  <td class="mono">172</td>
</tr>
</tbody>
</table>

<p>Notice how the EMA <strong>declines gradually</strong> when participation drops (180 &rarr; 168 &rarr; 152) but <strong>rebounds when turnout spikes</strong> (152 &rarr; 172). It never crashes to 90 just because one vote had low turnout. It never jumps to 250 just because one vote was contentious. The system is <em>stable</em>.</p>

<p>For a Legislative proposal requiring 40% quorum, the effective threshold across these votes would be:</p>

<ul>
<li>Vote N: 40% &times; 180 = <strong>72 voters</strong></li>
<li>Vote N+1: 40% &times; 168 = <strong>67 voters</strong></li>
<li>Vote N+2: 40% &times; 152 = <strong>61 voters</strong></li>
<li>Vote N+3: 40% &times; 172 = <strong>69 voters</strong></li>
</ul>

<p>The quorum is always achievable. It tracks reality. It breathes with the community.</p>

<h2>What About Gaming the System?</h2>

<p>A natural question: can someone manipulate the EMA by artificially driving participation up or down?</p>

<p>The 0.8/0.2 weighting makes manipulation expensive. To significantly move the EMA, an attacker would need to influence participation across <strong>many consecutive votes</strong> &mdash; not just one. Reducing the EMA from 180 to 100, for example, would require approximately 10 consecutive votes with near-zero participation. In a community where other citizens are actively voting, this is practically impossible without controlling the proposal pipeline itself.</p>

<p>The Republic's tiered structure provides an additional safeguard. Even if someone could manipulate the EMA downward, Constitutional proposals still require <strong>50% of active citizens</strong> with a <strong>75% supermajority</strong>. A lower EMA means a lower absolute quorum number, but the supermajority requirement ensures that any proposal that passes has overwhelming support among those who voted.</p>

<div class="callout green">
<p><strong>The insight:</strong> Dynamic quorum does not reduce governance legitimacy. It increases it. A proposal that passes with 75% support among 61 active voters is more legitimate than a proposal that fails to reach quorum despite 100% support among 50 voters. The first represents actual community will. The second represents a mathematical accident.</p>
</div>

<h2>The Compound Lesson, Applied</h2>

<p>Compound's journey &mdash; from 10% quorum (governance freeze) to 4% quorum (governance capture) &mdash; illustrates why static adjustment is insufficient. Lowering a fixed threshold is always a bet: that you've found the "right" number this time. The Martian Republic never makes that bet. The number adjusts itself, continuously, based on evidence.</p>

<p>If the Republic's early community of 50 citizens maintains 90% participation, the EMA will sit near 45 active citizens, and quorum thresholds will be proportionally high. If the community grows to 5,000 citizens and participation settles at 15% (a realistic long-term rate based on ecosystem data), the EMA will stabilize near 750 active citizens, and quorum thresholds will calibrate accordingly. No parameter change is required. No governance proposal to adjust the quorum. No emergency migration.</p>

<p>The system just works.</p>

<blockquote>
<p>The most actionable finding from analyzing 15+ major governance systems is the shift from total-citizenship quorum to active-citizen quorum with dynamic adjustment. This single change prevents the governance gridlock that has killed multiple DAOs while preserving meaningful participation requirements.</p>
</blockquote>

<p>Fixed quorum is a bet that the future will look like today. Dynamic quorum is an acknowledgment that it won't. For a colony that must govern itself across decades, through population growth and decline, through crises and calm, through the first generation and the tenth &mdash; dynamic quorum is not a nice feature. It is the difference between governance that survives and governance that doesn't.</p>

</article>

<!-- CONTINUE READING -->
<div class="continue-reading">
  <h3>Continue Learning</h3>
  <a href="/academy/governance-and-voting" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-landmark" style="margin-right:8px; color:var(--mr-mars);"></i> How Mars Governs Itself: The Complete Guide</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy/coinshuffle-secret-ballots" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-shield-halved" style="margin-right:8px; color:var(--mr-cyan);"></i> CoinShuffle: Secret Ballots on a Public Blockchain</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy/history-of-blockchain-governance" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-book" style="margin-right:8px; color:var(--mr-amber);"></i> The History of Blockchain Governance</span>
    <span class="continue-card-arrow"><i class="fa-solid fa-chevron-right"></i></span>
  </a>
  <a href="/academy" class="continue-card">
    <span class="continue-card-title"><i class="fa-solid fa-arrow-left" style="margin-right:8px; color:var(--mr-text-faint);"></i> Back to The Academy</span>
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