<?php header('X-Frame-Options: SAMEORIGIN'); ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Lab — Create custom QR codes</title>
<link rel="icon" type="image/svg+xml" href="favicon.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ─── Modern SaaS · Dribbble-inspired ─────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
button { cursor: pointer; font-family: inherit; }

:root {
  --bg:         #1a1a1a;
  --surface:    #242424;
  --surface-2:  #2a2a2a;
  --surface-3:  #333;
  --border:     rgba(255, 255, 255, 0.08);
  --border-2:   rgba(255, 255, 255, 0.12);
  --text:       #ffffff;
  --text-mid:   #a0a0a0;
  --text-dim:   #707070;
  --accent:     #a8ff00;
  --accent-2:   #b8ff20;
  --accent-glow: rgba(168, 255, 0, 0.2);
  --accent-soft: rgba(168, 255, 0, 0.12);
  --danger:     #ef4444;
  --radius:    12px;
  --radius-sm:  8px;
  --radius-lg:  16px;
  --font:       'Outfit', system-ui, sans-serif;
  --shadow:     0 4px 20px rgba(0, 0, 0, 0.3);
  --shadow-lg:  0 16px 40px rgba(0, 0, 0, 0.35);
  --safe-top:   env(safe-area-inset-top, 0);
  --safe-bottom: env(safe-area-inset-bottom, 0);
  --safe-left:  env(safe-area-inset-left, 0);
  --safe-right: env(safe-area-inset-right, 0);
  --paper:      var(--bg);
  --paper-2:    var(--surface-2);
  --ink:        var(--text);
  --ink-mid:    var(--text-mid);
  --ink-dim:    var(--text-dim);
}

html {
  background: var(--bg);
  scroll-behavior: smooth;
}
body {
  font-family: var(--font);
  font-size: 15px;
  line-height: 1.55;
  color: var(--text);
  min-height: 100vh;
  -webkit-font-smoothing: antialiased;
  background: var(--bg);
}

/* Subtle noise */
body::after {
  content: '';
  position: fixed; inset: 0;
  background: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.02'/%3E%3C/svg%3E");
  pointer-events: none; z-index: 9999;
}

.app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  max-width: 1400px;
  margin: 0 auto;
}

/* ─── Header ─────────────────────────────────────────────── */
header {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 16px 24px;
  padding: 20px 24px;
  background: var(--bg);
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 0;
  z-index: 100;
}

.brand {
  font-family: var(--font);
  font-size: 1.35rem;
  font-weight: 700;
  color: var(--accent);
  letter-spacing: -0.02em;
}

.type-pills {
  display: flex;
  gap: 8px;
  flex: 1;
  min-width: 0;
  overflow-x: auto;
  scrollbar-width: none;
}
.type-pills::-webkit-scrollbar { display: none; }

.pill {
  font-family: var(--font);
  font-size: 13px;
  font-weight: 500;
  padding: 9px 16px;
  border-radius: 100px;
  border: 1px solid var(--border-2);
  background: transparent;
  color: var(--text-dim);
  transition: all 0.2s ease;
  white-space: nowrap;
}
.pill:hover { color: var(--text-mid); border-color: var(--text-mid); }
.pill.active {
  border: 2px solid var(--accent);
  background: transparent;
  color: var(--accent);
  padding: 8px 15px;
}

.hdr-tag {
  font-size: 12px;
  color: var(--text-dim);
  display: none;
  align-items: center;
  gap: 8px;
}
.dot-live {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--accent);
  box-shadow: 0 0 8px var(--accent-glow);
  animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{ opacity:1 } 50%{ opacity:.4 } }

/* ─── Main layout ────────────────────────────────────────── */
.main {
  display: grid;
  grid-template-columns: 1fr;
  flex: 1;
  padding: 24px;
  gap: 24px;
}
.col-div { display: none; }

.left {
  display: flex;
  flex-direction: column;
  gap: 20px;
  animation: fadeUp 0.5s ease-out;
}
@keyframes fadeUp { from { opacity:0; transform:translateY(10px) } to { opacity:1; transform:translateY(0) } }

.right {
  display: flex;
  flex-direction: column;
  gap: 20px;
  animation: fadeUp 0.5s ease-out 0.1s both;
}

/* ─── Cards & panels ─────────────────────────────────────── */
.card {
  background: var(--surface-2);
  border-radius: var(--radius);
  padding: 24px;
  border: 1px solid var(--border);
  transition: border-color 0.2s ease;
}
.card:hover { border-color: var(--border-2); }

.lbl {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--text-dim);
  margin-bottom: 12px;
  font-family: var(--font);
}

/* ─── Inputs ─────────────────────────────────────────────── */
.type-view { display: none; }
.type-view.on { display: block; }

input[type=text], input[type=url], input[type=email], input[type=tel], input[type=password], textarea, select {
  width: 100%;
  font-family: var(--font);
  font-size: 15px;
  padding: 14px 16px;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  background: var(--bg);
  color: var(--text);
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  appearance: none;
}
input:focus, textarea:focus, select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 2px var(--accent-soft);
}
input::placeholder, textarea::placeholder { color: var(--text-dim); }
textarea { resize: vertical; min-height: 80px; line-height: 1.5; }

select {
  cursor: pointer;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath fill='%23707070' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 14px center;
  padding-right: 36px;
}
.stack { display: flex; flex-direction: column; gap: 10px; }

/* ─── Options & buttons ─────────────────────────────────── */
.opts { display: flex; flex-direction: column; gap: 18px; }
.opt-row { display: flex; flex-direction: column; }

.color-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.clr-wrap { display: flex; flex-direction: column; gap: 6px; }
.clr-lbl { font-size: 12px; color: var(--text-dim); font-family: var(--font); }
.clr-row {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 14px;
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  cursor: pointer;
  transition: border-color 0.2s;
}
.clr-row:hover { border-color: var(--border-2); }
.swatch {
  width: 24px; height: 24px;
  border-radius: 8px;
  border: 1px solid rgba(255,255,255,.15);
  flex-shrink: 0;
}
.hex-val { font-size: 12px; color: var(--text-mid); flex: 1; font-family: var(--font); }
input[type=color] { position: absolute; width: 0; height: 0; opacity: 0; pointer-events: none; }

.btn-g { display: flex; gap: 8px; flex-wrap: wrap; }
.og {
  flex: 1;
  min-width: 52px;
  font-family: var(--font);
  font-size: 13px;
  font-weight: 500;
  padding: 10px 14px;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--text-mid);
  text-align: center;
  transition: all 0.2s;
}
.og:hover { border-color: var(--border-2); color: var(--text); }
.og.on { border: 2px solid var(--accent); color: var(--accent); background: transparent; padding: 9px 13px; }

/* Generate button */
.btn-gen {
  width: 100%;
  padding: 18px 24px;
  font-family: var(--font);
  font-size: 16px;
  font-weight: 600;
  color: #1a1a1a;
  background: var(--accent);
  border: none;
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  transition: background 0.2s, opacity 0.2s;
}
.btn-gen:hover { background: var(--accent-2); }
.btn-gen:active { opacity: 0.95; }
.btn-gen.busy { opacity: 0.7; cursor: wait; pointer-events: none; }

