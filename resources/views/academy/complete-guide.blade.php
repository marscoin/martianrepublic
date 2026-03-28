<!DOCTYPE html>
<html lang="en">
<head>
<title>The Complete Guide to the Martian Republic — Interactive Course</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="A visual, step-by-step walkthrough of humanity's first blockchain democracy. From Marscoin to CoinShuffle, from wallets to voting, understand the entire Martian Republic in 16 slides.">
<meta name="keywords" content="Martian Republic, blockchain democracy, Marscoin, Mars governance, CoinShuffle, IPFS, direct democracy, Mars settlement">
<meta property="og:title" content="The Complete Guide to the Martian Republic — Interactive Course">
<meta property="og:description" content="A visual, step-by-step walkthrough of humanity's first blockchain democracy. From Marscoin to CoinShuffle, from wallets to voting, understand the entire Martian Republic in 16 slides.">
<meta property="og:image" content="https://martianrepublic.org/assets/academy/earth-mars-distance.jpg">
<meta property="og:type" content="website">
<meta property="og:url" content="https://martianrepublic.org/academy/complete-guide">
<meta property="og:site_name" content="The Martian Republic">
<meta property="og:locale" content="en_US">
<meta name="robots" content="index, follow">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="The Complete Guide to the Martian Republic — Interactive Course">
<meta name="twitter:description" content="A visual, step-by-step walkthrough of humanity's first blockchain democracy. From Marscoin to CoinShuffle, from wallets to voting, understand the entire Martian Republic in 16 slides.">
<meta name="twitter:image" content="https://martianrepublic.org/assets/academy/earth-mars-distance.jpg">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://martianrepublic.org/academy/complete-guide">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "The Complete Guide to the Martian Republic",
  "description": "A visual, step-by-step walkthrough of humanity's first blockchain democracy. From Marscoin to CoinShuffle, from wallets to voting, understand the entire Martian Republic in 16 slides.",
  "image": "https://martianrepublic.org/assets/academy/earth-mars-distance.jpg",
  "datePublished": "2026-03-27",
  "provider": {
    "@type": "Organization",
    "name": "The Martian Republic",
    "url": "https://martianrepublic.org",
    "logo": { "@type": "ImageObject", "url": "https://martianrepublic.org/assets/favicon.ico" }
  },
  "hasCourseInstance": {
    "@type": "CourseInstance",
    "courseMode": "online",
    "courseWorkload": "PT10M"
  },
  "mainEntityOfPage": "https://martianrepublic.org/academy/complete-guide"
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
  --total-slides: 16;
}

*, *::before, *::after { box-sizing: border-box; }

html {
  scroll-behavior: auto;
  overflow: hidden;
  height: 100%;
}

body {
  margin: 0; padding: 0;
  background: var(--mr-void);
  color: var(--mr-text);
  font-family: var(--mr-font-body);
  font-size: 16px;
  line-height: 1.7;
  -webkit-font-smoothing: antialiased;
  overflow: hidden;
  height: 100%;
}

a { color: var(--mr-cyan); transition: all 0.3s ease; text-decoration: none; }
a:hover { color: var(--mr-amber); text-decoration: none; }

/* ---- NAV ---- */
.mr-nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  background: rgba(6,6,12,0.6);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--mr-border);
  padding: 16px 0;
  transition: transform 0.35s ease;
}
.mr-nav.nav-hidden { transform: translateY(-100%); }
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

/* ---- SLIDE COUNTER ---- */
.slide-counter {
  position: fixed;
  top: 80px;
  right: 24px;
  z-index: 900;
  font-family: var(--mr-font-mono);
  font-size: 13px;
  color: var(--mr-text-faint);
  letter-spacing: 1px;
  background: rgba(6,6,12,0.6);
  backdrop-filter: blur(12px);
  padding: 6px 14px;
  border-radius: 6px;
  border: 1px solid var(--mr-border);
}
.slide-counter .current-num {
  color: #fff;
  font-weight: 600;
}

/* ---- DOT NAVIGATION ---- */
.dot-nav {
  position: fixed;
  right: 24px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 900;
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
}
.dot-nav-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: rgba(255,255,255,0.12);
  border: 1.5px solid rgba(255,255,255,0.2);
  cursor: pointer;
  transition: all 0.35s ease;
  position: relative;
}
.dot-nav-dot:hover {
  background: rgba(255,255,255,0.3);
  border-color: rgba(255,255,255,0.5);
  transform: scale(1.3);
}
.dot-nav-dot.active {
  background: var(--mr-cyan);
  border-color: var(--mr-cyan);
  box-shadow: 0 0 12px rgba(0,228,255,0.5);
  transform: scale(1.2);
}
.dot-nav-dot::before {
  content: attr(data-label);
  position: absolute;
  right: 22px;
  top: 50%;
  transform: translateY(-50%);
  font-family: var(--mr-font-mono);
  font-size: 10px;
  color: var(--mr-text-dim);
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.25s ease;
  background: rgba(6,6,12,0.85);
  padding: 4px 10px;
  border-radius: 4px;
  border: 1px solid var(--mr-border);
}
.dot-nav-dot:hover::before {
  opacity: 1;
}

