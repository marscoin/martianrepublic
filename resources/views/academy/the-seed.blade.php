<!DOCTYPE html>
<html lang="en">
<head>
<title>The Seed — A Civilization in Your Pocket | Martian Republic Academy</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Download the bootable USB image containing the entire Martian Republic: Marscoin blockchain, IPFS, governance platform, wallets, and documentation. One seed to bootstrap a civilization.">
<meta name="keywords" content="Martian Republic, bootable USB, Marscoin blockchain, IPFS, Mars governance, civilization bootstrap, USB image, Mars settlement">
<meta property="og:title" content="The Seed — A Civilization in Your Pocket | Martian Republic Academy">
<meta property="og:description" content="Download the bootable USB image containing the entire Martian Republic: Marscoin blockchain, IPFS, governance platform, wallets, and documentation. One seed to bootstrap a civilization.">
<meta property="og:image" content="https://martianrepublic.org/assets/academy/seed-hero.jpg">
<meta property="og:type" content="product">
<meta property="og:url" content="https://martianrepublic.org/academy/the-seed">
<meta property="og:site_name" content="The Martian Republic">
<meta property="og:locale" content="en_US">
<meta name="robots" content="index, follow">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="The Seed — A Civilization in Your Pocket">
<meta name="twitter:description" content="Download the bootable USB image containing the entire Martian Republic: Marscoin blockchain, IPFS, governance platform, wallets, and documentation. One seed to bootstrap a civilization.">
<meta name="twitter:image" content="https://martianrepublic.org/assets/academy/seed-hero.jpg">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://martianrepublic.org/academy/the-seed">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "The Seed — Martian Republic Bootable Image",
  "description": "A bootable USB image containing the entire Martian Republic: Marscoin blockchain, IPFS daemon, governance platform, wallet software, and citizen handbook.",
  "image": "https://martianrepublic.org/assets/academy/seed-hero.jpg",
  "brand": {
    "@type": "Organization",
    "name": "The Marscoin Foundation, Inc.",
    "url": "https://martianrepublic.org"
  },
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD",
    "availability": "https://schema.org/PreOrder"
  },
  "mainEntityOfPage": "https://martianrepublic.org/academy/the-seed"
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
  --mr-font-display: 'Chakra Petch', sans-serif;
  --mr-font-body: 'DM Sans', sans-serif;
  --mr-font-mono: 'JetBrains Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; }

html {
  scroll-behavior: smooth;
  height: auto;
  overflow-x: hidden;
}

body {
  margin: 0;
  padding: 0;
  background: var(--mr-void);
  color: var(--mr-text);
  font-family: var(--mr-font-body);
  font-size: 16px;
  line-height: 1.7;
  -webkit-font-smoothing: antialiased;
  overflow-x: hidden;
}

a { color: var(--mr-cyan); transition: all 0.3s ease; text-decoration: none; }
a:hover { color: var(--mr-amber); text-decoration: none; }

/* ---- NAV ---- */
.mr-nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  background: rgba(6,6,12,0.6);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
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

/* ---- SCROLL PROGRESS ---- */
.scroll-progress {
  position: fixed;
  top: 0;
  left: 0;
  width: 0%;
  height: 2px;
  background: linear-gradient(90deg, var(--mr-mars), var(--mr-mars-glow), var(--mr-cyan));
  z-index: 1001;
  transition: width 0.1s linear;
}

/* ---- GLOBAL SECTION STYLES ---- */
.stick-section {
  position: relative;
  overflow: hidden;
}

.section-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

.body-text {
  color: rgba(224, 223, 230, 0.9);
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
  font-size: 18px;
  line-height: 1.8;
}

/* Reveal animation base */
.reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: opacity 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94),
              transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.reveal.visible {
  opacity: 1;
  transform: translateY(0);
}
.reveal-delay-1 { transition-delay: 0.1s; }
.reveal-delay-2 { transition-delay: 0.2s; }
.reveal-delay-3 { transition-delay: 0.3s; }
.reveal-delay-4 { transition-delay: 0.4s; }
.reveal-delay-5 { transition-delay: 0.5s; }
.reveal-delay-6 { transition-delay: 0.6s; }

/* ==============================
   SECTION 1: HERO
   ============================== */
.hero-section {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
  background: var(--mr-void);
  padding: 120px 24px 80px;
}

/* Ambient background glow */
.hero-section::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -55%);
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(200, 65, 37, 0.08) 0%, transparent 70%);
  pointer-events: none;
}

.hero-label {
  font-family: var(--mr-font-mono);
  font-size: 13px;
  letter-spacing: 3px;
  color: var(--mr-text-faint);
  text-transform: uppercase;
  margin-bottom: 48px;
}