/* ─── Preview zone ────────────────────────────────────────── */
.preview-zone {
  aspect-ratio: 1;
  max-height: min(420px, 55vw);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  background-color: var(--surface-2);
  background-image:
    linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
  background-size: 20px 20px;
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  --corner: 24px;
}
/* L-shaped corner brackets via SVG */
.preview-zone::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100' preserveAspectRatio='none'%3E%3Cpath d='M12 2h2v10h10v2H12z' fill='none' stroke='%23a8ff00' stroke-width='2'/%3E%3Cpath d='M88 2h-2v10H76v2h12z' fill='none' stroke='%23a8ff00' stroke-width='2'/%3E%3Cpath d='M12 98v-2h10V86h2v12z' fill='none' stroke='%23a8ff00' stroke-width='2'/%3E%3Cpath d='M88 98v-2H76V86h-2v12z' fill='none' stroke='%23a8ff00' stroke-width='2'/%3E%3C/svg%3E");
  background-size: 100% 100%;
  padding: 12px;
  background-origin: content-box;
  background-repeat: no-repeat;
}

.qr-wrap { position: relative; display: inline-flex; align-items: center; justify-content: center; }
.scan-line {
  position: absolute; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, transparent, var(--accent), transparent);
  opacity: 0; top: 0; pointer-events: none;
}
.scanning .scan-line { animation: scandown 0.6s ease-out forwards; }
@keyframes scandown {
  0% { top: 0; opacity: 0; }
  15% { opacity: 0.9; }
  85% { opacity: 0.7; }
  100% { top: 100%; opacity: 0; }
}

#qr-canvas {
  display: none;
  border-radius: var(--radius-sm);
  max-width: min(520px, 100%);
  max-height: 100%;
  width: auto !important;
  height: auto !important;
}
.qr-fresh { animation: qrPop 0.35s ease-out; }
@keyframes qrPop { from { opacity: 0; transform: scale(0.96) } to { opacity: 1; transform: scale(1) } }

.ph {
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 14px; color: var(--text-dim);
}
.ph-grid {
  display: grid;
  grid-template-columns: repeat(7, 10px);
  grid-template-rows: repeat(7, 10px);
  gap: 2px;
  opacity: 0.3;
}
.ph-cell { border-radius: 2px; background: var(--text-mid); }
.ph-txt { font-size: 13px; font-family: var(--font); }

/* ─── Tabs ────────────────────────────────────────────────── */
.left-tabs {
  display: flex;
  gap: 4px;
  padding: 4px;
  background: var(--bg);
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
}
.left-tabs .tab {
  flex: 1;
  font-family: var(--font);
  font-size: 13px;
  font-weight: 500;
  padding: 10px 16px;
  border: none;
  border-radius: 6px;
  background: transparent;
  color: var(--text-dim);
  cursor: pointer;
  transition: all 0.2s;
}
.left-tabs .tab:hover { color: var(--text-mid); }
.left-tabs .tab.active {
  background: var(--accent);
  color: #1a1a1a;
}
.tab-pane { display: none; flex-direction: column; gap: 18px; }
.tab-pane.on { display: flex; }

.marker-opts .btn-g { margin-top: 8px; }
.marker-btn {
  width: 48px; height: 48px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--text);
  transition: all 0.2s;
}
.marker-btn:hover { border-color: var(--border-2); }
.marker-btn.selected { border: 2px solid var(--accent); background: transparent; color: var(--accent); }
.marker-btn svg { width: 26px; height: 26px; }

/* ─── Actions & buttons ──────────────────────────────────── */
.actions { display: flex; gap: 12px; }
.btn {
  flex: 1;
  font-family: var(--font);
  font-size: 14px;
  font-weight: 500;
  padding: 12px 18px;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: var(--surface-2);
  color: var(--text-mid);
  transition: all 0.2s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn:hover:not(:disabled) { border-color: var(--border-2); color: var(--text); }
.btn:disabled { opacity: 0.4; cursor: not-allowed; }
.btn-dl {
  background: var(--accent);
  border-color: var(--accent);
  color: #1a1a1a;
}
.btn-dl:hover:not(:disabled) { background: var(--accent-2); border-color: var(--accent-2); }
.btn-dl:disabled { background: var(--surface-2); border: 1px solid var(--border); color: var(--text-dim); }

.btn-reset {
  width: 100%;
  font-size: 13px;
  padding: 10px 16px;
  color: var(--text-dim);
  background: transparent;
  border: 1px dashed var(--border);
  border-radius: var(--radius-sm);
  font-family: var(--font);
}
.btn-reset:hover { color: var(--text-mid); border-color: var(--text-dim); }

.logo-upload { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
.logo-name { font-size: 13px; color: var(--text-dim); max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: var(--font); }
.btn-upload { flex-shrink: 0; border: 1px solid rgba(255,255,255,0.5); background: var(--bg); color: var(--text); }
.btn-upload:hover { border-color: var(--text); }
.btn-remove-logo { font-size: 12px; padding: 8px 12px; color: var(--text); background: var(--danger); border: none; font-family: var(--font); }
.btn-remove-logo:hover { background: #dc2626; }

/* ─── Style picker ───────────────────────────────────────── */
.style-picker { display: none; margin-top: 16px; }
.style-picker.on { display: block; }
.style-options { display: flex; gap: 12px; flex-wrap: wrap; }
.style-option {
  flex: 1;
  min-width: 96px;
  max-width: 130px;
  cursor: pointer;
  border: 2px solid var(--border);
  border-radius: var(--radius-sm);
  overflow: hidden;
  background: var(--surface-2);
  transition: all 0.2s;
}
.style-option:hover { border-color: var(--border-2); }
.style-option.selected { border-color: var(--accent); }
.style-option canvas { width: 100%; height: auto; display: block; }
.style-option .style-name { font-size: 11px; text-align: center; padding: 8px 0; color: var(--text-dim); font-weight: 500; font-family: var(--font); }

/* ─── History ─────────────────────────────────────────────── */
.hist-hdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
.btn-clr {
  font-size: 12px; color: var(--text-dim);
  background: none; border: none;
  cursor: pointer;
  transition: color 0.2s;
  font-family: var(--font);
}
.btn-clr:hover { color: var(--danger); }

.hist-row { display: flex; gap: 12px; overflow-x: auto; padding-bottom: 8px; }
.hist-row::-webkit-scrollbar { height: 6px; }
.hist-row::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); border-radius: 3px; }
.hist-row::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 3px; }

.hist-item {
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
  cursor: pointer;
  transition: transform 0.2s ease;
}
.hist-item:hover { transform: translateY(-2px); }
.hist-thumb {
  width: 64px; height: 64px;
  border-radius: var(--radius-sm);
  overflow: hidden;
  background: var(--surface-3);
  border: 1px solid var(--border);
  transition: border-color 0.2s;
}
.hist-item:hover .hist-thumb { border-color: var(--accent); }
.hist-thumb img { width: 64px; height: 64px; display: block; object-fit: cover; }
.hist-lbl {
  font-size: 11px;
  color: var(--text-dim);
  width: 64px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  text-align: center;
  font-family: var(--font);
}
.hist-empty { font-size: 13px; color: var(--text-dim); padding: 8px 0; font-family: var(--font); }

/* ─── Toast ──────────────────────────────────────────────── */
.toast {
  position: fixed;
  bottom: calc(28px + var(--safe-bottom));
  left: 50%;
  transform: translateX(-50%) translateY(20px);
  padding: 14px 28px;
  font-family: var(--font);
  font-size: 14px;
  font-weight: 500;
  color: #1a1a1a;
  background: var(--accent);
  border-radius: 100px;
  opacity: 0;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s;
  z-index: 10000;
  pointer-events: none;
  white-space: nowrap;
}
.toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

