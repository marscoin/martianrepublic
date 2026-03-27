<!DOCTYPE html>
<html lang="en">
<head>
<title>Martian Republic - Governance for Mars Settlement</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Join the Martian Congressional Republic and participate in a blockchain-based community preparing for life on Mars.">
<meta name="keywords" content="Mars, Marscoin, Martian Congressional Republic, blockchain, Mars settlement, citizen participation">
<meta name="author" content="The Marscoin&trade; Foundation, Inc.">

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="The Martian Congressional Republic">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.martianrepublic.org">
<meta property="og:image" content="https://martianrepublic.org/assets/citizen/mars_flag5.png">
<meta property="og:description" content="Participate in the Martian Congressional Republic and prepare for settling Mars with our blockchain-based governance and community tools.">
<meta property="og:site_name" content="Martian Congressional Republic">
<meta property="og:locale" content="en_US">

<!-- Additional Tags for Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@Marscoinorg">
<meta name="twitter:title" content="The Martian Congressional Republic">
<meta name="twitter:description" content="Join our blockchain-based community and prepare for life on Mars.">
<meta name="twitter:image" content="https://martianrepublic.org/assets/citizen/mars_flag5.png">
<meta name="apple-itunes-app" content="app-id=6480416861">
<link rel="icon" type="image/png" href="https://martianrepublic.org/assets/favicon.ico">
<link rel="canonical" href="https://www.martianrepublic.org">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=DM+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="/assets/landing/css/bootstrap.min.css">
<link rel="shortcut icon" href="/assets/favicon.ico">

<style>
/* ============================================
   MARTIAN REPUBLIC - LANDING PAGE
   Design: Dark space-tech / Mission Control
   ============================================ */

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
  --mr-amber: #d4a44a;
  --mr-cyan: #00e4ff;
  --mr-cyan-dim: rgba(0,228,255,0.15);
  --mr-green: #34d399;
  --mr-font-display: 'Chakra Petch', sans-serif;
  --mr-font-body: 'DM Sans', sans-serif;
  --mr-font-mono: 'JetBrains Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; }

html { scroll-behavior: smooth; }

body {
  margin: 0;
  padding: 0;
  background: var(--mr-void);
  color: var(--mr-text);
  font-family: var(--mr-font-body);
  font-size: 16px;
  line-height: 1.7;
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
}

/* Override Bootstrap defaults */
.container { max-width: 1200px; }
a { color: var(--mr-cyan); transition: all 0.3s ease; }
a:hover, a:focus { color: var(--mr-amber); text-decoration: none; }
h1, h2, h3, h4, h5, h6 { font-family: var(--mr-font-display); color: #fff; font-weight: 600; }
p { color: var(--mr-text-dim); }
img { max-width: 100%; height: auto; }

/* ---- NAVIGATION ---- */
.mr-nav {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  padding: 0;
  background: rgba(6,6,12,0.6);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-bottom: none;
  transition: background 0.4s ease;
}
.mr-nav.scrolled {
  background: rgba(6,6,12,0.92);
  border-bottom: 1px solid var(--mr-border);
}
.mr-nav .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 72px;
}
.mr-nav-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  color: #fff;
  font-family: var(--mr-font-display);
  font-weight: 700;
  font-size: 18px;
  letter-spacing: 1px;
  text-transform: uppercase;
}
.mr-nav-brand:hover, .mr-nav-brand:focus { color: #fff; text-decoration: none; }
.mr-nav-brand img { width: 42px; height: 42px; border-radius: 50%; }
.mr-nav-links {
  display: flex;
  align-items: center;
  gap: 8px;
  list-style: none;
  margin: 0;
  padding: 0;
}
.mr-nav-links li a {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border-radius: 6px;
  font-family: var(--mr-font-display);
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  color: var(--mr-text-dim);
  text-decoration: none;
  transition: all 0.25s ease;
}
.mr-nav-links li a:hover {
  color: #fff;
  background: rgba(255,255,255,0.05);
}
.mr-nav-links li a.mr-nav-cta {
  background: var(--mr-mars);
  color: #fff;
  border: none;
  margin-left: 4px;
}
.mr-nav-links li a.mr-nav-cta:hover {
  background: var(--mr-mars-glow);
  box-shadow: 0 0 24px rgba(200,65,37,0.4);
}
.mr-nav-social {
  display: flex;
  align-items: center;
  gap: 4px;
  list-style: none;
  margin: 0;
  padding: 0;
}
.mr-nav-social li a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  color: var(--mr-text-faint);
  text-decoration: none;
  transition: all 0.25s ease;
  font-size: 14px;
}
.mr-nav-social li a:hover {
  color: var(--mr-cyan);
  background: var(--mr-cyan-dim);
}
.mr-nav-toggle {
  display: none;
  background: none;
  border: 1px solid var(--mr-border-bright);
  border-radius: 6px;
  color: var(--mr-text);
  padding: 8px 12px;
  font-size: 18px;
  cursor: pointer;
}