/* CSS Seed Image visualization */
.usb-stick-container {
  position: relative;
  margin-bottom: 56px;
}

.usb-stick {
  position: relative;
  width: 200px;
  height: 80px;
}

/* Main body */
.usb-body {
  position: absolute;
  top: 0;
  left: 30px;
  width: 170px;
  height: 80px;
  border-radius: 8px 12px 12px 8px;
  background: linear-gradient(180deg,
    #3a3a4a 0%,
    #2a2a38 15%,
    #222230 50%,
    #1a1a28 85%,
    #28283a 100%
  );
  box-shadow:
    0 1px 0 rgba(255,255,255,0.08) inset,
    0 -1px 0 rgba(0,0,0,0.3) inset,
    0 20px 60px rgba(0,0,0,0.5),
    0 0 80px rgba(200, 65, 37, 0.12);
  border: 1px solid rgba(255,255,255,0.06);
}

/* Metallic top highlight */
.usb-body::before {
  content: '';
  position: absolute;
  top: 4px;
  left: 10px;
  right: 10px;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
}

/* USB connector */
.usb-connector {
  position: absolute;
  top: 20px;
  left: 0;
  width: 38px;
  height: 40px;
  border-radius: 3px 0 0 3px;
  background: linear-gradient(180deg,
    #c0c0cc 0%,
    #a0a0b0 20%,
    #909098 50%,
    #a0a0b0 80%,
    #b8b8c4 100%
  );
  box-shadow:
    0 1px 0 rgba(255,255,255,0.3) inset,
    -2px 0 6px rgba(0,0,0,0.3);
  border: 1px solid rgba(255,255,255,0.15);
  border-right: none;
}

/* Connector inner lines */
.usb-connector::before {
  content: '';
  position: absolute;
  top: 10px;
  left: 6px;
  width: 20px;
  height: 2px;
  background: rgba(0,0,0,0.15);
  box-shadow: 0 6px 0 rgba(0,0,0,0.15), 0 12px 0 rgba(0,0,0,0.15);
}

/* LED indicator line */
.usb-led {
  position: absolute;
  top: 36px;
  left: 50px;
  width: 130px;
  height: 3px;
  border-radius: 2px;
  background: var(--mr-mars);
  box-shadow:
    0 0 8px var(--mr-mars),
    0 0 20px rgba(200, 65, 37, 0.4),
    0 0 40px rgba(200, 65, 37, 0.2);
  animation: led-pulse 3s ease-in-out infinite;
}

/* Marscoin logo area */
.usb-logo {
  position: absolute;
  top: 50px;
  left: 75px;
  font-family: var(--mr-font-display);
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 2px;
  color: rgba(255,255,255,0.2);
  text-transform: uppercase;
}

@keyframes led-pulse {
  0%, 100% { opacity: 0.7; box-shadow: 0 0 8px var(--mr-mars), 0 0 20px rgba(200, 65, 37, 0.3); }
  50% { opacity: 1; box-shadow: 0 0 12px var(--mr-mars-glow), 0 0 30px rgba(200, 65, 37, 0.5), 0 0 60px rgba(200, 65, 37, 0.2); }
}

/* Stick ambient reflection */
.usb-stick-container::after {
  content: '';
  position: absolute;
  bottom: -30px;
  left: 50%;
  transform: translateX(-50%);
  width: 160px;
  height: 20px;
  background: radial-gradient(ellipse, rgba(200, 65, 37, 0.08) 0%, transparent 70%);
  filter: blur(6px);
}

.hero-headline {
  font-family: var(--mr-font-display);
  font-size: clamp(40px, 7vw, 80px);
  font-weight: 700;
  color: #fff;
  text-align: center;
  line-height: 1.1;
  margin: 0 0 24px;
  letter-spacing: -1px;
}

.hero-subtitle {
  font-size: clamp(16px, 2.2vw, 20px);
  color: var(--mr-text-dim);
  text-align: center;
  max-width: 640px;
  line-height: 1.7;
  margin: 0;
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
}

.scroll-indicator {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  color: var(--mr-text-faint);
  font-family: var(--mr-font-mono);
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  animation: scroll-hint 2.5s ease-in-out infinite;
}

.scroll-indicator i {
  font-size: 16px;
}

@keyframes scroll-hint {
  0%, 100% { opacity: 0.4; transform: translateX(-50%) translateY(0); }
  50% { opacity: 0.8; transform: translateX(-50%) translateY(6px); }
}

/* ==============================
   SECTION 2: THE PREMISE
   ============================== */
.premise-section {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 120px 24px;
  background: var(--mr-dark);
  position: relative;
}

.premise-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

.premise-inner {
  max-width: 900px;
  text-align: center;
}

