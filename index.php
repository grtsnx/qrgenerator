<?php header('X-Frame-Options: SAMEORIGIN'); ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>qr. — generate. share. connect.</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,300&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
<style>
/* ─── Reset ─────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
button { cursor: pointer; font-family: inherit; }

/* ─── Tokens ─────────────────────────────────────────────── */
:root {
  --bg:     #07070b;
  --s1:     #0d0d13;
  --s2:     #13131c;
  --s3:     #1b1b28;
  --bd:     #21212f;
  --bd2:    #2e2e42;
  --tx:     #dddad2;
  --tx-mid: #7b7990;
  --tx-dim: #3e3d4e;
  --ac:     #c9ff2e;
  --ac-dim: rgba(201,255,46,.08);
  --danger: #ff4e4e;
  --r:      5px;
  --r-lg:   10px;
  --mono:   'DM Mono', monospace;
  --sans:   'Syne', sans-serif;
}

/* ─── Base (mobile-first, no scroll lock) ───────────────── */
html { background: var(--bg); }
body {
  background: var(--bg);
  color: var(--tx);
  font-family: var(--mono);
  font-size: 13px;
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
  min-height: 100vh;
}

/* noise overlay */
body::after {
  content: '';
  position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='.035'/%3E%3C/svg%3E");
  background-size: 180px;
  pointer-events: none; z-index: 9999;
}

/* ─── App Shell ──────────────────────────────────────────── */
.app {
  display: grid;
  grid-template-rows: 50px auto;
  min-height: 100vh;
}

/* ─── Header ─────────────────────────────────────────────── */
header {
  display: flex;
  align-items: center;
  padding: 0 16px;
  background: var(--s1);
  border-bottom: 1px solid var(--bd);
  position: sticky;
  top: 0;
  z-index: 100;
  gap: 0;
}

.brand {
  font-family: var(--mono);
  font-size: 20px;
  font-weight: 500;
  color: var(--ac);
  letter-spacing: -.02em;
  user-select: none;
  padding-right: 16px;
  margin-right: 16px;
  border-right: 1px solid var(--bd);
  white-space: nowrap;
  flex-shrink: 0;
}

.type-pills {
  display: flex;
  gap: 3px;
  flex: 1;
  min-width: 0;
  overflow-x: auto;
  scrollbar-width: none;
}
.type-pills::-webkit-scrollbar { display: none; }

.pill {
  font-family: var(--mono);
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 100px;
  border: 1px solid transparent;
  background: transparent;
  color: var(--tx-dim);
  transition: all .14s;
  letter-spacing: .05em;
  white-space: nowrap;
  flex-shrink: 0;
}
.pill:hover  { color: var(--tx-mid); border-color: var(--bd2); }
.pill.active { background: var(--ac-dim); border-color: var(--ac); color: var(--ac); }

.hdr-tag {
  font-size: 10px;
  color: var(--tx-dim);
  letter-spacing: .1em;
  white-space: nowrap;
  display: none; /* hidden on mobile */
  align-items: center;
  gap: 7px;
}
.dot-live {
  width: 5px; height: 5px;
  border-radius: 50%; background: var(--ac);
  animation: blink 2.4s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }

/* ─── Main — mobile: single column ──────────────────────── */
.main {
  display: grid;
  grid-template-columns: 1fr;
}
.col-div { display: none; }

/* ─── Panels ─────────────────────────────────────────────── */
.left {
  display: flex;
  flex-direction: column;
  padding: 16px;
  gap: 14px;
  border-bottom: 1px solid var(--bd);
}

.right {
  display: flex;
  flex-direction: column;
  padding: 16px;
  gap: 14px;
}

/* ─── Labels ─────────────────────────────────────────────── */
.lbl {
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--tx-dim);
  margin-bottom: 7px;
  font-family: var(--sans);
}

/* ─── Inputs ─────────────────────────────────────────────── */
.type-view { display: none; }
.type-view.on { display: block; }