/* ---- HERO SECTION ---- */
.mr-hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  overflow: hidden;
  padding-top: 72px;
}
.mr-hero-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
}
.mr-hero-bg img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.3;
  filter: saturate(0.8);
}
.mr-hero-gradient {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(180deg, var(--mr-void) 0%, transparent 30%),
    linear-gradient(0deg, var(--mr-void) 0%, transparent 40%),
    linear-gradient(90deg, var(--mr-void) 0%, transparent 60%),
    radial-gradient(ellipse at 70% 50%, rgba(200,65,37,0.12) 0%, transparent 60%);
  z-index: 1;
}
#star-canvas {
  position: absolute;
  inset: 0;
  z-index: 0;
}
.mr-hero-content {
  position: relative;
  z-index: 2;
  max-width: 720px;
  padding: 60px 0;
}
.mr-hero-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 16px;
  background: var(--mr-cyan-dim);
  border: 1px solid rgba(0,228,255,0.2);
  border-radius: 100px;
  font-family: var(--mr-font-mono);
  font-size: 12px;
  color: var(--mr-cyan);
  letter-spacing: 1px;
  text-transform: uppercase;
  margin-bottom: 32px;
  animation: fadeSlideUp 0.8s ease both;
}
.mr-hero-tag .dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--mr-green);
  animation: pulse-dot 2s ease infinite;
}
@keyframes pulse-dot {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.3; }
}
.mr-hero h1 {
  font-size: clamp(36px, 5.5vw, 68px);
  line-height: 1.08;
  font-weight: 700;
  letter-spacing: -1px;
  color: #fff;
  margin: 0 0 28px 0;
  animation: fadeSlideUp 0.8s 0.15s ease both;
}
.mr-hero h1 span {
  background: linear-gradient(135deg, var(--mr-mars-glow) 0%, var(--mr-amber) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.mr-hero-sub {
  font-size: 18px;
  line-height: 1.7;
  color: var(--mr-text-dim);
  max-width: 540px;
  margin-bottom: 40px;
  animation: fadeSlideUp 0.8s 0.3s ease both;
}
.mr-hero-actions {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  animation: fadeSlideUp 0.8s 0.45s ease both;
}
.mr-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 14px 32px;
  border-radius: 8px;
  font-family: var(--mr-font-display);
  font-size: 15px;
  font-weight: 600;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  text-decoration: none;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}
.mr-btn-primary {
  background: var(--mr-mars);
  color: #fff;
  box-shadow: 0 4px 24px rgba(200,65,37,0.3);
}
.mr-btn-primary:hover {
  background: var(--mr-mars-glow);
  color: #fff;
  box-shadow: 0 8px 40px rgba(200,65,37,0.5);
  transform: translateY(-2px);
}
.mr-btn-outline {
  background: transparent;
  color: #fff;
  border: 1px solid var(--mr-border-bright);
  box-shadow: 0 0 0 transparent;
}
.mr-btn-outline:hover {
  color: #fff;
  border-color: var(--mr-cyan);
  background: var(--mr-cyan-dim);
  box-shadow: 0 0 24px rgba(0,228,255,0.12);
  transform: translateY(-2px);
}
.mr-hero-stats {
  display: flex;
  gap: 48px;
  margin-top: 60px;
  padding-top: 32px;
  border-top: 1px solid var(--mr-border);
  animation: fadeSlideUp 0.8s 0.6s ease both;
}
.mr-hero-stat-value {
  font-family: var(--mr-font-mono);
  font-size: 28px;
  font-weight: 500;
  color: #fff;
}
.mr-hero-stat-label {
  font-size: 12px;
  color: var(--mr-text-faint);
  text-transform: uppercase;
  letter-spacing: 1.5px;
  margin-top: 4px;
}

@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(24px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ---- TICKER BAR ---- */
.mr-ticker {
  position: relative;
  z-index: 10;
  background: var(--mr-surface);
  border-top: 1px solid var(--mr-border);
  border-bottom: 1px solid var(--mr-border);
  padding: 14px 0;
  overflow: hidden;
}
.mr-ticker-inner {
  display: flex;
  animation: ticker-scroll 30s linear infinite;
  white-space: nowrap;
}
.mr-ticker-item {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-right: 48px;
  font-family: var(--mr-font-mono);
  font-size: 12px;
  color: var(--mr-text-faint);
  letter-spacing: 0.5px;
  text-transform: uppercase;
  flex-shrink: 0;
}
.mr-ticker-item .label {
  color: var(--mr-text-dim);
}
.mr-ticker-item .value {
  color: var(--mr-cyan);
}
@keyframes ticker-scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

/* ---- SECTION COMMON ---- */
.mr-section {
  padding: 100px 0;
  position: relative;
}
.mr-section-header {
  text-align: center;
  max-width: 640px;
  margin: 0 auto 64px;
}
.mr-section-label {
  display: inline-block;
  font-family: var(--mr-font-mono);
  font-size: 11px;
  color: var(--mr-mars);
  text-transform: uppercase;
  letter-spacing: 3px;
  margin-bottom: 16px;
}
.mr-section-title {
  font-size: clamp(28px, 3.5vw, 42px);
  line-height: 1.2;
  font-weight: 700;
  margin-bottom: 16px;
}
.mr-section-desc {
  font-size: 17px;
  color: var(--mr-text-dim);
  line-height: 1.7;
}

/* ---- FEATURES GRID ---- */
.mr-features {
  background: var(--mr-dark);
}
.mr-features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2px;
}
.mr-feature-card {
  position: relative;
  background: var(--mr-surface);
  padding: 44px 36px;
  transition: all 0.4s ease;
  overflow: hidden;
}
.mr-feature-card::before {
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
.mr-feature-card:hover {
  background: var(--mr-surface-raised);
}
.mr-feature-card:hover::before {
  opacity: 1;
}
.mr-feature-icon {
  width: 52px;
  height: 52px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  background: rgba(200,65,37,0.1);
  border: 1px solid rgba(200,65,37,0.15);
  color: var(--mr-mars-glow);
  font-size: 22px;
  margin-bottom: 24px;
  transition: all 0.3s ease;
}
.mr-feature-card:hover .mr-feature-icon {
  background: rgba(200,65,37,0.2);
  border-color: rgba(200,65,37,0.3);
  box-shadow: 0 0 24px rgba(200,65,37,0.15);
}
.mr-feature-card h3 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 12px;
}
.mr-feature-card p {
  font-size: 14px;
  line-height: 1.7;
  color: var(--mr-text-dim);
  margin: 0;
}

/* ---- ABOUT / ALTERNATING SECTIONS ---- */
.mr-split {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
  padding: 80px 0;
}
.mr-split + .mr-split {
  border-top: 1px solid var(--mr-border);
}
.mr-split-media {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
}
.mr-split-media img {
  display: block;
  width: 100%;
  border-radius: 16px;
  border: 1px solid var(--mr-border);
}
.mr-split-media::after {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 16px;
  border: 1px solid var(--mr-border-bright);
  pointer-events: none;
}
.mr-split-text .mr-section-label { text-align: left; }
.mr-split-text h2 {
  font-size: clamp(24px, 3vw, 36px);
  line-height: 1.2;
  margin-bottom: 20px;
}
.mr-split-text p {
  font-size: 15px;
  color: var(--mr-text-dim);
  margin-bottom: 16px;
  line-height: 1.8;
}
.mr-split-text .mr-btn {
  margin-top: 16px;
}

/* ---- MISSION / MANIFESTO ---- */
.mr-mission {
  background: var(--mr-dark);
  position: relative;
  overflow: hidden;
}
.mr-mission::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(200,65,37,0.08) 0%, transparent 70%);
  transform: translate(-50%, -50%);
  pointer-events: none;
}
.mr-mission-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
  position: relative;
}
.mr-mission-card {
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--mr-border);
  border-radius: 16px;
  padding: 40px 32px;
  transition: all 0.4s ease;
}
.mr-mission-card:hover {
  border-color: var(--mr-border-bright);
  background: rgba(255,255,255,0.04);
  transform: translateY(-4px);
}
.mr-mission-num {
  font-family: var(--mr-font-mono);
  font-size: 13px;
  color: var(--mr-mars);
  margin-bottom: 20px;
  letter-spacing: 1px;
}
.mr-mission-card h3 {
  font-size: 20px;
  margin-bottom: 12px;
}
.mr-mission-card p {
  font-size: 14px;
  color: var(--mr-text-dim);
  line-height: 1.8;
}

