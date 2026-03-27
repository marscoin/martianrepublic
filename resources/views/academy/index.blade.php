<!DOCTYPE html>
<html lang="en">
<head>
<title>The Academy - Martian Republic</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Learn about Mars governance, blockchain technology, HD wallets, citizenship, voting systems, and the future of human civilization on Mars.">
<meta name="keywords" content="Mars, Marscoin, governance, blockchain, voting, academy, education, Mars colony">
<meta property="og:title" content="The Academy - Martian Republic">
<meta property="og:description" content="Deep knowledge for Martian citizens. Learn governance, blockchain, wallets, voting, and the science of Mars settlement.">
<meta property="og:image" content="https://martianrepublic.org/assets/citizen/mars_flag5.png">
<meta property="og:type" content="website">
<meta property="og:url" content="https://martianrepublic.org/academy">
<meta property="og:site_name" content="The Martian Republic">
<meta property="og:locale" content="en_US">
<meta name="robots" content="index, follow">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="The Academy - Martian Republic">
<meta name="twitter:description" content="Deep knowledge for Martian citizens. Learn governance, blockchain, wallets, voting, and the science of Mars settlement.">
<meta name="twitter:image" content="https://martianrepublic.org/assets/citizen/mars_flag5.png">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://martianrepublic.org/academy">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "The Academy - Martian Republic",
  "description": "Deep knowledge for Martian citizens. Learn governance, blockchain, wallets, voting, and the science of Mars settlement.",
  "url": "https://martianrepublic.org/academy",
  "publisher": {
    "@type": "Organization",
    "name": "The Martian Republic",
    "url": "https://martianrepublic.org",
    "logo": { "@type": "ImageObject", "url": "https://martianrepublic.org/assets/favicon.ico" }
  }
}
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=DM+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
  --mr-font-display: 'Chakra Petch', sans-serif;
  --mr-font-body: 'DM Sans', sans-serif;
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
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
a { color: var(--mr-cyan); transition: all 0.3s ease; text-decoration: none; }
a:hover { color: var(--mr-amber); text-decoration: none; }
h1,h2,h3,h4,h5,h6 { font-family: var(--mr-font-display); color: #fff; font-weight: 600; }
p { color: var(--mr-text-dim); }
img { max-width: 100%; height: auto; }

/* ---- NAV ---- */
.mr-nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  background: rgba(6,6,12,0.85);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--mr-border);
  padding: 16px 0;
}
.mr-nav .container { display: flex; align-items: center; justify-content: space-between; }
.mr-nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
.mr-nav-brand img { width: 32px; height: 32px; border-radius: 50%; }
.mr-nav-brand span { font-family: var(--mr-font-display); font-weight: 700; font-size: 15px; color: #fff; letter-spacing: 0.5px; text-transform: uppercase; }
.mr-nav-links { display: flex; align-items: center; gap: 28px; }
.mr-nav-links a { color: var(--mr-text-dim); font-size: 14px; font-weight: 500; }
.mr-nav-links a:hover { color: #fff; }
.mr-nav-cta {
  background: var(--mr-mars);
  color: #fff !important;
  padding: 8px 20px;
  border-radius: 6px;
  font-family: var(--mr-font-display);
  font-weight: 600;
  font-size: 13px;
  letter-spacing: 0.5px;
}
.mr-nav-cta:hover { background: var(--mr-mars-glow); color: #fff !important; }

/* ---- HERO ---- */
.academy-hero {
  padding: 140px 0 80px;
  position: relative;
  overflow: hidden;
  background: url('/assets/academy/mars-crescent.jpg') no-repeat bottom right / 65% auto;
}
.academy-hero::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background:
    linear-gradient(90deg, var(--mr-void) 35%, rgba(6,6,12,0.6) 70%, rgba(6,6,12,0.3) 100%),
    radial-gradient(ellipse at 30% 30%, rgba(200,65,37,0.08) 0%, transparent 50%);
  pointer-events: none;
}
.academy-hero::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright) 20%, var(--mr-mars) 50%, var(--mr-border-bright) 80%, transparent);
}
.hero-label {
  font-family: var(--mr-font-mono);
  font-size: 12px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--mr-mars);
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.hero-label .dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--mr-green);
  animation: pulse 2s infinite;
}
.academy-hero h1 {
  font-size: clamp(32px, 5vw, 56px);
  font-weight: 700;
  letter-spacing: -0.5px;
  line-height: 1.1;
  margin: 0 0 20px;
}
.academy-hero h1 span { color: var(--mr-mars); }
.hero-desc {
  font-size: 18px;
  line-height: 1.8;
  color: var(--mr-text-dim);
  max-width: 600px;
  margin-bottom: 32px;
}
.hero-stats {
  display: flex;
  gap: 40px;
  margin-top: 40px;
}
.hero-stat {
  text-align: left;
}
.hero-stat-value {
  font-family: var(--mr-font-display);
  font-size: 28px;
  font-weight: 700;
  color: #fff;
}
.hero-stat-label {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mr-text-faint);
}