input[type=text], input[type=url], input[type=email],
input[type=tel],  input[type=password], textarea, select {
  width: 100%;
  background: var(--s2);
  border: 1px solid var(--bd);
  border-radius: var(--r);
  color: var(--tx);
  font-family: var(--mono);
  font-size: 13px;
  padding: 10px 12px;
  outline: none;
  transition: border-color .13s, box-shadow .13s;
  appearance: none;
  -webkit-appearance: none;
}
input:focus, textarea:focus, select:focus {
  border-color: var(--ac);
  box-shadow: 0 0 0 2px var(--ac-dim);
}
input::placeholder, textarea::placeholder { color: var(--tx-dim); }
textarea { resize: none; height: 72px; line-height: 1.6; }

select {
  cursor: pointer;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%233e3d4e' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
  padding-right: 28px;
}
select option { background: var(--s2); }
.stack { display: flex; flex-direction: column; gap: 8px; }

/* ─── Options ─────────────────────────────────────────────── */
.opts { display: flex; flex-direction: column; gap: 14px; }
.opt-row { display: flex; flex-direction: column; }

/* color pickers */
.color-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.clr-wrap   { display: flex; flex-direction: column; gap: 4px; }
.clr-lbl    { font-size: 10px; color: var(--tx-dim); }
.clr-row {
  display: flex; align-items: center; gap: 8px;
  background: var(--s2); border: 1px solid var(--bd);
  border-radius: var(--r); padding: 7px 10px;
  cursor: pointer; transition: border-color .13s;
}
.clr-row:hover { border-color: var(--bd2); }
.swatch {
  width: 18px; height: 18px;
  border-radius: 3px;
  border: 1px solid rgba(255,255,255,.08);
  flex-shrink: 0;
}
.hex-val { font-size: 11px; color: var(--tx-mid); flex: 1; }
input[type=color] { position: absolute; width: 0; height: 0; opacity: 0; pointer-events: none; }

/* option button groups */
.btn-g { display: flex; gap: 3px; }
.og {
  flex: 1;
  font-family: var(--mono);
  font-size: 11px;
  padding: 7px 0;
  background: var(--s2);
  border: 1px solid var(--bd);
  border-radius: var(--r);
  color: var(--tx-mid);
  text-align: center;
  transition: all .12s;
}
.og:hover { border-color: var(--bd2); color: var(--tx); }
.og.on    { background: var(--ac-dim); border-color: var(--ac); color: var(--ac); }

/* ─── Generate button ─────────────────────────────────────── */
.btn-gen {
  width: 100%;
  padding: 13px;
  background: var(--ac);
  border: 2px solid var(--ac);
  border-radius: var(--r);
  color: #07070b;
  font-family: var(--mono);
  font-size: 13px;
  font-weight: 500;
  letter-spacing: .04em;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background .13s, transform .1s;
  flex-shrink: 0;
}
.btn-gen:hover  { background: #d5ff4a; border-color: #d5ff4a; }
.btn-gen:active { transform: scale(.98); }
.btn-gen.busy   { opacity: .6; cursor: wait; pointer-events: none; }

/* ─── Preview zone ────────────────────────────────────────── */
.preview-zone {
  width: 100%;
  height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-image:
    linear-gradient(var(--bd) 1px, transparent 1px),
    linear-gradient(90deg, var(--bd) 1px, transparent 1px);
  background-size: 22px 22px;
  background-position: -1px -1px;
  border-radius: var(--r-lg);
  border: 1px solid var(--bd);
  position: relative;
  overflow: hidden;
}
.preview-zone::before, .preview-zone::after {
  content: '';
  position: absolute;
  width: 20px; height: 20px;
  border-color: var(--ac);
  border-style: solid;
  border-width: 0;
}
.preview-zone::before {
  top: 10px; left: 10px;
  border-top-width: 2px; border-left-width: 2px;
  border-radius: 3px 0 0 0;
}
.preview-zone::after {
  bottom: 10px; right: 10px;
  border-bottom-width: 2px; border-right-width: 2px;
  border-radius: 0 0 3px 0;
}

.qr-wrap {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* scan line */
.scan-line {
  position: absolute; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, transparent, var(--ac), transparent);
  opacity: 0; top: 0; pointer-events: none;
}
.scanning .scan-line {
  animation: scandown .55s cubic-bezier(.4,0,.6,1) forwards;
}
@keyframes scandown {
  0%   { top: 0;    opacity: 0; }
  10%  {            opacity: .9; }
  90%  {            opacity: .7; }
  100% { top: 100%; opacity: 0; }
}

#qr-canvas {
  display: none;
  border-radius: 4px;
  max-width:  min(520px, calc(100% - 40px));
  max-height: min(520px, 55vh);
  width: auto !important;
  height: auto !important;
}