.premise-big {
  font-family: var(--mr-font-display);
  font-size: clamp(28px, 4.5vw, 48px);
  font-weight: 600;
  color: #fff;
  line-height: 1.3;
  margin: 0 0 40px;
}

.premise-body {
  font-size: clamp(16px, 1.8vw, 19px);
  color: rgba(224, 223, 230, 0.85);
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
  line-height: 1.9;
  max-width: 780px;
  margin: 0 auto;
}

/* ==============================
   SECTION 3: WHAT'S INSIDE
   ============================== */
.inventory-section {
  padding: 140px 24px;
  background: var(--mr-void);
  position: relative;
}

.inventory-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

.section-title {
  font-family: var(--mr-font-display);
  font-size: clamp(36px, 5vw, 64px);
  font-weight: 700;
  color: #fff;
  text-align: center;
  margin: 0 0 80px;
  letter-spacing: -0.5px;
}

.inventory-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  max-width: 1100px;
  margin: 0 auto;
}

.inventory-card {
  background: var(--mr-surface);
  border: 1px solid var(--mr-border);
  border-radius: 16px;
  padding: 40px 32px;
  transition: all 0.4s ease;
  position: relative;
  overflow: hidden;
}

.inventory-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--mr-mars), transparent);
  opacity: 0;
  transition: opacity 0.4s ease;
}

.inventory-card:hover {
  border-color: var(--mr-border-bright);
  transform: translateY(-4px);
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.inventory-card:hover::before {
  opacity: 1;
}

.inventory-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  background: rgba(200, 65, 37, 0.1);
  border: 1px solid rgba(200, 65, 37, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  color: var(--mr-mars-glow);
  margin-bottom: 24px;
}

.inventory-card-title {
  font-family: var(--mr-font-display);
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  margin: 0 0 12px;
}

.inventory-card-text {
  font-size: 15px;
  color: var(--mr-text-dim);
  line-height: 1.7;
  margin: 0;
}

/* Bottom row: 2 cards centered */
.inventory-grid .inventory-card:nth-child(4) {
  grid-column: 1 / 2;
  justify-self: end;
}
.inventory-grid .inventory-card:nth-child(5) {
  grid-column: 2 / 3;
  justify-self: start;
}

/* Override for 5 cards: 3 top + 2 bottom centered */
@media (min-width: 769px) {
  .inventory-grid {
    grid-template-columns: repeat(3, 1fr);
  }
  .inventory-grid .inventory-card:nth-child(4),
  .inventory-grid .inventory-card:nth-child(5) {
    grid-column: auto;
    justify-self: auto;
  }
  /* Use a different approach: wrap last 2 in centered row */
  .inventory-bottom-row {
    display: flex;
    justify-content: center;
    gap: 24px;
    max-width: 1100px;
    margin: 24px auto 0;
  }
  .inventory-bottom-row .inventory-card {
    width: calc(33.333% - 16px);
    flex-shrink: 0;
  }
}

/* ==============================
   SECTION 4: THE NUMBERS
   ============================== */
.stats-section {
  padding: 140px 24px;
  background: var(--mr-dark);
  position: relative;
}

.stats-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

.stats-grid {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  gap: 0;
  max-width: 1100px;
  margin: 0 auto;
  flex-wrap: wrap;
}

.stat-item {
  flex: 1;
  min-width: 160px;
  max-width: 220px;
  text-align: center;
  padding: 40px 20px;
  position: relative;
}

.stat-item:not(:last-child)::after {
  content: '';
  position: absolute;
  right: 0;
  top: 30%;
  height: 40%;
  width: 1px;
  background: var(--mr-border-bright);
}

.stat-number {
  font-family: var(--mr-font-display);
  font-size: clamp(36px, 5vw, 56px);
  font-weight: 700;
  color: #fff;
  line-height: 1;
  margin-bottom: 12px;
}

.stat-label {
  font-family: var(--mr-font-mono);
  font-size: 13px;
  color: var(--mr-text-dim);
  letter-spacing: 1px;
  text-transform: uppercase;
}

/* Punchline stat */
.stat-item.stat-punchline .stat-number {
  font-size: clamp(48px, 7vw, 80px);
  background: linear-gradient(135deg, var(--mr-mars-glow), var(--mr-amber));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stat-item.stat-punchline .stat-label {
  color: var(--mr-mars-glow);
  font-size: 14px;
  font-weight: 500;
}

/* ==============================
   SECTION 5: WHY THIS EXISTS
   ============================== */
.mission-section {
  padding: 160px 24px;
  background: var(--mr-void);
  position: relative;
}

.mission-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

.mission-inner {
  max-width: 760px;
  margin: 0 auto;
}

.mission-headline {
  font-family: var(--mr-font-display);
  font-size: clamp(32px, 5vw, 52px);
  font-weight: 700;
  color: #fff;
  line-height: 1.2;
  margin: 0 0 40px;
}

.mission-body p {
  font-size: clamp(16px, 1.8vw, 19px);
  color: rgba(224, 223, 230, 0.85);
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
  line-height: 1.9;
  margin: 0 0 28px;
}

.mission-body p:last-child {
  margin-bottom: 0;
}

/* ==============================
   SECTION 6: THE VISION
   ============================== */
.vision-section {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 120px 24px;
  background: var(--mr-dark);
  position: relative;
  overflow: hidden;
}

.vision-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

/* Ambient glow behind vision text */
.vision-section::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 800px;
  height: 400px;
  background: radial-gradient(ellipse, rgba(200, 65, 37, 0.06) 0%, transparent 70%);
  pointer-events: none;
}

.vision-inner {
  text-align: center;
  position: relative;
  z-index: 1;
  max-width: 960px;
}

.vision-label {
  font-family: var(--mr-font-mono);
  font-size: 12px;
  letter-spacing: 4px;
  color: var(--mr-mars-glow);
  text-transform: uppercase;
  margin-bottom: 40px;
}

.vision-headline {
  font-family: var(--mr-font-display);
  font-size: clamp(32px, 5.5vw, 60px);
  font-weight: 700;
  color: #fff;
  line-height: 1.2;
  margin: 0 0 40px;
}

.vision-body {
  font-size: clamp(16px, 1.8vw, 19px);
  color: rgba(224, 223, 230, 0.8);
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
  line-height: 1.9;
  max-width: 760px;
  margin: 0 auto;
}

/* ==============================
   SECTION 7: HOW IT WORKS
   ============================== */
.steps-section {
  padding: 140px 24px;
  background: var(--mr-void);
  position: relative;
}

.steps-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

.steps-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 48px;
  max-width: 1100px;
  margin: 0 auto;
}