/* ---- LEARNING PATHS ---- */
.section { padding: 80px 0; }
.section-label {
  font-family: var(--mr-font-mono);
  font-size: 12px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--mr-mars);
  margin-bottom: 12px;
}
.section-title {
  font-size: clamp(24px, 3vw, 36px);
  font-weight: 700;
  margin-bottom: 16px;
}
.section-desc {
  font-size: 16px;
  color: var(--mr-text-dim);
  max-width: 600px;
  margin-bottom: 48px;
}

.path-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}
.path-card {
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 12px;
  padding: 32px 28px;
  position: relative;
  transition: all 0.3s ease;
  overflow: hidden;
}
.path-card:hover {
  border-color: var(--mr-border-bright);
  transform: translateY(-2px);
}
.path-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
}
.path-card:nth-child(1)::before { background: var(--mr-mars); }
.path-card:nth-child(2)::before { background: var(--mr-cyan); }
.path-card:nth-child(3)::before { background: var(--mr-green); }
.path-card:nth-child(4)::before { background: var(--mr-amber); }

.path-number {
  font-family: var(--mr-font-display);
  font-size: 48px;
  font-weight: 700;
  color: var(--mr-border-bright);
  line-height: 1;
  margin-bottom: 16px;
}
.path-card:nth-child(1) .path-number { color: rgba(200,65,37,0.3); }
.path-card:nth-child(2) .path-number { color: rgba(0,228,255,0.2); }
.path-card:nth-child(3) .path-number { color: rgba(52,211,153,0.2); }
.path-card:nth-child(4) .path-number { color: rgba(212,164,74,0.2); }