/* ---- APP SECTION ---- */
.mr-app-section {
  position: relative;
}
.mr-app-section .mr-split-media {
  text-align: center;
}
.mr-app-section .mr-split-media img {
  max-width: 320px;
  margin: 0 auto;
  border: none;
  border-radius: 32px;
  box-shadow: 0 32px 80px rgba(0,0,0,0.6);
}
.mr-app-badges {
  display: flex;
  gap: 12px;
  margin-top: 24px;
}
.mr-app-badges a img {
  height: 48px;
  border-radius: 8px;
  border: none;
}

/* ---- PARTNERS ---- */
.mr-partners {
  background: var(--mr-dark);
  padding: 64px 0;
  border-top: 1px solid var(--mr-border);
  border-bottom: 1px solid var(--mr-border);
}
.mr-partners-header {
  text-align: center;
  margin-bottom: 40px;
}
.mr-partners-header h4 {
  font-size: 13px;
  font-family: var(--mr-font-mono);
  color: var(--mr-text-faint);
  text-transform: uppercase;
  letter-spacing: 3px;
  font-weight: 400;
}
.mr-partners-logos {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 48px;
  flex-wrap: wrap;
}
.mr-partners-logos a {
  display: block;
  opacity: 0.4;
  filter: grayscale(1) brightness(2);
  transition: all 0.3s ease;
}
.mr-partners-logos a:hover {
  opacity: 0.9;
  filter: grayscale(0) brightness(1);
}
.mr-partners-logos a img {
  height: 44px;
  width: auto;
}