.step-item {
  text-align: center;
  padding: 0 16px;
  position: relative;
}

/* Connector line between steps */
.step-item:not(:last-child)::after {
  content: '';
  position: absolute;
  top: 56px;
  right: -24px;
  width: 48px;
  height: 2px;
  background: linear-gradient(90deg, var(--mr-mars), transparent);
}

.step-number {
  font-family: var(--mr-font-display);
  font-size: 72px;
  font-weight: 700;
  line-height: 1;
  background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 8px;
}

.step-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: var(--mr-surface);
  border: 2px solid var(--mr-mars);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: var(--mr-mars-glow);
  margin: 0 auto 24px;
  box-shadow: 0 0 30px rgba(200, 65, 37, 0.15);
}

.step-title {
  font-family: var(--mr-font-display);
  font-size: 24px;
  font-weight: 700;
  color: #fff;
  margin: 0 0 16px;
}

.step-text {
  font-size: 15px;
  color: var(--mr-text-dim);
  line-height: 1.8;
  margin: 0;
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
}

/* ==============================
   SECTION 8: SPECS
   ============================== */
.specs-section {
  padding: 140px 24px;
  background: var(--mr-dark);
  position: relative;
}

.specs-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

.specs-table {
  max-width: 700px;
  margin: 0 auto;
}

.spec-row {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  padding: 20px 0;
  border-bottom: 1px solid var(--mr-border);
}

.spec-row:first-child {
  border-top: 1px solid var(--mr-border);
}

.spec-key {
  font-family: var(--mr-font-mono);
  font-size: 14px;
  color: var(--mr-text-dim);
  letter-spacing: 0.5px;
  flex-shrink: 0;
}

.spec-value {
  font-family: var(--mr-font-mono);
  font-size: 14px;
  color: #fff;
  font-weight: 500;
  text-align: right;
}

/* ==============================
   SECTION 9: DOWNLOAD CTA
   ============================== */
.cta-section {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 120px 24px 80px;
  background: var(--mr-void);
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--mr-border-bright), transparent);
}

/* Dramatic background glow */
.cta-section::after {
  content: '';
  position: absolute;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 700px;
  height: 700px;
  background: radial-gradient(circle, rgba(200, 65, 37, 0.08) 0%, transparent 60%);
  pointer-events: none;
}

.cta-inner {
  text-align: center;
  position: relative;
  z-index: 1;
  max-width: 700px;
}

.cta-headline {
  font-family: var(--mr-font-display);
  font-size: clamp(40px, 7vw, 72px);
  font-weight: 700;
  color: #fff;
  line-height: 1.1;
  margin: 0 0 24px;
  letter-spacing: -0.5px;
}