/* ─── Mobile-first: touch & safe area ─────────────────────── */
@media (max-width: 899px) {
  .app { padding-left: var(--safe-left); padding-right: var(--safe-right); }
  header {
    padding: 14px 16px 14px max(16px, var(--safe-left));
    padding-right: max(16px, var(--safe-right));
    gap: 12px 16px;
  }
  .brand { font-size: 1.35rem; }
  .type-pills { order: 3; width: 100%; margin: 0 -4px; padding: 4px 0; -webkit-overflow-scrolling: touch; }
  .pill {
    padding: 10px 16px;
    min-height: 44px;
    font-size: 14px;
  }
  .main {
    padding: 16px max(16px, var(--safe-left)) 24px max(16px, var(--safe-right));
    padding-bottom: calc(24px + var(--safe-bottom));
    gap: 20px;
  }
  .card { padding: 16px; }
  .left-tabs .tab {
    padding: 12px 14px;
    min-height: 44px;
    font-size: 14px;
  }
  input[type=text], input[type=url], input[type=email], input[type=tel], input[type=password], textarea, select {
    padding: 14px 16px;
    min-height: 48px;
    font-size: 16px;
  }
  textarea { min-height: 96px; }
  .btn-gen {
    padding: 18px 24px;
    min-height: 52px;
    font-size: 17px;
  }
  .actions { flex-wrap: wrap; }
  .actions .btn {
    min-height: 48px;
    padding: 14px 18px;
  }
  .preview-zone {
    max-height: min(380px, 75vw);
    min-height: 200px;
  }
  .style-options { gap: 10px; }
  .style-option { min-width: 80px; max-width: 120px; }
  .marker-btn {
    width: 52px;
    height: 52px;
    min-width: 52px;
    min-height: 52px;
  }
  .hist-row { padding-bottom: 8px; -webkit-overflow-scrolling: touch; }
  .hist-thumb { width: 56px; height: 56px; }
  .hist-thumb img { width: 56px; height: 56px; }
  .hist-lbl { width: 56px; }
}
@media (max-width: 480px) {
  header { flex-direction: column; align-items: stretch; }
  .brand { text-align: center; }
  .type-pills { justify-content: flex-start; }
  .color-grid { grid-template-columns: 1fr; }
  .btn-g { gap: 8px; }
}

/* ─── Desktop ─────────────────────────────────────────────── */
@media (min-width: 900px) {
  .main {
    grid-template-columns: 380px 1px 1fr;
    padding: 32px 40px;
    gap: 32px;
    align-items: start;
  }
  .col-div { display: block; background: var(--border); min-height: 400px; }
  .hdr-tag { display: flex; }
  .left { max-height: calc(100vh - 100px); overflow-y: auto; }
  .right { min-height: 0; }
  .preview-zone { max-height: 420px; aspect-ratio: 1; }
}
</style>
</head>
<body>
<div class="app">

  <header>
    <div class="brand">qr.</div>
    <nav class="type-pills" id="type-pills">
      <button class="pill active" data-type="url">URL</button>
      <button class="pill" data-type="text">Text</button>
      <button class="pill" data-type="email">Email</button>
      <button class="pill" data-type="phone">Phone</button>
      <button class="pill" data-type="wifi">Wi‑Fi</button>
    </nav>
    <div class="hdr-tag">
      <span class="dot-live"></span>
      <span>generate · share · connect</span>
    </div>
  </header>

  <main class="main">
    <aside class="left card">

      <nav class="left-tabs" id="left-tabs">
        <button type="button" class="tab active" data-tab="input">Input</button>
        <button type="button" class="tab" data-tab="style">Style</button>
        <button type="button" class="tab" data-tab="options">Options</button>
      </nav>

      <div class="tab-pane on" id="pane-input">
      <!-- Input -->
      <div class="input-wrap">
        <div class="lbl">input</div>

        <div class="type-view on" id="view-url">
          <input type="url" id="inp-url" placeholder="https://example.com"
                 autocomplete="off" spellcheck="false">
        </div>
        <div class="type-view" id="view-text">
          <textarea id="inp-text" placeholder="Enter any text…"></textarea>
        </div>
        <div class="type-view" id="view-email">
          <div class="stack">
            <input type="email" id="inp-email"     placeholder="user@example.com">
            <input type="text"  id="inp-email-sub" placeholder="Subject (optional)">
          </div>
        </div>
        <div class="type-view" id="view-phone">
          <input type="tel" id="inp-phone" placeholder="+1 234 567 8900">
        </div>
        <div class="type-view" id="view-wifi">
          <div class="stack">
            <input type="text"     id="inp-ssid" placeholder="Network name (SSID)">
            <input type="password" id="inp-pass" placeholder="Password">
            <select id="inp-enc">
              <option value="WPA">WPA / WPA2</option>
              <option value="WEP">WEP</option>
              <option value="nopass">No password</option>
            </select>
          </div>
        </div>
      </div>

      </div><!-- /pane-input -->

      <div class="tab-pane" id="pane-style">
        <div class="opt-row">
          <div class="lbl">dots</div>
          <div class="btn-g" id="dot-style-btns">
            <button type="button" class="marker-btn selected" data-dot="standard" title="Square">□</button>
            <button type="button" class="marker-btn" data-dot="dots" title="Circle">○</button>
            <button type="button" class="marker-btn" data-dot="rounded" title="Rounded">▢</button>
            <button type="button" class="marker-btn" data-dot="instagram" title="Instagram">◇</button>
          </div>
        </div>
        <div class="opt-row marker-opts">
          <div class="lbl">marker border</div>
          <div class="btn-g">
            <button type="button" class="marker-btn selected" data-marker-border="square" title="Square"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="0"/></svg></button>
            <button type="button" class="marker-btn" data-marker-border="rounded" title="Rounded"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="3"/></svg></button>
            <button type="button" class="marker-btn" data-marker-border="circle" title="Circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/></svg></button>
          </div>
        </div>
        <div class="opt-row marker-opts">
          <div class="lbl">marker center</div>
          <div class="btn-g">
            <button type="button" class="marker-btn selected" data-marker-center="square" title="Square"><svg viewBox="0 0 24 24" fill="currentColor"><rect x="8" y="8" width="8" height="8"/></svg></button>
            <button type="button" class="marker-btn" data-marker-center="circle" title="Circle"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="4"/></svg></button>
          </div>
        </div>
      </div><!-- /pane-style -->

      <div class="tab-pane" id="pane-options">
      <!-- Options -->
      <div class="opts">

        <div class="opt-row">
          <div class="lbl">colors</div>
          <div class="color-grid">
            <div class="clr-wrap">
              <div class="clr-lbl">foreground</div>
              <div class="clr-row" id="clr-fg-row">
                <div class="swatch" id="fg-sw" style="background:#000000"></div>
                <span class="hex-val" id="fg-hx">#000000</span>
                <input type="color" id="fg-p" value="#000000">
              </div>
            </div>
            <div class="clr-wrap">
              <div class="clr-lbl">background</div>
              <div class="clr-row" id="clr-bg-row">
                <div class="swatch" id="bg-sw" style="background:#ffffff"></div>
                <span class="hex-val" id="bg-hx">#ffffff</span>
                <input type="color" id="bg-p" value="#ffffff">
              </div>
            </div>
          </div>
        </div>

        <div class="opt-row">
          <div class="lbl">export size</div>
          <div class="btn-g">
            <button class="og"    data-sz="128">xs</button>
            <button class="og"    data-sz="256">s</button>
            <button class="og on" data-sz="400">m</button>
            <button class="og"    data-sz="600">l</button>
            <button class="og"    data-sz="1024">xl</button>
          </div>
        </div>

        <div class="opt-row">
          <div class="lbl">error correction</div>
          <div class="btn-g">
            <button class="og"    data-ecc="L">L</button>
            <button class="og on" data-ecc="M">M</button>
            <button class="og"    data-ecc="Q">Q</button>
            <button class="og"    data-ecc="H">H</button>
          </div>
        </div>

        <div class="opt-row">
          <div class="lbl">format</div>
          <div class="btn-g">
            <button class="og on" data-fmt="png">PNG</button>
            <button class="og"    data-fmt="svg">SVG</button>
          </div>
        </div>

        <div class="opt-row">
          <button class="btn btn-reset" id="btn-reset" type="button">Reset to default</button>
        </div>

        <div class="opt-row logo-row">
          <div class="lbl">logo (optional)</div>
          <div class="logo-upload">
            <input type="file" id="inp-logo" accept="image/*" hidden>
            <button type="button" class="btn btn-upload" id="btn-logo">Choose image</button>
            <span class="logo-name" id="logo-name"></span>
            <button type="button" class="btn btn-remove-logo" id="btn-remove-logo" style="display:none">Remove</button>
          </div>
        </div>

      </div><!-- /pane-options -->

      <!-- Generate button (visible in all tabs) -->
      <button class="btn-gen" id="btn-gen">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
             stroke="currentColor" stroke-width="1.6"
             stroke-linecap="round" stroke-linejoin="round"
             aria-hidden="true">
          <rect x="1" y="1" width="5" height="5" rx="1"/>
          <rect x="8" y="1" width="5" height="5" rx="1"/>
          <rect x="1" y="8" width="5" height="5" rx="1"/>
          <path d="M8 10.5h2m3 0h-1M10.5 8v2m0 3v-1"/>
        </svg>
        generate
      </button>

    </aside><!-- /left -->

    <div class="col-div"></div>

    <section class="right card">
      <!-- Preview -->
      <div class="preview-zone" id="preview-zone">
        <div class="qr-wrap" id="qr-wrap">
          <div class="scan-line"></div>
          <canvas id="qr-canvas"></canvas>
          <div class="ph" id="ph">
            <div class="ph-grid" id="ph-grid"></div>
            <div class="ph-txt">press generate</div>
          </div>
        </div>
      </div>

      <!-- Style picker (shown after generate) -->
      <div class="style-picker" id="style-picker">
        <div class="lbl">choose style</div>
        <div class="style-options" id="style-options"></div>
      </div>

      <!-- Actions -->
      <div class="actions">
        <button class="btn" id="btn-copy" disabled>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
               stroke="currentColor" stroke-width="1.2"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="4" y="4" width="7.5" height="7.5" rx="1.2"/>
            <path d="M.5 8V1.5A1 1 0 011.5.5H8"/>
          </svg>
          copy
        </button>
        <button class="btn btn-dl" id="btn-dl" disabled>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
               stroke="currentColor" stroke-width="1.5"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 1v7M3 5.5L6 9l3-3.5M1.5 11h9"/>
          </svg>
          <span id="dl-lbl">download png</span>
        </button>
      </div>

      <!-- History -->
      <div class="history">
        <div class="hist-hdr">
          <div class="lbl" style="margin:0">recent</div>
          <button class="btn-clr" id="btn-clr">clear all</button>
        </div>
        <div class="hist-row" id="hist-row"></div>
      </div>

    </section><!-- /right -->
  </main>