.path-title {
  font-family: var(--mr-font-display);
  font-size: 18px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 8px;
}
.path-desc {
  font-size: 14px;
  color: var(--mr-text-dim);
  line-height: 1.7;
  margin-bottom: 20px;
}
.path-meta {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  color: var(--mr-text-faint);
  letter-spacing: 1px;
  margin-bottom: 20px;
}
.path-link {
  font-family: var(--mr-font-display);
  font-size: 13px;
  font-weight: 600;
  color: var(--mr-cyan);
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.path-link:hover { color: #fff; }

/* ---- FEATURED ARTICLES ---- */
.articles-section { padding: 80px 0; }

.article-card {
  display: grid;
  grid-template-columns: 200px 1fr;
  gap: 28px;
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 12px;
  padding: 28px;
  margin-bottom: 16px;
  transition: all 0.3s ease;
  text-decoration: none;
}
.article-card:hover {
  border-color: var(--mr-border-bright);
  transform: translateY(-1px);
  text-decoration: none;
}
.article-thumb {
  width: 200px;
  height: 140px;
  border-radius: 8px;
  overflow: hidden;
  background: var(--mr-dark);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
}
.article-thumb.governance { background: linear-gradient(135deg, rgba(200,65,37,0.2), rgba(0,228,255,0.1)); color: var(--mr-mars); }
.article-thumb.blockchain { background: linear-gradient(135deg, rgba(0,228,255,0.2), rgba(52,211,153,0.1)); color: var(--mr-cyan); }
.article-thumb.mars { background: linear-gradient(135deg, rgba(212,164,74,0.2), rgba(200,65,37,0.1)); color: var(--mr-amber); }
.article-thumb.wallet { background: linear-gradient(135deg, rgba(52,211,153,0.2), rgba(0,228,255,0.1)); color: var(--mr-green); }

.article-body { display: flex; flex-direction: column; justify-content: center; }
.article-tag {
  font-family: var(--mr-font-mono);
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
  padding: 3px 10px;
  border-radius: 4px;
  display: inline-block;
  margin-bottom: 10px;
  width: fit-content;
}
.article-tag.governance { background: rgba(200,65,37,0.15); color: var(--mr-mars); }
.article-tag.blockchain { background: var(--mr-cyan-dim); color: var(--mr-cyan); }
.article-tag.mars { background: rgba(212,164,74,0.15); color: var(--mr-amber); }
.article-tag.wallet { background: rgba(52,211,153,0.15); color: var(--mr-green); }
.article-tag.citizenship { background: rgba(200,65,37,0.15); color: var(--mr-mars); }

.article-title {
  font-family: var(--mr-font-display);
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 8px;
  line-height: 1.3;
}
.article-card:hover .article-title { color: var(--mr-cyan); }
.article-excerpt {
  font-size: 14px;
  color: var(--mr-text-dim);
  line-height: 1.7;
  margin-bottom: 12px;
}
.article-meta {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  color: var(--mr-text-faint);
  display: flex;
  gap: 16px;
}

/* ---- COMING SOON GRID ---- */
.upcoming-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 16px;
  margin-top: 32px;
}
.upcoming-card {
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 10px;
  padding: 24px;
  opacity: 0.6;
  position: relative;
}
.upcoming-card::after {
  content: 'COMING SOON';
  position: absolute;
  top: 12px; right: 12px;
  font-family: var(--mr-font-mono);
  font-size: 9px;
  letter-spacing: 2px;
  color: var(--mr-text-faint);
  background: var(--mr-dark);
  padding: 3px 8px;
  border-radius: 3px;
  border: 1px solid var(--mr-border);
}
.upcoming-title {
  font-family: var(--mr-font-display);
  font-size: 15px;
  font-weight: 600;
  color: var(--mr-text-dim);
  margin-bottom: 6px;
}
.upcoming-desc {
  font-size: 13px;
  color: var(--mr-text-faint);
  line-height: 1.6;
}

/* ---- FOOTER ---- */
.mr-footer {
  background: var(--mr-dark);
  padding: 60px 0 30px;
  margin-top: 40px;
  border-top: 1px solid var(--mr-border);
}
.mr-footer-inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.mr-footer-copy {
  font-size: 13px;
  color: var(--mr-text-faint);
}
.mr-footer-links { display: flex; gap: 24px; }
.mr-footer-links a { color: var(--mr-text-faint); font-size: 13px; }
.mr-footer-links a:hover { color: #fff; }

/* ---- RESPONSIVE ---- */
@media (max-width: 991px) {
  .path-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .path-grid { grid-template-columns: 1fr; }
  .article-card { grid-template-columns: 1fr; }
  .article-thumb { width: 100%; height: 160px; }
  .hero-stats { flex-direction: column; gap: 16px; }
}

/* ---- ANIMATIONS ---- */
@keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.fade-up { animation: fadeUp 0.6s ease-out both; }
.fade-up-d1 { animation-delay: 0.1s; }
.fade-up-d2 { animation-delay: 0.2s; }
.fade-up-d3 { animation-delay: 0.3s; }
.fade-up-d4 { animation-delay: 0.4s; }
</style>
</head>

<body>

<!-- NAV -->
<nav class="mr-nav">
  <div class="container">
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

<!-- HERO -->
<section class="academy-hero">
  <div class="container">
    <div class="hero-label">
      <span class="dot"></span> The Academy
    </div>
    <h1 class="fade-up">Knowledge for<br><span>Martian Citizens</span></h1>
    <p class="hero-desc fade-up fade-up-d1">
      Deep, authoritative articles on governance, blockchain technology, Mars settlement,
      and the systems that power the first human democracy beyond Earth.
    </p>
    <div class="hero-stats fade-up fade-up-d2">
      <div class="hero-stat">
        <div class="hero-stat-value">4</div>
        <div class="hero-stat-label">Learning Paths</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-value">100%</div>
        <div class="hero-stat-label">Open Access</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-value">Free</div>
        <div class="hero-stat-label">Always</div>
      </div>
    </div>
  </div>
</section>

<!-- LEARNING PATHS -->
<section class="section">
  <div class="container">
    <div class="section-label">Learning Paths</div>
    <h2 class="section-title">Master the Republic</h2>
    <p class="section-desc">Structured journeys through the core knowledge every Martian citizen needs. From blockchain fundamentals to constitutional governance.</p>

    <div class="path-grid">
      <div class="path-card fade-up">
        <div class="path-number">01</div>
        <div class="path-title">Mars &amp; Settlement</div>
        <div class="path-desc">The planet, its challenges, and why humanity must become multi-planetary. Habitats, resources, terraforming, and the case for Mars.</div>
        <div class="path-meta">4 ARTICLES &bull; FUNDAMENTALS</div>
        <a href="#mars" class="path-link">Coming soon <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="path-card fade-up fade-up-d1">
        <div class="path-number">02</div>
        <div class="path-title">Blockchain &amp; Marscoin</div>
        <div class="path-desc">How blockchains work, why they matter for governance, the history of Marscoin, HD wallets, IPFS, and cryptographic identity.</div>
        <div class="path-meta">6 ARTICLES &bull; TECHNICAL</div>
        <a href="#blockchain" class="path-link">Coming soon <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="path-card fade-up fade-up-d2">
        <div class="path-number">03</div>
        <div class="path-title">Governance &amp; Congress</div>
        <div class="path-desc">How the Martian Republic governs itself. Proposal tiers, voting mechanics, CoinShuffle privacy, and lessons from Earth's experiments in democracy.</div>
        <div class="path-meta">5 ARTICLES &bull; ESSENTIAL</div>
        <a href="/academy/governance-and-voting" class="path-link">Start learning <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="path-card fade-up fade-up-d3">
        <div class="path-number">04</div>
        <div class="path-title">Citizenship &amp; Identity</div>
        <div class="path-desc">The pioneer journey from arrival to full citizenship. Endorsements, civic wallets, identity verification, and what it means to be a Martian citizen.</div>
        <div class="path-meta">4 ARTICLES &bull; ONBOARDING</div>
        <a href="#citizenship" class="path-link">Coming soon <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED ARTICLES -->
<section class="articles-section">
  <div class="container">
    <div class="section-label">Featured Articles</div>
    <h2 class="section-title">Deep Reads</h2>
    <p class="section-desc">In-depth explorations of the systems, science, and philosophy behind the Martian Republic.</p>

    <!-- LIVE ARTICLE -->
    <a href="/academy/governance-and-voting" class="article-card fade-up">
      <div class="article-thumb governance" style="background:url('/assets/academy/congress-chamber-1.jpg') center/cover; font-size:0;">
        &nbsp;
      </div>
      <div class="article-body">
        <span class="article-tag governance">Governance</span>
        <h3 class="article-title">How Mars Governs Itself: The Complete Guide to Martian Democracy</h3>
        <p class="article-excerpt">From signal polls to constitutional amendments -- how the Martian Republic's four-tier governance system works, why it's different from every DAO on Earth, and the lessons learned from a decade of blockchain governance experiments.</p>
        <div class="article-meta">
          <span><i class="fa-solid fa-clock"></i> 25 min read</span>
          <span><i class="fa-solid fa-calendar"></i> March 2026</span>
          <span><i class="fa-solid fa-signal"></i> Essential</span>
        </div>
      </div>
    </a>

    <!-- LIVE ARTICLES -->
    <a href="/academy/history-of-blockchain-governance" class="article-card fade-up fade-up-d1">
      <div class="article-thumb governance" style="background:url('/assets/academy/congress-chamber-3.jpg') center/cover; font-size:0;">
        &nbsp;
      </div>
      <div class="article-body">
        <span class="article-tag governance">Governance</span>
        <h3 class="article-title">The History of Blockchain Governance: From Bitcoin to DAOs</h3>
        <p class="article-excerpt">Compound's capture, Beanstalk's $182M flash loan exploit, the Steem/Hive war, Tezos's success, MakerDAO's oligarchy, and the $46M Curve bribery market. The real stories behind DAO governance.</p>
        <div class="article-meta">
          <span><i class="fa-solid fa-clock"></i> 20 min read</span>
          <span><i class="fa-solid fa-calendar"></i> March 2026</span>
          <span><i class="fa-solid fa-signal"></i> Essential</span>
        </div>
      </div>
    </a>

    <a href="/academy/coinshuffle-secret-ballots" class="article-card fade-up fade-up-d2">
      <div class="article-thumb blockchain" style="background:url('/assets/academy/congress-chamber-2.jpg') center/cover; font-size:0;">
        &nbsp;
      </div>
      <div class="article-body">
        <span class="article-tag blockchain">Blockchain &amp; Cryptography</span>
        <h3 class="article-title">CoinShuffle: Secret Ballots on a Public Blockchain</h3>
        <p class="article-excerpt">The cryptographic mixing protocol explained step by step. How the shuffle works, why ballot secrecy matters, comparison with MACI and zk-SNARKs, and the Martian Republic's implementation.</p>
        <div class="article-meta">
          <span><i class="fa-solid fa-clock"></i> 15 min read</span>
          <span><i class="fa-solid fa-calendar"></i> March 2026</span>
          <span><i class="fa-solid fa-signal"></i> Technical</span>
        </div>
      </div>
    </a>

    <a href="/academy/dynamic-quorum" class="article-card fade-up fade-up-d3">
      <div class="article-thumb governance" style="background:url('/assets/academy/congress-chamber-2.jpg') center/cover; font-size:0;">
        &nbsp;
      </div>
      <div class="article-body">
        <span class="article-tag governance">Governance</span>
        <h3 class="article-title">Dynamic Quorum: Why Fixed Thresholds Kill DAOs</h3>
        <p class="article-excerpt">The Tezos exponential moving average formula, Compound's migration from 10% to 4%, Yam Finance's mathematical death, and the math behind the Martian Republic's active-citizen quorum.</p>
        <div class="article-meta">
          <span><i class="fa-solid fa-clock"></i> 12 min read</span>
          <span><i class="fa-solid fa-calendar"></i> March 2026</span>
          <span><i class="fa-solid fa-signal"></i> Essential</span>
        </div>
      </div>
    </a>

    <!-- UPCOMING ARTICLES -->
    <div class="upcoming-grid">
      <div class="upcoming-card">
        <div class="upcoming-title">Git as Constitution</div>
        <div class="upcoming-desc">The radical idea that code IS law, literally. How Constitutional proposals work as pull requests, and why machine-verifiable governance rules eliminate ambiguity.</div>
      </div>
      <div class="upcoming-card">
        <div class="upcoming-title">The Civic Alarm: Designing for Engagement</div>
        <div class="upcoming-desc">Tiered notifications, interest tagging, the UX of participation. How the Republic fights voter fatigue through design, not mandates.</div>
      </div>

      {{-- BLOCKCHAIN & WALLET SERIES --}}
      <div class="upcoming-card">
        <div class="upcoming-title">HD Wallets: Your Key to Mars</div>
        <div class="upcoming-desc">How hierarchical deterministic wallets work, why they're essential, and the architecture behind civic vs treasury addresses.</div>
      </div>
      <div class="upcoming-card">
        <div class="upcoming-title">Marscoin: A Currency for Another World</div>
        <div class="upcoming-desc">The history, technology, and economics of Marscoin -- from its 2014 launch to its role as the backbone of Martian governance.</div>
      </div>
      <div class="upcoming-card">
        <div class="upcoming-title">Blockchain-Attested Data Streams</div>
        <div class="upcoming-desc">APIs that prove reality. How sensor data, inspections, and certifications can be cryptographically anchored for tamper-proof compliance.</div>
      </div>

      {{-- CITIZENSHIP & MARS SERIES --}}
      <div class="upcoming-card">
        <div class="upcoming-title">The Pioneer's Journey: From Earth to Citizen</div>
        <div class="upcoming-desc">A complete guide to the citizenship process -- from account creation through endorsement to full civic participation.</div>
      </div>
      <div class="upcoming-card">
        <div class="upcoming-title">Living on Mars: Challenges and Solutions</div>
        <div class="upcoming-desc">Radiation, dust storms, thin atmosphere, and extreme cold. The real science of Mars habitation and what the Republic is building toward.</div>
      </div>
      <div class="upcoming-card">
        <div class="upcoming-title">Land Registry: Staking Claims on Mars</div>
        <div class="upcoming-desc">How the Martian Republic's planetary registry uses blockchain to track land claims, habitat assignments, and resource rights.</div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="mr-footer">
  <div class="container">
    <div class="mr-footer-inner">
      <div class="mr-footer-copy">
        &copy; 2014&ndash;{{ date('Y') }} The Marscoin Foundation, Inc.
      </div>
      <div class="mr-footer-links">
        <a href="/">Home</a>
        <a href="/academy">Academy</a>
        <a href="/congress/all">Congress</a>
        <a href="/privacy">Privacy</a>
        <a href="/tos">Terms</a>
      </div>
    </div>
  </div>
</footer>

</body>
</html>