/* ---- SLIDESHOW CONTAINER ---- */
.slideshow {
  scroll-snap-type: y mandatory;
  overflow-y: scroll;
  height: 100vh;
  width: 100%;
  scroll-behavior: smooth;
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.slideshow::-webkit-scrollbar { display: none; }

/* ---- SLIDE BASE ---- */
.slide {
  scroll-snap-align: start;
  height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}
.slide-inner {
  max-width: 1000px;
  width: 100%;
  padding: 80px 48px;
  position: relative;
  z-index: 2;
}

/* ---- SLIDE NUMBER ---- */
.slide-number {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--mr-text-faint);
  margin-bottom: 24px;
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.7s ease 0.1s;
}

/* ---- SLIDE HEADLINE ---- */
.slide h1, .slide h2 {
  font-family: var(--mr-font-display);
  font-weight: 700;
  line-height: 1.1;
  letter-spacing: -1px;
  margin: 0 0 24px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.2s;
}
.slide h1 {
  font-size: clamp(36px, 6vw, 72px);
  color: #fff;
}
.slide h2 {
  font-size: clamp(28px, 5vw, 64px);
  color: #fff;
}

/* ---- SLIDE BODY TEXT ---- */
.slide-body {
  font-family: var(--mr-font-body);
  font-size: clamp(16px, 2vw, 20px);
  line-height: 1.7;
  color: rgba(224, 223, 230, 0.9);
  max-width: 680px;
  margin-bottom: 32px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.35s;
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
}
.slide-body strong { color: #fff; }

/* ---- SLIDE SUBTITLE ---- */
.slide-subtitle {
  font-family: var(--mr-font-display);
  font-size: clamp(16px, 2.5vw, 24px);
  font-weight: 400;
  color: var(--mr-text-dim);
  margin-bottom: 48px;
  letter-spacing: 0.5px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.3s;
}

/* ---- ANIMATIONS: VISIBLE STATE ---- */
.slide.visible .slide-number,
.slide.visible h1,
.slide.visible h2,
.slide.visible .slide-body,
.slide.visible .slide-subtitle,
.slide.visible .slide-visual,
.slide.visible .slide-stats,
.slide.visible .slide-columns,
.slide.visible .slide-tiers,
.slide.visible .slide-ctas {
  opacity: 1;
  transform: translateY(0);
}

/* ---- STAT BOXES ---- */
.slide-stats {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  margin-top: 8px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.5s;
}
.stat-box {
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--mr-border-bright);
  border-radius: 10px;
  padding: 20px 28px;
  text-align: center;
  min-width: 140px;
  flex: 1;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
.stat-box:hover {
  border-color: rgba(255,255,255,0.2);
}
.stat-box .stat-value {
  font-family: var(--mr-font-display);
  font-size: clamp(20px, 3vw, 32px);
  font-weight: 700;
  color: #fff;
  display: block;
  margin-bottom: 4px;
}
.stat-box .stat-label {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--mr-text-faint);
}

/* Accent colors for stat boxes */
.accent-cyan .stat-box { border-color: rgba(0,228,255,0.2); }
.accent-cyan .stat-box:hover { border-color: rgba(0,228,255,0.4); box-shadow: 0 0 20px rgba(0,228,255,0.08); }
.accent-cyan .stat-box .stat-value { color: var(--mr-cyan); }

.accent-mars .stat-box { border-color: rgba(200,65,37,0.2); }
.accent-mars .stat-box:hover { border-color: rgba(200,65,37,0.4); box-shadow: 0 0 20px rgba(200,65,37,0.08); }
.accent-mars .stat-box .stat-value { color: var(--mr-mars-glow); }

.accent-green .stat-box { border-color: rgba(52,211,153,0.2); }
.accent-green .stat-box:hover { border-color: rgba(52,211,153,0.4); box-shadow: 0 0 20px rgba(52,211,153,0.08); }
.accent-green .stat-box .stat-value { color: var(--mr-green); }

.accent-amber .stat-box { border-color: rgba(212,164,74,0.2); }
.accent-amber .stat-box:hover { border-color: rgba(212,164,74,0.4); box-shadow: 0 0 20px rgba(212,164,74,0.08); }
.accent-amber .stat-box .stat-value { color: var(--mr-amber); }

/* ---- SLIDE VISUAL (icon, diagram placeholder) ---- */
.slide-visual {
  margin-top: 16px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.5s;
}