@keyframes qr-appear {
  from { opacity: 0; transform: scale(.97); }
  to   { opacity: 1; transform: scale(1); }
}
.qr-fresh { animation: qr-appear .22s ease-out; }

/* placeholder */
.ph {
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 12px; color: var(--tx-dim);
}
.ph-grid {
  display: grid;
  grid-template-columns: repeat(7, 9px);
  grid-template-rows: repeat(7, 9px);
  gap: 2px; opacity: .18;
}
.ph-cell { border-radius: 1.5px; background: var(--tx-mid); }
.ph-txt  { font-size: 11px; letter-spacing: .06em; }

/* ─── Action buttons ──────────────────────────────────────── */
.actions { display: flex; gap: 7px; }

.btn {
  flex: 1;
  font-family: var(--mono);
  font-size: 12px;
  padding: 10px 14px;
  border-radius: var(--r);
  border: 1px solid var(--bd);
  background: var(--s2);
  color: var(--tx-mid);
  transition: all .13s;
  display: flex; align-items: center; justify-content: center; gap: 6px;
  white-space: nowrap;
}
.btn:hover:not(:disabled) { border-color: var(--bd2); color: var(--tx); }
.btn:disabled { opacity: .28; cursor: not-allowed; }

.btn-dl {
  background: var(--ac); border-color: var(--ac);
  color: #07070b; font-weight: 500;
}
.btn-dl:hover:not(:disabled) { background: #d5ff4a; border-color: #d5ff4a; }
.btn-dl:disabled { background: var(--s2); border-color: var(--bd); color: var(--tx-dim); }

.btn-reset {
  width: 100%;
  font-size: 11px;
  padding: 8px 12px;
  color: var(--tx-dim);
  background: transparent;
  border: 1px dashed var(--bd);
}
.btn-reset:hover { color: var(--tx-mid); border-color: var(--bd2); }

/* ─── History ─────────────────────────────────────────────── */
.hist-hdr {
  display: flex; align-items: center;
  justify-content: space-between; margin-bottom: 9px;
}
.btn-clr {
  font-size: 10px; color: var(--tx-dim);
  background: none; border: none;
  font-family: var(--mono); transition: color .1s; padding: 0;
}
.btn-clr:hover { color: var(--danger); }

.hist-row {
  display: flex; gap: 7px;
  overflow-x: auto; padding-bottom: 4px;
}
.hist-row::-webkit-scrollbar { height: 2px; }
.hist-row::-webkit-scrollbar-thumb { background: var(--bd); border-radius: 1px; }

.hist-item {
  flex-shrink: 0; display: flex; flex-direction: column;
  gap: 4px; cursor: pointer;
}
.hist-thumb {
  width: 56px; height: 56px;
  border: 1px solid var(--bd); border-radius: 4px;
  overflow: hidden; background: white;
  transition: border-color .13s;
}
.hist-item:hover .hist-thumb { border-color: var(--ac); }
.hist-thumb img { width: 56px; height: 56px; display: block; }
.hist-lbl {
  font-size: 9px; color: var(--tx-dim);
  width: 56px; overflow: hidden;
  text-overflow: ellipsis; white-space: nowrap;
  text-align: center;
}
.hist-empty { font-size: 10px; color: var(--tx-dim); padding: 4px 0; }

/* ─── Toast ──────────────────────────────────────────────── */
.toast {
  position: fixed; bottom: 20px; left: 50%;
  transform: translateX(-50%) translateY(52px);
  background: var(--s3); border: 1px solid var(--bd2);
  color: var(--tx); font-size: 11px;
  padding: 8px 18px; border-radius: 100px;
  font-family: var(--mono);
  transition: transform .2s cubic-bezier(.34,1.56,.64,1), opacity .2s;
  opacity: 0; z-index: 10000; pointer-events: none;
  white-space: nowrap;
}
.toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

/* ─── Desktop layout (≥ 860px): two columns, no scroll ──── */
@media (min-width: 860px) {
  html, body { height: 100%; overflow: hidden; }
  .app { height: 100vh; grid-template-rows: 50px 1fr; overflow: hidden; }
  .main { grid-template-columns: 370px 1px 1fr; overflow: hidden; }
  .col-div { display: block; background: var(--bd); }
  .hdr-tag { display: flex; }

  .left {
    overflow: hidden;
    border-bottom: none;
    padding: 18px;
  }
  .opts { flex: 1; overflow: hidden; }

  .right {
    overflow: hidden;
    padding: 18px 22px;
  }
  .preview-zone {
    flex: 1;
    height: auto;
    min-height: 0;
  }
  #qr-canvas {
    max-width:  min(520px, calc(100% - 40px));
    max-height: min(520px, 55vh);
  }
}
</style>
</head>
<body>
<div class="app">

  <!-- Header -->
  <header>
    <div class="brand">qr.</div>
    <nav class="type-pills" id="type-pills">
      <button class="pill active" data-type="url">url</button>
      <button class="pill"        data-type="text">text</button>
      <button class="pill"        data-type="email">email</button>
      <button class="pill"        data-type="phone">phone</button>
      <button class="pill"        data-type="wifi">wifi</button>
    </nav>
    <div class="hdr-tag">
      <span class="dot-live"></span>generate · share · connect
    </div>
  </header>

  <div class="main">

    <!-- ── Left Panel ── -->
    <div class="left">

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

      </div>

      <!-- Generate button -->
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

    </div><!-- /left -->

    <div class="col-div"></div>

    <!-- ── Right Panel ── -->
    <div class="right">

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

    </div><!-- /right -->
  </div>