</div>

<div class="toast" id="toast"></div>

<script type="module">
import QRCode from 'https://esm.sh/qrcode@1.5.4';

/* ── State ────────────────────────────────────────────────── */
const DEFAULT_SETTINGS = { type:'url', fg:'#000000', bg:'#ffffff', size:400, ecc:'M', fmt:'png' };
const S = { type:DEFAULT_SETTINGS.type, data:'', fg:DEFAULT_SETTINGS.fg, bg:DEFAULT_SETTINGS.bg,
            size:DEFAULT_SETTINGS.size, ecc:DEFAULT_SETTINGS.ecc, fmt:DEFAULT_SETTINGS.fmt, hasQR:false,
            logoDataUrl: null, selectedStyle: 'standard',
            markerBorder: 'square', markerCenter: 'square' };
const PREV_MAX = 520; /* cap preview pixel size so layout doesn't break */
const STYLES = ['standard', 'dots', 'rounded', 'instagram'];

/* ── DOM ─────────────────────────────────────────────────── */
const canvas  = document.getElementById('qr-canvas');
const ph      = document.getElementById('ph');
const pzone   = document.getElementById('preview-zone');
const btnGen  = document.getElementById('btn-gen');
const btnCopy = document.getElementById('btn-copy');
const btnDl   = document.getElementById('btn-dl');
const dlLbl   = document.getElementById('dl-lbl');
const histRow = document.getElementById('hist-row');
const toastEl = document.getElementById('toast');
const stylePicker = document.getElementById('style-picker');
const styleOptions = document.getElementById('style-options');

/* ── Placeholder grid ────────────────────────────────────── */
(function(){
  const g = document.getElementById('ph-grid');
  [1,1,1,1,1,1,1, 1,0,0,0,0,0,1, 1,0,1,1,1,0,1,
   1,0,1,0,1,0,1, 1,0,1,1,1,0,1, 1,0,0,0,0,0,1, 1,1,1,1,1,1,1]
  .forEach(v => {
    const c = document.createElement('div');
    c.className = 'ph-cell';
    c.style.opacity = v ? '1' : '0';
    g.appendChild(c);
  });
})();

/* ── Color picker triggers ───────────────────────────────── */
document.getElementById('clr-fg-row')
  .addEventListener('click', () => document.getElementById('fg-p').click());
document.getElementById('clr-bg-row')
  .addEventListener('click', () => document.getElementById('bg-p').click());

/* ── Left panel tabs ─────────────────────────────────────── */
document.querySelectorAll('.left-tabs .tab').forEach(t => {
  t.addEventListener('click', () => {
    const tab = t.dataset.tab;
    document.querySelectorAll('.left-tabs .tab').forEach(x => x.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('on'));
    t.classList.add('active');
    document.getElementById('pane-' + tab).classList.add('on');
  });
});

/* ── Style tab: dot style (syncs with right-panel style picker) ── */
document.querySelectorAll('[data-dot]').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('[data-dot]').forEach(x => x.classList.remove('selected'));
    b.classList.add('selected');
    S.selectedStyle = b.dataset.dot;
    if (S.hasQR) { syncStylePickerSelection(); redrawCurrentStyle(); }
  });
});
document.querySelectorAll('[data-marker-border]').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('[data-marker-border]').forEach(x => x.classList.remove('selected'));
    b.classList.add('selected');
    S.markerBorder = b.dataset.markerBorder;
    if (S.hasQR) generate();
  });
});
document.querySelectorAll('[data-marker-center]').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('[data-marker-center]').forEach(x => x.classList.remove('selected'));
    b.classList.add('selected');
    S.markerCenter = b.dataset.markerCenter;
    if (S.hasQR) generate();
  });
});

function syncStylePickerSelection() {
  styleOptions.querySelectorAll('.style-option').forEach(el => {
    el.classList.toggle('selected', el.getAttribute('data-style') === S.selectedStyle);
  });
}

/* ── Type pill switching ─────────────────────────────────── */
document.querySelectorAll('.pill').forEach(p => {
  p.addEventListener('click', () => {
    document.querySelectorAll('.pill').forEach(x => x.classList.remove('active'));
    p.classList.add('active');
    S.type = p.dataset.type;
    document.querySelectorAll('.type-view').forEach(v => v.classList.remove('on'));
    document.getElementById('view-' + S.type).classList.add('on');
    saveSettings().catch(() => {});
  });
});

/* ── Enter key triggers generate ────────────────────────── */
document.querySelectorAll('input:not([type=color]):not([type=password]), textarea').forEach(el => {
  el.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); generate(); }
  });
});