.cta-subtitle {
  font-size: clamp(16px, 2vw, 19px);
  color: var(--mr-text-dim);
  line-height: 1.8;
  margin: 0 0 48px;
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
}

.cta-buttons {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 32px;
}

.btn-primary-mars {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 18px 44px;
  background: var(--mr-mars);
  color: #fff;
  font-family: var(--mr-font-display);
  font-weight: 700;
  font-size: 17px;
  letter-spacing: 0.5px;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  box-shadow: 0 4px 24px rgba(200, 65, 37, 0.3);
}

.btn-primary-mars:hover {
  background: var(--mr-mars-glow);
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(200, 65, 37, 0.4);
  text-decoration: none;
}

.btn-secondary-cyan {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 18px 44px;
  background: transparent;
  color: var(--mr-cyan);
  font-family: var(--mr-font-display);
  font-weight: 700;
  font-size: 17px;
  letter-spacing: 0.5px;
  border-radius: 10px;
  border: 2px solid var(--mr-cyan);
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-secondary-cyan:hover {
  background: var(--mr-cyan-dim);
  color: var(--mr-cyan);
  transform: translateY(-2px);
  text-decoration: none;
}

.cta-fine-print {
  font-size: 13px;
  color: var(--mr-text-faint);
  font-family: var(--mr-font-mono);
  letter-spacing: 0.5px;
}

/* ==============================
   FOOTER
   ============================== */
.site-footer {
  padding: 32px 0;
  border-top: 1px solid var(--mr-border);
  background: var(--mr-void);
}

.footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.footer-copy {
  font-size: 13px;
  color: var(--mr-text-faint);
}

.footer-links {
  display: flex;
  gap: 24px;
}

.footer-links a {
  color: var(--mr-text-faint);
  font-size: 13px;
}

.footer-links a:hover {
  color: #fff;
}

/* ==============================
   MOBILE RESPONSIVE
   ============================== */
@media (max-width: 768px) {
  .mr-nav-links a:not(.mr-nav-cta) { display: none; }
  .mr-nav-links { gap: 12px; }

  .hero-section {
    padding: 100px 20px 80px;
  }

  .usb-stick {
    transform: scale(0.85);
  }

  .hero-headline {
    font-size: 36px;
  }

  .premise-big {
    font-size: 26px;
  }

  .inventory-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .inventory-bottom-row {
    flex-direction: column;
    align-items: stretch;
  }

  .inventory-bottom-row .inventory-card {
    width: 100%;
  }

  .inventory-card {
    padding: 32px 24px;
  }

  .stats-grid {
    flex-direction: column;
    align-items: center;
    gap: 0;
  }

  .stat-item {
    max-width: none;
    width: 100%;
    padding: 24px 20px;
    border-bottom: 1px solid var(--mr-border);
  }

  .stat-item:not(:last-child)::after {
    display: none;
  }

  .stat-item:last-child {
    border-bottom: none;
  }

  .steps-grid {
    grid-template-columns: 1fr;
    gap: 48px;
  }

  .step-item:not(:last-child)::after {
    display: none;
  }

  .spec-row {
    flex-direction: column;
    gap: 4px;
  }

  .spec-value {
    text-align: left;
  }

  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }

  .btn-primary-mars,
  .btn-secondary-cyan {
    width: 100%;
    max-width: 320px;
    justify-content: center;
  }

  .footer-inner {
    flex-direction: column;
    gap: 16px;
    text-align: center;
  }

  .section-title {
    margin-bottom: 48px;
  }

  .mission-headline {
    font-size: 28px;
  }

  .vision-headline {
    font-size: 28px;
  }
}

@media (max-width: 480px) {
  .hero-headline {
    font-size: 30px;
  }

  .hero-subtitle {
    font-size: 15px;
  }

  .usb-stick {
    transform: scale(0.7);
  }

  .stat-number {
    font-size: 36px;
  }

  .stat-item.stat-punchline .stat-number {
    font-size: 48px;
  }
}
</style>
</head>
<body>

<!-- SCROLL PROGRESS BAR -->
<div class="scroll-progress" id="scrollProgress"></div>

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

<!-- ==================== SECTION 1: HERO ==================== -->
<section class="stick-section hero-section" id="hero">
  <div class="hero-label reveal">LEARNING PATH 06</div>

  <div class="usb-stick-container reveal reveal-delay-1" id="usbStick">
    <div class="usb-stick">
      <div class="usb-connector"></div>
      <div class="usb-body">
        <div class="usb-led"></div>
        <div class="usb-logo">MARSCOIN</div>
      </div>
    </div>
  </div>

  <h1 class="hero-headline reveal reveal-delay-2">A Civilization<br>in a Seed.</h1>
  <p class="hero-subtitle reveal reveal-delay-3">The entire Martian Republic &mdash; blockchain, governance, identity, storage &mdash; in one bootable image. Download it. Flash it. Boot a new world.</p>

  <div class="scroll-indicator">
    <span>Scroll</span>
    <i class="fas fa-chevron-down"></i>
  </div>