/* ---- EARTH-MARS SIGNAL VISUAL ---- */
.signal-visual {
  display: flex;
  align-items: center;
  gap: 24px;
  margin-top: 32px;
  flex-wrap: wrap;
}
.signal-planet {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  flex-shrink: 0;
}
.signal-planet.earth {
  background: radial-gradient(circle at 40% 40%, #4a90d9, #1a4a8a);
  box-shadow: 0 0 20px rgba(74,144,217,0.3);
}
.signal-planet.mars {
  background: radial-gradient(circle at 40% 40%, #e05535, #8b2a15);
  box-shadow: 0 0 20px rgba(224,85,53,0.3);
}
.signal-line {
  flex: 1;
  min-width: 100px;
  height: 2px;
  background: repeating-linear-gradient(
    90deg,
    var(--mr-text-faint) 0px,
    var(--mr-text-faint) 8px,
    transparent 8px,
    transparent 16px
  );
  position: relative;
  animation: signalPulse 2s ease-in-out infinite;
}
.signal-line::after {
  content: '4 — 24 min';
  position: absolute;
  top: -28px;
  left: 50%;
  transform: translateX(-50%);
  font-family: var(--mr-font-mono);
  font-size: 18px;
  font-weight: 600;
  color: var(--mr-amber);
  white-space: nowrap;
  letter-spacing: 1px;
}
@keyframes signalPulse {
  0%, 100% { opacity: 0.4; }
  50% { opacity: 1; }
}

/* ---- TWO COLUMNS (Slide 7) ---- */
.slide-columns {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
  margin-top: 16px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.45s;
}
.slide-col {
  padding: 32px;
  border-radius: 12px;
  border: 1px solid var(--mr-border);
}
.slide-col.col-dim {
  background: rgba(255,255,255,0.02);
  border-color: rgba(255,255,255,0.06);
}
.slide-col.col-bright {
  background: rgba(0,228,255,0.04);
  border-color: rgba(0,228,255,0.15);
  box-shadow: 0 0 30px rgba(0,228,255,0.05);
}
.slide-col-label {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-bottom: 16px;
  display: block;
}
.col-dim .slide-col-label { color: var(--mr-text-faint); }
.col-bright .slide-col-label { color: var(--mr-cyan); }
.slide-col p {
  font-family: var(--mr-font-body);
  font-size: 16px;
  line-height: 1.7;
  margin: 0;
}
.col-dim p { color: var(--mr-text-faint); }
.col-bright p { color: var(--mr-text); }

/* ---- TIER STACK (Slide 11) ---- */
.slide-tiers {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-top: 24px;
  max-width: 480px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.5s;
}
.tier-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 14px 20px;
  border-radius: 8px;
  border: 1px solid var(--mr-border);
  background: rgba(255,255,255,0.02);
  transition: all 0.3s ease;
}
.tier-item:hover {
  border-color: var(--mr-border-bright);
  background: rgba(255,255,255,0.04);
}
.tier-item .tier-num {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  color: var(--mr-text-faint);
  width: 20px;
}
.tier-item .tier-name {
  font-family: var(--mr-font-display);
  font-size: 16px;
  font-weight: 600;
  color: #fff;
}
.tier-item .tier-arrow {
  margin-left: auto;
  color: var(--mr-text-faint);
  font-size: 12px;
}
.tier-item:nth-child(1) { border-left: 3px solid var(--mr-green); }
.tier-item:nth-child(2) { border-left: 3px solid var(--mr-cyan); }
.tier-item:nth-child(3) { border-left: 3px solid var(--mr-amber); }
.tier-item:nth-child(4) { border-left: 3px solid var(--mr-mars); }

/* ---- ENDORSEMENT NODES (Slide 9) ---- */
.node-visual {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
  margin-top: 32px;
  position: relative;
  height: 100px;
}
.node-center {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: var(--mr-green);
  box-shadow: 0 0 30px rgba(52,211,153,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  z-index: 2;
  position: relative;
}
.node-orbit {
  position: absolute;
  width: 200px;
  height: 200px;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
.node-satellite {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: rgba(52,211,153,0.3);
  border: 2px solid var(--mr-green);
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
}
.node-satellite:nth-child(1) { top: 0; left: 50%; transform: translateX(-50%); }
.node-satellite:nth-child(2) { top: 25%; right: 0; }
.node-satellite:nth-child(3) { bottom: 25%; right: 0; }
.node-satellite:nth-child(4) { bottom: 0; left: 50%; transform: translateX(-50%); }
.node-satellite:nth-child(5) { bottom: 25%; left: 0; }
.node-line {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 80px;
  height: 1px;
  background: rgba(52,211,153,0.25);
  transform-origin: left center;
}
.node-line:nth-child(6) { transform: rotate(270deg); }
.node-line:nth-child(7) { transform: rotate(324deg); }
.node-line:nth-child(8) { transform: rotate(18deg); }
.node-line:nth-child(9) { transform: rotate(90deg); }
.node-line:nth-child(10) { transform: rotate(198deg); }

/* ---- CTA BUTTONS ---- */
.slide-ctas {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  margin-top: 32px;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease 0.5s;
}
.cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 16px 36px;
  border-radius: 8px;
  font-family: var(--mr-font-display);
  font-weight: 600;
  font-size: 16px;
  letter-spacing: 0.5px;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
}
.cta-btn-primary {
  background: var(--mr-mars);
  color: #fff;
  border: 2px solid var(--mr-mars);
}
.cta-btn-primary:hover {
  background: var(--mr-mars-glow);
  border-color: var(--mr-mars-glow);
  color: #fff;
  box-shadow: 0 0 30px rgba(200,65,37,0.4);
  transform: translateY(-2px);
}
.cta-btn-outline {
  background: transparent;
  color: var(--mr-cyan);
  border: 2px solid var(--mr-cyan);
}
.cta-btn-outline:hover {
  background: rgba(0,228,255,0.1);
  color: var(--mr-cyan);
  box-shadow: 0 0 30px rgba(0,228,255,0.2);
  transform: translateY(-2px);
}

/* ---- SCROLL INDICATOR ---- */
.scroll-indicator {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
  z-index: 5;
  transition: opacity 0.5s ease;
}
.scroll-indicator.hidden { opacity: 0; pointer-events: none; }
.scroll-indicator span {
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mr-text-faint);
  display: block;
  margin-bottom: 12px;
}
.scroll-arrow {
  display: block;
  margin: 0 auto;
  color: var(--mr-text-faint);
  font-size: 18px;
  animation: bounceDown 2s ease-in-out infinite;
}
@keyframes bounceDown {
  0%, 100% { transform: translateY(0); opacity: 0.5; }
  50% { transform: translateY(10px); opacity: 1; }
}

/* ---- BACKGROUND OVERLAYS ---- */
.slide-bg-image {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  z-index: 0;
}
.slide-bg-image::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    180deg,
    rgba(6,6,12,0.65) 0%,
    rgba(6,6,12,0.75) 40%,
    rgba(6,6,12,0.88) 100%
  );
}