/* ── Option buttons (re-generate if QR exists) ───────────── */
document.querySelectorAll('[data-sz]').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('[data-sz]').forEach(x => x.classList.remove('on'));
    b.classList.add('on');
    S.size = +b.dataset.sz;
    if (S.hasQR) generate();
    saveSettings().catch(() => {});
  });
});
document.querySelectorAll('[data-ecc]').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('[data-ecc]').forEach(x => x.classList.remove('on'));
    b.classList.add('on');
    S.ecc = b.dataset.ecc;
    if (S.hasQR) generate();
    saveSettings().catch(() => {});
  });
});
document.querySelectorAll('[data-fmt]').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('[data-fmt]').forEach(x => x.classList.remove('on'));
    b.classList.add('on');
    S.fmt = b.dataset.fmt;
    dlLbl.textContent = 'download ' + S.fmt;
    saveSettings().catch(() => {});
  });
});

/* ── Color pickers (re-generate if QR exists) ────────────── */
document.getElementById('fg-p').addEventListener('input', e => {
  S.fg = e.target.value;
  document.getElementById('fg-sw').style.background = S.fg;
  document.getElementById('fg-hx').textContent = S.fg;
  if (S.hasQR) generate();
  saveSettings().catch(() => {});
});
document.getElementById('bg-p').addEventListener('input', e => {
  S.bg = e.target.value;
  document.getElementById('bg-sw').style.background = S.bg;
  document.getElementById('bg-hx').textContent = S.bg;
  if (S.hasQR) generate();
  saveSettings().catch(() => {});
});

/* ── Generate button ─────────────────────────────────────── */
btnGen.addEventListener('click', generate);

/* ── Logo ────────────────────────────────────────────────── */
document.getElementById('btn-logo').addEventListener('click', () => document.getElementById('inp-logo').click());
document.getElementById('inp-logo').addEventListener('change', async e => {
  const file = e.target.files[0];
  if (!file) return;
  const r = new FileReader();
  r.onload = async () => {
    S.logoDataUrl = r.result;
    document.getElementById('logo-name').textContent = file.name;
    document.getElementById('btn-remove-logo').style.display = '';
    if (S.hasQR) await applyLogoToCanvas(canvas);
  };
  r.readAsDataURL(file);
});
document.getElementById('btn-remove-logo').addEventListener('click', async () => {
  S.logoDataUrl = null;
  document.getElementById('inp-logo').value = '';
  document.getElementById('logo-name').textContent = '';
  document.getElementById('btn-remove-logo').style.display = 'none';
  if (S.hasQR) { await redrawCurrentStyle(); }
});

/* ── Data assembly ───────────────────────────────────────── */
function getData() {
  switch (S.type) {
    case 'url':  return document.getElementById('inp-url').value.trim();
    case 'text': return document.getElementById('inp-text').value.trim();
    case 'email': {
      const em  = document.getElementById('inp-email').value.trim();
      const sub = document.getElementById('inp-email-sub').value.trim();
      if (!em) return '';
      return sub ? 'mailto:' + em + '?subject=' + encodeURIComponent(sub) : 'mailto:' + em;
    }
    case 'phone': {
      const v = document.getElementById('inp-phone').value.trim();
      return v ? 'tel:' + v : '';
    }
    case 'wifi': {
      const ssid = document.getElementById('inp-ssid').value.trim();
      const pass = document.getElementById('inp-pass').value;
      const enc  = document.getElementById('inp-enc').value;
      if (!ssid) return '';
      return enc === 'nopass'
        ? 'WIFI:T:nopass;S:' + ssid + ';;;'
        : 'WIFI:T:' + enc + ';S:' + ssid + ';P:' + pass + ';;';
    }
    default: return '';
  }
}

/* ── Generate ────────────────────────────────────────────── */
function getOpts(overrides = {}) {
  return {
    errorCorrectionLevel: S.ecc,
    width: Math.min(S.size, PREV_MAX),
    margin: 1,
    color: { dark: S.fg, light: S.bg },
    ...overrides,
  };
}

function drawQRToCanvas(canvasEl, data, opts) {
  return new Promise((resolve, reject) => {
    QRCode.toCanvas(canvasEl, data, opts, err => (err ? reject(err) : resolve()));
  });
}

function detectModuleGrid(sourceCanvas) {
  const ctx = sourceCanvas.getContext('2d');
  const w = sourceCanvas.width;
  const h = sourceCanvas.height;
  const margin = 1;
  const data = ctx.getImageData(0, 0, w, h);
  const row = Math.floor(h / 2);
  let x = 0;
  const runs = [];
  while (x < w) {
    const idx = (row * w + Math.min(x, w - 1)) * 4;
    const isDark = data.data[idx] + data.data[idx + 1] + data.data[idx + 2] < 384;
    let len = 0;
    const startX = x;
    while (x < w) {
      const i = (row * w + x) * 4;
      const d = data.data[i] + data.data[i + 1] + data.data[i + 2] < 384;
      if (d !== isDark) break;
      len++;
      x++;
    }
    if (len >= 1) runs.push(len);
  }
  const minRun = runs.length ? (runs.filter(r => r >= 2).length ? Math.min(...runs.filter(r => r >= 2)) : runs[0]) : 1;
  const cellSize = minRun;
  const n = Math.round((w - 2 * margin) / cellSize);
  const nClamped = Math.max(17, Math.min(45, n));
  return { n: nClamped, cellSize: (w - 2 * margin) / nClamped, margin };
}

function buildStyledCanvas(sourceCanvas, styleName, fg, bg) {
  const { n, cellSize, margin } = detectModuleGrid(sourceCanvas);
  const w = sourceCanvas.width;
  const h = sourceCanvas.height;
  const out = document.createElement('canvas');
  out.width = w;
  out.height = h;
  const ctx = out.getContext('2d');
  ctx.fillStyle = bg;
  ctx.fillRect(0, 0, w, h);
  const srcData = sourceCanvas.getContext('2d').getImageData(0, 0, w, h);
  const isDark = (x, y) => {
    const px = Math.min(w - 1, Math.max(0, Math.floor(x)));
    const py = Math.min(h - 1, Math.max(0, Math.floor(y)));
    const idx = (py * w + px) * 4;
    return srcData.data[idx] + srcData.data[idx + 1] + srcData.data[idx + 2] < 384;
  };
  ctx.fillStyle = fg;
  for (let i = 0; i < n; i++) {
    for (let j = 0; j < n; j++) {
      const cx = margin + (i + 0.5) * cellSize;
      const cy = margin + (j + 0.5) * cellSize;
      if (!isDark(cx, cy)) continue;
      if (styleName === 'dots') {
        ctx.beginPath();
        ctx.arc(cx, cy, cellSize * 0.45, 0, Math.PI * 2);
        ctx.fill();
      } else if (styleName === 'instagram') {
        const r = Math.max(2, cellSize * 0.42);
        const x = margin + i * cellSize;
        const y = margin + j * cellSize;
        ctx.beginPath();
        if (ctx.roundRect) {
          ctx.roundRect(x, y, cellSize, cellSize, r);
        } else {
          ctx.rect(x, y, cellSize, cellSize);
        }
        ctx.fill();
      } else {
        const r = Math.max(1, cellSize * 0.25);
        const x = margin + i * cellSize;
        const y = margin + j * cellSize;
        ctx.beginPath();
        if (ctx.roundRect) {
          ctx.roundRect(x, y, cellSize, cellSize, r);
        } else {
          ctx.rect(x, y, cellSize, cellSize);
        }
        ctx.fill();
      }
    }
  }
  return out;
}