</section>

<!-- ==================== SECTION 2: THE PREMISE ==================== -->
<section class="stick-section premise-section" id="premise">
  <div class="premise-inner">
    <h2 class="premise-big reveal">What if you could carry an entire civilization's infrastructure in your pocket?</h2>
    <p class="premise-body reveal reveal-delay-1">The Marscoin Foundation maintains a bootable disk image &mdash; regularly updated &mdash; containing every piece of software, every block of the chain, and every tool needed to run the Martian Republic. One seed. One boot. A complete, functioning node of humanity's first blockchain democracy.</p>
  </div>
</section>

<!-- ==================== SECTION 3: WHAT'S INSIDE ==================== -->
<section class="stick-section inventory-section" id="inventory">
  <div class="section-inner">
    <h2 class="section-title reveal">Everything. All of It.</h2>

    <div class="inventory-grid">
      <div class="inventory-card reveal reveal-delay-1">
        <div class="inventory-icon"><i class="fas fa-cube"></i></div>
        <h3 class="inventory-card-title">Marscoin Core</h3>
        <p class="inventory-card-text">The full blockchain node. Source code, compiled binaries, and the complete chain &mdash; every block since January 1, 2014. Twelve years of uninterrupted history.</p>
      </div>

      <div class="inventory-card reveal reveal-delay-2">
        <div class="inventory-icon"><i class="fas fa-network-wired"></i></div>
        <h3 class="inventory-card-title">IPFS Daemon</h3>
        <p class="inventory-card-text">A preconfigured InterPlanetary File System node with bootstrap peers. Pin, serve, and retrieve citizen data from boot. No internet dependency.</p>
      </div>

      <div class="inventory-card reveal reveal-delay-3">
        <div class="inventory-icon"><i class="fas fa-landmark"></i></div>
        <h3 class="inventory-card-title">Martian Republic</h3>
        <p class="inventory-card-text">The complete governance application. Wallet, citizen registry, congress, forum, inventory, logbook, land registry. Every module, ready to run.</p>
      </div>
    </div>

    <div class="inventory-bottom-row">
      <div class="inventory-card reveal reveal-delay-4">
        <div class="inventory-icon"><i class="fas fa-key"></i></div>
        <h3 class="inventory-card-title">Wallet Software</h3>
        <p class="inventory-card-text">Electrum-Mars desktop wallet, CLI tools, and the full HD wallet stack. Generate keys, sign transactions, manage civic and treasury addresses.</p>
      </div>

      <div class="inventory-card reveal reveal-delay-5">
        <div class="inventory-icon"><i class="fas fa-book"></i></div>
        <h3 class="inventory-card-title">Documentation</h3>
        <p class="inventory-card-text">The Citizen Handbook: setup guides, governance rules, emergency procedures, and the complete technical reference. Everything a founding citizen needs to know.</p>
      </div>
    </div>
  </div>
</section>

<!-- ==================== SECTION 4: THE NUMBERS ==================== -->
<section class="stick-section stats-section" id="stats">
  <div class="section-inner">
    <div class="stats-grid">
      <div class="stat-item reveal">
        <div class="stat-number"><span class="count-up" data-target="12" data-suffix="+">0</span></div>
        <div class="stat-label">Years of Blockchain</div>
      </div>
      <div class="stat-item reveal reveal-delay-1">
        <div class="stat-number"><span class="count-up" data-target="3.1" data-suffix="M+" data-decimal="true">0</span></div>
        <div class="stat-label">Blocks</div>
      </div>
      <div class="stat-item reveal reveal-delay-2">
        <div class="stat-number"><span class="count-up" data-target="40" data-prefix="~" data-suffix="M">0</span></div>
        <div class="stat-label">MARS Supply</div>
      </div>
      <div class="stat-item reveal reveal-delay-3">
        <div class="stat-number"><span class="count-up" data-target="7">0</span></div>
        <div class="stat-label">Republic Modules</div>
      </div>
      <div class="stat-item stat-punchline reveal reveal-delay-4">
        <div class="stat-number"><span class="count-up" data-target="1">0</span></div>
        <div class="stat-label">Seed Image</div>
      </div>
    </div>
  </div>
</section>