/* Gradient backgrounds */
.slide-bg-gradient {
  position: absolute;
  inset: 0;
  z-index: 0;
}

/* Animated grid pattern */
.slide-bg-grid {
  position: absolute;
  inset: 0;
  z-index: 0;
  background:
    linear-gradient(rgba(0,228,255,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0,228,255,0.03) 1px, transparent 1px);
  background-size: 60px 60px;
  animation: gridShift 20s linear infinite;
}
@keyframes gridShift {
  0% { transform: translate(0, 0); }
  100% { transform: translate(60px, 60px); }
}

/* Glowing orbs */
.glow-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  z-index: 0;
  pointer-events: none;
}

/* ---- ACCENT LINE ON HEADLINES ---- */
.accent-line {
  width: 48px;
  height: 3px;
  border-radius: 2px;
  margin-bottom: 24px;
  opacity: 0;
  transform: scaleX(0);
  transform-origin: left;
  transition: all 0.6s ease 0.15s;
}
.slide.visible .accent-line {
  opacity: 1;
  transform: scaleX(1);
}
.accent-line.cyan { background: var(--mr-cyan); box-shadow: 0 0 12px rgba(0,228,255,0.4); }
.accent-line.mars { background: var(--mr-mars); box-shadow: 0 0 12px rgba(200,65,37,0.4); }
.accent-line.green { background: var(--mr-green); box-shadow: 0 0 12px rgba(52,211,153,0.4); }
.accent-line.amber { background: var(--mr-amber); box-shadow: 0 0 12px rgba(212,164,74,0.4); }