function drawMarkerOverlay(canvasEl, n, cellSize, margin, fg, bg, markerBorder, markerCenter) {
  if (markerBorder === 'square' && markerCenter === 'square') return;
  const ctx = canvasEl.getContext('2d');
  const finders = [
    { x0: margin, y0: margin },
    { x0: margin + (n - 8) * cellSize, y0: margin },
    { x0: margin, y0: margin + (n - 8) * cellSize },
  ];
  finders.forEach(({ x0, y0 }) => {
    const cx = x0 + 3.5 * cellSize;
    const cy = y0 + 3.5 * cellSize;
    const rOuter = 3.5 * cellSize;
    const rMid = 2.5 * cellSize;
    const rInner = 0.5 * cellSize;
    ctx.fillStyle = bg;
    ctx.fillRect(x0, y0, 8 * cellSize, 8 * cellSize);
    if (markerBorder === 'circle') {
      ctx.fillStyle = fg;
      ctx.beginPath();
      ctx.arc(cx, cy, rOuter, 0, Math.PI * 2);
      ctx.fill();
      ctx.fillStyle = bg;
      ctx.beginPath();
      ctx.arc(cx, cy, rMid, 0, Math.PI * 2);
      ctx.fill();
      ctx.fillStyle = fg;
      ctx.beginPath();
      ctx.arc(cx, cy, markerCenter === 'circle' ? rInner : rInner * 1.2, 0, Math.PI * 2);
      ctx.fill();
    } else if (markerBorder === 'rounded') {
      const rad = cellSize * 0.4;
      ctx.fillStyle = fg;
      ctx.beginPath();
      if (ctx.roundRect) ctx.roundRect(x0 + 0.5 * cellSize, y0 + 0.5 * cellSize, 7 * cellSize, 7 * cellSize, rad);
      else ctx.rect(x0 + 0.5 * cellSize, y0 + 0.5 * cellSize, 7 * cellSize, 7 * cellSize);
      ctx.fill();
      ctx.fillStyle = bg;
      ctx.beginPath();
      if (ctx.roundRect) ctx.roundRect(x0 + 1.5 * cellSize, y0 + 1.5 * cellSize, 5 * cellSize, 5 * cellSize, rad * 0.8);
      else ctx.rect(x0 + 1.5 * cellSize, y0 + 1.5 * cellSize, 5 * cellSize, 5 * cellSize);
      ctx.fill();
      ctx.fillStyle = fg;
      ctx.beginPath();
      if (ctx.roundRect) ctx.roundRect(x0 + 2.5 * cellSize, y0 + 2.5 * cellSize, 3 * cellSize, 3 * cellSize, rad * 0.5);
      else ctx.rect(x0 + 2.5 * cellSize, y0 + 2.5 * cellSize, 3 * cellSize, 3 * cellSize);
      ctx.fill();
      ctx.fillStyle = fg;
      if (markerCenter === 'circle') {
        ctx.beginPath();
        ctx.arc(cx, cy, rInner, 0, Math.PI * 2);
        ctx.fill();
      } else {
        ctx.beginPath();
        if (ctx.roundRect) ctx.roundRect(cx - 0.5 * cellSize, cy - 0.5 * cellSize, cellSize, cellSize, 2);
        else ctx.rect(cx - 0.5 * cellSize, cy - 0.5 * cellSize, cellSize, cellSize);
        ctx.fill();
      }
    } else {
      /* markerBorder square, markerCenter circle: redraw finder with circular center */
      ctx.fillStyle = fg;
      ctx.fillRect(x0 + 0.5 * cellSize, y0 + 0.5 * cellSize, 7 * cellSize, 7 * cellSize);
      ctx.fillStyle = bg;
      ctx.fillRect(x0 + 1.5 * cellSize, y0 + 1.5 * cellSize, 5 * cellSize, 5 * cellSize);
      ctx.fillStyle = fg;
      ctx.fillRect(x0 + 2.5 * cellSize, y0 + 2.5 * cellSize, 3 * cellSize, 3 * cellSize);
      ctx.fillStyle = fg;
      ctx.beginPath();
      ctx.arc(cx, cy, rInner, 0, Math.PI * 2);
      ctx.fill();
    }
  });
}

function applyLogoToCanvas(canvasEl) {
  if (!S.logoDataUrl) return Promise.resolve();
  return new Promise((resolve) => {
    const ctx = canvasEl.getContext('2d');
    const w = canvasEl.width;
    const h = canvasEl.height;
    const logoSize = Math.floor(Math.min(w, h) * 0.22);
    const pad = Math.floor(logoSize * 0.15);
    const total = logoSize + pad * 2;
    const x0 = (w - total) / 2;
    const y0 = (h - total) / 2;
    ctx.fillStyle = '#ffffff';
    ctx.beginPath();
    if (ctx.roundRect) ctx.roundRect(x0, y0, total, total, 8);
    else ctx.rect(x0, y0, total, total);
    ctx.fill();
    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.onload = () => {
      ctx.drawImage(img, x0 + pad, y0 + pad, logoSize, logoSize);
      resolve();
    };
    img.onerror = () => resolve();
    img.src = S.logoDataUrl;
  });
}

async function buildExportCanvas(size) {
  const opts = getOpts({ width: size });
  const temp = document.createElement('canvas');
  temp.width = size;
  temp.height = size;
  await drawQRToCanvas(temp, S.data, opts);
  let out = temp;
  if (S.selectedStyle === 'dots' || S.selectedStyle === 'rounded' || S.selectedStyle === 'instagram') {
    out = buildStyledCanvas(temp, S.selectedStyle, S.fg, S.bg);
  }
  const grid = detectModuleGrid(temp);
  drawMarkerOverlay(out, grid.n, grid.cellSize, grid.margin, S.fg, S.bg, S.markerBorder, S.markerCenter);
  await applyLogoToCanvas(out);
  return out;
}

function redrawCurrentStyle() {
  const src = S.styleCanvases && S.styleCanvases[S.selectedStyle];
  if (!src) return Promise.resolve();
  const w = src.width;
  const h = src.height;
  canvas.width = w;
  canvas.height = h;
  canvas.getContext('2d').drawImage(src, 0, 0);
  return applyLogoToCanvas(canvas);
}

function generate() {
  const data = getData();
  S.data = data;
  if (!data) { toast('nothing to generate'); return; }

  btnGen.classList.add('busy');
  btnGen.textContent = 'generating…';
  pzone.classList.remove('scanning');
  void pzone.offsetWidth;
  pzone.classList.add('scanning');

  const outSize = Math.min(S.size, PREV_MAX);
  const opts = getOpts({ width: outSize });

  drawQRToCanvas(canvas, data, opts).then(async () => {
    pzone.classList.remove('scanning');
    btnGen.classList.remove('busy');
    setGenLabel('generate');
    try {
      S.styleCanvases = { standard: document.createElement('canvas') };
      S.styleCanvases.standard.width = outSize;
      S.styleCanvases.standard.height = outSize;
      S.styleCanvases.standard.getContext('2d').drawImage(canvas, 0, 0);

      S.styleCanvases.dots = buildStyledCanvas(canvas, 'dots', S.fg, S.bg);
      S.styleCanvases.rounded = buildStyledCanvas(canvas, 'rounded', S.fg, S.bg);
      S.styleCanvases.instagram = buildStyledCanvas(canvas, 'instagram', S.fg, S.bg);

      const grid = detectModuleGrid(canvas);
      ['standard', 'dots', 'rounded', 'instagram'].forEach(key => {
        drawMarkerOverlay(S.styleCanvases[key], grid.n, grid.cellSize, grid.margin, S.fg, S.bg, S.markerBorder, S.markerCenter);
      });

      S.selectedStyle = 'standard';
      ph.style.display = 'none';
      canvas.style.display = 'block';
      canvas.classList.remove('qr-fresh');
      void canvas.offsetWidth;
      canvas.classList.add('qr-fresh');
      S.hasQR = true;
      btnCopy.disabled = false;
      btnDl.disabled = false;

      await redrawCurrentStyle();

      clearNode(styleOptions);
      STYLES.forEach(key => {
        const label = key === 'standard' ? 'Standard' : key === 'dots' ? 'Dots' : key === 'rounded' ? 'Rounded' : 'Instagram';
        const div = document.createElement('div');
        div.className = 'style-option' + (key === S.selectedStyle ? ' selected' : '');
        div.setAttribute('data-style', key);
        const c = document.createElement('canvas');
        c.width = Math.min(100, S.styleCanvases[key].width);
        c.height = Math.min(100, S.styleCanvases[key].height);
        c.getContext('2d').drawImage(S.styleCanvases[key], 0, 0, c.width, c.height);
        div.appendChild(c);
        const name = document.createElement('div');
        name.className = 'style-name';
        name.textContent = label;
        div.appendChild(name);
        div.addEventListener('click', async () => {
          S.selectedStyle = key;
          styleOptions.querySelectorAll('.style-option').forEach(el => el.classList.remove('selected'));
          div.classList.add('selected');
          document.querySelectorAll('[data-dot]').forEach(b => b.classList.toggle('selected', b.dataset.dot === key));
          await redrawCurrentStyle();
        });
        styleOptions.appendChild(div);
      });
      stylePicker.classList.add('on');

      saveHist().catch(() => {});
    } catch (e) {
      console.error(e);
      toast('error generating QR');
    }
  }).catch(err => {
    pzone.classList.remove('scanning');
    btnGen.classList.remove('busy');
    setGenLabel('generate');
    console.error(err);
    toast('error generating QR');
  });
}