<!-- ==================== SECTION 5: WHY THIS EXISTS ==================== -->
<section class="stick-section mission-section" id="mission">
  <div class="section-inner">
    <div class="mission-inner">
      <h2 class="mission-headline reveal">The bootstrapping problem.</h2>
      <div class="mission-body">
        <p class="reveal reveal-delay-1">When the first settlers arrive on Mars, they won't have time to download a blockchain, configure IPFS, install a governance platform, and set up wallets. They need to plug in a seed and boot. The network must exist from minute one &mdash; because on Mars, governance isn't optional. It's survival infrastructure.</p>
        <p class="reveal reveal-delay-2">This image solves the cold-start problem. One seed seeds the network. A second seed creates redundancy. By the tenth seed, you have a resilient, distributed civilization operating at full capacity.</p>
      </div>
    </div>
  </div>
</section>

<!-- ==================== SECTION 6: THE VISION ==================== -->
<section class="stick-section vision-section" id="vision" style="background: linear-gradient(rgba(6,6,12,0.7), rgba(6,6,12,0.8)), url('/assets/academy/seed-starship.jpg') center/cover;">
  <div class="vision-inner">
    <div class="vision-label reveal">THE VISION</div>
    <h2 class="vision-headline reveal reveal-delay-1">Someone boarding a Starship will carry this in their pocket.</h2>
    <p class="vision-body reveal reveal-delay-2">Not a hard drive. Not a server rack. A seed. Everything a civilization needs to govern itself, transact, store data, verify identity, vote by secret ballot, and maintain an immutable record of its own history. Updated by the Marscoin Foundation. Open source. Forever free.</p>
  </div>
</section>

<!-- ==================== SECTION 7: HOW IT WORKS ==================== -->
<section class="stick-section steps-section" id="steps" style="background: linear-gradient(rgba(6,6,12,0.8), rgba(6,6,12,0.9)), url('/assets/academy/seed-boot.jpg') center/cover;">
  <div class="section-inner">
    <h2 class="section-title reveal">Three Steps to Mars.</h2>

    <div class="steps-grid">
      <div class="step-item reveal reveal-delay-1">
        <div class="step-number">01</div>
        <div class="step-icon"><i class="fas fa-download"></i></div>
        <h3 class="step-title">Download</h3>
        <p class="step-text">Get the latest .img file from the Marscoin Foundation. Updated regularly with the latest blockchain data, software patches, and documentation.</p>
      </div>

      <div class="step-item reveal reveal-delay-2">
        <div class="step-number">02</div>
        <div class="step-icon"><i class="fab fa-usb"></i></div>
        <h3 class="step-title">Flash</h3>
        <p class="step-text">Write the image to any USB 3.0+ drive using Etcher, dd, or Rufus. 64GB minimum recommended. Rugged, industrial-grade USB drives survive anything &mdash; including Mars.</p>
      </div>

      <div class="step-item reveal reveal-delay-3">
        <div class="step-number">03</div>
        <div class="step-icon"><i class="fas fa-power-off"></i></div>
        <h3 class="step-title">Boot</h3>
        <p class="step-text">Plug it into any x86_64 machine. Boot from USB. The Marscoin node starts syncing. IPFS initializes. The Martian Republic is live. You're a founding node.</p>
      </div>
    </div>
  </div>
</section>

<!-- ==================== SECTION 8: SPECS ==================== -->
<section class="stick-section specs-section" id="specs">
  <div class="section-inner">
    <h2 class="section-title reveal">Technical Specifications</h2>

    <div class="specs-table">
      <div class="spec-row reveal">
        <span class="spec-key">Image format</span>
        <span class="spec-value">Raw .img (dd-compatible)</span>
      </div>
      <div class="spec-row reveal reveal-delay-1">
        <span class="spec-key">Target size</span>
        <span class="spec-value">~32-64 GB</span>
      </div>
      <div class="spec-row reveal reveal-delay-1">
        <span class="spec-key">Architecture</span>
        <span class="spec-value">x86_64 (Linux-based)</span>
      </div>
      <div class="spec-row reveal reveal-delay-2">
        <span class="spec-key">OS base</span>
        <span class="spec-value">Debian minimal / custom</span>
      </div>
      <div class="spec-row reveal reveal-delay-2">
        <span class="spec-key">Includes</span>
        <span class="spec-value">Marscoin Core, IPFS, Martian Republic, Electrum-Mars, docs</span>
      </div>
      <div class="spec-row reveal reveal-delay-3">
        <span class="spec-key">Update frequency</span>
        <span class="spec-value">Quarterly</span>
      </div>
      <div class="spec-row reveal reveal-delay-3">
        <span class="spec-key">License</span>
        <span class="spec-value">MIT / Open Source</span>
      </div>
      <div class="spec-row reveal reveal-delay-4">
        <span class="spec-key">Maintained by</span>
        <span class="spec-value">The Marscoin Foundation, Inc.</span>
      </div>
      <div class="spec-row reveal reveal-delay-4">
        <span class="spec-key">First release</span>
        <span class="spec-value">Coming Soon</span>
      </div>
    </div>
  </div>