/* ---- FOOTER ---- */
.mr-footer {
  background: var(--mr-dark);
  padding: 80px 0 0;
}
.mr-footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 48px;
  padding-bottom: 60px;
  border-bottom: 1px solid var(--mr-border);
}
.mr-footer-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  text-decoration: none;
  color: #fff;
  font-family: var(--mr-font-display);
  font-weight: 700;
  font-size: 16px;
  letter-spacing: 0.5px;
}
.mr-footer-brand img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
}
.mr-footer-desc {
  font-size: 14px;
  color: var(--mr-text-faint);
  line-height: 1.7;
  max-width: 300px;
}
.mr-footer h5 {
  font-size: 12px;
  font-family: var(--mr-font-mono);
  text-transform: uppercase;
  letter-spacing: 2px;
  color: var(--mr-text-dim);
  margin-bottom: 20px;
  font-weight: 500;
}
.mr-footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}
.mr-footer-links li { margin-bottom: 10px; }
.mr-footer-links li a {
  font-size: 14px;
  color: var(--mr-text-faint);
  text-decoration: none;
  transition: color 0.25s ease;
}
.mr-footer-links li a:hover { color: #fff; }
.mr-footer-social {
  display: flex;
  gap: 8px;
  list-style: none;
  padding: 0;
  margin: 0;
}
.mr-footer-social li a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--mr-border);
  color: var(--mr-text-faint);
  text-decoration: none;
  font-size: 16px;
  transition: all 0.25s ease;
}
.mr-footer-social li a:hover {
  color: var(--mr-cyan);
  border-color: rgba(0,228,255,0.3);
  background: var(--mr-cyan-dim);
}
.mr-footer-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 24px 0;
}
.mr-footer-bottom p {
  font-size: 13px;
  color: var(--mr-text-faint);
  margin: 0;
}
.mr-footer-bottom a {
  font-size: 13px;
  color: var(--mr-text-faint);
  text-decoration: none;
}
.mr-footer-bottom a:hover { color: var(--mr-text); }
.mr-footer-bottom-links {
  display: flex;
  gap: 24px;
}

/* ---- SCROLL REVEAL ---- */
.mr-reveal {
  opacity: 0;
  transform: translateY(32px);
  transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}
.mr-reveal.visible {
  opacity: 1;
  transform: translateY(0);
}
.mr-reveal-delay-1 { transition-delay: 0.1s; }
.mr-reveal-delay-2 { transition-delay: 0.2s; }
.mr-reveal-delay-3 { transition-delay: 0.3s; }
.mr-reveal-delay-4 { transition-delay: 0.4s; }
.mr-reveal-delay-5 { transition-delay: 0.5s; }