function setGenLabel(text) {
  while (btnGen.firstChild) btnGen.removeChild(btnGen.firstChild);
  const svg = document.createElementNS('http://www.w3.org/2000/svg','svg');
  svg.setAttribute('width','14'); svg.setAttribute('height','14');
  svg.setAttribute('viewBox','0 0 14 14'); svg.setAttribute('fill','none');
  svg.setAttribute('stroke','currentColor'); svg.setAttribute('stroke-width','1.6');
  svg.setAttribute('stroke-linecap','round'); svg.setAttribute('stroke-linejoin','round');
  svg.setAttribute('aria-hidden','true');
  const r1 = document.createElementNS('http://www.w3.org/2000/svg','rect');
  r1.setAttribute('x','1');r1.setAttribute('y','1');r1.setAttribute('width','5');r1.setAttribute('height','5');r1.setAttribute('rx','1');
  const r2 = document.createElementNS('http://www.w3.org/2000/svg','rect');
  r2.setAttribute('x','8');r2.setAttribute('y','1');r2.setAttribute('width','5');r2.setAttribute('height','5');r2.setAttribute('rx','1');
  const r3 = document.createElementNS('http://www.w3.org/2000/svg','rect');
  r3.setAttribute('x','1');r3.setAttribute('y','8');r3.setAttribute('width','5');r3.setAttribute('height','5');r3.setAttribute('rx','1');
  const path = document.createElementNS('http://www.w3.org/2000/svg','path');
  path.setAttribute('d','M8 10.5h2m3 0h-1M10.5 8v2m0 3v-1');
  svg.appendChild(r1); svg.appendChild(r2); svg.appendChild(r3); svg.appendChild(path);
  const span = document.createElement('span');
  span.textContent = text;
  btnGen.appendChild(svg);
  btnGen.appendChild(span);
}

/* ── Copy ────────────────────────────────────────────────── */
btnCopy.addEventListener('click', async () => {
  if (!S.hasQR) return;
  try {
    await applyLogoToCanvas(canvas);
    const blob = await new Promise(res => canvas.toBlob(res));
    await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
    toast('copied to clipboard');
  } catch {
    try {
      await applyLogoToCanvas(canvas);
      await navigator.clipboard.writeText(canvas.toDataURL());
      toast('copied as data URL');
    } catch { toast('copy not supported here'); }
  }
});

/* ── Download ────────────────────────────────────────────── */
btnDl.addEventListener('click', () => {
  if (!S.hasQR) return;
  S.fmt === 'svg' ? dlSVG() : dlPNG();
});

function slugify() {
  return S.data.replace(/[^a-z0-9]/gi, '-').toLowerCase().slice(0, 28) || 'qrcode';
}

async function dlPNG() {
  try {
    const exportCanvas = await buildExportCanvas(S.size);
    const url = exportCanvas.toDataURL('image/png');
    const a = document.createElement('a');
    a.download = 'qr-' + slugify() + '.png';
    a.href = url;
    a.click();
    await saveHist();
    toast('downloaded \u2193');
  } catch (e) { console.error(e); }
}