</section>

<!-- ==================== SECTION 9: DOWNLOAD CTA ==================== -->
<section class="stick-section cta-section" id="download">
  <div class="cta-inner">
    <h2 class="cta-headline reveal">Download<br>the Future.</h2>
    <p class="cta-subtitle reveal reveal-delay-1">The first release is being assembled. Join the mailing list to be notified &mdash; or explore the Academy while you wait.</p>

    <div class="cta-buttons reveal reveal-delay-2">
      <a href="#" class="btn-primary-mars"><i class="fas fa-bell"></i> Notify Me</a>
      <a href="/academy" class="btn-secondary-cyan"><i class="fas fa-graduation-cap"></i> Explore the Academy</a>
    </div>

    <p class="cta-fine-print reveal reveal-delay-3">Open source. Free forever. Maintained by the Marscoin Foundation.</p>
  </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-copy">
      &copy; 2014&ndash;{{ date('Y') }} The Marscoin Foundation, Inc.
    </div>
    <div class="footer-links">
      <a href="/">Home</a>
      <a href="/academy">Academy</a>
      <a href="/congress/all">Congress</a>
      <a href="/privacy">Privacy</a>
    </div>
  </div>
</footer>

<script>
(function() {
  'use strict';

  // ---- Scroll Progress Bar ----
  const progressBar = document.getElementById('scrollProgress');

  function updateProgress() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
    progressBar.style.width = progress + '%';
  }

  // ---- Nav auto-hide on scroll ----
  const nav = document.getElementById('mainNav');
  let lastScrollY = 0;
  let ticking = false;

  function handleNav() {
    const currentScrollY = window.pageYOffset;
    if (currentScrollY > lastScrollY && currentScrollY > 100) {
      nav.classList.add('nav-hidden');
    } else {
      nav.classList.remove('nav-hidden');
    }
    lastScrollY = currentScrollY;
    ticking = false;
  }

  // ---- IntersectionObserver: Reveal on Scroll ----
  const revealElements = document.querySelectorAll('.reveal');

  const revealObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        // Don't unobserve — keep it simple, one-time reveal
        revealObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15,
    rootMargin: '0px 0px -40px 0px'
  });

  revealElements.forEach(function(el) {
    revealObserver.observe(el);
  });

  // ---- Counter Animation ----
  const counters = document.querySelectorAll('.count-up');
  let countersAnimated = new Set();

  const counterObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting && !countersAnimated.has(entry.target)) {
        countersAnimated.add(entry.target);
        animateCounter(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.5
  });

  counters.forEach(function(counter) {
    counterObserver.observe(counter);
  });

  function animateCounter(el) {
    const target = parseFloat(el.getAttribute('data-target'));
    const suffix = el.getAttribute('data-suffix') || '';
    const prefix = el.getAttribute('data-prefix') || '';
    const isDecimal = el.getAttribute('data-decimal') === 'true';
    const duration = 2000;
    const startTime = performance.now();

    function easeOutCubic(t) {
      return 1 - Math.pow(1 - t, 3);
    }

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const easedProgress = easeOutCubic(progress);
      const current = target * easedProgress;

      if (isDecimal) {
        el.textContent = prefix + current.toFixed(1) + suffix;
      } else {
        el.textContent = prefix + Math.round(current) + suffix;
      }

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        if (isDecimal) {
          el.textContent = prefix + target.toFixed(1) + suffix;
        } else {
          el.textContent = prefix + target + suffix;
        }
      }
    }

    requestAnimationFrame(update);
  }

  // ---- Parallax-lite on Hero Seed Image ----
  const usbStick = document.getElementById('usbStick');
  const heroSection = document.getElementById('hero');

  function handleParallax() {
    if (!usbStick || !heroSection) return;
    const rect = heroSection.getBoundingClientRect();
    const scrolled = -rect.top;
    const sectionHeight = heroSection.offsetHeight;

    if (scrolled >= 0 && scrolled <= sectionHeight) {
      const parallaxOffset = scrolled * 0.15;
      usbStick.style.transform = 'translateY(' + parallaxOffset + 'px)';
    }
  }

  // ---- Unified Scroll Handler ----
  window.addEventListener('scroll', function() {
    if (!ticking) {
      requestAnimationFrame(function() {
        handleNav();
        handleParallax();
        updateProgress();
      });
      ticking = true;
    }
  }, { passive: true });

  // Initial calls
  updateProgress();

})();
</script>

</body>
</html>