/* ---- RESPONSIVE ---- */
@media (max-width: 991px) {
  .mr-features-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .mr-split {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  .mr-split.reverse > .mr-split-media { order: -1; }
  .mr-mission-grid {
    grid-template-columns: 1fr;
  }
  .mr-hero-stats { gap: 32px; }
  .mr-footer-grid {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 767px) {
  .mr-nav .container { padding: 0 16px; }
  .mr-nav-links, .mr-nav-social { display: none; }
  .mr-nav-toggle { display: block; }
  .mr-nav-mobile-open .mr-nav-links,
  .mr-nav-mobile-open .mr-nav-social {
    display: flex;
    flex-direction: column;
    position: absolute;
    top: 72px;
    left: 0;
    right: 0;
    background: rgba(6,6,12,0.97);
    backdrop-filter: blur(20px);
    padding: 16px;
    border-bottom: 1px solid var(--mr-border);
  }
  .mr-nav-mobile-open .mr-nav-social {
    flex-direction: row;
    flex-wrap: wrap;
    top: auto;
    padding-top: 0;
    border-bottom: none;
    justify-content: center;
  }
  .mr-nav-links li a.mr-nav-cta { margin-left: 0; }
  .mr-hero h1 { letter-spacing: -0.5px; }
  .mr-hero-stats {
    flex-direction: column;
    gap: 20px;
  }
  .mr-features-grid {
    grid-template-columns: 1fr;
  }
  .mr-feature-card { padding: 32px 24px; }
  .mr-section { padding: 64px 0; }
  .mr-split { padding: 48px 0; }
  .mr-footer-grid {
    grid-template-columns: 1fr;
    gap: 32px;
  }
  .mr-footer-bottom {
    flex-direction: column;
    gap: 12px;
    text-align: center;
  }
  .mr-hero-actions { flex-direction: column; }
  .mr-hero-actions .mr-btn { text-align: center; justify-content: center; }
  .mr-partners-logos { gap: 24px; }
  .mr-partners-logos a img { height: 32px; }
}

@media (max-width: 480px) {
  .mr-hero-content { padding: 32px 0; }
  .mr-hero-tag { font-size: 10px; margin-bottom: 24px; }
  .mr-app-badges { flex-direction: column; }
}
</style>
</head>
<body>

<!-- ============ NAVIGATION ============ -->
<nav class="mr-nav" id="mainNav">
  <div class="container">
    <a href="/" class="mr-nav-brand">
      <img src="/assets/landing/img/logomarscoinwallet.png" alt="Martian Republic">
      <span>Martian Republic</span>
    </a>

    <ul class="mr-nav-social">
      <li><a target="_blank" href="https://facebook.com/marscoin" title="Facebook"><i class="fa-brands fa-facebook"></i></a></li>
      <li><a target="_blank" href="https://twitter.com/marscoinorg" title="X / Twitter"><i class="fa-brands fa-x-twitter"></i></a></li>
      <li><a target="_blank" href="https://discord.gg/6vVKH6QdYb" title="Discord"><i class="fa-brands fa-discord"></i></a></li>
      <li><a target="_blank" href="https://reddit.com/r/marscoin" title="Reddit"><i class="fa-brands fa-reddit"></i></a></li>
      <li><a target="_blank" href="https://github.com/marscoin/martianrepublic" title="GitHub"><i class="fa-brands fa-github"></i></a></li>
      <li><a target="_blank" href="https://app.gitter.im/#/room/#marscoin-dev_community:gitter.im" title="Gitter"><i class="fa-brands fa-gitter"></i></a></li>
    </ul>

    <ul class="mr-nav-links">
      <li><a href="/academy">Academy</a></li>
      <li><a href="/login">Login</a></li>
      <li><a href="/signup" class="mr-nav-cta">Become a Citizen</a></li>
    </ul>

    <button class="mr-nav-toggle" id="navToggle" aria-label="Toggle navigation">
      <i class="fa fa-bars"></i>
    </button>
  </div>
</nav>

<!-- ============ HERO ============ -->
<section class="mr-hero">
  <div class="mr-hero-bg">
    <canvas id="star-canvas"></canvas>
    <img src="/assets/landing/img/city_on_mars.webp" alt="Mars Settlement">
    <div class="mr-hero-gradient"></div>
  </div>
  <div class="container">
    <div class="mr-hero-content">
      <div class="mr-hero-tag">
        <span class="dot"></span>
        Network Active
      </div>
      <h1>Build the first <span>government on Mars</span></h1>
      <p class="mr-hero-sub">A decentralized, transparent, and cryptographically secured governance platform. Direct democracy for the first Martian settlers &mdash; built with blockchain, run by citizens.</p>
      <div class="mr-hero-actions">
        <a href="/signup" class="mr-btn mr-btn-primary">
          Become a Citizen
          <i class="fa fa-arrow-right"></i>
        </a>
        <a href="/academy" class="mr-btn mr-btn-outline">
          Read the Constitution
        </a>
      </div>
      <div class="mr-hero-stats">
        <div>
          <div class="mr-hero-stat-value">2014</div>
          <div class="mr-hero-stat-label">Founded</div>
        </div>
        <div>
          <div class="mr-hero-stat-value">100%</div>
          <div class="mr-hero-stat-label">Open Source</div>
        </div>
        <div>
          <div class="mr-hero-stat-value">On-Chain</div>
          <div class="mr-hero-stat-label">Governance</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ TICKER ============ -->
<div class="mr-ticker">
  <div class="mr-ticker-inner" id="ticker">
    <span class="mr-ticker-item"><span class="label">Protocol</span><span class="value">Marscoin v28</span></span>
    <span class="mr-ticker-item"><span class="label">Consensus</span><span class="value">Proof of Work</span></span>
    <span class="mr-ticker-item"><span class="label">Registry</span><span class="value">IPFS + Blockchain</span></span>
    <span class="mr-ticker-item"><span class="label">Voting</span><span class="value">CoinShuffle Protocol</span></span>
    <span class="mr-ticker-item"><span class="label">Wallet</span><span class="value">HD Non-Custodial</span></span>
    <span class="mr-ticker-item"><span class="label">Identity</span><span class="value">Proof of Humanity</span></span>
    <span class="mr-ticker-item"><span class="label">License</span><span class="value">Open Source</span></span>
    <span class="mr-ticker-item"><span class="label">Network</span><span class="value">Mainnet Live</span></span>
    <!-- Duplicate for seamless scroll -->
    <span class="mr-ticker-item"><span class="label">Protocol</span><span class="value">Marscoin v28</span></span>
    <span class="mr-ticker-item"><span class="label">Consensus</span><span class="value">Proof of Work</span></span>
    <span class="mr-ticker-item"><span class="label">Registry</span><span class="value">IPFS + Blockchain</span></span>
    <span class="mr-ticker-item"><span class="label">Voting</span><span class="value">CoinShuffle Protocol</span></span>
    <span class="mr-ticker-item"><span class="label">Wallet</span><span class="value">HD Non-Custodial</span></span>
    <span class="mr-ticker-item"><span class="label">Identity</span><span class="value">Proof of Humanity</span></span>
    <span class="mr-ticker-item"><span class="label">License</span><span class="value">Open Source</span></span>
    <span class="mr-ticker-item"><span class="label">Network</span><span class="value">Mainnet Live</span></span>
  </div>
</div>

<!-- ============ FEATURES ============ -->
<section class="mr-section mr-features">
  <div class="container">
    <div class="mr-section-header mr-reveal">
      <span class="mr-section-label">Infrastructure</span>
      <h2 class="mr-section-title">Everything a civilization needs</h2>
      <p class="mr-section-desc">Six integrated systems forming the backbone of Martian self-governance. Every module is open source, on-chain, and ready for deployment.</p>
    </div>
  </div>
  <div class="container" style="padding: 0;">
    <div class="mr-features-grid">
      <div class="mr-feature-card mr-reveal mr-reveal-delay-1">
        <div class="mr-feature-icon"><i class="fa fa-wallet"></i></div>
        <h3>Marscoin Wallet</h3>
        <p>Open source non-custodial HD wallet with encrypted backup and seed phrase recovery. Your keys, your coins.</p>
      </div>
      <div class="mr-feature-card mr-reveal mr-reveal-delay-2">
        <div class="mr-feature-icon"><i class="fa fa-fingerprint"></i></div>
        <h3>Citizen Registry</h3>
        <p>On-chain proof-of-humanity registry using decentralized cryptographic file storage for private/public identity attestation.</p>
      </div>
      <div class="mr-feature-card mr-reveal mr-reveal-delay-3">
        <div class="mr-feature-icon"><i class="fa fa-landmark"></i></div>
        <h3>Martian Congress</h3>
        <p>On-chain, end-to-end auditable governance with encrypted ballot distribution ensuring anonymous yet verifiable voting.</p>
      </div>
      <div class="mr-feature-card mr-reveal mr-reveal-delay-1">
        <div class="mr-feature-icon"><i class="fa fa-cubes"></i></div>
        <h3>Inventory Tracker</h3>
        <p>Immutable ledger-based system for in-situ resource utilization tracking, from raw materials to station inventory.</p>
      </div>
      <div class="mr-feature-card mr-reveal mr-reveal-delay-2">
        <div class="mr-feature-icon"><i class="fa fa-flask"></i></div>
        <h3>Research Logbook</h3>
        <p>Scientific data notarization anchored to the blockchain via IPFS &mdash; time-proofed and congress-notarized.</p>
      </div>
      <div class="mr-feature-card mr-reveal mr-reveal-delay-3">
        <div class="mr-feature-icon"><i class="fa fa-earth-americas"></i></div>
        <h3>Planetary Registry</h3>
        <p>In cooperation with the Martian Planetary Registry Project. Mapping the new frontier, on-chain.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ ABOUT ============ -->
<section class="mr-section">
  <div class="container">
    <div class="mr-split mr-reveal">
      <div class="mr-split-media">
        <img src="/assets/citizen/mars_flag5.png" alt="Mars Congressional Republic Flag" loading="lazy">
      </div>
      <div class="mr-split-text">
        <span class="mr-section-label">About</span>
        <h2>The code <em>is</em> the constitution</h2>
        <p>The Martian Congressional Republic is an experimental blockchain-based community exploring the technologies needed to operate a human civilization on Mars.</p>
        <p>Unlike token-based projects, our Congress is formed by known individuals who participate openly in discussion but vote cryptographically secured and anonymously. The Marscoin blockchain immutably anchors every decision.</p>
        <a href="/signup" class="mr-btn mr-btn-outline">Join the Republic</a>
      </div>
    </div>

    <div class="mr-split reverse mr-reveal">
      <div class="mr-split-text">
        <span class="mr-section-label">Community</span>
        <h2>Built by humans, for humans on Mars</h2>
        <p>This project is built and used by real people with the sincere intent to settle Mars. We believe blockchain technology offers a unique opportunity to improve upon current methods of cooperation.</p>
        <p>This open source reference implementation is our proposal to harness advances in cryptography for direct citizen participation &mdash; and to form our community before we settle.</p>
        <a href="/signup" class="mr-btn mr-btn-outline">Become a Citizen</a>
      </div>
      <div class="mr-split-media">
        <img src="/assets/citizen/mars_flag4.jpg" alt="Mars" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- ============ MISSION PRINCIPLES ============ -->
<section class="mr-section mr-mission">
  <div class="container">
    <div class="mr-section-header mr-reveal">
      <span class="mr-section-label">The Mission</span>
      <h2 class="mr-section-title">Principles of Martian governance</h2>
      <p class="mr-section-desc">The foundation of a free society on a new world, encoded in open source software and cryptographic truth.</p>
    </div>
    <div class="mr-mission-grid">
      <div class="mr-mission-card mr-reveal mr-reveal-delay-1">
        <div class="mr-mission-num">01</div>
        <h3>Direct Democracy</h3>
        <p>Every citizen votes directly on proposals, bills, and amendments. No representatives needed &mdash; governance is transparent and accountable to the public it serves.</p>
      </div>
      <div class="mr-mission-card mr-reveal mr-reveal-delay-2">
        <div class="mr-mission-num">02</div>
        <h3>Cryptographic Privacy</h3>
        <p>Votes are anonymous yet fully auditable. Our CoinShuffle ballot protocol ensures no one can trace a vote to a citizen, while the community can verify every ballot's legitimacy.</p>
      </div>
      <div class="mr-mission-card mr-reveal mr-reveal-delay-3">
        <div class="mr-mission-num">03</div>
        <h3>Proof of Humanity</h3>
        <p>Citizens are endorsed by peers through a decentralized identity attestation system. Provably human participants form the electorate &mdash; no bots, no sock puppets.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ WALLET SECTION ============ -->
<section class="mr-section">
  <div class="container">
    <div class="mr-split mr-reveal">
      <div class="mr-split-media">
        <img src="/assets/wallet/img/marscoin_wallet.png" alt="Marscoin Wallet" loading="lazy">
      </div>
      <div class="mr-split-text">
        <span class="mr-section-label">Wallet</span>
        <h2>Non-custodial HD wallet, built in</h2>
        <p>Create a two-factor authenticated account that serves as a backup for your wallet generated locally on your device. Your keys never leave your browser.</p>
        <p>Seed phrase recovery, encrypted backups, and simple send/receive functionality. The currency of future Mars, available today.</p>
        <a href="/signup" class="mr-btn mr-btn-outline">Create a Wallet</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ VOTER REGISTRY ============ -->
<section class="mr-section" style="padding-top: 0;">
  <div class="container">
    <div class="mr-split reverse mr-reveal">
      <div class="mr-split-text">
        <span class="mr-section-label">Identity</span>
        <h2>Proof-of-Humanity voter registry</h2>
        <p>A community-driven identity attestation system where members invite and vouch for new citizens using clear programmatic guidelines that future proposals can modify.</p>
        <p>Starting with a liveness test and peer endorsement, the registry could scale to kiosk-style terminals upon arrival on Mars. After registration, citizens are vetted and integrated by the community itself.</p>
        <a href="/signup" class="mr-btn mr-btn-outline">Register as a Citizen</a>
      </div>
      <div class="mr-split-media">
        <img src="/assets/citizen/registry_screenshot.png" alt="Citizen Registry" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- ============ MOBILE APP ============ -->
<section class="mr-section mr-app-section" style="padding-top: 0;">
  <div class="container">
    <div class="mr-split mr-reveal">
      <div class="mr-split-media">
        <a href="https://apps.apple.com/us/app/martianrepublic/id6480416861">
          <img src="/assets/landing/img/ios.png" alt="Martian Republic iOS App" loading="lazy">
        </a>
      </div>
      <div class="mr-split-text">
        <span class="mr-section-label">Mobile</span>
        <h2>Governance from your pocket</h2>
        <p>The Martian Republic app integrates wallet, voter registry, and encrypted ballot distribution. Vote on proposals, manage your identity, and send Marscoin &mdash; all from your device.</p>
        <p>Purely on-chain, the app lets every citizen propose, amend, and vote on legislation directly. Open source and ready for Mars.</p>
        <div class="mr-app-badges">
          <a href="https://apps.apple.com/us/app/martianrepublic/id6480416861" target="_blank">
            <img src="/assets/landing/img/apple.png" alt="Download on App Store">
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ PARTNERS ============ -->
<section class="mr-partners">
  <div class="container">
    <div class="mr-partners-header">
      <h4>Supporting Mars Settlement</h4>
    </div>
    <div class="mr-partners-logos">
      <a href="https://www.spacex.com/human-spaceflight/mars/" target="_blank"><img src="/assets/landing/img/clients/logo1-grayscale.png" alt="SpaceX"></a>
      <a href="https://www.marssociety.org" target="_blank"><img src="/assets/landing/img/clients/logo2-grayscale.png" alt="The Mars Society"></a>
      <a href="https://www.humanmars.net" target="_blank"><img src="/assets/landing/img/clients/logo3-grayscale.png" alt="HumanMars"></a>
      <a href="https://www.marscoinfoundation.org" target="_blank"><img src="/assets/landing/img/clients/logo4-grayscale.png" alt="Marscoin Foundation"></a>
      <a href="http://marspedia.org/Home" target="_blank"><img src="/assets/landing/img/clients/logo5-grayscale.png" alt="Marspedia"></a>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="mr-section" style="padding-bottom: 120px;">
  <div class="container" style="text-align: center;">
    <div class="mr-reveal" style="max-width: 600px; margin: 0 auto;">
      <span class="mr-section-label">Ready?</span>
      <h2 class="mr-section-title">The Republic needs you, citizen</h2>
      <p class="mr-section-desc" style="margin-bottom: 40px;">Join a growing community of Mars enthusiasts building the governance infrastructure for humanity's next frontier.</p>
      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="/signup" class="mr-btn mr-btn-primary">
          Become a Citizen
          <i class="fa fa-arrow-right"></i>
        </a>
        <a href="https://discord.gg/6vVKH6QdYb" target="_blank" class="mr-btn mr-btn-outline">
          <i class="fa-brands fa-discord"></i>
          Join Discord
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ============ FOOTER ============ -->
<footer class="mr-footer">
  <div class="container">
    <div class="mr-footer-grid">
      <div>
        <a href="/" class="mr-footer-brand">
          <img src="/assets/landing/img/logomarscoinwallet.png" alt="Martian Republic">
          Martian Republic
        </a>
        <p class="mr-footer-desc">An initiative of the Marscoin&trade; Foundation, Inc., dedicated to advancing blockchain technology for space exploration and Martian self-governance.</p>
        <ul class="mr-footer-social" style="margin-top: 24px;">
          <li><a href="https://twitter.com/marscoinorg" target="_blank" title="X"><i class="fa-brands fa-x-twitter"></i></a></li>
          <li><a href="https://facebook.com/marscoin" target="_blank" title="Facebook"><i class="fa-brands fa-facebook"></i></a></li>
          <li><a href="https://discord.gg/6vVKH6QdYb" target="_blank" title="Discord"><i class="fa-brands fa-discord"></i></a></li>
          <li><a href="https://github.com/marscoin/martianrepublic" target="_blank" title="GitHub"><i class="fa-brands fa-github"></i></a></li>
          <li><a href="https://app.gitter.im/#/room/#marscoin-dev_community:gitter.im" target="_blank" title="Gitter"><i class="fa-brands fa-gitter"></i></a></li>
        </ul>
      </div>
      <div>
        <h5>Platform</h5>
        <ul class="mr-footer-links">
          <li><a href="/signup">Create Account</a></li>
          <li><a href="/login">Login</a></li>
          <li><a href="/status">Server Status</a></li>
        </ul>
      </div>
      <div>
        <h5>Resources</h5>
        <ul class="mr-footer-links">
          <li><a href="/academy">Academy</a></li>
          <li><a href="https://github.com/marscoin/martianrepublic" target="_blank">Source Code</a></li>
          <li><a href="https://apps.apple.com/us/app/martianrepublic/id6480416861" target="_blank">iOS App</a></li>
        </ul>
      </div>
      <div>
        <h5>Community</h5>
        <ul class="mr-footer-links">
          <li><a href="https://discord.gg/6vVKH6QdYb" target="_blank">Discord</a></li>
          <li><a href="https://twitter.com/marscoinorg" target="_blank">X / Twitter</a></li>
          <li><a href="https://reddit.com/r/marscoin" target="_blank">Reddit</a></li>
        </ul>
      </div>
    </div>
    <div class="mr-footer-bottom">
      <p>Copyright &copy; 2014&ndash;<?=date('Y')?> The Marscoin Foundation, Inc.</p>
      <div class="mr-footer-bottom-links">
        <a href="/status">Server Status</a>
        <a href="/privacy">Privacy</a>
      </div>
    </div>
  </div>
</footer>

<!-- ============ SCRIPTS ============ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js" integrity="sha512-YHQNqPhxuCY2ddskIbDlZfwY6Vx3L3w9WRbyJCY81xpqLmrM6rL2+LocBgeVHwGY9SXYfQWJ+lcEWx1fKS2s8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
// Star field canvas
(function() {
  var canvas = document.getElementById('star-canvas');
  if (!canvas) return;
  var ctx = canvas.getContext('2d');
  var stars = [];
  var count = 180;

  function resize() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }

  function init() {
    resize();
    stars = [];
    for (var i = 0; i < count; i++) {
      stars.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: Math.random() * 1.5 + 0.3,
        a: Math.random(),
        da: (Math.random() - 0.5) * 0.008
      });
    }
  }

  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (var i = 0; i < stars.length; i++) {
      var s = stars[i];
      s.a += s.da;
      if (s.a > 1 || s.a < 0.1) s.da *= -1;
      ctx.beginPath();
      ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(255,255,255,' + s.a.toFixed(2) + ')';
      ctx.fill();
    }
    requestAnimationFrame(draw);
  }

  init();
  draw();
  window.addEventListener('resize', function() {
    resize();
    for (var i = 0; i < stars.length; i++) {
      stars[i].x = Math.random() * canvas.width;
      stars[i].y = Math.random() * canvas.height;
    }
  });
})();

// Navbar scroll effect
(function() {
  var nav = document.getElementById('mainNav');
  window.addEventListener('scroll', function() {
    if (window.scrollY > 80) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  });
})();

// Mobile nav toggle
(function() {
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  if (toggle) {
    toggle.addEventListener('click', function() {
      nav.classList.toggle('mr-nav-mobile-open');
    });
  }
})();

// Scroll reveal
(function() {
  var reveals = document.querySelectorAll('.mr-reveal');
  function check() {
    var windowHeight = window.innerHeight;
    for (var i = 0; i < reveals.length; i++) {
      var el = reveals[i];
      var top = el.getBoundingClientRect().top;
      if (top < windowHeight - 80) {
        el.classList.add('visible');
      }
    }
  }
  window.addEventListener('scroll', check);
  window.addEventListener('load', check);
  check();
})();
</script>
</body>
</html>