async function dlSVG() {
  try {
    if (S.selectedStyle !== 'standard' || S.logoDataUrl) {
      await dlPNG();
      return;
    }
    const svg = await new Promise((res, rej) =>
      QRCode.toString(S.data, {
        type: 'svg', errorCorrectionLevel: S.ecc, width: S.size, margin: 2,
        color: { dark: S.fg, light: S.bg },
      }, (e, s) => e ? rej(e) : res(s))
    );
    const blob = new Blob([svg], { type: 'image/svg+xml' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.download = 'qr-' + slugify() + '.svg';
    a.href = url; a.click();
    URL.revokeObjectURL(url);
    await saveHist();
    toast('downloaded as SVG \u2193');
  } catch (e) { console.error(e); }
}

/* ── IndexedDB ───────────────────────────────────────────── */
const DB_NAME    = 'qr-app';
const DB_VERSION = 2;
const STORE      = 'history';
const SETTINGS_STORE = 'settings';
let   db         = null;

function openDB() {
  return new Promise((resolve, reject) => {
    const req = indexedDB.open(DB_NAME, DB_VERSION);
    req.onupgradeneeded = e => {
      const d = e.target.result;
      if (!d.objectStoreNames.contains(STORE)) {
        const store = d.createObjectStore(STORE, { keyPath: 'id' });
        store.createIndex('by_data', 'data', { unique: false });
      }
      if (!d.objectStoreNames.contains(SETTINGS_STORE)) {
        d.createObjectStore(SETTINGS_STORE, { keyPath: 'id' });
      }
    };
    req.onsuccess = e => { db = e.target.result; resolve(db); };
    req.onerror   = e => reject(e.target.error);
  });
}

function dbTx(mode) { return db.transaction(STORE, mode).objectStore(STORE); }

function dbGetAll() {
  return new Promise((res, rej) => {
    const req = dbTx('readonly').getAll();
    req.onsuccess = () => res(req.result || []);
    req.onerror   = e => rej(e.target.error);
  });
}

function dbPut(entry) {
  return new Promise((res, rej) => {
    const req = dbTx('readwrite').put(entry);
    req.onsuccess = () => res();
    req.onerror   = e => rej(e.target.error);
  });
}

function dbDelete(id) {
  return new Promise((res, rej) => {
    const req = dbTx('readwrite').delete(id);
    req.onsuccess = () => res();
    req.onerror   = e => rej(e.target.error);
  });
}

function dbClear() {
  return new Promise((res, rej) => {
    const req = dbTx('readwrite').clear();
    req.onsuccess = () => res();
    req.onerror   = e => rej(e.target.error);
  });
}

function settingsTx(mode) { return db.transaction(SETTINGS_STORE, mode).objectStore(SETTINGS_STORE); }

function getSettings() {
  return new Promise((res, rej) => {
    const req = settingsTx('readonly').get('app');
    req.onsuccess = () => res(req.result || null);
    req.onerror   = e => rej(e.target.error);
  });
}

function putSettings(settings) {
  return new Promise((res, rej) => {
    const req = settingsTx('readwrite').put({ id: 'app', ...settings });
    req.onsuccess = () => res();
    req.onerror   = e => rej(e.target.error);
  });
}

async function saveSettings() {
  if (!db) return;
  await putSettings({
    type: S.type, fg: S.fg, bg: S.bg,
    size: S.size, ecc: S.ecc, fmt: S.fmt,
  });
}

function applyUIFromState() {
  document.querySelectorAll('.pill').forEach(p =>
    p.classList.toggle('active', p.dataset.type === S.type));
  document.querySelectorAll('.type-view').forEach(v => v.classList.remove('on'));
  document.getElementById('view-' + S.type).classList.add('on');
  document.getElementById('fg-p').value = S.fg;
  document.getElementById('bg-p').value = S.bg;
  document.getElementById('fg-sw').style.background = S.fg;
  document.getElementById('bg-sw').style.background = S.bg;
  document.getElementById('fg-hx').textContent = S.fg;
  document.getElementById('bg-hx').textContent = S.bg;
  document.querySelectorAll('[data-sz]').forEach(b =>
    b.classList.toggle('on', +b.dataset.sz === S.size));
  document.querySelectorAll('[data-ecc]').forEach(b =>
    b.classList.toggle('on', b.dataset.ecc === S.ecc));
  document.querySelectorAll('[data-fmt]').forEach(b =>
    b.classList.toggle('on', b.dataset.fmt === S.fmt));
  dlLbl.textContent = 'download ' + S.fmt;
}

async function loadSettings() {
  const saved = await getSettings();
  if (!saved) return;
  if (saved.type) S.type = saved.type;
  if (saved.fg) S.fg = saved.fg;
  if (saved.bg) S.bg = saved.bg;
  if (saved.size) S.size = saved.size;
  if (saved.ecc) S.ecc = saved.ecc;
  if (saved.fmt) S.fmt = saved.fmt;
  applyUIFromState();
}

/* ── Migrate any old localStorage data into IndexedDB ───── */
async function migrateLS() {
  try {
    const raw = localStorage.getItem('qr-hist');
    if (!raw) return;
    const items = JSON.parse(raw);
    for (const item of items) await dbPut(item);
    localStorage.removeItem('qr-hist');
  } catch { /* silently skip */ }
}

/* ── History: save ───────────────────────────────────────── */
async function saveHist() {
  if (!db) return;
  const thumb = await new Promise(res => {
    const tc = document.createElement('canvas');
    QRCode.toCanvas(tc, S.data, {
      errorCorrectionLevel: S.ecc, width: 64, margin: 0,
      color: { dark: S.fg, light: S.bg },
    }, () => res(tc.toDataURL('image/png')));
  });

  const entry = {
    id: Date.now(), data: S.data, type: S.type,
    fg: S.fg, bg: S.bg, ecc: S.ecc, thumb,
  };

  // Remove any existing record with same data (deduplicate)
  const all = await dbGetAll();
  const dups = all.filter(h => h.data === S.data);
  for (const dup of dups) await dbDelete(dup.id);

  await dbPut(entry);

  // Trim to max 20 entries (delete oldest)
  const updated = await dbGetAll();
  updated.sort((a, b) => b.id - a.id);
  for (const old of updated.slice(20)) await dbDelete(old.id);

  await renderHist();
}

/* ── History: render ─────────────────────────────────────── */
function clearNode(el) {
  while (el.firstChild) el.removeChild(el.firstChild);
}

async function renderHist() {
  clearNode(histRow);
  if (!db) return;
  const all  = await dbGetAll();
  const list = all.sort((a, b) => b.id - a.id);

  if (!list.length) {
    const d = document.createElement('div');
    d.className = 'hist-empty';
    d.textContent = 'no history yet';
    histRow.appendChild(d);
    return;
  }

  list.forEach(h => {
    const item = document.createElement('div');
    item.className = 'hist-item';
    item.title = h.data;

    const thumb = document.createElement('div');
    thumb.className = 'hist-thumb';
    const img = document.createElement('img');
    img.src = h.thumb; img.width = 56; img.height = 56; img.alt = 'QR';
    thumb.appendChild(img);

    const lbl = document.createElement('div');
    lbl.className = 'hist-lbl';
    lbl.textContent = h.data
      .replace(/^https?:\/\/(www\.)?/, '')
      .replace(/^mailto:/, '').replace(/^tel:/, '')
      .replace(/^WIFI:.*?S:/, '');

    item.appendChild(thumb);
    item.appendChild(lbl);
    item.addEventListener('click', () => restoreHist(h));
    histRow.appendChild(item);
  });
}

function restoreHist(h) {
  S.type = h.type; S.fg = h.fg; S.bg = h.bg; S.ecc = h.ecc;
  document.querySelectorAll('.pill').forEach(p =>
    p.classList.toggle('active', p.dataset.type === h.type));
  document.querySelectorAll('.type-view').forEach(v => v.classList.remove('on'));
  document.getElementById('view-' + h.type).classList.add('on');
  document.getElementById('fg-p').value = h.fg;
  document.getElementById('bg-p').value = h.bg;
  document.getElementById('fg-sw').style.background = h.fg;
  document.getElementById('bg-sw').style.background = h.bg;
  document.getElementById('fg-hx').textContent = h.fg;
  document.getElementById('bg-hx').textContent = h.bg;
  document.querySelectorAll('[data-ecc]').forEach(b =>
    b.classList.toggle('on', b.dataset.ecc === h.ecc));
  fillInput(h.type, h.data);
  generate();
}

function fillInput(type, data) {
  switch (type) {
    case 'url':   document.getElementById('inp-url').value = data; break;
    case 'text':  document.getElementById('inp-text').value = data; break;
    case 'email': {
      const m = data.match(/^mailto:([^?]+)(?:\?subject=(.*))?$/);
      if (m) {
        document.getElementById('inp-email').value = m[1] || '';
        document.getElementById('inp-email-sub').value = m[2] ? decodeURIComponent(m[2]) : '';
      }
      break;
    }
    case 'phone': document.getElementById('inp-phone').value = data.replace(/^tel:/, ''); break;
    case 'wifi': {
      const ms = data.match(/S:([^;]+)/);
      const mp = data.match(/P:([^;]+)/);
      const me = data.match(/T:([^;]+)/);
      if (ms) document.getElementById('inp-ssid').value = ms[1];
      if (mp) document.getElementById('inp-pass').value = mp[1];
      if (me) document.getElementById('inp-enc').value  = me[1];
      break;
    }
  }
}

document.getElementById('btn-clr').addEventListener('click', async () => {
  if (!db) return;
  await dbClear();
  await renderHist();
  toast('history cleared');
});

/* ── Toast ───────────────────────────────────────────────── */
let toastTmr = null;
function toast(msg) {
  toastEl.textContent = msg;
  toastEl.classList.add('show');
  clearTimeout(toastTmr);
  toastTmr = setTimeout(() => toastEl.classList.remove('show'), 2200);
}

/* ── Init ────────────────────────────────────────────────── */
openDB()
  .then(migrateLS)
  .then(() => loadSettings())
  .then(renderHist)
  .catch(err => console.warn('IndexedDB unavailable, history disabled:', err));

document.getElementById('btn-reset').addEventListener('click', () => {
  S.type = DEFAULT_SETTINGS.type;
  S.fg = DEFAULT_SETTINGS.fg;
  S.bg = DEFAULT_SETTINGS.bg;
  S.size = DEFAULT_SETTINGS.size;
  S.ecc = DEFAULT_SETTINGS.ecc;
  S.fmt = DEFAULT_SETTINGS.fmt;
  applyUIFromState();
  saveSettings().catch(() => {});
  if (S.hasQR) generate();
  toast('settings reset to default');
});

const qd = new URLSearchParams(location.search).get('d');
if (qd) {
  document.getElementById('inp-url').value = qd;
  generate();
}
</script>
</body>
</html>