/* ---- FOOTER (only on last slide) ---- */
.slide-footer {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 20px 0;
  border-top: 1px solid var(--mr-border);
  z-index: 5;
}
.slide-footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.slide-footer-copy {
  font-size: 13px;
  color: var(--mr-text-faint);
}
.slide-footer-links {
  display: flex;
  gap: 24px;
}
.slide-footer-links a {
  color: var(--mr-text-faint);
  font-size: 13px;
}
.slide-footer-links a:hover { color: #fff; }

/* ---- MOBILE RESPONSIVE ---- */
@media (max-width: 768px) {
  .mr-nav-links a:not(.mr-nav-cta) { display: none; }
  .mr-nav-links { gap: 12px; }

  .slide-inner {
    padding: 70px 24px 60px;
  }

  .slide-counter {
    top: 74px;
    right: 14px;
    font-size: 11px;
    padding: 4px 10px;
  }

  .dot-nav {
    right: 10px;
    gap: 7px;
  }
  .dot-nav-dot {
    width: 7px;
    height: 7px;
  }
  .dot-nav-dot::before { display: none; }

  .slide-columns {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  .slide-col {
    padding: 20px;
  }

  .slide-stats {
    gap: 10px;
  }
  .stat-box {
    padding: 14px 16px;
    min-width: 100px;
  }
  .stat-box .stat-value {
    font-size: 18px;
  }

  .signal-visual {
    gap: 12px;
  }
  .signal-planet {
    width: 40px;
    height: 40px;
    font-size: 18px;
  }

  .node-visual {
    transform: scale(0.75);
    margin-top: 16px;
  }

  .cta-btn {
    padding: 14px 24px;
    font-size: 14px;
    width: 100%;
    justify-content: center;
  }

  .slide-tiers {
    max-width: 100%;
  }

  .scroll-indicator {
    bottom: 24px;
  }
}

@media (max-width: 480px) {
  .slide-stats {
    flex-direction: column;
  }
  .stat-box {
    min-width: auto;
  }
  .signal-line::after {
    font-size: 14px;
    top: -24px;
  }
}

/* ---- PROGRESS BAR ---- */
.slide-progress {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: rgba(255,255,255,0.04);
  z-index: 999;
}
.slide-progress-bar {
  height: 100%;
  background: linear-gradient(90deg, var(--mr-mars), var(--mr-cyan));
  width: 0%;
  transition: width 0.4s ease;
}
</style>
</head>

<body>

<!-- NAV -->
<nav class="mr-nav" id="mainNav">
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

<!-- SLIDE COUNTER -->
<div class="slide-counter" id="slideCounter">
  <span class="current-num">1</span> / 16
</div>

<!-- DOT NAVIGATION -->
<div class="dot-nav" id="dotNav">
  <div class="dot-nav-dot active" data-slide="1" data-label="The Martian Republic"></div>
  <div class="dot-nav-dot" data-slide="2" data-label="The Problem"></div>
  <div class="dot-nav-dot" data-slide="3" data-label="The Vision"></div>
  <div class="dot-nav-dot" data-slide="4" data-label="Marscoin"></div>
  <div class="dot-nav-dot" data-slide="5" data-label="IPFS"></div>
  <div class="dot-nav-dot" data-slide="6" data-label="Your Wallet"></div>
  <div class="dot-nav-dot" data-slide="7" data-label="Earth vs Mars"></div>
  <div class="dot-nav-dot" data-slide="8" data-label="Citizenship"></div>
  <div class="dot-nav-dot" data-slide="9" data-label="Endorsement"></div>
  <div class="dot-nav-dot" data-slide="10" data-label="The Forum"></div>
  <div class="dot-nav-dot" data-slide="11" data-label="The Congress"></div>
  <div class="dot-nav-dot" data-slide="12" data-label="Secret Ballots"></div>
  <div class="dot-nav-dot" data-slide="13" data-label="OP_RETURN"></div>
  <div class="dot-nav-dot" data-slide="14" data-label="Code as Law"></div>
  <div class="dot-nav-dot" data-slide="15" data-label="Arrival Hall"></div>
  <div class="dot-nav-dot" data-slide="16" data-label="Join"></div>
</div>

<!-- PROGRESS BAR -->
<div class="slide-progress">
  <div class="slide-progress-bar" id="progressBar"></div>
</div>

<!-- ==================== SLIDESHOW ==================== -->
<main class="slideshow" id="slideshow">

  <!-- SLIDE 1: Title -->
  <section class="slide" id="slide-1">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/why-mars.jpg');"></div>
    <div class="glow-orb" style="width:400px;height:400px;background:rgba(200,65,37,0.15);top:-100px;right:-100px;"></div>
    <div class="slide-inner" style="text-align:center; max-width:900px;">
      <div class="slide-number" style="text-align:center;">Learning Path 05</div>
      <h1 style="font-size:clamp(40px,7vw,84px); margin-bottom:16px;">The Martian Republic</h1>
      <div class="slide-subtitle" style="max-width:600px; margin:0 auto 0;">A Complete Guide to Humanity&rsquo;s First Blockchain Democracy</div>
    </div>
    <div class="scroll-indicator" id="scrollIndicator">
      <span>Scroll to begin</span>
      <i class="fa-solid fa-chevron-down scroll-arrow"></i>
    </div>
  </section>

  <!-- SLIDE 2: The Problem -->
  <section class="slide" id="slide-2">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/mars-assembly.jpg');"></div>
    <div class="slide-inner">
      <div class="slide-number">02 &mdash; The Problem</div>
      <div class="accent-line mars"></div>
      <h2>Earth Governance Fails at Mars Distance</h2>
      <p class="slide-body">Light takes 4 to 24 minutes to travel between Earth and Mars. You can&rsquo;t call a senator. You can&rsquo;t petition a court. You can&rsquo;t confirm a Bitcoin transaction. The same physics that makes Earth governance impossible at Mars distance makes Earth&rsquo;s blockchain unusable too. Mars must govern itself &mdash; and it needs its own blockchain. That&rsquo;s Marscoin.</p>
      <div class="slide-visual">
        <div class="signal-visual">
          <div class="signal-planet earth"><i class="fa-solid fa-earth-americas"></i></div>
          <div class="signal-line"></div>
          <div class="signal-planet mars"><i class="fa-solid fa-planet-ringed" style="display:none;"></i><span style="font-size:16px;font-weight:700;">&#9679;</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 3: The Vision -->
  <section class="slide" id="slide-3">
    <div class="slide-bg-grid"></div>
    <div class="slide-bg-gradient" style="background: radial-gradient(ellipse at 50% 50%, rgba(0,228,255,0.05) 0%, transparent 60%), var(--mr-void);"></div>
    <div class="slide-inner">
      <div class="slide-number">03 &mdash; The Vision</div>
      <div class="accent-line cyan"></div>
      <h2>Direct Democracy, Cryptographically Secured</h2>
      <p class="slide-body">No representatives. No lobbyists. No electoral college. Every citizen votes directly. Every vote is secret. Every result is publicly auditable. The math guarantees it.</p>
      <div class="slide-stats accent-cyan">
        <div class="stat-box">
          <span class="stat-value">1 Citizen = 1 Vote</span>
          <span class="stat-label">Equal Voice</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-eye-slash"></i></span>
          <span class="stat-label">Secret Ballots</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-magnifying-glass"></i></span>
          <span class="stat-label">Public Audits</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 4: Marscoin -->
  <section class="slide" id="slide-4">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/marscoin-story.jpg');"></div>
    <div class="glow-orb" style="width:300px;height:300px;background:rgba(0,228,255,0.1);bottom:-50px;left:-50px;"></div>
    <div class="slide-inner">
      <div class="slide-number">04 &mdash; Layer 1</div>
      <div class="accent-line cyan"></div>
      <h2>The Marscoin Blockchain</h2>
      <p class="slide-body">Launched January 1, 2014. Twelve years of uninterrupted operations. Proof of Work, 123-second blocks, ~40 million coin supply. The immutable ledger that records every transaction, vote, and civic action. We intend to take it to Mars.</p>
      <div class="slide-stats accent-cyan">
        <div class="stat-box">
          <span class="stat-value">Est. 2014</span>
          <span class="stat-label">Launch Year</span>
        </div>
        <div class="stat-box">
          <span class="stat-value">123s</span>
          <span class="stat-label">Block Time</span>
        </div>
        <div class="stat-box">
          <span class="stat-value">12 Years</span>
          <span class="stat-label">Live &amp; Running</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 5: IPFS -->
  <section class="slide" id="slide-5">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/ipfs.jpg');"></div>
    <div class="slide-inner">
      <div class="slide-number">05 &mdash; Layer 2</div>
      <div class="accent-line cyan"></div>
      <h2>IPFS &mdash; Interplanetary File System</h2>
      <p class="slide-body">Every citizen application, every proposal, every document &mdash; content-addressed, tamper-evident, censorship-resistant. A dedicated, decentralized file storage system that runs by default on Mars &mdash; so all citizens can share data from the get-go. No central server required.</p>
      <div class="slide-stats accent-cyan">
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-network-wired"></i></span>
          <span class="stat-label">Distributed</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-fingerprint"></i></span>
          <span class="stat-label">Content-Addressed</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-shield-halved"></i></span>
          <span class="stat-label">Tamper-Evident</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 6: The Wallet -->
  <section class="slide" id="slide-6">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/hd-wallets.jpg');"></div>
    <div class="glow-orb" style="width:350px;height:350px;background:rgba(0,228,255,0.08);top:20%;right:-80px;"></div>
    <div class="slide-inner">
      <div class="slide-number">06 &mdash; Layer 3</div>
      <div class="accent-line cyan"></div>
      <h2>Your Wallet</h2>
      <p class="slide-body">A non-custodial HD wallet grown from a single seed phrase. 12 words that generate your money, your identity, your voting power. No bank. No intermediary. Mathematics is your vault.</p>
      <div class="slide-stats accent-cyan">
        <div class="stat-box">
          <span class="stat-value">12 Words</span>
          <span class="stat-label">Seed Phrase</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-key"></i></span>
          <span class="stat-label">Non-Custodial</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-infinity"></i></span>
          <span class="stat-label">Unlimited Addresses</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 7: Earth vs Mars -->
  <section class="slide" id="slide-7">
    <div class="slide-bg-gradient" style="background: linear-gradient(135deg, rgba(12,12,22,1) 0%, rgba(6,6,12,1) 50%, rgba(0,228,255,0.03) 100%);"></div>
    <div class="glow-orb" style="width:400px;height:400px;background:rgba(0,228,255,0.06);bottom:-100px;right:-100px;"></div>
    <div class="slide-inner">
      <div class="slide-number">07 &mdash; A New Paradigm</div>
      <div class="accent-line cyan"></div>
      <h2>A Fundamental Shift</h2>
      <div class="slide-columns">
        <div class="slide-col col-dim">
          <span class="slide-col-label">On Earth</span>
          <p>Wallet + ID = Two things. Issued by the state. Tracked. Surveilled. Revocable.</p>
        </div>
        <div class="slide-col col-bright">
          <span class="slide-col-label">On Mars</span>
          <p>Wallet = ID = One thing. Generated by you. Self-sovereign. Private. Permanent.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 8: Becoming a Citizen -->
  <section class="slide" id="slide-8">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/pioneers-journey.jpg');"></div>
    <div class="glow-orb" style="width:300px;height:300px;background:rgba(52,211,153,0.1);top:10%;left:-60px;"></div>
    <div class="slide-inner">
      <div class="slide-number">08 &mdash; Citizenship</div>
      <div class="accent-line green"></div>
      <h2>Step 1: Prove You&rsquo;re Real</h2>
      <p class="slide-body">Upload your name, photo, and a liveness video showing your civic wallet address. Your identity is pinned to IPFS and anchored on the blockchain. No government. No bureaucracy. Just you, proving you exist.</p>
      <div class="slide-stats accent-green">
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-camera"></i></span>
          <span class="stat-label">Photo + Video</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-link"></i></span>
          <span class="stat-label">Pinned to IPFS</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-cube"></i></span>
          <span class="stat-label">Anchored On-Chain</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 9: Endorsement -->
  <section class="slide" id="slide-9">
    <div class="slide-bg-gradient" style="background: radial-gradient(ellipse at 60% 50%, rgba(52,211,153,0.08) 0%, transparent 55%), var(--mr-void);"></div>
    <div class="slide-inner">
      <div class="slide-number">09 &mdash; Trust Network</div>
      <div class="accent-line green"></div>
      <h2>Step 2: Get Endorsed</h2>
      <p class="slide-body">Existing citizens vouch for you &mdash; on-chain, permanently, with their reputation at stake. It&rsquo;s not a CAPTCHA. It&rsquo;s not an algorithm. It&rsquo;s real humans, exercising the oldest form of trust: personal testimony.</p>
      <div class="slide-visual">
        <div class="node-visual">
          <div class="node-center"><i class="fa-solid fa-user" style="color:#fff;font-size:18px;"></i></div>
          <div class="node-orbit">
            <div class="node-satellite"><i class="fa-solid fa-check" style="color:var(--mr-green);font-size:8px;"></i></div>
            <div class="node-satellite"><i class="fa-solid fa-check" style="color:var(--mr-green);font-size:8px;"></i></div>
            <div class="node-satellite"><i class="fa-solid fa-check" style="color:var(--mr-green);font-size:8px;"></i></div>
            <div class="node-satellite"><i class="fa-solid fa-check" style="color:var(--mr-green);font-size:8px;"></i></div>
            <div class="node-satellite"><i class="fa-solid fa-check" style="color:var(--mr-green);font-size:8px;"></i></div>
            <div class="node-line"></div>
            <div class="node-line"></div>
            <div class="node-line"></div>
            <div class="node-line"></div>
            <div class="node-line"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 10: The Forum -->
  <section class="slide" id="slide-10">
    <div class="slide-bg-gradient" style="background: radial-gradient(ellipse at 30% 60%, rgba(200,65,37,0.08) 0%, transparent 55%), var(--mr-void);"></div>
    <div class="slide-bg-grid"></div>
    <div class="slide-inner">
      <div class="slide-number">10 &mdash; Discussion</div>
      <div class="accent-line mars"></div>
      <h2>The Martian Forum</h2>
      <p class="slide-body">A censorship-resistant discussion space. Posts are Merkle-tree notarized to the blockchain. No one can silently delete or modify the record. Free speech, mathematically guaranteed.</p>
      <div class="slide-stats accent-mars">
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-comments"></i></span>
          <span class="stat-label">Open Discussion</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-tree"></i></span>
          <span class="stat-label">Merkle Notarized</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-lock-open"></i></span>
          <span class="stat-label">Censorship-Resistant</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 11: The Congress -->
  <section class="slide" id="slide-11">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/earth-mars-distance.jpg');"></div>
    <div class="slide-inner">
      <div class="slide-number">11 &mdash; Decision-Making</div>
      <div class="accent-line mars"></div>
      <h2>The Martian Congress</h2>
      <p class="slide-body">Four tiers of proposals &mdash; from lightweight signal polls to constitutional code changes. Requirements scale with consequences. The bigger the decision, the higher the bar.</p>
      <div class="slide-tiers">
        <div class="tier-item">
          <span class="tier-num">I</span>
          <span class="tier-name">Signal</span>
          <span class="tier-arrow"><i class="fa-solid fa-arrow-right"></i></span>
        </div>
        <div class="tier-item">
          <span class="tier-num">II</span>
          <span class="tier-name">Operational</span>
          <span class="tier-arrow"><i class="fa-solid fa-arrow-right"></i></span>
        </div>
        <div class="tier-item">
          <span class="tier-num">III</span>
          <span class="tier-name">Legislative</span>
          <span class="tier-arrow"><i class="fa-solid fa-arrow-right"></i></span>
        </div>
        <div class="tier-item">
          <span class="tier-num">IV</span>
          <span class="tier-name">Constitutional</span>
          <span class="tier-arrow"><i class="fa-solid fa-arrow-right"></i></span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 12: Secret Ballots -->
  <section class="slide" id="slide-12">
    <div class="slide-bg-gradient" style="background: radial-gradient(ellipse at 50% 30%, rgba(200,65,37,0.1) 0%, transparent 55%), var(--mr-void);"></div>
    <div class="glow-orb" style="width:350px;height:350px;background:rgba(200,65,37,0.06);top:-80px;left:30%;"></div>
    <div class="slide-inner">
      <div class="slide-number">12 &mdash; Voting</div>
      <div class="accent-line mars"></div>
      <h2>Secret and Verifiable</h2>
      <p class="slide-body">CoinShuffle breaks the link between your identity and your ballot. You are a verified voter &mdash; but no one can trace your vote. The blockchain proves the result is correct without revealing who voted how &mdash; and because every voter is a verified citizen in the registry, no one can stuff the ballot box with fake identities.</p>
      <div class="slide-stats accent-mars">
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-shuffle"></i></span>
          <span class="stat-label">CoinShuffle</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-user-secret"></i></span>
          <span class="stat-label">Anonymous Ballots</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-square-check"></i></span>
          <span class="stat-label">Verifiable Results</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 13: OP_RETURN -->
  <section class="slide" id="slide-13">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/op-return.jpg');"></div>
    <div class="slide-inner">
      <div class="slide-number">13 &mdash; Permanence</div>
      <div class="accent-line cyan"></div>
      <h2>80 Bytes That Write History</h2>
      <p class="slide-body">Every civic action &mdash; citizenship, endorsement, proposal, vote &mdash; is recorded in 80 bytes of OP_RETURN data embedded in the blockchain. Permanent. Immutable. Auditable. Less than a tweet, more than enough.</p>
      <div class="slide-stats accent-cyan">
        <div class="stat-box">
          <span class="stat-value" style="font-family:var(--mr-font-mono);">80 B</span>
          <span class="stat-label">Per Record</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-lock"></i></span>
          <span class="stat-label">Immutable</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-clock-rotate-left"></i></span>
          <span class="stat-label">Permanent</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 14: Code as Constitution -->
  <section class="slide" id="slide-14">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/git-constitution.jpg');"></div>
    <div class="glow-orb" style="width:400px;height:400px;background:rgba(200,65,37,0.08);bottom:-100px;right:-100px;"></div>
    <div class="slide-inner">
      <div class="slide-number">14 &mdash; Constitution</div>
      <div class="accent-line mars"></div>
      <h2>The Code IS the Law</h2>
      <p class="slide-body">The Republic&rsquo;s constitution isn&rsquo;t a document &mdash; it&rsquo;s a Git repository. Laws are code. Amendments are pull requests. Machine-verifiable. No ambiguity. No lawyers. If the community disagrees, they can fork.</p>
      <div class="slide-stats accent-mars">
        <div class="stat-box">
          <span class="stat-value"><i class="fa-brands fa-git-alt"></i></span>
          <span class="stat-label">Git Repository</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-code-pull-request"></i></span>
          <span class="stat-label">PR = Amendment</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-code-fork"></i></span>
          <span class="stat-label">Forkable</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 15: The Arrival Hall -->
  <section class="slide" id="slide-15">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/arrival-hall.jpg');"></div>
    <div class="slide-inner" style="text-align:center; max-width:800px;">
      <div class="slide-number" style="text-align:center;">15 &mdash; The Future</div>
      <div class="accent-line amber" style="margin:0 auto 24px;"></div>
      <h2>Arriving on Mars</h2>
      <p class="slide-body" style="max-width:600px; margin:0 auto 24px;">You step off the Starship. Kiosks in the Immigration Hall connect to the local Marscoin network. Your wallet &mdash; set up on Earth &mdash; works instantly. Buy coffee. Vote on a proposal. You&rsquo;re home.</p>
      <div class="slide-stats accent-amber" style="justify-content:center;">
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-rocket"></i></span>
          <span class="stat-label">Arrive</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-wallet"></i></span>
          <span class="stat-label">Connect</span>
        </div>
        <div class="stat-box">
          <span class="stat-value"><i class="fa-solid fa-house-chimney"></i></span>
          <span class="stat-label">Home</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 16: Call to Action -->
  <section class="slide" id="slide-16">
    <div class="slide-bg-image" style="background-image: url('/assets/academy/wallet-coffee.jpg');"></div>
    <div class="slide-inner" style="text-align:center; max-width:800px;">
      <div class="slide-number" style="text-align:center;">16 &mdash; Your Move</div>
      <h2 style="font-size:clamp(32px,6vw,72px);">Join the Republic</h2>
      <p class="slide-body" style="max-width:600px; margin:0 auto 40px;">The system being built today is the system that will govern the first civilization on another planet. Every citizen who joins now is stress-testing the future.</p>
      <div class="slide-ctas" style="justify-content:center;">
        <a href="/signup" class="cta-btn cta-btn-primary">
          <i class="fa-solid fa-rocket"></i> Become a Citizen
        </a>
        <a href="/academy" class="cta-btn cta-btn-outline">
          <i class="fa-solid fa-graduation-cap"></i> Explore the Academy
        </a>
      </div>
    </div>
    <!-- Mini footer on the last slide -->
    <div class="slide-footer">
      <div class="slide-footer-inner">
        <div class="slide-footer-copy">
          &copy; 2014&ndash;{{ date('Y') }} The Marscoin Foundation, Inc.
        </div>
        <div class="slide-footer-links">
          <a href="/">Home</a>
          <a href="/academy">Academy</a>
          <a href="/congress/all">Congress</a>
          <a href="/privacy">Privacy</a>
        </div>
      </div>
    </div>
  </section>

</main>

<script>
(function() {
  'use strict';

  const slideshow = document.getElementById('slideshow');
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot-nav-dot');
  const counter = document.getElementById('slideCounter');
  const counterNum = counter.querySelector('.current-num');
  const progressBar = document.getElementById('progressBar');
  const scrollIndicator = document.getElementById('scrollIndicator');
  const nav = document.getElementById('mainNav');
  const totalSlides = slides.length;

  let currentSlide = 1;
  let isScrolling = false;
  let lastScrollTop = 0;

  // ---- IntersectionObserver for active slide detection ---- //
  const observerOptions = {
    root: slideshow,
    threshold: 0.55
  };

  const slideObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        const slideEl = entry.target;
        const slideId = parseInt(slideEl.id.replace('slide-', ''));

        // Update current
        currentSlide = slideId;
        updateUI(slideId);

        // Add visible class for animations
        slideEl.classList.add('visible');
      }
    });
  }, observerOptions);

  slides.forEach(function(slide) {
    slideObserver.observe(slide);
  });

  // ---- Update UI elements ---- //
  function updateUI(slideNum) {
    // Counter
    counterNum.textContent = slideNum;

    // Progress bar
    var progress = ((slideNum - 1) / (totalSlides - 1)) * 100;
    progressBar.style.width = progress + '%';

    // Dots
    dots.forEach(function(dot) {
      var dotSlide = parseInt(dot.getAttribute('data-slide'));
      dot.classList.toggle('active', dotSlide === slideNum);
    });

    // Scroll indicator: hide after first slide
    if (slideNum > 1) {
      scrollIndicator.classList.add('hidden');
    } else {
      scrollIndicator.classList.remove('hidden');
    }
  }

  // ---- Dot navigation click ---- //
  dots.forEach(function(dot) {
    dot.addEventListener('click', function() {
      var target = parseInt(this.getAttribute('data-slide'));
      scrollToSlide(target);
    });
  });

  // ---- Keyboard navigation ---- //
  document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowDown' || e.key === 'ArrowRight' || e.key === 'PageDown') {
      e.preventDefault();
      if (currentSlide < totalSlides) {
        scrollToSlide(currentSlide + 1);
      }
    } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft' || e.key === 'PageUp') {
      e.preventDefault();
      if (currentSlide > 1) {
        scrollToSlide(currentSlide - 1);
      }
    } else if (e.key === 'Home') {
      e.preventDefault();
      scrollToSlide(1);
    } else if (e.key === 'End') {
      e.preventDefault();
      scrollToSlide(totalSlides);
    }
  });

  // ---- Scroll to specific slide ---- //
  function scrollToSlide(num) {
    var target = document.getElementById('slide-' + num);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  }

  // ---- Nav hide/show on scroll direction ---- //
  slideshow.addEventListener('scroll', function() {
    var st = slideshow.scrollTop;

    if (st > lastScrollTop && st > 100) {
      nav.classList.add('nav-hidden');
    } else {
      nav.classList.remove('nav-hidden');
    }

    lastScrollTop = st <= 0 ? 0 : st;
  }, { passive: true });

  // ---- Initialize first slide as visible ---- //
  slides[0].classList.add('visible');

  // ---- Touch support: prevent any weird behaviors ---- //
  var touchStartY = 0;
  slideshow.addEventListener('touchstart', function(e) {
    touchStartY = e.touches[0].clientY;
  }, { passive: true });

})();
</script>

</body>
</html>