</div>

<div class="toast" id="toast"></div>

<script type="module">
import QRCode from 'https://esm.sh/qrcode@1.5.4';

/* ── State ────────────────────────────────────────────────── */
const DEFAULT_SETTINGS = { type:'url', fg:'#000000', bg:'#ffffff', size:400, ecc:'M', fmt:'png' };
const S = { type:DEFAULT_SETTINGS.type, data:'', fg:DEFAULT_SETTINGS.fg, bg:DEFAULT_SETTINGS.bg,
            size:DEFAULT_SETTINGS.size, ecc:DEFAULT_SETTINGS.ecc, fmt:DEFAULT_SETTINGS.fmt, hasQR:false };
const PREV_MAX = 520; /* cap preview pixel size so layout doesn't break */

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
function generate() {
  const data = getData();
  S.data = data;
  if (!data) { toast('nothing to generate'); return; }

  btnGen.classList.add('busy');
  btnGen.textContent = 'generating…';

  pzone.classList.remove('scanning');
  void pzone.offsetWidth;
  pzone.classList.add('scanning');

  QRCode.toCanvas(canvas, data, {
    errorCorrectionLevel: S.ecc,
    width: Math.min(S.size, PREV_MAX),
    margin: 1,
    color: { dark: S.fg, light: S.bg },
  }, err => {
    pzone.classList.remove('scanning');
    btnGen.classList.remove('busy');
    btnGen.textContent = '';
    setGenLabel('generate');
    try {
      if (err) { console.error(err); toast('error generating QR'); return; }

      ph.style.display     = 'none';
      canvas.style.display = 'block';
      canvas.classList.remove('qr-fresh');
      void canvas.offsetWidth;
      canvas.classList.add('qr-fresh');
      S.hasQR = true;
      btnCopy.disabled = false;
      btnDl.disabled   = false;

      saveHist().catch(() => {});
    } catch (e) {
      console.error(e);
      toast('error generating QR');
    }
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
    const blob = await new Promise(res => canvas.toBlob(res));
    await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
    toast('copied to clipboard');
  } catch {
    try {
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
    const url = await new Promise((res, rej) =>
      QRCode.toDataURL(S.data, {
        errorCorrectionLevel: S.ecc, width: S.size, margin: 2,
        color: { dark: S.fg, light: S.bg },
      }, (e, u) => e ? rej(e) : res(u))
    );
    const a = document.createElement('a');
    a.download = 'qr-' + slugify() + '.png';
    a.href = url; a.click();
    await saveHist();
    toast('downloaded \u2193');
  } catch (e) { console.error(e); }
}

async function dlSVG() {
  try {
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
